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
// START Add New Job To the Data Base ///
///
if ( isset($_POST['admin_submit_new_job']) ) {
	// Clean the data before proceeding.
	$int_xp_value = fun_clean_input_data($_POST["int_xp_value"]);
	$str_job_name = fun_clean_input_data($_POST["str_job_name"]);
	$str_job_description = fun_clean_input_data($_POST["str_job_description"]);
	settype($int_xp_value, "integer");
	settype($str_job_name, "string");
	settype($str_job_description, "string");
	// Make sure all form Data was filled out and passed.
	if ( (!empty($int_xp_value)) && (!empty($str_job_name)) && (!empty($str_job_description)) ) {
		// All needed Data was passed.
		///
		$str_sql = "SELECT `job_id` FROM `jobs` WHERE `job_name` = '$str_job_name'";
		$bln_does_job_name_exist = fun_check_db_for_existing_values($str_sql);
		settype($bln_does_job_name_exist, "bool");
		unset($str_sql);
		// Confirm the job check came back as no, then put the new job in the database.
		if ( $bln_does_job_name_exist == false ) {
			// The Job name does not Exist. safe to insert into the Database.
			$bln_job_active = true;
			settype($bln_job_active, "bool");
			$str_sql = "INSERT INTO `jobs`(`job_name`, `job_description`, `job_xp_value`, `job_active`) 
						VALUES ('$str_job_name','$str_job_description','$int_xp_value','$bln_job_active')";
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
				fun_message_redirect("admin_console","Successfully added new Job.");
			}
		} else { /// The JOB name exists in the the DB.
			fun_error("admin_console", "ERROR: The job name already exists." );
		}
	} else { // One of the form fields were not filled in.
		fun_error("admin_console", "ERROR: Not all form fields were filled out. Please fill out all fields and submit again.");
	}
}
/// END Add New Job To the Data Base  ///
//
///////////////////
// START EDIT Job in the Data Base ///
///
if ( isset($_POST['admin_edit_job']) ) {
	
}
/// END EDIT Job in the Data Base ///
//
///////////////////
// START LOAD a Job to be edited from the Data Base ///
///
if ( isset($_POST['admin_load_job']) ) {
	
}
/// END LOAD a Job to be edited from the Data Base  ///
//
///
///////////////////////////////////////
// END Admin User Account Control ////
/////////////////////////////////////
///