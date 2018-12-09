<?php 
/////////////////////////////
// START Main INDEX Page ///
///////////////////////////
///
// Below used for testing
error_reporting(E_ALL);
ini_set("display_errors", true);
///
// Import Header
require(".pages/header.php");
///
// The next two lines are to fix the browsers "back" request from having a cache error.
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
// Start Session
session_start();
///////// START Side Navbar /////////
//  Start with top of the side Navbar, style "div.sidenav".
echo'<div id="Sidenav" class="sidenav">
<a href="javascript:void(0)" class="btn_close" onclick="fun_closeNav()">&times;</a>';
///
// Check if a User is already logged in.
if (isset($_SESSION["str_username"])) {
	require("./.functions/.global.functions.php");
	fun_navbar();
} else {
	// ELSE If User not logged in display login prompt using the "fun_login_form()" funtion.
	require(".functions/.login.php");
	fun_login_form();
}
// Echo out end of div.sidenav
echo '</div><span style="font-size:30px;cursor:pointer" onclick="fun_openNav()">&#9776;</span></div><div id="main">';
///
/////////  END Side Navbar /////////
///
//  If there are any Error codes display them here. Then clear the error code.
if ( isset($_SESSION["str_error_code"]) ) {
	echo '<h3 style="color: red">' . $_SESSION["str_error_code"]  .'</h3>';
	unset($_SESSION["str_error_code"]) ;
}
///
//  If there are any session mesaages display them here.
if ( isset($_SESSION["str_message"]) ) {
	echo '<h3 style="color: black">' . $_SESSION["str_message"] .'</h3>';
	unset($_SESSION["str_message"]) ;
}
///
//  Load the requested page or default to the home page.
//    This method will pull in the code need for diffrent pages
//    insted of loading a whole new page and having repetitive code.
if ( (isset($_POST["var_page"])) && (!empty($_POST["var_page"])) ) {
	// If a "posted" page request given then load that page.
	require(".pages/" . $_POST["var_page"] . ".php");
	unset($_POST["var_page"]);
//
} elseif ( (isset($_SESSION["var_page"])) && (!empty($_SESSION["var_page"])) ) {
	// If PHP  page request given then load that page.
	require(".pages/" . $_SESSION["var_page"] . ".php");
	unset($_SESSION["var_page"]);
//
} else {
	// If nither are set load the home page.
	require(".pages/home.php");
}
///
// Load Footer
require(".pages/footer.php")
///
/////////////////////////////
// END Main INDEX Page /////
///////////////////////////
///
?>
