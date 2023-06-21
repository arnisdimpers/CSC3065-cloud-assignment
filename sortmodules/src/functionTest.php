<?php
use PHPUnit\Framework\TestCase;
require 'functions.inc.php';

class functionTest extends TestCase
{

    // live http test - very wrong input type for marks returns an error message and does not crash or take the value as 'null',
    // instead everything now works as normal and message is displayed to user with 'missing mark 1...5' for specific mark fields.
    public function test_live_http_wrong_input_for_marks_non_numeric_messages_displayed()
    {
        $web='http://sortmodules.40233517.qpc.hal.davecutting.uk/?module_1=m1&mark_1=&module_2=m2&mark_2=&module_3=m3&mark_3=&module_4=m4&mark_4=&module_5=m5&mark_5=';
 
        $obj = json_decode(file_get_contents($web), true);
        echo $obj["error"];

        $this->assertEquals("m5", $obj["sorted_modules"][0]["module"]);
        $this->assertEquals("m4", $obj["sorted_modules"][1]["module"]);
        $this->assertEquals("m3", $obj["sorted_modules"][2]["module"]);
        $this->assertEquals("m2", $obj["sorted_modules"][3]["module"]);
        $this->assertEquals("m1", $obj["sorted_modules"][4]["module"]);
        $this->assertEquals("Missing mark for: 5", $obj["sorted_modules"][0]["marks"]);
        $this->assertEquals("Missing mark for: 4", $obj["sorted_modules"][1]["marks"]);
        $this->assertEquals("Missing mark for: 3", $obj["sorted_modules"][2]["marks"]);
        $this->assertEquals("Missing mark for: 2", $obj["sorted_modules"][3]["marks"]);
        $this->assertEquals("Missing mark for: 1", $obj["sorted_modules"][4]["marks"]);
    }

    // live http test for code 200, this signifies the function is operational and ready for requests.
    public function test_live_http_200()
    {
        $web='http://sortmodules.40233517.qpc.hal.davecutting.uk/';
        
        $obj = json_decode(file_get_contents($web), true);
        file_get_contents($web);
        echo $obj["error"];

        $read = json_encode($http_response_header[0]);

        //assert equals to compare the read http status of the page is equal to 200.
        $this->assertEquals('"HTTP\/1.1 200 OK"', $read);
    }
    
    
    // live http test to read the error string message that should explain what modules and marks are missing.
    // this string is smart and changes on actual depending missing parameters for names and marks.
    public function test_live_http_smart_error_message_for_SOME_missing_parameters_test_2_of_2()
    {
        $web='http://sortmodules.40233517.qpc.hal.davecutting.uk/?module_1=m1&mark_1=&module_2=&mark_2=&module_3=m3&mark_3=8&module_4=&mark_4=&module_5=m5&mark_5=10';
 
        $obj = json_decode(file_get_contents($web), true);
        echo $obj["error"];
        //when only some modules are missing, error is set to false. Because user is still receiving a sorted array as well as this error String
        $this->assertEquals(false, $obj["error"]); 
        $this->assertEquals("Missing name for module: 2 4 . missing mark for module: 1 2 4 ", $obj["string"]);
    }

    // live http test to read the error string message that should explain what modules and marks are missing.
    // this string is smart and changes on actual depending missing parameters for names and marks.
    public function test_live_http_smart_error_message_for_ALL_missing_parameters_test_1_of_2()
    {
        $web='http://sortmodules.40233517.qpc.hal.davecutting.uk/';
 
        $obj = json_decode(file_get_contents($web), true);
        echo $obj["error"];
        $this->assertEquals(true, $obj["error"]); //when all modules are missing the Error is set to true.
        $this->assertEquals("Missing name for module: 1 2 3 4 5 . missing mark for module: 1 2 3 4 5 ", $obj["string"]);
    }

    // Missing parameters are no longer displayed as null values in the array sorted reply, but a message instead explaining that Module 1, or Mark 5 is missing.
    // These messages are also displayed for non-numeric values. If Mark is 'abc' or ';;;' then the string message will say the input is invalid. Requires Int.
    public function test_live_http_missing_parameters_for_name_are_no_longer_null_but_an_explaining_message() 
    {
        $web='http://sortmodules.40233517.qpc.hal.davecutting.uk/?module_1=&mark_1=12&module_2=&mark_2=99&module_3=&mark_3=89&module_4=&mark_4=105&module_5=&mark_5=10';
 
        $obj = json_decode(file_get_contents($web), true);
        echo $obj["error"];

        $this->assertEquals("Missing name for: 4", $obj["sorted_modules"][0]["module"]);
        $this->assertEquals("Missing name for: 2", $obj["sorted_modules"][1]["module"]);
        $this->assertEquals("Missing name for: 3", $obj["sorted_modules"][2]["module"]);
        $this->assertEquals("Missing name for: 1", $obj["sorted_modules"][3]["module"]);
        $this->assertEquals("Missing name for: 5", $obj["sorted_modules"][4]["module"]);
        $this->assertEquals("105", $obj["sorted_modules"][0]["marks"]);
        $this->assertEquals("99", $obj["sorted_modules"][1]["marks"]);
        $this->assertEquals("89", $obj["sorted_modules"][2]["marks"]);
        $this->assertEquals("12", $obj["sorted_modules"][3]["marks"]);
        $this->assertEquals("10", $obj["sorted_modules"][4]["marks"]);
    }

    // live http test for the basic functionality of this whole function.
    public function test_http_proper_functionality()
    {
        $web='http://sortmodules.40233517.qpc.hal.davecutting.uk/?module_1=m1&mark_1=12&module_2=m2&mark_2=99&module_3=m3&mark_3=89&module_4=m4&mark_4=105&module_5=m5&mark_5=10';
 
        $obj = json_decode(file_get_contents($web), true);
        echo $obj["error"];
        
        // we assert equals all the lines inside the array for sorted values.
        // in the table below they are sorted accordingly, and are equal to the returned array from function.
        $this->assertEquals("m4", $obj["sorted_modules"][0]["module"]);
        $this->assertEquals("m2", $obj["sorted_modules"][1]["module"]);
        $this->assertEquals("m3", $obj["sorted_modules"][2]["module"]);
        $this->assertEquals("m1", $obj["sorted_modules"][3]["module"]);
        $this->assertEquals("m5", $obj["sorted_modules"][4]["module"]);
        $this->assertEquals("105", $obj["sorted_modules"][0]["marks"]);
        $this->assertEquals("99", $obj["sorted_modules"][1]["marks"]);
        $this->assertEquals("89", $obj["sorted_modules"][2]["marks"]);
        $this->assertEquals("12", $obj["sorted_modules"][3]["marks"]);
        $this->assertEquals("10", $obj["sorted_modules"][4]["marks"]);

    }

    // Live http test for some missing marks, fixed to displaye a Missing message instead of Null value, while still sorting remaining marks.
    public function test_live_http_missing_parameters_for_MARKS_are_no_longer_null_but_an_explaining_message()
    {
        $web='http://sortmodules.40233517.qpc.hal.davecutting.uk/?module_1=m1&mark_1=&module_2=m2&mark_2=&module_3=m3&mark_3=&module_4=m4&mark_4=&module_5=m5&mark_5=';
 
        $obj = json_decode(file_get_contents($web), true);
        echo $obj["error"];

        $this->assertEquals("m5", $obj["sorted_modules"][0]["module"]);
        $this->assertEquals("m4", $obj["sorted_modules"][1]["module"]);
        $this->assertEquals("m3", $obj["sorted_modules"][2]["module"]);
        $this->assertEquals("m2", $obj["sorted_modules"][3]["module"]);
        $this->assertEquals("m1", $obj["sorted_modules"][4]["module"]);
        $this->assertEquals("Missing mark for: 5", $obj["sorted_modules"][0]["marks"]);
        $this->assertEquals("Missing mark for: 4", $obj["sorted_modules"][1]["marks"]);
        $this->assertEquals("Missing mark for: 3", $obj["sorted_modules"][2]["marks"]);
        $this->assertEquals("Missing mark for: 2", $obj["sorted_modules"][3]["marks"]);
        $this->assertEquals("Missing mark for: 1", $obj["sorted_modules"][4]["marks"]);
    }

    // instead of working with null values, or crashing - the function has been fixed to display an error message with information
    // message: "Missing name for module: 1 2 3 4 5 . missing mark for module: 1 2 3 4 5 "
    // this is a smart function that changes to what actual names and marks are missing, and is displayed to user at all times but will not be read
    // on the homepage side if Error = true will not be triggered, which it will with non numeric input for marks such as 'abc'. More tests on this to follow.
    public function test_no_input_for_module_name_and_marks_returns_apropriate_message()
    {   
        //we create a manual array to test against the function array that's returned.
        $manual = array();

        $m1 = "";
        $m2 = "";
        $m3 = "";
        $m4 = "";
        $m5 = "";

        $m_1 = ""; //missing mark 1 instead will now return "missing mark" field to let user know.
        $m_2 = ""; //missing mark 2 instead will now return "missing mark" field to let user know.
        $m_3 = "";
        $m_4 = ""; //missing mark 4 instead will now return "missing mark" field to let user know.
        $m_5 = ""; 

        //fill in the unsorted array modules and marks to use for function.
        $modules = array($m1,$m2,$m3,$m4,$m5);
        $marks = array($m_1,$m_2,$m_3,$m_4,$m_5);


        //we fill the manual (expected) array with apropriately sorted modules and marks in the order they should be.
        //for each temporary array, we push it into the manual (expected) array.


        $temporaryArray = array("module"=>"Missing name for: 5", "marks"=>"Missing mark for: 5");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"Missing name for: 4", "marks"=>"Missing mark for: 4");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"Missing name for: 3", "marks"=>"Missing mark for: 3");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"Missing name for: 2", "marks"=>"Missing mark for: 2");
        array_push($manual,$temporaryArray); 

        $temporaryArray = array("module"=>"Missing name for: 1", "marks"=>"Missing mark for: 1");
        array_push($manual,$temporaryArray);


        //use the function on non-sorted arrays of modules and marks.
        $output = getSortedModules($modules, $marks);

        //print an assert equals whether the manually filled out sorted array is the same as $output array from function.
        $this->assertEquals($manual,$output);
    
    }

    // User input test for normal functionality as intended.
    public function test_user_input()
    {
        //we create a manual array to test against the function array that's returned.
        $manual = array();

        $m1 = "m1";
        $m2 = "m2";
        $m3 = "m3";
        $m4 = "m4";
        $m5 = "m5";
        $m_1 = "99";
        $m_2 = "101";
        $m_3 = "85";
        $m_4 = "3";
        $m_5 = "17";

        //fill in the unsorted array modules and marks to use for function.
        $modules = array($m1,$m2,$m3,$m4,$m5);
        $marks = array($m_1,$m_2,$m_3,$m_4,$m_5);

        //we fill the manual (expected) array with apropriately sorted modules and marks in the order they should be.
        $temporaryArray = array("module"=>"m2", "marks"=>"101");
        array_push($manual,$temporaryArray); //for each temporary array, we push it into the manual (expected) array.

        $temporaryArray = array("module"=>"m1", "marks"=>"99");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m3", "marks"=>"85");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m5", "marks"=>"17");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m4", "marks"=>"3");
        array_push($manual,$temporaryArray);

        //use the function on non-sorted arrays of modules and marks.
        $output = getSortedModules($modules, $marks);

        //print an assert equals whether the manually filled out sorted array is the same as $output array from function.
        $this->assertEquals($manual,$output);
    }

    // No mark given now returns "missing mark for: 5" instead of "null" which provides good user knowledge they missed or didn't input the mark for given module.
    public function test_new_missing_mark_field_instead_of_null()
    {   
        //we create a manual array to test against the function array that's returned.
        $manual = array();

        $m1 = "m1";
        $m2 = "m2";
        $m3 = "m3";
        $m4 = "m4";
        $m5 = "m5";

        $m_1 = ""; //missing mark 1 instead will now return "missing mark" field to let user know.
        $m_2 = ""; //missing mark 2 instead will now return "missing mark" field to let user know.
        $m_3 = "85";
        $m_4 = ""; //missing mark 4 instead will now return "missing mark" field to let user know.
        $m_5 = "17"; 

        //fill in the unsorted array modules and marks to use for function.
        $modules = array($m1,$m2,$m3,$m4,$m5);
        $marks = array($m_1,$m_2,$m_3,$m_4,$m_5);


        //we fill the manual (expected) array with apropriately sorted modules and marks in the order they should be.
        //for each temporary array, we push it into the manual (expected) array.

        $temporaryArray = array("module"=>"m4", "marks"=>"Missing mark for: 4");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m2", "marks"=>"Missing mark for: 2");
        array_push($manual,$temporaryArray); 

        $temporaryArray = array("module"=>"m1", "marks"=>"Missing mark for: 1");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m3", "marks"=>"85");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m5", "marks"=>"17");
        array_push($manual,$temporaryArray);

        //use the function on non-sorted arrays of modules and marks.
        $output = getSortedModules($modules, $marks);

        //print an assert equals whether the manually filled out sorted array is the same as $output array from function.
        $this->assertEquals($manual,$output);
    
    }

    // Function now returns missing module name positions, while still sorting the marks numerically. Instead of 'null' we now have 'Missing name module: 1"
    public function test_new_missing_name_field_instead_of_null()
    {   
        //we create a manual array to test against the function array that's returned.
        $manual = array();

        $m1 = "m1";
        $m2 = ""; //no input given for module name 2
        $m3 = "m3";
        $m4 = "m4";
        $m5 = ""; //missing module name 5

        $m_1 = "99";
        $m_2 = "101";
        $m_3 = "85";
        $m_4 = "3";
        $m_5 = "17";

        //fill in the unsorted array modules and marks to use for function.
        $modules = array($m1,$m2,$m3,$m4,$m5);
        $marks = array($m_1,$m_2,$m_3,$m_4,$m_5);

        //we fill the manual (expected) array with apropriately sorted modules and marks in the order they should be.
        $temporaryArray = array("module"=>"Missing name for: 2", "marks"=>"101");
        array_push($manual,$temporaryArray); //for each temporary array, we push it into the manual (expected) array.

        $temporaryArray = array("module"=>"m1", "marks"=>"99");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m3", "marks"=>"85");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"Missing name for: 5", "marks"=>"17");
        array_push($manual,$temporaryArray);

        $temporaryArray = array("module"=>"m4", "marks"=>"3");
        array_push($manual,$temporaryArray);

        //use the function on non-sorted arrays of modules and marks.
        $output = getSortedModules($modules, $marks);

        //print an assert equals whether the manually filled out sorted array is the same as $output array from function.
        $this->assertEquals($manual,$output);
    
    }

    



    


}