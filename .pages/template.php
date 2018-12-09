<?php
///////////////////////////////////////
// START Template Page Content //
/////////////////////////////////////
///
if(!isset($_SESSION)) { 
	session_start(); 
}
///////////////////
// Below used for testing
error_reporting(E_ALL);
ini_set("display_errors", true);
///////////////////
//  Import Global Funtions.
require_once(".functions/.global.functions.php");
///
//  Confirm the User has Admin Rights.
fun_check_admin_rights();
///
///////////////////

///
///////////////////////////////////////
// END Template Page Content ////
/////////////////////////////////////
///
?>