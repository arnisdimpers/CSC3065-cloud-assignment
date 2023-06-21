using NUnit.Framework;
using System;
using System.Net;
using Newtonsoft.Json.Linq;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using System.Net.Http;

/*
if you didnt see in controller file, to get a unit test class just paste 3 commands into root,
then just create this test class yourself and test.

$ dotnet add package Microsoft.NET.Test.Sdk
$ dotnet add package Nunit3TestAdapter
$ dotnet add package NUnit
$ dotnet add package Microsoft.Net.Http --version 2.2.29

sudo dotnet test
sudo dotnet test --no-build     (if a text file is already busy with a dotnet run process, use no build to avoid file writing)
command to run the tests.
*/
namespace ClassifyGradeTest
{
    [TestFixture]
    public class ClassifyGradeTest
    {
        [Test]
        public void Test_Random_Marks_And_Module_Names_Return_Correct_Grading()
        {
            string[] grades = {"85", "45", "55", "85", "85"};
            string[] modules = {"Databases", "mod2", "Programming", "mod1", "Cloud Computing"};
            List<object> result = classifygrade.ClassifyGrade.count(modules, grades);
            List<object> expected = new List<object>();

            //creating an expected JSON response object based on grades/modules array used in the classify grade function
            var graded_modules1 = new
            {
                module = "Module: Databases",
                grade = "Distinction"
            };

            var graded_modules2 = new
            {
                module = "Module: mod2",
                grade = "Marginal Fail"
            };

            var graded_modules3 = new
            {
                module = "Module: Programming",
                grade = "Pass"
            };

            var graded_modules4 = new
            {
                module = "Module: mod1",
                grade = "Distinction"
            };

            var graded_modules5 = new
            {
                module = "Module: Cloud Computing",
                grade = "Distinction"
            };

            expected.Add(graded_modules1);
            expected.Add(graded_modules2);
            expected.Add(graded_modules3);
            expected.Add(graded_modules4);
            expected.Add(graded_modules5);

            Assert.AreEqual(result, expected);
            Assert.IsTrue(result.SequenceEqual(expected));

        }

        [Test]
        public void Test_MarginalFail_And_Distinction_Both_Get_Graded_Correctly_Half_Modules_Grades_Empty()
        {
            string[] grades = {"45", "90", null, null, null};
            string[] modules = {"mod1", "mod2", null, null, null};

            List<object> result = classifygrade.ClassifyGrade.count(modules, grades);

            List<object> expected = new List<object>();

            //creating an expected JSON response object based on grades/modules array used in the classify grade function
            var graded_modules1 = new
            {
                module = "Module: mod1",
                grade = "Marginal Fail"
            };
            
            var graded_modules2 = new
            {
                module = "Module: mod2",
                grade = "Distinction"
            };

            var graded_modules3 = new
            {
                module = "Please insert name for module 3",
                grade = "Please insert a grade"
            };

            var graded_modules4 = new
            {
                module = "Please insert name for module 4",
                grade = "Please insert a grade"
            };

            var graded_modules5 = new
            {
                module = "Please insert name for module 5",
                grade = "Please insert a grade"
            };

            expected.Add(graded_modules1);
            expected.Add(graded_modules2);
            expected.Add(graded_modules3);
            expected.Add(graded_modules4);
            expected.Add(graded_modules5);

            Assert.AreEqual(result, expected);
            Assert.IsTrue(result.SequenceEqual(expected));

        }

        [Test]
        public void Test_Pass_And_Commendation_Both_Get_Graded_Correctly_Half_Modules_Grades_Empty()
        {
            string[] grades = {null, null, "65", "51", null};
            string[] modules = {null, null, "Computational Theory", "Cloud", null};

            List<object> result = classifygrade.ClassifyGrade.count(modules, grades);

            List<object> expected = new List<object>();

            var graded_modules1 = new
            {
                module = "Please insert name for module 1",
                grade = "Please insert a grade"
            };
            var graded_modules2 = new
            {
                module = "Please insert name for module 2",
                grade = "Please insert a grade"
            };
            var graded_modules3 = new
            {
                module = "Module: Computational Theory",
                grade = "Commendation"
            };
            var graded_modules4 = new
            {
                module = "Module: Cloud",
                grade = "Pass"
            };
            var graded_modules5 = new
            {
                module = "Please insert name for module 5",
                grade = "Please insert a grade"
            };
            expected.Add(graded_modules1);
            expected.Add(graded_modules2);
            expected.Add(graded_modules3);
            expected.Add(graded_modules4);
            expected.Add(graded_modules5);

            Assert.AreEqual(result, expected);
            Assert.IsTrue(result.SequenceEqual(expected));
        }

        [Test]
        public void Test_Fail_And_Low_Fail_Both_Get_Graded_Correctly_Half_Modules_Grades_Empty()
        {
            string[] grades = {null, "3", null, "25", null};
            string[] modules = {null, "Programming", null, "Networks", null};

            List<object> result = classifygrade.ClassifyGrade.count(modules, grades);

            List<object> expected = new List<object>();

            var graded_modules1 = new
            {
                module = "Please insert name for module 1",
                grade = "Please insert a grade"
            };
            var graded_modules3 = new
            {
                module = "Please insert name for module 3",
                grade = "Please insert a grade"
            };
            var graded_modules2 = new
            {
                module = "Module: Programming",
                grade = "Low Fail"
            };
            var graded_modules4 = new
            {
                module = "Module: Networks",
                grade = "Fail"
            };
            var graded_modules5 = new
            {
                module = "Please insert name for module 5",
                grade = "Please insert a grade"
            };
            expected.Add(graded_modules1);
            expected.Add(graded_modules2);
            expected.Add(graded_modules3);
            expected.Add(graded_modules4);
            expected.Add(graded_modules5);

            Assert.AreEqual(result, expected);
            Assert.IsTrue(result.SequenceEqual(expected));
        }


        [Test]
        public void Test_Grades_Empty_Modules_All_Named_Returns_Insert_Grade_For_All_Modules()
        {
            string[] grades = {null, null, null, null, null};
            string[] modules = {"Databases", "Programming", "Cloud", "Networks", "Algorithms"};

            List<object> result = classifygrade.ClassifyGrade.count(modules, grades);

            List<object> expected = new List<object>();

            var graded_modules1 = new
            {
                module = "Module: Databases",
                grade = "Please insert a grade"
            };
            var graded_modules2 = new
            {
                module = "Module: Programming",
                grade = "Please insert a grade"
            };
            var graded_modules3 = new
            {
                module = "Module: Cloud",
                grade = "Please insert a grade"
            };
            var graded_modules4 = new
            {
                module = "Module: Networks",
                grade = "Please insert a grade"
            };
            var graded_modules5 = new
            {
                module = "Module: Algorithms",
                grade = "Please insert a grade"
            };
            expected.Add(graded_modules1);
            expected.Add(graded_modules2);
            expected.Add(graded_modules3);
            expected.Add(graded_modules4);
            expected.Add(graded_modules5);

            Assert.AreEqual(result, expected);
            Assert.IsTrue(result.SequenceEqual(expected));
        }

        [Test]
        public void Test_Modules_All_Empty_Grades_Full_Returns_Graded_Modules_Asking_For_Module_Name()
        {
            string[] grades = {"5", "45", "55", "65", "75"};
            string[] modules = {null, null, null, null, null};

            List<object> result = classifygrade.ClassifyGrade.count(modules, grades);

            List<object> expected = new List<object>();

            var graded_modules1 = new
            {
                module = "Please insert name for module 1",
                grade = "Low Fail"
            };
            var graded_modules2 = new
            {
                module = "Please insert name for module 2",
                grade = "Marginal Fail"
            };
            var graded_modules3 = new
            {
                module = "Please insert name for module 3",
                grade = "Pass"
            };
            var graded_modules4 = new
            {
                module = "Please insert name for module 4",
                grade = "Commendation"
            };
            var graded_modules5 = new
            {
                module = "Please insert name for module 5",
                grade = "Distinction"
            };
            expected.Add(graded_modules1);
            expected.Add(graded_modules2);
            expected.Add(graded_modules3);
            expected.Add(graded_modules4);
            expected.Add(graded_modules5);

            Assert.AreEqual(result, expected);
            Assert.IsTrue(result.SequenceEqual(expected));
        }

        //Part B point 4(last) - make unit tests fetch real web api functionality, not just test simple function
        [Test]
        async public Task Test_status_code_OK()
        {
            using (HttpClient client = new HttpClient())
            {
                var s = await client.GetAsync("http://classifygrade.40233517.qpc.hal.davecutting.uk/");
                Assert.AreEqual(HttpStatusCode.OK, s.StatusCode);
            }
        }

        [Test]
        async public Task Test_Empty_Marks_All_Grades_Filled_In_Returns_Correct_Naming_For_Each_Module_And_Asks_To_Insert_Grade()
        {
            using (HttpClient client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync("http://classifygrade.40233517.qpc.hal.davecutting.uk/?module_1=Programming&module_2=Coding&module_3=Networks&module_4=Cloud&module_5=Databases");
                response.EnsureSuccessStatusCode();
                var expectedValues = new Dictionary<string, object>();
                var responsebody = response.Content.ReadAsStringAsync().GetAwaiter().GetResult();
                JObject root = JObject.Parse(responsebody);

                Assert.AreEqual(HttpStatusCode.OK, response.StatusCode);

                Assert.AreEqual("False", Convert.ToString((string)root["error"]));

                Assert.That("Module: Programming", Is.EqualTo(Convert.ToString((string)root["grades"][0]["module"])).NoClip);
                Assert.That("Module: Coding", Is.EqualTo(Convert.ToString((string)root["grades"][1]["module"])).NoClip);
                Assert.That("Module: Networks", Is.EqualTo(Convert.ToString((string)root["grades"][2]["module"])).NoClip);
                Assert.That("Module: Cloud", Is.EqualTo(Convert.ToString((string)root["grades"][3]["module"])).NoClip);
                Assert.That("Module: Databases", Is.EqualTo(Convert.ToString((string)root["grades"][4]["module"])).NoClip);

                Assert.That("Please insert a grade", Is.EqualTo(Convert.ToString((string)root["grades"][0]["grade"])).NoClip);
                Assert.That("Please insert a grade", Is.EqualTo(Convert.ToString((string)root["grades"][1]["grade"])).NoClip);
                Assert.That("Please insert a grade", Is.EqualTo(Convert.ToString((string)root["grades"][2]["grade"])).NoClip);
                Assert.That("Please insert a grade", Is.EqualTo(Convert.ToString((string)root["grades"][3]["grade"])).NoClip);
                Assert.That("Please insert a grade", Is.EqualTo(Convert.ToString((string)root["grades"][4]["grade"])).NoClip);
            }
        }

        [Test]
        async public Task Test_Empty_Modules_All_Marks_Filled_In_Returns_Correct_Grading_For_Each_Module_And_Asks_For_Module_Name()
        {
            using (HttpClient client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync("http://classifygrade.40233517.qpc.hal.davecutting.uk/?mark_1=5&mark_2=45&mark_3=55&mark_4=65&mark_5=75");
                response.EnsureSuccessStatusCode();
                var expectedValues = new Dictionary<string, object>();
                var responsebody = response.Content.ReadAsStringAsync().GetAwaiter().GetResult();
                JObject root = JObject.Parse(responsebody);

                Assert.AreEqual(HttpStatusCode.OK, response.StatusCode);

                Assert.AreEqual("False", Convert.ToString((string)root["error"]));

                Assert.That("Please insert name for module 1", Is.EqualTo(Convert.ToString((string)root["grades"][0]["module"])).NoClip);
                Assert.That("Please insert name for module 2", Is.EqualTo(Convert.ToString((string)root["grades"][1]["module"])).NoClip);
                Assert.That("Please insert name for module 3", Is.EqualTo(Convert.ToString((string)root["grades"][2]["module"])).NoClip);
                Assert.That("Please insert name for module 4", Is.EqualTo(Convert.ToString((string)root["grades"][3]["module"])).NoClip);
                Assert.That("Please insert name for module 5", Is.EqualTo(Convert.ToString((string)root["grades"][4]["module"])).NoClip);

                Assert.That("Low Fail", Is.EqualTo(Convert.ToString((string)root["grades"][0]["grade"])).NoClip);
                Assert.That("Marginal Fail", Is.EqualTo(Convert.ToString((string)root["grades"][1]["grade"])).NoClip);
                Assert.That("Pass", Is.EqualTo(Convert.ToString((string)root["grades"][2]["grade"])).NoClip);
                Assert.That("Commendation", Is.EqualTo(Convert.ToString((string)root["grades"][3]["grade"])).NoClip);
                Assert.That("Distinction", Is.EqualTo(Convert.ToString((string)root["grades"][4]["grade"])).NoClip);
            }
        }

        [Test]
        async public Task Test_All_Marks_And_Modules_Filled_In()
        {
            using (HttpClient client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync("http://classifygrade.40233517.qpc.hal.davecutting.uk/?mark_1=5&mark_2=45&mark_3=55&mark_4=65&mark_5=75&module_1=Programming&module_2=Coding&module_3=Networks&module_4=Cloud&module_5=Databases");
                response.EnsureSuccessStatusCode();
                var expectedValues = new Dictionary<string, object>();
                var responsebody = response.Content.ReadAsStringAsync().GetAwaiter().GetResult();
                JObject root = JObject.Parse(responsebody);

                Assert.AreEqual(HttpStatusCode.OK, response.StatusCode);

                Assert.AreEqual("False", Convert.ToString((string)root["error"]));

                Assert.That("Module: Programming", Is.EqualTo(Convert.ToString((string)root["grades"][0]["module"])).NoClip);
                Assert.That("Module: Coding", Is.EqualTo(Convert.ToString((string)root["grades"][1]["module"])).NoClip);
                Assert.That("Module: Networks", Is.EqualTo(Convert.ToString((string)root["grades"][2]["module"])).NoClip);
                Assert.That("Module: Cloud", Is.EqualTo(Convert.ToString((string)root["grades"][3]["module"])).NoClip);
                Assert.That("Module: Databases", Is.EqualTo(Convert.ToString((string)root["grades"][4]["module"])).NoClip);

                Assert.That("Low Fail", Is.EqualTo(Convert.ToString((string)root["grades"][0]["grade"])).NoClip);
                Assert.That("Marginal Fail", Is.EqualTo(Convert.ToString((string)root["grades"][1]["grade"])).NoClip);
                Assert.That("Pass", Is.EqualTo(Convert.ToString((string)root["grades"][2]["grade"])).NoClip);
                Assert.That("Commendation", Is.EqualTo(Convert.ToString((string)root["grades"][3]["grade"])).NoClip);
                Assert.That("Distinction", Is.EqualTo(Convert.ToString((string)root["grades"][4]["grade"])).NoClip);
            }
        }

        [Test]
        async public Task Test_Initiliazed_All_Parameters_With_Null_Value_Returns_Error_Add_Atleast_One_Grade_Or_Module()
        {
            using (HttpClient client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync("http://classifygrade.40233517.qpc.hal.davecutting.uk/?mark_1=&mark_2=&mark_3=&mark_4=&mark_5=&module_1=&module_2=&module_3=&module_4=&module_5=");
                response.EnsureSuccessStatusCode();
                var expectedValues = new Dictionary<string, object>();
                var responsebody = response.Content.ReadAsStringAsync().GetAwaiter().GetResult();
                JObject root = JObject.Parse(responsebody);

                var graded_modules1 = new
                {
                    error = true,
                    error_msg = "Please Insert Module Or Marks",
                    answer = 0
                };

                Assert.AreEqual(HttpStatusCode.OK, response.StatusCode);

                Assert.AreEqual("True", Convert.ToString((string)root["error"]));
                Assert.That("Please Insert Module Or Marks", Is.EqualTo(Convert.ToString((string)root["error_msg"])).NoClip);
                Assert.That("0", Is.EqualTo(Convert.ToString((string)root["answer"])).NoClip);
            }
        }

        [Test]
        async public Task Test_Empty_Grades_Empty_Modules_Returns_Error_True_And_Error_Message()
        {
            using (HttpClient client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync("http://classifygrade.40233517.qpc.hal.davecutting.uk");
                response.EnsureSuccessStatusCode();
                var expectedValues = new Dictionary<string, object>();
                var responsebody = response.Content.ReadAsStringAsync().GetAwaiter().GetResult();
                JObject root = JObject.Parse(responsebody);

                var graded_modules1 = new
                {
                    error = true,
                    error_msg = "Please Insert Module Or Marks",
                    answer = 0
                };

                Assert.AreEqual(HttpStatusCode.OK, response.StatusCode);

                Assert.AreEqual("True", Convert.ToString((string)root["error"]));
                Assert.That("Please Insert Module Or Marks", Is.EqualTo(Convert.ToString((string)root["error_msg"])).NoClip);
                Assert.That("0", Is.EqualTo(Convert.ToString((string)root["answer"])).NoClip);
                
            }
        }

        [Test]
        async public Task Test_Wrong_Datatype_String_Passed_In_Marks_Causes_Return_To_Ask_For_Real_Mark_Not_String()
        {
            using (HttpClient client = new HttpClient())
            {
                HttpResponseMessage response = await client.GetAsync("http://classifygrade.40233517.qpc.hal.davecutting.uk/?mark_1=hey_this_should_fail");
                response.EnsureSuccessStatusCode();
                var expectedValues = new Dictionary<string, object>();
                var responsebody = response.Content.ReadAsStringAsync().GetAwaiter().GetResult();
                JObject root = JObject.Parse(responsebody);

                Assert.AreEqual(HttpStatusCode.OK, response.StatusCode);

                Assert.AreEqual("False", Convert.ToString((string)root["error"]));

                Assert.That("Please insert name for module 1", Is.EqualTo(Convert.ToString((string)root["grades"][0]["module"])).NoClip);
                
            }
        }
    }
}
