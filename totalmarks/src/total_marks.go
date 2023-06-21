package main



//go run function.go



import (
	"strconv"
	"net/http"
	"encoding/json"
	"strings"
	"fmt"
)

func param_handler(w http.ResponseWriter, r *http.Request) {

	w.Header().Set("Content-Type", "application/json") 
	w.Header().Set("Access-Control-Allow-Origin", "*") 
	w.WriteHeader(http.StatusOK) 
	
	// initialize string array to 4 (for 5 marks: 0-4). 
	// Used for storing MISSING marks - to output in Error message.
	stringArray := make([]string, 0, 4)	

	//we initialize input to 0 for every mark_1...5 - in case of wrong input, program will overwrite if mark_1...5 parameter has value - via url query request.
	input1 := "0"
	input2 := "0"
	input3 := "0"
	input4 := "0"
	input5 := "0"


	//for every mark_1...5 we get url query if it exists and input is correct, if not then error is catched and stringArray appended.
	//if mark_1...5 exists we overwrite above input1...5 with it.
	var mark_1 string = r.URL.Query().Get("mark_1") //read the mark_1 if possible, if not throw error and record stringArray the errored mark_1...5
	http, err := strconv.Atoi(mark_1)
	if (err != nil) {stringArray = append(stringArray, "1") //append stringArray with module name m1...m5
	} else {
		input1 = strconv.Itoa(http) //if no error, overwrite input1...5 with new mark_1..5 value
	}
	
	var mark_2 string = r.URL.Query().Get("mark_2") //read the mark_1 if possible, if not throw error and record stringArray the errored mark_1...5
	http2, err := strconv.Atoi(mark_2)
	if (err != nil) {stringArray = append(stringArray, "2") //append stringArray with module name m1...m5
	} else {
		input2 = strconv.Itoa(http2) //if no error, overwrite input1...5 with new mark_1..5 value
	}

	var mark_3 string = r.URL.Query().Get("mark_3") //read the mark_1 if possible, if not throw error and record stringArray the errored mark_1...5
	http3, err := strconv.Atoi(mark_3)
	if (err != nil) {stringArray = append(stringArray, "3") //append stringArray with module name m1...m5
	} else {
		input3 = strconv.Itoa(http3) //if no error, overwrite input1...5 with new mark_1..5 value
	}

	var mark_4 string = r.URL.Query().Get("mark_4") //read the mark_1 if possible, if not throw error and record stringArray the errored mark_1...5
	http4, err := strconv.Atoi(mark_4)
	if (err != nil) {stringArray = append(stringArray, "4") //append stringArray with module name m1...m5
	} else {
		input4 = strconv.Itoa(http4) //if no error, overwrite input1...5 with new mark_1..5 value
	}

	var mark_5 string = r.URL.Query().Get("mark_5") //read the mark_1 if possible, if not throw error and record stringArray the errored mark_1...5
	http5, err := strconv.Atoi(mark_5)
	if (err != nil) {stringArray = append(stringArray, "5")//append stringArray with module name m1...m5
	} else {
		input5 = strconv.Itoa(http5) //if no error, overwrite input1...5 with new mark_1..5 value
	}



	var intArr = []int{} //initialized temporary int array to hold below string array values
	var stringArr = []string{input1, input2, input3, input4, input5} //initialized string array with mark_1...5 values
	
	//we need to convert String array to Int array because homepage sends mark_1...5 as String, so a seperate converting for loop is needed to work with Numbers.
	for _, a := range stringArr {
		b, err := strconv.Atoi(a)
        if err != nil {
			fmt.Printf("error has occured")
            panic(err)
        }
        intArr = append(intArr, b)
    }

	//initialize totalMarks integer, to store value from function getTotal, used on the intArr
	var totalMarks int = getTotal(intArr)
	
	//convert justString storing m1...m5 error cases, to string to print in JSON error string
	justString := strings.Join(stringArray," ")

	
	// else if statement to print apropriate JSON page.
	if justString == "1 2 3 4 5" { //if all marks are absent - error = true and list all absent marks, and print answer.
		print := make(map[string]string)
		print["string"] = "absent mark: " + justString
		print["answer"] = strconv.Itoa(totalMarks)
		print["error"] = "true"
		json.NewEncoder(w).Encode(print)
	} else if totalMarks >= 0 && justString == "" { //if all marks are present, no need to print string with error message for "missing marks"
		print := make(map[string]string)
		print["answer"] = strconv.Itoa(totalMarks)
		print["error"] = "false"
		json.NewEncoder(w).Encode(print)
	} else if totalMarks >= 0 && justString != "" { //if some absent marks exist then print error = false, and print answer.
		print := make(map[string]string)
		print["string"] = "absent mark: " + justString
		print["answer"] = strconv.Itoa(totalMarks)
		print["error"] = "false"
		json.NewEncoder(w).Encode(print)
	} else { //final else when all marks are absent and total is arbituary
		print := make(map[string]string)
		print["string"] = "absent mark: " + justString
		print["error"] = "true"
		json.NewEncoder(w).Encode(print)
	}
	


}

func getTotal(marks []int) int { //function to calculate Total for given array

	total := 0
	temp := 0 //temporary int to store array pointer value
	for i:=0; i<len(marks); i++ {
		temp = marks[i]
		total += temp //add temporary int to total on loop for full array
	}

	return total //return total with total of array values
}


func main() { //main function that runs all the time, calling param_handler after / for parameters.
	http.HandleFunc("/", param_handler)
	http.ListenAndServe("0.0.0.0:5001", nil) //golang works on port 5001
}
