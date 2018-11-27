<?php
////////////////////////////////////
// START User Request XP Control //
//////////////////////////////////
///
session_start();
///////////////////
// Below used for testing
//error_reporting(E_ALL);
//ini_set("display_errors", true);
///////////////////
//  Import Global Funtions.
require_once($_SERVER["DOCUMENT_ROOT"].".functions/.global.functions.php");
///
//  Confirm the User has Admin Rights.
fun_check_user_rights();
///
///////////////////
// START Pull Job Info from GET Requestto Auto fill Form ///
//   This function allows a Admin to Disable or enable a Account.
if ( isset($_GET['int_job_info']) ){
	$int_job_info = intval($_GET['int_job_info']);
	// Pull the Data from the Database
	require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
	$str_sql      = "SELECT `job_id`, `job_name`, `job_description`, `job_xp_value` FROM `jobs` WHERE `job_id` = '$int_job_info' ";
	$str_result   = mysqli_query($str_dbConnect,$str_sql);
	// Build a table with the data given.
	while($ary_row = mysqli_fetch_array($str_result)) {
		echo '<p><label>XP Value : </label><label>' .  $ary_row['job_xp_value'] . '</label></p>';
        echo '<input name="int_job_id_2" type="hidden" value="' .  $ary_row['job_id'] . '" />';
        echo '<p><table border="0" cellpadding="10"><tr><td>' .  nl2br($ary_row['job_description'], true) . '</td></tr></table></p>';

	}
}
/// END Pull Job Info from GET Requestto Auto fill Form  ///
//
///////////////////
/// START User Submits XP request to Database Code ///
//   A User submits a XP to the database. /
if ( isset($_POST['user_submit_xp_request']) ) {
	// Clean and prep the data passed from the form.
	$int_ticket_number     = fun_clean_input_data($_POST['int_ticket_number']);
	$str_submiter_username = fun_clean_input_data($_SESSION['str_username']);
	$int_job_id            = fun_clean_input_data($_POST['ary_jobs_list']);
	// Set the variable types.
	settype($int_ticket_number, 'integer');
	settype($int_job_id, 'integer');
	settype($str_submiter_username, 'string');
	// Check to make sure all the data needed has been submitted.
	if( (!empty($int_ticket_number)) && (!empty($str_submiter_username)) && (!empty($int_job_id)) ) {
		// All the needed Data found.
		///
		// Check if a request for Bonus XP was requested.
		if ( isset($_POST['bln_bonus_xp_request']) ) {
			// Clean and prep the data passed for the Bonus XP reques, IF it was requested.
			$bln_bonus_xp_request = fun_clean_input_data($_POST['bln_bonus_xp_request']);
			// Set the variable types.
			settype($bln_bonus_xp_request, 'bool');
			// Check if Request was made for Bonus XP.
			if ($bln_bonus_xp_request) {
				// Request WAS made for Bonus XP. Clean the data and check it before INSERTing it into the DB.
				$int_bonus_xp_request_value  = fun_clean_input_data($_POST['int_bonus_xp_request_value']);
				$str_bonus_request_reason    = fun_clean_input_data($_POST['str_bonus_request_reason']);
				// Set the variable types.
				settype($int_bonus_xp_request_value, 'integer');
				settype($str_bonus_request_reason, 'string');
				// Check if either value is empty after the data clean.
				if ( (empty($int_bonus_xp_request_value))  || (empty($str_bonus_request_reason)) ) {
					fun_error("xp_request", "ERROR: One or more of the Bonus XP Request Feilds were not filled in.");
				}
			}
		} else { // Bouse XP request was not made, or not all fields were set right.
			$int_bonus_xp_request_value  = 0 ;
			$str_bonus_request_reason  = "NA";
			settype($int_bonus_xp_request_value, 'integer');
			settype($str_bonus_request_reason, 'string');
		} //// END Bonus Data Chank and Prep.
		///
		// Get the User-ID from the database using the username provided.
		$str_sql = "SELECT `user_id` FROM `user_data` WHERE `username` = '$str_submiter_username' ";
		$int_user_id = fun_get_one_varabile_from_db($str_sql, 'user_id');
		unset($str_sql);
		// Get the Job XP value from the database using the job_id provided. Doing this on the back end prevents user front end manipulation.
		$str_sql = "SELECT `job_xp_value` FROM `jobs` WHERE `job_id` = '$int_job_id' ";
		$int_job_xp_value = fun_get_one_varabile_from_db($str_sql, 'job_xp_value');
		unset($str_sql);
		///
		//  
		$str_sql = "INSERT INTO `xp_data`(`user_id`, `job_id`, `ticket_number`, `requested_xp`, `bonus_xp`, `bonus_reason` ) 
					VALUES ($int_user_id,'$int_job_id','$int_ticket_number','$int_job_xp_value','$int_bonus_xp_request_value','$str_bonus_request_reason')";
		// Connect to the DB and INSERT the new user record.
		require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
		if ( !mysqli_query($str_dbConnect,$str_sql) ) {
			$str_error_code = "ERROR: Failed to insert into the Database. " . mysqli_error($str_dbConnect) . "Mysql command given: --> " . $str_sql;
			unset($str_sql);
			mysqli_close($str_dbConnect);
			fun_error("xp_request", $str_error_code);
		} else { // connetion to DB all good
			unset($str_sql);
			mysqli_close($str_dbConnect);
			// FINALLY, the new user has been added into the database. Report the success.
			fun_message_redirect("xp_request","XP Request Successful.");
		}	
	} else { // Missing required data or unclean data was given and parsed out by the "fun_clean_input_data" function.
		fun_error("xp_request", "ERROR: One or more fields are missing required information. Please make sure all fields are filled out.");
	}
}
///
// END  User Submits XP request to Database Code ///
//
///
////////////////////////////////////
// END User Request XP Control ////
//////////////////////////////////
?>