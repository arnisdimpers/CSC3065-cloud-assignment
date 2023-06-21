import unittest
import requests

import averagegrade

class TestAdd(unittest.TestCase):

    #Part B Point 4: UNIT TESTS WITH LIVE HTTP REQUESTS FOR TESTING WEB API FUNCTIONALITY
    def test_status_code_200(self):
        #create a response that stores the result of requesting service URL
        response = requests.get("http://average.40233517.qpc.hal.davecutting.uk")
        #assert that response page gave a status code of 200 meaning OK
        self.assertEqual(response.status_code, 200)


    def test_average_grade_correct_for_all_marks(self):
        #test that function returns correct average when all parameters passed in, are numeric, and not empty
        response = requests.get("http://average.40233517.qpc.hal.davecutting.uk/?mark_1=50&mark_2=100&mark_3=79&"
        +"mark_4=92&mark_5=89&module_1=mod1&module_2=mod2&module_3=mod3&module_4=mod4&module_5=mod5")
        response_body = response.json()

        #assert that page status code is 200, error flag is set to false, and average is 82
        self.assertEqual(response.status_code, 200)
        self.assertFalse(response_body["error"])
        self.assertEqual(response_body["average"], 82.0)


    def test_read_error_true_when_text_in_one_mark_mixed_with_normal_grades(self):
        #test that function flags error = true in response when one of the passed in parameters is a string and not a numeric value
        #function continues to work fine and gets the average from eligible marks that passed the verification of not being null/string
        response = requests.get("http://average.40233517.qpc.hal.davecutting.uk/?mark_1=50&mark_2=100&mark_3=hey&module_1=mod1&module_4=mod4&module_5=mod5")
        response_body = response.json()

        #test that expected message asking to remove the string value is correct
        expected_message = ['Please remove: hey']

        #assert that error status is set to true, error message matches the one we expect, and average is still correct with remaining marks
        self.assertTrue(response_body["error"])
        self.assertEqual(response_body["error_message"], expected_message)
        self.assertEqual(response_body["average"], 75.0)


    def test_empty_marks_returns_error_true_error_message(self):
        #test that if passing function with no parameters an error = true flag is triggered and an error message
        #matches asking the user to insert at least 1 grade
        response = requests.get("http://average.40233517.qpc.hal.davecutting.uk")
        response_body = response.json()

        expected_message = 'Please insert at least 1 grade'

        self.assertTrue(response_body["error"])
        self.assertEqual(response_body["error_message"], expected_message)
        self.assertEqual(response.status_code, 200)


    def test_read_error_string_in_grades_no_correct_values(self):
        #test that if passing in parameters for marks all contain string value and no numeric values then no average is calculated
        #and a error = true is triggered with a matching error message asking user to insert atleast 1 grade
        response = requests.get("http://average.40233517.qpc.hal.davecutting.uk/?mark_1=whyyoudothis&?mark_2=thisisastring&?mark_3=text&module_1=mod1&module_2=mod2")
        response_body = response.json()

        expected_message = 'Please insert at least 1 grade'

        self.assertTrue(response_body["error"])
        self.assertEqual(response_body["error_message"], expected_message)

    
    def test_one_mark_initialized_with_null_value_returns_error(self):
        #test that if passing mark parameters that exist but are not initialized returns an error = true flag and an error
        #message that matches the expected one asking user to insert at least 1 grade
        response = requests.get("http://average.40233517.qpc.hal.davecutting.uk/?mark_1=&mark_2=&mark_3=")
        response_body = response.json()

        expected_message = 'Please insert at least 1 grade'

        self.assertTrue(response_body["error"])
        self.assertEqual(response_body["error_message"], expected_message)

    def test_one_mark_initialized_with_null_value_others_correct(self):
        #test that if passing in 2 correct marks and 1 initialized to NULL return a correct average grade and an error = true flag because
        #and not error = true because the function could workout the average, the error = true flag would be raised only if mark_1 would be
        #a string or invalid type, a null meaning empty does not trigger a response from function
        response = requests.get("http://average.40233517.qpc.hal.davecutting.uk/?mark_1=&mark_2=83&mark_3=75")
        response_body = response.json()

        self.assertFalse(response_body["error"])
        self.assertEqual(response_body["average"], 79.0)



    #Normal Unit Tests of Function
    def test_exception_raised_if_array_contains_string(self):
        #test that an exception is raised if the get_average function is ran with string values for array
        with self.assertRaises(Exception):
            averagegrade.get_average("hey", "this", "will", "raise", "exception")


    def test_empty_array_returns_0(self):
        #test that if marks array passed into get_average are empty, the average returned will be 0
        marks_response = []
        response = averagegrade.get_average(marks_response)
        self.assertEqual(response, 0)


    def test_average_grade_correct_for_all_marks(self):
        #test that if mark array passed in has all correct grades, a correct average is returned
        marks_response = [61, 70, 79, 75, 81]
        response = averagegrade.get_average(marks_response)
        self.assertEqual(response, 73.2)
        self.assertIsNotNone(response)


    def test_all_marks_0_returns_0(self):
        #test that if marks array passed in is all 0s that a 0 average is returned
        marks_response = [0, 0, 0, 0, 0]
        response = averagegrade.get_average(marks_response)
        self.assertEqual(response, 0)



    def test_1_grade_initialized_others_null_returns_correct_grade(self):
        #test that if a single mark in array is passed then average still returns a correct matching result
        marks_response = [79]
        response = averagegrade.get_average(marks_response)
        self.assertEqual(response, 79)
    

if __name__ == 'main':
    unittest.main()
