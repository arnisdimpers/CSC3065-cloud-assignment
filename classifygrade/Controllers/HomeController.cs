using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Http.Extensions;

namespace firstWebAPI.Controllers
{
    [Route("/")]
    [ApiController]

    public class HomeController : ControllerBase
    {
        /*Inside the controller class make a httpget function to read the url text variable, and work with it.
        */
        [HttpGet]
        public IActionResult Get(string? module_1 = null, string? module_2 = null, string? module_3 = null, string? module_4 = null, string? module_5 = null, string? mark_1 = null
        , string? mark_2 = null, string? mark_3 = null, string? mark_4 = null, string? mark_5 = null)
        {
            //Part B 3 - If all Modules/Grades empty or NULL validation, make service produce Error:true, homepage can catch error and print String containing error information
            if (string.IsNullOrWhiteSpace(module_1) && string.IsNullOrWhiteSpace(mark_1) && string.IsNullOrWhiteSpace(module_2) && string.IsNullOrWhiteSpace(mark_2) &&
            string.IsNullOrWhiteSpace(module_3) && string.IsNullOrWhiteSpace(mark_3) && string.IsNullOrWhiteSpace(module_4) && string.IsNullOrWhiteSpace(mark_4) &&
            string.IsNullOrWhiteSpace(module_5) && string.IsNullOrWhiteSpace(mark_5))
            {
                var result = new
                {
                    error = true,
                    error_msg = String.Format("Please Insert Module Or Marks"),
                    answer = 0
                };
                Response.Headers.Add("Access-Control-Allow-Origin", "*");
                return Ok(result);
            }
            else
            {
                string[] modules_array = {module_1, module_2, module_3, module_4, module_5};

                string[] marks_array = {mark_1, mark_2, mark_3, mark_4, mark_5};

                List<object> number = classifygrade.ClassifyGrade.count(modules_array, marks_array);

                var result = new
                {
                    error = false,
                    modules = modules_array,
                    marks = marks_array,
                    grades = number
                };

                Response.Headers.Add("Access-Control-Allow-Origin", "*");
                return Ok(result);
            }
        }
    }
}
