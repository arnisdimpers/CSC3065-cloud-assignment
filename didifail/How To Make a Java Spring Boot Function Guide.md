www.start.spring.io

choose maven build, java language, springboot 2.6.7(or whatever default), java ver 11, add dependency Spring Web

open project in visual studio code, when asked to install java install it.

if HashMap and code is red, error:
The project was not built since its build path is incomplete. Cannot find the class file for java.lang.Object. Fix the build path then try building this project.
run command: brew install temurin

[RUN]
To run website navigate main/java/demoapp and click Play button
OR
type: mvn spring-boot:run

[TESTS]
To run tests: sudo apt install maven > mvn test


https://dockerize.io/guides/docker-spring-boot-guide
[DOCKERIZE]
Change IP: go to main/resources/application.properties, add: server.port = 5001
Copy Dockerfile change sdk version from 11 to whatever u chose for Java on website builder.

type: mvn clean package (a target folder is created)

then type this command:
java -jar ./target/demo-0.0.1-SNAPSHOT.jar (or whatever the jar file name and version is)

ctrl+shift+p build image
run with: docker run -p 5001:5001 spring_image

[HEADER CROSS ORIGIN ACCESS]
DemoApplication.java main function, at bottom of page added a simple class to allow cross origin request.
From Springboot documentation: https://spring.io/guides/gs/rest-service-cors/