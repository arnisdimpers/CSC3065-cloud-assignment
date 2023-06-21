#define method to get the average
#array_grades = STRING array holding marks
def get_average(array_grades):
    
    #check if length of passed in array is 0, if 0 that means 0 grades, return average 0
    if len(array_grades) == 0: return 0

    #if passed in array of marks greater than 0, try to get the average
    try:
        #take the sum of the passed in array and divide it by the length of the array
        average_grade = sum(array_grades) / (len(array_grades))
    except:

        #if error raised that means a string was passed into the array, return an error
        raise Exception('String found inside grades array')

    #if no exception raised, return average
    return average_grade