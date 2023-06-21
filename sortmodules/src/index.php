<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
require('functions.inc.php');

// to run: php -S 127.0.0.1:8000

$output = array(
	"error" => false,
  "modules" => "",
	"marks" => 0,
	"sorted_modules" => ""
);

// a new isset statement is required for all parameters because of new php 7.2+ updates (tested on 8.1.12).
// These errors when parameters does not exist or is null, do not automatically become null anymore, and instead of
// a small error message which can be ignored, now crashes the program. Reading parameters with isset and default 'null' fixed the new php issies.
if (isset($_REQUEST['module_1'])) {
	$module_1 = $_REQUEST['module_1'];
} else {
	$module_1 = null;
}
if (isset($_REQUEST['module_2'])) {
	$module_2 = $_REQUEST['module_2'];
} else {
	$module_2 = null;
}
if (isset($_REQUEST['module_3'])) {
	$module_3 = $_REQUEST['module_3'];
} else {
	$module_3 = null;
}
if (isset($_REQUEST['module_4'])) {
	$module_4 = $_REQUEST['module_4'];
} else {
	$module_4 = null;
}
if (isset($_REQUEST['module_5'])) {
	$module_5 = $_REQUEST['module_5'];
} else {
	$module_5 = null;
}
if (isset($_REQUEST['mark_1'])) {
	$mark_1 = $_REQUEST['mark_1'];
} else {
	$mark_1 = null;
}
if (isset($_REQUEST['mark_2'])) {
	$mark_2 = $_REQUEST['mark_2'];
} else {
	$mark_2 = null;
}
if (isset($_REQUEST['mark_3'])) {
	$mark_3 = $_REQUEST['mark_3'];
} else {
	$mark_3 = null;
}
if (isset($_REQUEST['mark_4'])) {
	$mark_4 = $_REQUEST['mark_4'];
} else {
	$mark_4 = null;
}
if (isset($_REQUEST['mark_5'])) {
	$mark_5 = $_REQUEST['mark_5'];
} else {
	$mark_5 = null;
}

$modules = array($module_1,$module_2,$module_3,$module_4,$module_5);
$marks = array($mark_1,$mark_2,$mark_3,$mark_4,$mark_5);



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// added to the function - check for existing parameters, which ones are missing and JSON response messages on what is wrong.
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//missing module names
$missingModules = ""; 
//missing marks
$missingMarks = ""; 
//counter for parameters with no input. If this exceeds the number of existing parameters the program will display message explaining in detail error.
$empty = 0;


//For loop to count the missing or "null" values in input arrays, if exists then save the array position in a message to display to user.
for ($i = 0; $i < count($modules); $i++) {
	$temp = $i+1; //user friendly counter, instead of saving 0...4 as current array that is missing the value - we will position as save 1...5
	if (!is_numeric($marks[$i])) { // check if the input is NUMERIC, if mark is non numeric - make it Null, program will work as no value was given.
		$marks[$i] = null;
	}
	if (empty($modules[$i])) { //check whether module name exists.
		$missingModules .= $temp . " "; //adding to the message what position in array is missing the module names.
		$empty++; //total missing parameter counter goes up by 1.
	}
	if (empty($marks[$i])) { //check whether mark exists.
		$missingMarks .= $temp . " "; //adding to the message what position in array is missing the mark.
		$empty++; //total missing parameter counter goes up by 1.
	}
}

// If empty parameters are below 10 = means we can still work with existing parameters and despite not having some input - produce an answer.
if ($empty < 10) {
// If loop explaining the kind of input error we encounter. 
	if ($missingModules != "" && $missingMarks != null) { // If missing both module names and marks = display both of them in the error message.
		$output['error'] = false;
		$output['string']= "Missing name for module: " .  $missingModules . ". missing mark for module: " . $missingMarks . "";
	} else if ($missingMarks != null) { // If missing marks = display the missing marks only.
		$output['error'] = false;
		$output['string'] = "missing mark for module: " . $missingMarks;
	} else if ($missingModules != "") { // If missing module names = display the missing module names only.
		$output['error'] = false;
		$output['string'] = "Missing name for module: " .  $missingModules;
	}
	$sorted_modules=getSortedModules($modules, $marks);
	$output['modules']=$modules;
	$output['marks']=$marks;
	$output['sorted_modules']=$sorted_modules;
}
// Else if missing parameters exceed 9, meaning all of them are missing - produce a cleaner response with explaination
// AND don't calculate the sorted modules answer, because we don't have any paramteres to work with at all.
else {
	$output['error'] = true;
	$output['string']= "Missing name for module: " .  $missingModules . ". missing mark for module: " . $missingMarks . "";
}

echo json_encode($output);
exit();
