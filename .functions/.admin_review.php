<?php
///////////////////////////////////////
// START Admin User Account Control //
/////////////////////////////////////
///
session_start();
///////////////////
// Below used for testing
error_reporting(E_ALL);
ini_set("display_errors", true);
///////////////////
//  Import Global Funtions.
require_once($_SERVER["DOCUMENT_ROOT"].".functions/.global.functions.php");
///
//  Confirm the User has Admin Rights.
fun_check_admin_rights();
///
///////////////////
// START SUBMIT Ticket/XP Review Code ///
//   This code will take the Admin's review of the ticket and the final XP score then add it to the database.
if ( isset($_POST['admin_submit_xp_review']) ){
	///
	// Clean the user input date
	$int_request_xp_id  = fun_clean_input_data($_POST['xp_id']);
	$int_total_xp_value = fun_clean_input_data($_POST['int_total_xp_value']);
	$str_admin_review   = fun_clean_input_data($_POST['admin_submit_xp_review']);
	$str_admin_feedback = fun_clean_input_data($_POST['str_admin_feedback']);
	///
	// Set the variable types
	settype($int_request_xp_id, 'integer');
	settype($int_total_xp_value, 'integer');
	settype($str_admin_review, 'string');
	settype($str_admin_feedback, 'string'); 
	///
	// Confirm data cleaning did not return empty values.
	if ( (!empty($int_request_xp_id)) && (!empty($int_total_xp_value)) && (!empty($str_admin_review)) ) {
		// All the needed values are Set. Now check to make sure those vaules are not empty.
		if ( $str_admin_review == "Accept" ) {
			$bln_xp_accepted = true;
		} else { // The Admin DENIED the XP request. Commit the values to the DB with DENIED status.
			$bln_xp_accepted = false;
			$int_total_xp_value = 0;
			settype($int_total_xp_value, 'integer');
		}
		///
		// Set the boolean that the review process has happened.
		$bln_reviewed_status = true;
		settype($bln_reviewed_status, 'bool');
		///
		// Prepare the SLQ Statment.
		$str_sql = "UPDATE `xp_data` SET `date_reviewed`=now(),`reviewed_status`='$bln_reviewed_status',
					`xp_accepted`='$bln_xp_accepted',`final_xp_score`='$int_total_xp_value',
					`review_feedback`='$str_admin_feedback' WHERE `xp_id` = '$int_request_xp_id'";
		///
		// Connect to the DB and INSERT the new user record.
		require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
		if ( !mysqli_query($str_dbConnect,$str_sql) ) {
			$str_error_code = "ERROR: Failed to insert into the Database. " . mysqli_error($str_dbConnect) . "Mysql command given: --> " . $str_sql;
			unset($str_sql);
			mysqli_close($str_dbConnect);
			fun_error("admin_console", $str_error_code);
		} else { // connetion to DB all good
			unset($str_sql);
			mysqli_close($str_dbConnect);
			// FINALLY, the new user has been added into the database. Report the success.
			fun_message_redirect("admin_console","Review submited.");
		}
	} else { // One or more of the data variables were empty. Kick User to Error page.
		fun_error("admin_console", "ERROR: The submitted data was not clean or empty. Valuse: " . $int_request_xp_id . $int_total_xp_value . $str_admin_review );
	}
} /// END "$_POST['admin_submit_xp_review']" was not set.

///
// END SUBMIT Ticket/XP Review Code ///
///
///////////////////////////////////////
// END Admin User Account Control ////
/////////////////////////////////////
?>
