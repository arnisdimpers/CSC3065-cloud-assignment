using System;
using System.Collections.Generic;

namespace classifygrade
{
public class ClassifyGrade
{
    public static List<object> count(string[] array_modules, string[] array_marks)
    {

        List<object> list = new List<object>();

        for (int i = 0; i < 5; i++) {
            int index = i + 1;
            if (array_marks[i] != null && int.TryParse(array_marks[i], out int value)) { //check if mark parameter is NULL/String > send to Else clause and return error message
                int numerical_value = int.Parse(array_marks[i]);
            if (numerical_value > 69)
            {
                if (array_modules[i] == null) {
                    var graded = new
                    {
                        module = "Please insert name for module " + index,
                        grade = "Distinction"
                    };
                    list.Add(graded);
                }
                else {
                    var graded = new
                    {
                        module = "Module: " + array_modules[i],
                        grade = "Distinction"
                    };
                    list.Add(graded);
                }
            }
            if (numerical_value > 59 && numerical_value < 70)
            {
                if (array_modules[i] == null) {
                    var graded = new
                    {
                        module = "Please insert name for module " + index,
                        grade = "Commendation"
                    };
                    list.Add(graded);
                }
                else {
                    var graded = new
                    {
                        module = "Module: " + array_modules[i],
                        grade = "Commendation"
                    };
                    list.Add(graded);
                }
            }

            if (numerical_value > 49 && numerical_value < 60)
            {
                if (array_modules[i] == null) {
                    var graded = new
                    {
                        module = "Please insert name for module " + index,
                        grade = "Pass"
                    };
                    list.Add(graded);
                }
                else {
                    var graded = new
                    {
                        module = "Module: " + array_modules[i],
                        grade = "Pass"
                    };
                    list.Add(graded);
                }
            }

            if (numerical_value > 39 && numerical_value < 50)
            {
                if (array_modules[i] == null) {
                    var graded = new
                    {
                        module = "Please insert name for module " + index,
                        grade = "Marginal Fail"
                    };
                    list.Add(graded);
                }
                else {
                    var graded = new
                    {
                        module = "Module: " + array_modules[i],
                        grade = "Marginal Fail"
                    };
                    list.Add(graded);
                }
            }

            if (numerical_value > 19 && numerical_value < 40)
            {
                if (array_modules[i] == null) {
                    var graded = new
                    {
                        module = "Please insert name for module " + index,
                        grade = "Fail"
                    };
                    list.Add(graded);
                }
                else {
                    var graded = new
                    {
                        module = "Module: " + array_modules[i],
                        grade = "Fail"
                    };
                    list.Add(graded);
                }
            }

            if (numerical_value >= 0 && numerical_value < 20)
            {
                if (array_modules[i] == null) {
                    var graded = new
                    {
                        module = "Please insert name for module " + index,
                        grade = "Low Fail"
                    };
                    list.Add(graded);
                }
                else {
                    var graded = new
                    {
                        module = "Module: " + array_modules[i],
                        grade = "Low Fail"
                    };
                    list.Add(graded);
                };
            }
        }
        else {
            if (array_modules[i] == null) {
                var graded = new
            {
                module = "Please insert name for module " + index,
                grade = "Please insert a grade"
            };
            list.Add(graded);
            }
            else {
                var graded = new
                    {
                        module = "Module: " + array_modules[i],
                        grade = "Please insert a grade"
                    };
                    list.Add(graded);
            }
        }}

        // foreach (string i in array_marks)
        // {
        //     //check if any marks null, if yes the int.parse will crash so we avoid it
        //     if (i != null) {
        //         int numerical_value = int.Parse(i);
        //     if (numerical_value > 69)
        //     {
        //         if (array_modules[index] == null) {
        //             var graded = new
        //             {
        //                 module = "Please insert name for module",
        //                 grade = "Distinction"
        //             };
        //             list.Add(graded);
        //             list[index - 1] = "hey";
        //         }
        //         else {
        //             var graded = new
        //             {
        //                 module = array_modules[index],
        //                 grade = "Distinction"
        //             };
        //             list.Add(graded);
        //         }
        //     }

        //     if (numerical_value > 59 && numerical_value < 70)
        //     {
        //         if (array_modules[index] == null) {
        //             var graded = new
        //             {
        //                 module = "Please insert name for module",
        //                 grade = "Commendation"
        //             };
        //             list.Add(graded);
        //         }
        //         else {
        //             var graded = new
        //             {
        //                 module = array_modules[index],
        //                 grade = "Commendation"
        //             };
        //             list.Add(graded);
        //         }
        //     }

        //     if (numerical_value > 49 && numerical_value < 60)
        //     {
        //         if (array_modules[index] == null) {
        //             var graded = new
        //             {
        //                 module = "Please insert name for module",
        //                 grade = "Pass"
        //             };
        //             list.Add(graded);
        //         }
        //         else {
        //             var graded = new
        //             {
        //                 module = array_modules[index],
        //                 grade = "Pass"
        //             };
        //             list.Add(graded);
        //         }
        //     }

        //     if (numerical_value > 39 && numerical_value < 50)
        //     {
        //         if (array_modules[index] == null) {
        //             var graded = new
        //             {
        //                 module = "Please insert name for module",
        //                 grade = "Marginal Fail"
        //             };
        //             list.Add(graded);
        //         }
        //         else {
        //             var graded = new
        //             {
        //                 module = array_modules[index],
        //                 grade = "Marginal Fail"
        //             };
        //             list.Add(graded);
        //         }
        //     }

        //     if (numerical_value > 19 && numerical_value < 40)
        //     {
        //         if (array_modules[index] == null) {
        //             var graded = new
        //             {
        //                 module = "Please insert name for module",
        //                 grade = "Fail"
        //             };
        //             list.Add(graded);
        //         }
        //         else {
        //             var graded = new
        //             {
        //                 module = array_modules[index],
        //                 grade = "Fail"
        //             };
        //             list.Add(graded);
        //         }
        //     }

        //     if (numerical_value >= 0 && numerical_value < 20)
        //     {
        //         if (array_modules[index] == null) {
        //             var graded = new
        //             {
        //                 module = "Please insert name for module",
        //                 grade = "Low Fail"
        //             };
        //             list.Add(graded);
        //         }
        //         else {
        //             var graded = new
        //             {
        //                 module = array_modules[index],
        //                 grade = "Low Fail"
        //             };
        //             list.Add(graded);
        //         };
        //     }

        //     }
        //     index++;
        // }

        return list;
    }
}
}