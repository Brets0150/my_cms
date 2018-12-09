<?php
//////////////////////////////////////////////
// START User Login controls and Functions //
////////////////////////////////////////////
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
require_once($_SERVER['DOCUMENT_ROOT']."/.functions/.global.functions.php");
///
///////////////////
// START LOGIN-FORM Funtion Code //
function fun_login_form() {
	echo '
	<form action="/.functions/.login" method="post" autocomplete="off">
		<table border="0" cellpadding="20">
  			<tr><td>
				<input name="bln_login" type="hidden" value="TRUE" />
				<input type="text" name="str_username" placeholder="UserName" required autocomplete="off" /><br>
				<input type="password" name="str_password" placeholder="Password" required autocomplete="off" /><br>		
			</td></tr>
		</table>
		<input type="submit" value="Login" />
	</form>
	' ;
}
//
// END LOGIN-FORM Funtion Code //
///
// START LOGOUT Code //
// Check if logout request given. If yes, clear all user session data.
if ( isset($_POST["bln_logout"]) ) {
	if( session_destroy() ) {
		header("Location: /");
	}
}
// END LOGOUT Code //
///
// START LOGIN Code //
// Check if login request given.
if ( isset($_POST["bln_login"]) ) {
	// Import required files to run and start the seasion.
	require("../.config/.sql.php");
	//
	// Check if Username and Password were given. If not return to Login page with error code.
	if ( (isset($_POST["str_username"])) && (isset($_POST["str_password"])) ) {
		// Username given. Securly clean and check the data.
		$str_username = fun_clean_input_data($_POST['str_username']);
		$str_password = fun_clean_input_data($_POST['str_password']);
		//  Convert password to MD5 Hash
		$str_password = md5($str_password);
		//
		// Connect to the database and look up if a Username and password match the provided credentials.
		$str_sql = "SELECT `user_id` FROM `user_data` WHERE `username` = '$str_username' AND `md5_password` = '$str_password' AND `account_active` = 1";
		$str_result = mysqli_query($str_dbConnect,$str_sql);
		$ary_row = mysqli_fetch_array($str_result,MYSQLI_ASSOC);
		$int_count = mysqli_num_rows($str_result);
		//
		// If more one row was found then an account matching the Username, Password and is Active was found.
		if ( $int_count == 1 ) {
			// Now that the User is athenticated, get account details.
			// Connect to DB and get account details about the user.
			$str_sql = "SELECT `user_email`, `admin_rights` FROM `user_data` WHERE `user_id` = ".$ary_row['user_id'];
	      	$str_result = mysqli_query($str_dbConnect,$str_sql);
    	  	$ary_row = mysqli_fetch_array($str_result,MYSQLI_ASSOC);
			///
			// Add the info pulled from DB to Session Variables.
			$_SESSION["str_username"] = $str_username;
			$_SESSION["bln_admin"] = $ary_row['admin_rights'] ;
			$_SESSION["str_email_addr"] = $ary_row['user_email'];
			fun_message_redirect("", "Hello ".$_SESSION["str_username"].". Welcome Back!");
		} else {
			fun_error("", "Login Failed. Maybe wrong Username, Password, or account disabled?");
		}
	}
}
// END LOGIN Code //
///
///
//////////////////////////////////////////////
// END User Login controls and Functions ////
////////////////////////////////////////////
?>
