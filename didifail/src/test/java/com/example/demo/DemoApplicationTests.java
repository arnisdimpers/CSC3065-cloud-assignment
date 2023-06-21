package com.example.demo;

import static org.assertj.core.api.Assertions.assertThat;

import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.context.SpringBootTest.WebEnvironment;
import org.springframework.boot.test.web.client.TestRestTemplate;
import org.springframework.boot.web.server.LocalServerPort;
import org.springframework.http.HttpStatus;

@SpringBootTest(webEnvironment = WebEnvironment.RANDOM_PORT)
public class DemoApplicationTests {

	@LocalServerPort
	private int port;

	@Autowired
	private TestRestTemplate restTemplate;

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////
	////// Live HTTP Tests///////Live HTTP Tests////////Live HTTP Tests////
	////// Live HTTP Tests///////Live HTTP Tests////////Live HTTP Tests////
	////// Live HTTP Tests///////Live HTTP Tests////////Live HTTP Tests////
	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	// Live HTTP test to check OK status code of function. If it's ready for
	// operation or not.
	@Test
	public void HTTP_Status_Code_OK_Test() throws Exception {
		assertThat(this.restTemplate.getForEntity("http://didifail.40233517.qpc.hal.davecutting.uk/",
				String.class).getStatusCode()).isEqualTo(HttpStatus.OK);
	}

	// Completely broken test works as intended and everything is caught and
	// response JSON is appropriate.
	// Some missing module names, some missing marks, some Failing marks, some
	// Passing marks, some Wrong Input marks. Some missing parameters.
	// Returns JSON response as intended with displayed Module Names, "Missing
	// names", "Missing Marks" and Failed/Passed. Error is True because of wrong
	// input for Marks,
	// and String is explaining which module encountered the wrong Input error.
	@Test
	public void HTTP_Break_Everything_Test() throws Exception {
		assertThat(this.restTemplate.getForObject(
				"http://didifail.40233517.qpc.hal.davecutting.uk/?module_1=m1&mark_1=abc&module_2=&mark_2=1&mark_3=7581&module_4=&mark_4=&module_5=12373+no_&mark_5=0+123+41+65-123/-10",
				String.class)).contains(
						"{\"string\":\"Non integer input for modules: 1 5\",\"answer3\":\"Module 3: name missing. 7581 is a passing grade.\",\"answer2\":\"Module 2: name missing. Failed.\",\"answer5\":\"Module 5: 12373 no_. Failed.\",\"answer4\":\"Module 4: name missing. Failed.\",\"error\":true,\"answer1\":\"Module 1: m1. Failed.\"}");
	}

	// Live HTTP for Error - True. Wrong input type for marks will cause the Error
	// True to appear with explanation what mark encountered the wrong input.
	@Test
	public void HTTP_Error_True_Wrong_Input_Type_For_Marks_Test() throws Exception {
		assertThat(this.restTemplate.getForEntity(
				"http://didifail.40233517.qpc.hal.davecutting.uk/?module_1=m1&mark_1=abc&module_2=m2&mark_2='''&module_3=m3&mark_3={}{}&module_4=m4&mark_4=this mark is wrong&module_5=m5&mark_5=||||",
				String.class).getBody()).contains(
						"{\"string\":\"Non integer input for modules: 1 2 3 4 5\",\"answer3\":\"Module 3: m3. Failed.\",\"answer2\":\"Module 2: m2. Failed.\",\"answer5\":\"Module 5: m5. Failed.\",\"answer4\":\"Module 4: m4. Failed.\",\"error\":true,\"answer1\":\"Module 1: m1. Failed.\"}");

	}

	// Live HTTP missing parameters returns apropriate "name missing" and "Mark
	// missing". No Error and no String explaining the error because no input - no
	// parameters were passed.
	// This does not cause for an error because input is within the normal
	// possibility of being empty/not existing.
	@Test
	public void HTTP_No_Parameters_Returns_Apropriate_Error_True_And_Name_Missing_Mark_Missing_Response_Test()
			throws Exception {
		assertThat(this.restTemplate.getForEntity(
				"http://didifail.40233517.qpc.hal.davecutting.uk/",
				String.class).getBody()).contains(
						"{\"answer3\":\"Module 3: name missing. Failed.\",\"answer2\":\"Module 2: name missing. Failed.\",\"answer5\":\"Module 5: name missing. Failed.\",\"answer4\":\"Module 4: name missing. Failed.\",\"answer1\":\"Module 1: name missing. Failed.\"}");

	}

	// Live HTTP functionality test for normal input. Populated module and mark
	// parameters.
	@Test
	public void HTTP_Normal_Functionality_Test_With_Populated_Proper_Input_Test() throws Exception {
		assertThat(this.restTemplate.getForEntity(
				"http://didifail.40233517.qpc.hal.davecutting.uk/?module_1=m1&mark_1=98&module_2=m2&mark_2=39&module_3=m3&mark_3=100&module_4=m4&mark_4=0&module_5=m5&mark_5=67",
				String.class).getBody()).contains(
						"{\"answer3\":\"Module 3: m3. 100 is a passing grade.\",\"answer2\":\"Module 2: m2. Failed.\",\"answer5\":\"Module 5: m5. 67 is a passing grade.\",\"answer4\":\"Module 4: m4. Failed.\",\"answer1\":\"Module 1: m1. 98 is a passing grade.\"}");

	}

	// Live HTTP test for completely empty parameters. When a homepage has not
	// filled in parameters this would be the built URL.
	// This is different from Non existing parameters which the function also takes
	// into account and produces the same orientated error for.
	@Test
	public void HTTP_Completely_Empty_Parameters_Returns_Module_And_Mark_Missing_Test() throws Exception {
		assertThat(this.restTemplate.getForObject(
				"http://didifail.40233517.qpc.hal.davecutting.uk/?module_1=&mark_1=&module_2=&mark_2=&module_3=&mark_3=&module_4=&mark_4=&module_5=&mark_5=",
				String.class)).contains(
						"{\"answer3\":\"Module 3: name missing. Failed.\",\"answer2\":\"Module 2: name missing. Failed.\",\"answer5\":\"Module 5: name missing. Failed.\",\"answer4\":\"Module 4: name missing. Failed.\",\"answer1\":\"Module 1: name missing. Failed.\"}");
	}

	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////
	/////////// Unit Tests////////// Unit Tests ////////// Unit Tests /////
	/////////// Unit Tests////////// Unit Tests ////////// Unit Tests /////
	/////////// Unit Tests////////// Unit Tests ////////// Unit Tests /////
	///////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////

	// Did I fail test - populated mark and module names fields. Works as intended
	// with populated failing marks below 40. All modules failed.
	@Test
	public void Did_I_Fail_With_Existing_Module_Names_And_Failing_Marks_Test() throws Exception {
		assertThat(this.restTemplate.getForObject("http://localhost:" + port
				+ "/?module_1=m1&mark_1=39&module_2=m2&mark_2=0&module_3=m3&mark_3=11&module_4=m4&mark_4=1&module_5=m5&mark_5=25",
				String.class)).contains(
						"{\"answer3\":\"Module 3: m3. Failed.\",\"answer2\":\"Module 2: m2. Failed.\",\"answer5\":\"Module 5: m5. Failed.\",\"answer4\":\"Module 4: m4. Failed.\",\"answer1\":\"Module 1: m1. Failed.\"}");
	}

	// Normal Functionality test - populated module name and mark fields with normal
	// values. Some failing and some passing grades.
	@Test
	public void Normal_Functionality_With_Existing_Module_Names_And_Marks_Test() throws Exception {
		assertThat(this.restTemplate.getForObject("http://localhost:" + port
				+ "/?module_1=m1&mark_1=98&module_2=m2&mark_2=39&module_3=m3&mark_3=100&module_4=m4&mark_4=0&module_5=m5&mark_5=67",
				String.class)).contains(
						"{\"answer3\":\"Module 3: m3. 100 is a passing grade.\",\"answer2\":\"Module 2: m2. Failed.\",\"answer5\":\"Module 5: m5. 67 is a passing grade.\",\"answer4\":\"Module 4: m4. Failed.\",\"answer1\":\"Module 1: m1. 98 is a passing grade.\"}");
	}

	// No marks with only name input, should return back all module names and
	// "Failed.". Error string should print explanation of error - marks 1...5
	// missing.
	@Test
	public void No_Marks_With_Existing_Module_Names_Test() throws Exception {
		assertThat(this.restTemplate.getForObject("http://localhost:" + port
				+ "/?module_1=m1&module_2=m2&module_3=m3&module_4=m4&module_5=m5",
				String.class)).contains(
						"{\"answer3\":\"Module 3: m3. Failed.\",\"answer2\":\"Module 2: m2. Failed.\",\"answer5\":\"Module 5: m5. Failed.\",\"answer4\":\"Module 4: m4. Failed.\",\"answer1\":\"Module 1: m1. Failed.\"}");
	}

	// All marks are 40+ and module names parameters do not exist
	@Test
	public void No_Module_Name_All_Marks_Passed_Above_40_Test() throws Exception {
		assertThat(this.restTemplate.getForObject("http://localhost:" + port
				+ "/?mark_1=40&mark_2=72&mark_3=7684&mark_4=99&mark_5=100",
				String.class)).contains(
						"{\"answer3\":\"Module 3: name missing. 7684 is a passing grade.\",\"answer2\":\"Module 2: name missing. 72 is a passing grade.\",\"answer5\":\"Module 5: name missing. 100 is a passing grade.\",\"answer4\":\"Module 4: name missing. 99 is a passing grade.\",\"answer1\":\"Module 1: name missing. 40 is a passing grade.\"}");
	}

	// Marks wrong input. Not integer passed through Marks parameteres - JSON should
	// print module names, Failed mark, Error - True and String explaining the wrong
	// input marks.
	@Test
	public void Error_True_Wrong_Mark_Input_Non_Integer_And_String_Explaining_The_Error_Test() throws Exception {
		assertThat(this.restTemplate.getForObject("http://localhost:" + port
				+ "/?module_1=m1&mark_1=abc&module_2=m2&mark_2='''&module_3=m3&mark_3={}{}&module_4=m4&mark_4=this mark is wrong&module_5=m5&mark_5=||||",
				String.class)).contains(
						"{\"string\":\"Non integer input for modules: 1 2 3 4 5\",\"answer3\":\"Module 3: m3. Failed.\",\"answer2\":\"Module 2: m2. Failed.\",\"answer5\":\"Module 5: m5. Failed.\",\"answer4\":\"Module 4: m4. Failed.\",\"error\":true,\"answer1\":\"Module 1: m1. Failed.\"}");
	}

	// Did I fail test - populated mark and NOT populated module name fields. Should
	// return for all "Missing name" and "Failed".
	@Test
	public void No_Module_Name_And_Failing_Marks_Test() throws Exception {
		assertThat(this.restTemplate.getForObject("http://localhost:" + port
				+ "/?mark_1=39&mark_2=0&mark_3=11&mark_4=1&mark_5=25",
				String.class)).contains(
						"{\"answer3\":\"Module 3: name missing. Failed.\",\"answer2\":\"Module 2: name missing. Failed.\",\"answer5\":\"Module 5: name missing. Failed.\",\"answer4\":\"Module 4: name missing. Failed.\",\"answer1\":\"Module 1: name missing. Failed.\"}");
	}

	// No module name and no marks with EXISTING parameters but EMPTY.
	@Test
	public void Empty_Parameters_For_Module_Name_And_Marks_Test() throws Exception {
		assertThat(this.restTemplate.getForObject(
				"http://localhost:" + port
						+ "/?module_1=&mark_1=&module_2=&mark_2=&module_3=&mark_3=&module_4=&mark_4=&module_5=&mark_5=",
				String.class)).contains(
						"{\"answer3\":\"Module 3: name missing. Failed.\",\"answer2\":\"Module 2: name missing. Failed.\",\"answer5\":\"Module 5: name missing. Failed.\",\"answer4\":\"Module 4: name missing. Failed.\",\"answer1\":\"Module 1: name missing. Failed.\"}");
	}

}
