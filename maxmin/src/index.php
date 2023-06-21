<?php
header("Access-Control-Allow-Origin: *");
header("Content-type: application/json");
require('functions.inc.php');

$output = array(
	"error" => false,
  	"modules" => "",
	"marks" => 0,
	"max_module" => "",
	"min_module" => ""
);

$module_1 = $_REQUEST['module_1'];
$module_2 = $_REQUEST['module_2'];
$module_3 = $_REQUEST['module_3'];
$module_4 = $_REQUEST['module_4'];
$module_5 = $_REQUEST['module_5'];
$mark_1 = $_REQUEST['mark_1'];
$mark_2 = $_REQUEST['mark_2'];
$mark_3 = $_REQUEST['mark_3'];
$mark_4 = $_REQUEST['mark_4'];
$mark_5 = $_REQUEST['mark_5'];

//save all modules and marks into seperate arrays
$modules = array($module_1,$module_2,$module_3,$module_4,$module_5);
$marks = array($mark_1,$mark_2,$mark_3,$mark_4,$mark_5);

//function that checks if array has 0 values
function is_array_empty($arr){
	if(is_array($arr)){
		foreach($arr as $value){
			if(!empty($value)){
				//if values found in array return false
				return false;
			}
		}
	}
	//if no values found in array and its empty then return true
	return true;
}

//double check if modules and marks paremeters all missing or null then respond with error message
if (is_array_empty($modules)) {
	if (is_array_empty($marks)) {
		$output['error']=true;
		$output['error_message']="Please insert at least 1 module or grade";

		echo json_encode($output);
		exit();
	}
}

//if array is not empty continue with program and get max min from custom function method
$max_min_modules=getMaxMin($modules, $marks);

//output the resulting max and min modules with marks and module names
$output['modules']=$modules;
$output['marks']=$marks;
$output['max_module']=$max_min_modules[0];
$output['min_module']=$max_min_modules[1];

echo json_encode($output);
exit();
