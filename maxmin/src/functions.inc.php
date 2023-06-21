<?php
function getMaxMin($modules, $marks)
{

    $max_grade = 0; //initialize max_grade to 0

    //make for loop to iterate 5 values of modules and find the highest grade
    for ($i = 0; $i < 5; $i++) {

      //if new grade higher than current max grade then make it new max
      if (is_numeric($marks[$i])) {
        if ($marks[$i] >= $max_grade) {
          $max_grade = $marks[$i];
  
          //if modules index (module name for max grade) is equal to null or empty string then
          if (is_null($modules[$i]) || $modules[$i] == "") {
            //ask user to insert module name, give max grade
            $max_grade_value = array('module' => "Please insert module name", 'grade' => $marks[$i]);
          }
          else {
            //otherwise give max grade and module name associated with it
            $max_grade_value = array('module' => $modules[$i], 'grade' => $marks[$i]);
          }
        }
      }
    }

    //after all 5 iterations of the loop check if max_grade_value has been set, if not that means the marks passed in had 0 grades
    //produce an error message asking to insert module name and grade
    if (!isset($max_grade_value)) {
      $max_grade_value = array('module' => "Please insert module name", 'grade' => "Please insert grade");
    }

    //initialize current minimum grade to existing max or 0 if no marks found in array
    if (is_empty_array($marks)) {
      $min_grade = 0;
    } else {
      $min_grade = max($marks);
    }

    //for loop will iterate every grade and find lowest grade
    for ($i = 0; $i < 5; $i++) {

      //check if current mark is lower than minimum grade and not empty, if both conditions pass
      if ($marks[$i] <= $min_grade && $marks[$i] != "") {

        //make min grade new mark
        $min_grade = $marks[$i];

        //if module name doesnt exist then create array with min mark and error message asking user to insert module name
        if (is_null($modules[$i]) || $modules[$i] == "") {
          $min_grade_value = array('module' => "Please insert module name", 'grade' => $marks[$i]);
        }
        else {
          //if module name assigned to current new min_grade is not null or empty string then create array with current min grade and module name
          $min_grade_value = array('module' => $modules[$i], 'grade' => $marks[$i]);
        }
      }
    }
    
    //after all 5 iterations of the loop check if min_grade_value has been set, if not that means the marks passed in had 0 grades
    //produce an error message asking to insert module name and grade
    if (!isset($min_grade_value)) {
      $min_grade_value = array('module' => "Please insert module name", 'grade' => "Please insert grade");
    }

    //pass in new minimum grade and its assigned name as var_result and return it
    $var_result = array($max_grade_value, $min_grade_value);
    return $var_result;
}

    //check if array is empty function
function is_empty_array($arr) {
  if (is_array($arr)) {
    foreach($arr as $value){
      if(!empty($value)){
        return false;
      }
    }
  }
  return true;
}
