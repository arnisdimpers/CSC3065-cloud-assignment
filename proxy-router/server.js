'use strict';

import express from 'express';
import fetch from 'node-fetch'; //used for Fetch Await
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
import { getDatabase, ref, onValue } from "firebase/database";

//set open port and IP for local hosting
const PORT = 8088;
const HOST = '0.0.0.0';

//need FIREFOX to open
//install nodejs
//npm install express
//node server.js     to run

//initialize loadbalancer to false
var loadBalancer = false;

//initialize result to null, will be used to fill in the response and respond with it to homepage
var result = null;

//use express to read request
const app = express();
app.get('/', (req, res) => {
  res.setHeader('Content-Type', 'application/json');
  res.setHeader('Access-Control-Allow-Origin', '*')

  console.log("started app");


  //read and save operation passed in, used as key to search through database and find the linked child nodes that
  //will have url1 and url2 for the service
  var operation = req.query.operation;

  //initialize all modules/grades and check if they are null replace with empty string ""
  //to avoid an error when building the url the parameters being passed in as "undefined"
  var module1 = req.query.module_1;
  if(module1 == null) {
    module1 = "";
  }

  var module2 = req.query.module_2;
  if(module2 == null) {
    module2 = "";
  }

  var module3 = req.query.module_3;
  if(module3 == null) {
    module3 = "";
  }

  var module4 = req.query.module_4;
  if(module4 == null) {
    module4 = "";
  }

  var module5 = req.query.module_5;
  if(module5 == null) {
    module5 = "";
  }

  var grades1 = req.query.mark_1;
  if(grades1 == null) {
    grades1 = "";
  }

  var grades2 = req.query.mark_2;
  if(grades2 == null) {
    grades2 = "";
  }

  var grades3 = req.query.mark_3;
  if(grades3 == null) {
    grades3 = "";
  }

  var grades4 = req.query.mark_4;
  if(grades4 == null) {
    grades4 = "";
  }

  var grades5 = req.query.mark_5;
  if(grades5 == null) {
    grades5 = "";
  }


  
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries
  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  const firebaseConfig = {
    apiKey: "AIzaSyABtvG0L-WYBr-lfupAP0iLlKxVdJzER08",
    authDomain: "cloudwebsite-1d9e7.firebaseapp.com",
    databaseURL: "https://cloudwebsite-1d9e7-default-rtdb.europe-west1.firebasedatabase.app",
    projectId: "cloudwebsite-1d9e7",
    storageBucket: "cloudwebsite-1d9e7.appspot.com",
    messagingSenderId: "792302685609",
    appId: "1:792302685609:web:da7e01b695b5f1192088b8",
    measurementId: "G-WD7ELKL5QD"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  //const analytics = getAnalytics(app);
  const db = getDatabase(app);

  //initialize that will be used to reach the service endpoint
  //1 for service 1, 2nd for service url 2, same service but
  //2 urls for load balancing/if 1 service is offline
  var serviceURL, serviceURL2 = null;

  //main method that checks the passed in operation
  function getURL(operation) {

    //try catch to catch any expections that may crash the program
    try {
      //define the route for the database and make an active 'onValue' listener
      //that will listen for any changes to url and url2 nodes of the selected operatio
      const dbref = ref(db, operation + '/');
      onValue(dbref, (snapshot) => {

        //read and save the 2 urls for the selected service
        serviceURL = snapshot.child("url").val();
        serviceURL2 = snapshot.child("url2").val();

        console.log("database operation read for: " + operation + " and returned value: " + serviceURL);

        //if operation is not null that means it exists in the database
        if (operation != null) {
          console.log("OPERATION BUILDING URL:  ~module1=" + module1 + "  grade1= " + grades1 + "  ~operation=" + operation);
          //if load balancer is false then build url to reach service point using url1
          if (loadBalancer == false) {
            var url = serviceURL + "?module_1=" + module1 + "&module_2=" + module2 + "&module_3=" + module3 + "&module_4=" + module4 + "&module_5=" + module5 + "&mark_1=" + grades1 + "&mark_2=" + grades2 + "&mark_3=" + grades3 + "&mark_4=" + grades4 + "&mark_5=" + grades5;
            
            //send built url with backup url2 and modules and grades
            getFunction(url, serviceURL2, module1, module2, module3, module4, module5, grades1, grades2, grades3, grades4, grades5);

            //set balancer to true for next iteration to use 2nd url of service
            loadBalancer = true;
          } else {
            //if statement false then build url using 2nd url that points to service
            var url = serviceURL2 + "?module_1=" + module1 + "&module_2=" + module2 + "&module_3=" + module3 + "&module_4=" + module4 + "&module_5=" + module5 + "&mark_1=" + grades1 + "&mark_2=" + grades2 + "&mark_3=" + grades3 + "&mark_4=" + grades4 + "&mark_5=" + grades5;
            
            //send built url with backup url2 and modules and grades
            getFunction(url, serviceURL, module1, module2, module3, module4, module5, grades1, grades2, grades3, grades4, grades5);

            //set balancer to false for next iteration to use the 1st url of the service
            loadBalancer = false;
          }
        }
      });
    } catch (err) {
      //catch any errors and print them in console
      console.log("error" + err);
    }
  }

  //run the getURL function to build the url used to fetch the service point response
  getURL(operation);

  //url = the url for service we built in main method before calling this function
  //proxy2 = the link to a 2nd proxy backup of the function called in url
  //module/grade = contains user input taken from url parameters, used to build a new proxy url using link2
  async function getFunction(url, proxy2, module1, module2, module3, module4, module5, grade1 = "", grade2 = "", grade3 = "", grade4 = "", grade5 = "") {
    try {
      //try fetch url and await for response
      const response = await fetch(url);
      //print to console
      console.log("1: URL passed: ~" + url);
      if (!response.ok) {
        //if response is not OK then throw error
        console.log("thowing error");
        throw new Error(`Error! status: ${response.status}`);
      }

      //if response is OK then await response to be encoded to JSON
      result = await response.json();
      console.log("2: passing result: ~");
      console.log(result);

      //respond with JSON result in string value
      res.end(JSON.stringify(result));

    } catch (err) {
      //catch error and print to console
      console.log("3: error: " + result.error + " = changing to proxy2");

      //build url using proxy2 service url
      url = proxy2 + "?module_1=" + module1 + "&module_2=" + module2 + "&module_3=" + module3 + "&module_4=" + module4 + "&module_5=" + module5 + "&mark_1=" + grades1 + "&mark_2=" + grades2 + "&mark_3=" + grades3 + "&mark_4=" + grades4 + "&mark_5=" + grades5;
      
      //call function with built url
      getFunction(url);
    }
  }
});

app.listen(PORT, HOST);
console.log("hey it works");
