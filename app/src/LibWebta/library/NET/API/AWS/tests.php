<?php
    /**
     * This file is a part of LibWebta, PHP class library.
     *
     * LICENSE
     *
	 * This source file is subject to version 2 of the GPL license,
	 * that is bundled with this package in the file license.txt and is
	 * available through the world-wide-web at the following url:
	 * http://www.gnu.org/copyleft/gpl.html
     *
     * @category   LibWebta
     * @package NET_API
     * @subpackage AWS
     * @copyright  Copyright (c) 2003-2007 Webta Inc, http://www.gnu.org/licenses/gpl.html
     * @license    http://www.gnu.org/licenses/gpl.html
     * @filesource
     */     


	Core::Load("NET/API/AWS/AmazonEC2");
	Core::Load("NET/API/AWS/AmazonS3");
	
	/**
	 * @category   LibWebta
     * @package NET_API
     * @subpackage AWS 
	 * @name NET_API_AWS_Test
	 */
	class NET_API_AWS_Test extends UnitTestCase 
	{
		
		public $AmazonEC2;
		public $AWSAccountID;
		
        function __construct() 
        {
        	$this->UnitTestCase('AWS tests');
            $this->AmazonEC2 = new AmazonEC2(
            APPPATH . "/etc/pk-UIBAE6UUI6KM4GULBKP5BDLM7SOHTMN3.pem", 
            APPPATH . "/etc/cert-UIBAE6UUI6KM4GULBKP5BDLM7SOHTMN3.pem");
            
            $this->AWSAccountID = "788921246207";
        }
        
        function _testS3Bucket()
        {
            $AmazonS3 = new AmazonS3("0EJNVE9QFYY3TD554T02", "VOtWnbI2PmsqKOqDNVVgfLVsEnGD/6miiYDY552S");
            $res = $AmazonS3->ListBuckets();            
            $this->assertTrue(is_array($res->Bucket), "ListBuckets returned array");
            
            $res = $AmazonS3->CreateBucket("MySQLDumps");
            $this->assertTrue($res, "Bucket successfull created");
            
            $res = $AmazonS3->CreateObject("fonts/test.ttf", "offload-public", "/tmp/PhotoEditService.wsdl", "plain/text");
            $this->assertTrue($res, "Object successfull created");
            
            $res = $AmazonS3->DownloadObject("fonts/test.ttf", "offload-public");
            $this->assertTrue($res, "Object successfull downloaded");
            
            $res = $AmazonS3->DeleteObject("fonts/test.ttf", "offload-public");
            $this->assertTrue($res, "Object successfull removed");
        }

		function testDescribeAvailabilityZones()
        {
            $res = $this->AmazonEC2->DescribeAvailabilityZones();            
            $this->assertTrue($res->availabilityZoneInfo, "DescribeAvailabilityZones returned avail zones");
        }
        
        function _testCreateKeyPair()
        {
            $res = $this->AmazonEC2->CreateKeyPair("farm-2");            
            $this->assertTrue($res->keyMaterial, "CreateKeyPair returned key info");
        }
        
        function _testCreateSecurityGroup()
        {
            $res = $this->AmazonEC2->CreateSecurityGroup("testGroup", "testGroup");
            $this->assertTrue($res->return, "CreateSecurityGroup returned true");
        }
        
        function _testAuthorizeSecurityGroupIngress()
        {
            $IpPermissionSet = new IpPermissionSetType();
            $IpPermissionSet->AddItem("tcp", "80", "80", null, array("0.0.0.0/0"));
            
            $res = $this->AmazonEC2->AuthorizeSecurityGroupIngress($this->AWSAccountID, "testGroup", $IpPermissionSet);
            $this->assertTrue($res->return === true, "AuthorizeSecurityGroupIngress returned true");
        }
        
        function _testDeleteSecurityGroup()
        {
            $res = $this->AmazonEC2->DeleteSecurityGroup("testGroup");
            $this->assertTrue($res->return === true, "DeleteSecurityGroup returned true");
        }
        
        function _testDescribeSecurityGroups()
        {
            $groups = $this->AmazonEC2->DescribeSecurityGroups();
            $this->assertTrue(is_array($groups->securityGroupInfo->item), "AmazonEC2->DescribeSecurityGroups->securityGroupInfo->item is array");
        }
        
        function _testDescribeImages()
        {
            $DescribeImagesType = new DescribeImagesType();
		    $DescribeImagesType->ownersSet = array("item" => array("owner" => $this->AWSAccountID));
		    
		    $result = $this->AmazonEC2->DescribeImages($DescribeImagesType);
		    $this->assertTrue(is_array($result->imagesSet->item), "AmazonEC2->DescribeImages->imagesSet->item is array");
		    
		    $this->imageId = $result->imagesSet->item[0]->imageId;
        }
        
        function _testRunInstances()
        {
            $RunInstancesType = new RunInstancesType();
            $RunInstancesType->imageId = $this->imageId;
            $RunInstancesType->minCount = 1;
            $RunInstancesType->maxCount = 1;
            $RunInstancesType->AddSecurityGroup("default");
            $RunInstancesType->additionalInfo = "http://webta.net";
            $RunInstancesType->SetUserData("test123");
            $RunInstancesType->instanceType = "m1.small";
            
            $result = $this->AmazonEC2->RunInstances($RunInstancesType);
            $this->assertTrue($result->instancesSet->item->instanceId, "RunInstances return instanceId");
            $this->instanceId = $result->instancesSet->item->instanceId;
        }
        
        function _testTerminateInstances()
        {
            $res = $this->AmazonEC2->TerminateInstances(array($this->instanceId));
            $this->assertTrue($res->instancesSet->item->instanceId, "TerminateInstances return instanceId");
        }
    }


?>