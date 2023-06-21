<?php
function getSortedModules($modules, $marks)
{
    $module_marks = array();
    for ($i = 0; $i < count($modules); $i++) {
    $temp = $i + 1;


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// rewritten function logic, instead of taking any value in arrays, replace with "missing name" or "mark" where apropriate
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    if($modules[$i] != "" && $modules[$i] != null ) { //if module name exists
      $module_array = array("module"=>$modules[$i]); //save module name in temporary array to push into final json as Sorted array
    }
    else {
      $module_array = array("module"=>"Missing name for: " . $temp); //if module name does not exist, specify at which module number 1..5 name is missing.
    }

    if ($marks[$i] != "" && $marks[$i] != null ) { // if mark does exist and is not empty
      $mark_array = array("marks"=>$marks[$i]); // we add the mark in temporary array to push into final json as Sorted array
    }
    else {
      $mark_array = array("marks"=>"Missing mark for: " . $temp); //if mark does not exist, specify at which position 1..5 mark is missing.
    }
    
    $pushto = $module_array + $mark_array; // we combine both "module" and "mark" arrays into 1 single array, '+' works best as it "combines" them, and not adds/merges/deletes
    array_push($module_marks,$pushto); // we push the temp array as final $module_marks array, before sorting in this function again
    
    }

    usort($module_marks, function($a, $b) { //we sort the array comparing 'marks' values.
          return $b['marks'] <=> $a['marks'];
    });

    // in the end, return the now ready array for JSON response, as final 'sorted_modules' field.
    return $module_marks;
}
