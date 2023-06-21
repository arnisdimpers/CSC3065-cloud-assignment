<?php
use PHPUnit\Framework\TestCase;
require 'functions.inc.php';

//phpunit is a extension with which i chose to do unit tests, because its an addon it was hard to get the yaml working, but i did it
//phpunit test functionTest.php
class functionTest extends TestCase
{
    public function test_Max_Min_Value_Correct()
    {
        //create modules with different names to be passed into function
        $module1 = "programming";
        $module2 = "network";
        $module3 = "databases";
        $module4 = "cloud";
        $module5 = "theory";

        //put all modules into modules array
        $array_module = array($module1, $module2, $module3, $module4, $module5);

        //make a grades array and fill in with testing grades
        $array_grade = array(90, 24, 46, 75, 20);

        //create expected values that programming will be max
        $expected_max = array('module' => "programming", 'grade' => "90");

        //create expected values that theory will be lowest with 20 grade
        $expected_min = array('module' => "theory", 'grade' => "20");

        //save both min and max expectations into array
        $expected_result = array($expected_max, $expected_min);

        //compare if expected array equals same as 2 module and grade arrays inserted into the getMaxMin function
        $this->assertEquals($expected_result, getMaxMin($array_module, $array_grade));
    }

    public function test_Modules_Filled_Grades_Empty_Still_Works()
    {
        //create modules with different names to be passed into function
        $module1 = "programming";
        $module2 = "network";
        $module3 = "databases";
        $module4 = "cloud";
        $module5 = "theory";

        //put all modules into modules array
        $array_module = array($module1, $module2, $module3, $module4, $module5);

        //create array of grades filled with NULL values for testing how it will handle
        $array_grade = array(null, null, null, null, null);

        //create expected min and max
        $expected_max = array('module' => "Please insert module name", 'grade' => "Please insert grade");
        $expected_min = array('module' => "Please insert module name", 'grade' => "Please insert grade");

        //combine both expected values into result array
        $expected_result = array($expected_max, $expected_min);

        //compare if expected array matches the function
        $this->assertEquals($expected_result, getMaxMin($array_module, $array_grade));
    }

    public function test_Modules_Empty_Grades_Filled_Still_Works()
    {
        //create modules with NULL value names for testing purpose
        $array_module = array(null, null, null, null, null);

        //create array filled with grades
        $array_grade = array(12, 24, 46, 65, 80);

        //create expected max and min modules asking for module name but sorting grades correctly
        $expected_max = array('module' => "Please insert module name", 'grade' => "80");
        $expected_min = array('module' => "Please insert module name", 'grade' => "12");

        //combine expected arrays into 1 single result
        $expected_result = array($expected_max, $expected_min);

        //see if expected result matches actual function
        $this->assertEquals($expected_result, getMaxMin($array_module, $array_grade));
    }

    public function test_Half_Modules_Grades_Empty()
    {
        //create modules with different names to be passed into function
        $module1 = "programming";
        $module2 = "network";
        $module3 = "databases";

        //fill modules array with module names and also 2 NULL values for testing
        $array_module = array($module1, $module2, $module3, null, null);

        //fill grade array with grades and also NULL values
        $array_grade = array(12, 24, 46, null, null);

        //create expected result that should ignore NULL values and see max min regardless
        $expected_max = array('module' => "databases", 'grade' => "46");
        $expected_min = array('module' => "programming", 'grade' => "12");

        //combine expected output
        $expected_result = array($expected_max, $expected_min);
        
        //see if expected result array matches the actual function
        $this->assertEquals($expected_result, getMaxMin($array_module, $array_grade));
    }

    public function test_Empty_Modules_Empty_Grades()
    {
        //create all modules & grades as NULL to see if program will handle it correctly with error messaging
        $array_module = array(null, null, null, null, null);
        $array_grade = array(null, null, null, null, null);

        //create expected result
        $expected_max = array('module' => "Please insert module name", 'grade' => "Please insert grade");
        $expected_min = array('module' => "Please insert module name", 'grade' => "Please insert grade");
        $expected_result = array($expected_max, $expected_min);

        //test if expected result matches actual function
        $this->assertEquals($expected_result, getMaxMin($array_module, $array_grade));
    }


    //HTTP website API testing improvement
    public function test_Web_Status_Code_200()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/';

        //decode url response into JSON format
        $obj = json_decode(file_get_contents($url), true);
        file_get_contents($url);

        echo $obj["error"];

        //assert code 200 matches service
        $this->assertEquals('"HTTP\/1.1 200 OK"', json_encode($http_response_header[0]));
    }

    public function test_Web_Error_Is_True_Error_Msg_Correct_For_No_Parameters_In_Url()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/';

        //decode url response into JSON format
        $obj = json_decode(file_get_contents($url), true);
        echo $obj["error"];

        $this->assertEquals(true, $obj["error"]);
        $this->assertEquals("Please insert at least 1 module or grade", $obj["error_message"]);
    }

    public function test_Web_Max_Min_Grades_Both_Correct()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/?mark_1=89&mark_2=75&mark_3=86&mark_4=70&mark_5=60&module_1=programming&module_2=network&module_3=cloud&module_4=algorithms&module_5=theory';

        //decode url response into JSON format
        $obj = json_decode(file_get_contents($url), true);
        echo $obj["error"];

        //assert that page status is false because no errors found
        $this->assertEquals(false, $obj["error"]);

        //testing for max module name and grade
        $this->assertEquals("programming", $obj["max_module"]["module"]);
        $this->assertEquals("89", $obj["max_module"]["grade"]);

        //testing for min module name and grade
        $this->assertEquals("theory", $obj["min_module"]["module"]);
        $this->assertEquals("60", $obj["min_module"]["grade"]);
    }

    public function test_Web_Max_Min_Grades_No_Module_Names_Returns_Correct_Grade_And_Msg_Asking_For_Module_Names()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/?mark_1=89&mark_2=75&mark_3=86&mark_4=70&mark_5=60';

        //decode url response into JSON format
        $obj = json_decode(file_get_contents($url), true);
        echo $obj["error"];

        //test that page returns error flag false
        $this->assertEquals(false, $obj["error"]);

        //testing for max module name and grade
        $this->assertEquals("Please insert module name", $obj["max_module"]["module"]);
        $this->assertEquals("89", $obj["max_module"]["grade"]);

        //testing for min module name and grade
        $this->assertEquals("Please insert module name", $obj["min_module"]["module"]);
        $this->assertEquals("60", $obj["min_module"]["grade"]);
    }

    public function test_Web_Modules_Filled_Grades_All_Empty_Returns_Error_Message_Asking_For_Grades()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/?module_1=programming&module_2=network&module_3=cloud&module_4=algorithms&module_5=theory';

        //decode url response into JSON format1
        $obj = json_decode(file_get_contents($url), true);
        echo $obj["error"];

        //test that page returns error flag false
        $this->assertEquals(false, $obj["error"]);

        //testing for max module name and grade
        $this->assertEquals("Please insert module name", $obj["max_module"]["module"]);
        $this->assertEquals("Please insert grade", $obj["max_module"]["grade"]);

        //testing for min module name and grade
        $this->assertEquals("Please insert module name", $obj["min_module"]["module"]);
        $this->assertEquals("Please insert grade", $obj["min_module"]["grade"]);
    }

    public function test_Web_Error_Handling_Half_Empty_Grades_Half_Empty_Modules_Returns_Correct_Answer()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/?mark_1=89&mark_2=75&mark_3=86&module_1=cloud&module_2=algorithms&module_3=theory';

        //decode url response into JSON format
        $obj = json_decode(file_get_contents($url), true);
        echo $obj["error"];

        //test that page returns error flag false
        $this->assertEquals(false, $obj["error"]);

        //testing for max module name and grade
        $this->assertEquals("cloud", $obj["max_module"]["module"]);
        $this->assertEquals("89", $obj["max_module"]["grade"]);

        //testing for min module name and grade
        $this->assertEquals("algorithms", $obj["min_module"]["module"]);
        $this->assertEquals("75", $obj["min_module"]["grade"]);
    }

    public function test_Web_Initialized_To_Null_All_Parameters_Return_Correct_Error_Response()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/?mark_1=&mark_2=&mark_3=&mark_4=&mark_5=&module_1=&module_2=&module_3=&module_4=&module_5=';

        //decode url response into JSON format
        $obj = json_decode(file_get_contents($url), true);
        echo $obj["error"];

        //test that page error status = true because all parameters missing
        $this->assertEquals(true, $obj["error"]);

        //read error message
        $this->assertEquals("Please insert at least 1 module or grade", $obj["error_message"]);
    }

    public function test_Web_Grades_All_Initialized_To_Null_Returns_Error_Asking_To_Insert_Module_Grades()
    {
        //create service URL to be used in test with custom parameters
        $url='http://maxmin.40233517.qpc.hal.davecutting.uk/?mark_1=&mark_2=&mark_3=&mark_4=&mark_5=&module_1=programming&module_2=network&module_3=cloud&module_4=algorithms&module_5=theory';

        //decode url response into JSON format1)
        $obj = json_decode(file_get_contents($url), true);
        echo $obj["error"];

        //assert that error flag is false
        $this->assertEquals(false, $obj["error"]);

        //testing for min module name and grade
        $this->assertEquals("Please insert module name", $obj["min_module"]["module"]);
        $this->assertEquals("Please insert grade", $obj["min_module"]["grade"]);

        //testing for max module name and grade
        $this->assertEquals("Please insert module name", $obj["max_module"]["module"]);
        $this->assertEquals("Please insert grade", $obj["max_module"]["grade"]);
    }
}
