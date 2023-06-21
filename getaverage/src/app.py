from flask import Flask
from flask import request
from flask import Response
import json

import averagegrade

app = Flask(__name__)

#listen for requests with mark_1 to mark_5 parameters, assign default value None to all 
@app.route('/', defaults={'mark_1': None, 'mark_2': None, 'mark_3': None, 'mark_4': None, 'mark_5': None})
def addnum(mark_1, mark_2, mark_3, mark_4, mark_5):

    #save all parameters into variables as string
    marks1 = str(request.args.get('mark_1'))
    marks2 = str(request.args.get('mark_2'))
    marks3 = str(request.args.get('mark_3'))
    marks4 = str(request.args.get('mark_4'))
    marks5 = str(request.args.get('mark_5'))

    #create an empty array to store all marks in after they are verified to contain numeric not null value
    marks_array = []

    #error message array that will contain all error messages encountered in program to then display to user
    error_message = []

    #creating an array that holds all passed in marks, this way it will be easier to iterate through
    #every mark using a for loop, filtering out if the passed in mark is equal to None or empty
    marks_response = [marks1, marks2, marks3, marks4, marks5]

    for mark in marks_response:
        try:
            #test each mark in array if it is None or empty, if no then add the mark into special array for marks
            #that passed verification
            if (mark != "None" and mark != ""):
                marks_array.append(float(mark))
                
        except:
            #because marks are passed into marks_array as float value, if one is not a numeric value it will
            #cause an exception which will be caught and added as an error message inside the error array
            error_message.append("Please remove: " + mark)


    #check if marks_array is greater than 0, that means at least 1 mark from the user has passed all verification as
    #a numeric non empty value, and the program can use it in the "get_average" method
    if (len(marks_array) > 0):

        #pass in array of legitimate marks and save the average
        average_grade = averagegrade.get_average(marks_array)

        #check the length of the error_message array to see if there are ny
        #errors that need to be displayed to the user
        errors_found = len(error_message)

        #initialize the r variable that will be filled in with the response for the page
        r = ""

        #if statement checks if there is at least 1 error message found
        if (errors_found > 0):

            #if yes then create the page response to have an error flag set as true for homepage to read
            #and pass in the error_message the array that contains all the errors the program encountered
            r = {
                "error": True,
                "average": average_grade,
                "error_message": error_message
            }
        else:
            #if no errors found in program that means set page error status to false, and just return the average grade worked out
            r = {
                "error": False,
                "average": average_grade
            }

        #make a reply that will store the value of our response in JSON format
        reply = json.dumps(r)

        #create a response with the reply JSON message, page code status 200 meaning OK, and mimetype a json application
        response = Response(response=reply, status=200,
                            mimetype="application/json")

        #set headers of page to allow access control origin
        response.headers['Content-Type'] = 'application/json'
        response.headers['Access-Control-Allow-Origin'] = '*'

        #return the response to the user
        return response
    else:
        #else if marks_array is not more than 0

        #create a reply with error flag set to True, and a single error message saying 0 grades passed in
        r = {
            "error": True,
            "error_message": "Please insert at least 1 grade"
        }
        #format it into JSON
        reply = json.dumps(r)

        #call the Response function of Flask and pass in the reply with status code 200 and JSON type application
        response = Response(response=reply, status=200,
                            mimetype="application/json")

        #add neccesary headers and return the response
        response.headers['Content-Type'] = 'application/json'
        response.headers['Access-Control-Allow-Origin'] = '*'

        return response

#run the app on port 5002 and local host
if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5002)
