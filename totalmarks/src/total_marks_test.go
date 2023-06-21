package main


import ( //importing necessary functions
	"strings"
	"io/ioutil"
	"net/http"
	"testing"	
)

//google Go, requires package to be "main"(same between all pages for functions to work) test class 'function_test.go' must end in '_test.go'
//for tests to run code must be pushed to Gitlab, 'go build' file created for IMPORTS! and command is 'go test ./...' or 'go test .' 'go test' proper naming is required, tests named upper case Test_etc...
//or run a specific function test with 'go test -run=Test_..
//'go run function.go' to run the function, not applicible to tests

//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//// LIVE HTTP TESTS ////////// LIVE HTTP TESTS ////////// LIVE HTTP TESTS //////
//// LIVE HTTP TESTS ////////// LIVE HTTP TESTS ////////// LIVE HTTP TESTS //////
//// LIVE HTTP TESTS ////////// LIVE HTTP TESTS ////////// LIVE HTTP TESTS //////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

//live functionality test, seeing if page returns code 200 letting us know it is live and ready
func Test_LIVE_Returns_200(t *testing.T) {
    response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk") //access Rancher url for live function and save response
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }

	//if to check status code is equal to 200, if it isn't then print error
	if !(response.StatusCode >= 200 && response.StatusCode <= 299) {
		t.Errorf("Status did not return 200") //print error
	}

}


//test the function status - OK, if not print error
func Test_LIVE_Status_OK(t *testing.T) {
	response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk") //access Rancher url for live function and save response
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK != response.StatusCode {
		t.Errorf("Status returns NOT - OK")
	}
	
}


//Live test to check error response
func Test_LIVE_Error(t *testing.T) {
    response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk") //access Rancher url for live function and save response with
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK == response.StatusCode {
		read, error := ioutil.ReadAll(response.Body)
		if error != nil {
			t.Fatal(error)
		}
		
		output := string(read)
		if !(strings.Contains(output, "error\":\"true")) {
			t.Errorf("Test returns incorrect error. Error must be TRUE.")
		}
	}
}


//Live test to check normal operation and status code OK
func Test_LIVE_Normal_Operation_And_Status_OK(t *testing.T) {
	input := "/?module_1=m1&mark_1=100&module_2=m2&mark_2=91&module_3=m3&mark_3=32&module_4=m4&mark_4=78&module_5=m5&mark_5=103" //creating user input for module name and marks
	response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk" + input) //access Rancher url for live function and save response with + user input
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK == response.StatusCode {
    read, error := ioutil.ReadAll(response.Body)
    if error != nil {
        t.Fatal(error)
    }
	
	output := string(read)
	if !(strings.Contains(output, "answer\":\"404")) {
		t.Errorf("Test returns incorrect value. Answer must be 404")
	}
	}
}

//Live test no given module names, only 1 mark given. Should operate as intended.
func Test_LIVE_Error_False_While_All_Names_Missing_And_Only_1_Mark_Given(t *testing.T) {
	input := "/?mark_5=100" //creating user input for marks
	response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk" + input) //access Rancher url for live function and save response with + user input
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK == response.StatusCode {
    read, error := ioutil.ReadAll(response.Body)
    if error != nil {
        t.Fatal(error)
    }
	
	output := string(read)
	if !(strings.Contains(output, "answer\":\"100")) {
		t.Errorf("Test returns incorrect value. Answer must be 100")
	}
	}
}


//Live test with SOME missing marks. Only mark 3 and 5 exist, program should operate as intended
func Test_LIVE_Some_Marks_Missing(t *testing.T) {
	input := "/?module_1=m1&module_2=m2&module_3=m3&mark_3=50&module_4=m4&module_5=m5&mark_5=99" //creating user input for module name and marks
	response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk" + input) //access Rancher url for live function and save response with + user input
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK == response.StatusCode {
    read, error := ioutil.ReadAll(response.Body)
    if error != nil {
        t.Fatal(error)
    }
	
	output := string(read)
	if !(strings.Contains(output, "answer\":\"149")) {
		t.Errorf("Test returns incorrect value. Answer must be 149")
	}
	}
}


//Live test error message for missing marks for module 1-5 and error true
func Test_LIVE_Answer_0_Error_True_String_Missing_Marks_Explained(t *testing.T) {
	response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk") //access Rancher url for live function and save response
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK == response.StatusCode {
    read, error := ioutil.ReadAll(response.Body)
    if error != nil {
        t.Fatal(error)
    }
	
	output := string(read)
	if !(strings.Contains(output, "answer\":\"0")) { //check answer is 0
		if !(strings.Contains(output, "error\":\"true")) { //check error is true
			if !(strings.Contains(output, "string\":\"absent mark: 1 2 3 4 5")) {//check error string - explains missing marks
				t.Errorf("Test returns incorrect.")
			}
		}
	}
	}
}


//Live test for invalid user input - non numerical
func Test_LIVE_Wrong_User_Input_Non_Numerical(t *testing.T) {
	input := "/?mark_1= asd_asd&mark_2=hgjfj&mark_3=^'''&mark_4=[][][]&mark_5=''''/'/'/'/'/" //creating user input for marks
	response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk" + input) //access Rancher url for live function and save response
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK == response.StatusCode {
    read, error := ioutil.ReadAll(response.Body)
    if error != nil {
        t.Fatal(error)
    }
	
	output := string(read)
	if !(strings.Contains(output, "error\":\"true")) {
		if !(strings.Contains(output, "string\":\"absent mark: 1 2 3 4 5")) {//check error string - explains missing marks
			t.Errorf("Test returns incorrect.")
		}
	}
	}
}



//Live test to check no error is thrown while input is all 0 because input has been given and it does exist, as intended
func Test_LIVE_Marks_0_Returns_No_Error(t *testing.T) {
	input := "/?mark_1=0&mark_2=0&mark_3=0&mark_4=0&mark_5=0" //creating user input for marks
	response, error := http.Get("http://totalmarks.40233517.qpc.hal.davecutting.uk" + input) //access Rancher url for live function and save response
    if error != nil  { //if error exists - print an error
        t.Fatal(error)

    }
	defer response.Body.Close()

	//if to check status returns OK and function is live and ready for operation, if it isn't then print error
	if http.StatusOK == response.StatusCode {
    read, error := ioutil.ReadAll(response.Body)
    if error != nil {
        t.Fatal(error)
    }
	
	output := string(read)
	if !(strings.Contains(output, "error\":\"false")) {
		t.Errorf("Test returns incorrect error. Error must be false")
	}
	}
}




//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//// UNIT TESTS BELOW ////////// UNIT TESTS BELOW ////////// UNIT TESTS BELOW //////
//// UNIT TESTS BELOW ////////// UNIT TESTS BELOW ////////// UNIT TESTS BELOW //////
//// UNIT TESTS BELOW ////////// UNIT TESTS BELOW ////////// UNIT TESTS BELOW //////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////

//normal operation test to check validity of total function.
func Test_Normal_Operation(t *testing.T) {
	var intArray = []int{30,29,1030,1,8893} //initialize normal array
	total := getTotal(intArray) //use local getTotal function

	value := 0; //initialize temporary int 'value' and 'actual'
	actual := 0;
	for i:=0; i<len(intArray); i++ { //for loop to calculate real value of intArray.
		value = intArray[i]
		actual += value
	}
	for (actual != total) { //compare if getTotal function returns same answer as for loop above.
		t.Errorf("Encountered error: total marks must be %d while received %d", actual, total) //if not - print error.
		break;
	}
}


//test to see if function still operational with 0 input given from user
func Test_Contains_No_Marks(t *testing.T) {
	var intArray = []int{} //initialize a completely empty array
	total := getTotal(intArray) //use local getTotal function

	actual := 0; //initialize temporary int 'actual' to 0
	for (actual != total) { //compare if getTotal function returns same answer as for loop above.
		t.Errorf("Encountered error: total marks must be %d while received %d", actual, total) //if not - print error.
		break;
	}
}

//very wrong input test, with not whole numbers, multipliers in between, negative digits
func Test_Not_Normal_Integer_Input(t *testing.T) {
	var intArray = []int{-0, 0+1+030303-123/555, 88-1-11, -784/95*9} //initialize array with very wrong input
	total := getTotal(intArray) //use local getTotal function

	value := 0; //initialize temporary int 'value' and 'actual'
	actual := 0;
	for i:=0; i<len(intArray); i++ { //for loop to calculate real value of intArray.
		value = intArray[i]
		actual += value
	}
	for (actual != total) { //compare if getTotal function returns same answer as for loop above.
		t.Errorf("Encountered error: total marks must be %d while received %d", actual, total) //if not - print error.
		break;
	}
}

//not all marks given test that function proceeds as normal and works with only 2 marks in given array.
func Test_Contains_Empty_Marks(t *testing.T) {
	var intArray = []int{768,89} //initialize array with empty marks
	total := getTotal(intArray) //use local getTotal function

	value := 0; //initialize temporary int 'value' and 'actual'
	actual := 0;
	for i:=0; i<len(intArray); i++ { //for loop to calculate real value of intArray.
		value = intArray[i]
		actual += value
	}
	for (actual != total) { //compare if getTotal function returns same answer as for loop above.
		t.Errorf("Encountered error: total marks must be %d while received %d", actual, total) //if not - print error.
		break;
	}
}




