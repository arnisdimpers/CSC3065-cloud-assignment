package com.example.demo;

import org.springframework.web.bind.annotation.RestController;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.ResponseBody;
import org.springframework.web.servlet.config.annotation.CorsRegistry;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurer;
import org.springframework.boot.SpringApplication;
import org.springframework.web.bind.annotation.GetMapping;

import java.util.*;


@SpringBootApplication
@RestController
public class DemoApplication {

	// initializing global bool for array conversion function
	boolean convertError = false;
	// global string for module numbers with wrong input
	String wrongInput = "";

	// Java Springboot request for parameters - main body of function
	@GetMapping("/")
	@ResponseBody	//requesting parameters from url
	public HashMap bruh(@RequestParam(required = false) String text, @RequestParam(required = false) String mark_1,
			@RequestParam(required = false) String mark_2,
			@RequestParam(required = false) String mark_3, @RequestParam(required = false) String mark_4,
			@RequestParam(required = false) String mark_5,
			@RequestParam(required = false) String module_1, @RequestParam(required = false) String module_2,
			@RequestParam(required = false) String module_3,
			@RequestParam(required = false) String module_4, @RequestParam(required = false) String module_5) {

		// initializing string arrays to store module names and marks
		String[] nArr, mArr;
		// storing collected parameters in arrays - if parameter doesn't exist will be null or empty, will be catched and verified
		nArr = new String[] { module_1, module_2, module_3, module_4, module_5 };
		mArr = new String[] { mark_1, mark_2, mark_3, mark_4, mark_5 };

		// map object to print the JSON reply with
		HashMap<String, Object> map = new HashMap<>();

		// Accessing the convert array function, which returns a converted string array to int array, 
		// For values which are null or empty - function saves in global bool and string the details.
		int intArr[] = convertArray(mArr);
		System.out.println("Converted the array.");
		

		// for loop to cycle through nArr and mArr arrays - to choose how to populate 'map' JSON response
		for (int i = 0; i <= nArr.length - 1; i++) {
			int m1 = i + 1; // counter for user friendly numbers in JSON response when working with Arrays e.g. module 1 = 1, not module 1 = 0

			//if statement for when module name exists so not null and not empty, while marks also exist - not null.
			//this is a normal functioning operation, where every parameter is correct with named modules and marks in integer.

			if ((nArr[i] != null && nArr[i] != "") && (mArr[i] != null && mArr[i] != "")) {
				if (intArr[i] > 39) { //if statement to see if module is scoring 40+ which is considered a passing grade.
					map.put("answer" + m1,
							"Module " + m1 + ": " + nArr[i] + ". " + intArr[i] + " is a passing grade."); //adds to map JSON - answer1..5 when passed.
				} else { //if else where module is scoring below 40 which is a failing grade.
					map.put("answer" + m1,
							"Module " + m1 + ": " + nArr[i] + ". Failed."); //adds to map JSON - answer1..5 when failed.
				}
			}


			//if statement for when module name does NOT exist, so either null or empty. While marks exist - not null.
			//this operation has still normal functionality, despite module having no name. As such a "Missing Name" will be attached to let user know.

			else if ((nArr[i] == null || nArr[i] == "") && (mArr[i] != null || mArr[i] != "")) { 
				// if mark is 40+ which is considered a passing grade
				if (intArr[i] > 39) {
					map.put("answer" + m1,
							"Module " + m1 + ": name missing. " + intArr[i] + " is a passing grade."); //'map' json response with missing name and a passing grade.
				} else {
					map.put("answer" + m1,
							"Module " + m1 + ": name missing. Failed."); //'map' json response with missing name and failing grade because mark is below 40.
				}

			}

			//if statement for when name exists but mark is missing.
			else if ((nArr[i] != null && nArr[i] != "") && (mArr[i] == null || mArr[i] == "")) {
				map.put("answer" + m1, "Module " + m1 + ": " + nArr[i] + ". Failed."); //'map' JSON response with existing module name but Failing mark.

			}
			//if statement for when name is missing and mark is missing. For incorrect parameters such as empty or non existent JSON response.
			if ((nArr[i] == null || nArr[i] == "") && ( mArr[i] == null || mArr[i] == "")) {
				map.put("answer" + m1, "Module " + m1 + ": name missing. Failed."); //'map' JSON response with missing module name and Failing mark.

			}
		}

		// if statement to print Error True or error False.
		// string explanation for error is added to 'map' JSON response, displaying which module encountered an error. Module mark is non integer for modules: 1...5.
		if (convertError) {
			map.put("error", true); //set Error to True.
			map.put("string", "Non integer input for modules:" + wrongInput); //explaining the error.
			wrongInput = ""; //reset global wronginput string
			convertError = false; //reset global error for array conversion
		}

		//returns the final map to print as JSON response. This will include Answers1..5, Missing name, Missing marks, Error if exists and String explanation of error.
		return map; 
	}

	//convert string array of marks into int array and return it
	public int[] convertArray(String[] mArr) { //input string array of marks
		int size = mArr.length; //get length to work with
		int[] intArr = new int[size]; //create array int to return it with marks from for loop

		for (int i = 0; i < mArr.length; i++) { //for loop going through string array and parsing value into int array, try catch for if error
			if (mArr[i] != null) {
				try { //try catch if error occurs while parsing - if input string is not parsable to int - throw error
					if (mArr[i] == "") { mArr[i]="0"; } //empty mark initialied to 0, as it is just empty and should not be caught in Error for wrong input type
					intArr[i] = Integer.parseInt(mArr[i]);
					String t1 = mArr[i]; //temporary values for system print
					int t2 = intArr[i];
					System.out.println("PARSING " + t1 + " into this " + t2);
				} catch (Exception e) { //catch for error
					System.out.println("entered error catch");
					convertError = true; //change global convert error to true for final 'map' JSON
					int t = i+1;
					wrongInput += " " + Integer.toString(t); //add to global wrong input string the failed module number where number couldn't be parsed
				}
			}
		}
		return intArr; //return integer array now parsed with string mark input
	}


	public static void main(String[] args) {
		SpringApplication.run(DemoApplication.class, args);
	}

	
	@Bean
	public WebMvcConfigurer corsConfigurer() {
		return new WebMvcConfigurer() {
			@Override
			public void addCorsMappings(CorsRegistry registry) {
				registry.addMapping("/").allowedOrigins("*");
			}
		};
	}
}