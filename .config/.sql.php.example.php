<?php
//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
// This file supliys the MySQL connection details. This fille
// is loaded whenever a MySQL database connection is needed.
//
// This file need to be kept secure. Make sure it has the
// permisison of "chmod 550". 
//
//  Update the below with your database connection infomation. 
//  Then rename the file to ".sql.php".
//////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////
$str_DataBaseName="YOUR_DATABASE_NAME";
$str_DataBaseUser="YOUR_DATABASE_USERNAME";
$str_DataBasePass="YOUR_DATABASE_PASSWORD";
$str_DataBaseServer="localhost";
//
// Variable for connection to the DB
$str_dbConnect=mysqli_connect($str_DataBaseServer, $str_DataBaseUser, $str_DataBasePass, $str_DataBaseName);
//
// Check DB Connection, if the connection fails kill the process. 
if($str_dbConnect === false){
    die("ERROR: DB Connecion Fail. Please report to Admin. " . mysqli_connect_error());
}
?>
