<?	
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
     * @package    Data
     * @subpackage Formater
     * @copyright  Copyright (c) 2003-2007 Webta Inc, http://www.gnu.org/licenses/gpl.html
     * @license    http://www.gnu.org/licenses/gpl.html
     * @filesource
     * 
     */
    
	Core::Load("Data/Formater");
	
	/**
	 * @category   LibWebta
     * @package    Data
     * @subpackage Formater
	 * @name Data_Formater_Test
	 */
	class Data_Formater_Test extends UnitTestCase 
	{
        function __construct() 
        {
            $this->UnitTestCase('Data/Formater Tests');
        }
        
        function testFormater() 
        {
        	// BytesToString
        	$res = Formater::Bytes2String(23552);
        	$this->assertEqual($res, "23 KB", "BytesToString");
        	
        	//Date2Fuzzy
        	$res = Formater::Date2Fuzzy("2006-05-04 00:00:01");
        	$this->assertEqual($res, "May 4 at 12:00am", "Date2Fuzzy");
        	
        	//CompareDates
        	$res = Formater::CompareDates(date("Y-m-d"), date("Y-m-d", time()-102240));
        	$this->assertEqual($res, 1, "CompareDates");
        	
        	//TimeStringToStamp
        	$res = Formater::TimeString2Stamp(date("Y-m-d"));
        	$this->assertEqual($res, mktime(0,0,0,date("m"), date("d"), date("Y")), "TimeStringToStamp");
        	
        	//TimeStampToString
        	$res = Formater::TimeStamp2String(time(), "Y-m-d");
        	$this->assertEqual($res, date("Y-m-d"), "TimeStampToString");
        	
        	//TimeFormat
        	$res = Formater::TimeFormat(time(), "Y-m-d");
        	$this->assertEqual($res, date("Y-m-d"), "TimeFormat");
        	
        	//TimeToHumanReadable
        	$res = Formater::Time2HumanReadable(86400);
	       	$this->assertTrue((strcasecmp($res,'1 day') == 0), "TimeToHumanReadable");
        	
        	//FuzzyTimeString
        	$res = Formater::FuzzyTimeString(time());
			$this->assertTrue(stristr($res, "today"), "FuzzyTimeString");
			
			// time ago
			$time = time() - 58 - 60*2;
			$res = Formater::TimeAgo($time);
			$this->assertEqual($res, "2 mins 58 seconds ago", "TimeAgo");
			
        }
    }
?>