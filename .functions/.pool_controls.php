<?php
////////////////////////////////
// START Cash Pools Control ///
//////////////////////////////
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
fun_check_admin_rights();
///
///////////////////
// START ADD Cash to pool Code ///
//   This Code taked the Admin input and added it to the monthly cash pool
///
if ( isset($_POST['submit_cash_pool_add']) ) {
	// Clean and prep the data.
	$int_cash_pool_month  = fun_clean_input_data($_POST['int_cash_pool_month']);
	$int_cash_pool_year   = fun_clean_input_data($_POST['int_cash_pool_year']);
	$int_cash_pool_amount = fun_clean_input_data($_POST['int_usd_cash_amount']);
	settype($int_cash_pool_month, 'integer');
	settype($int_cash_pool_year, 'integer');
	settype($int_cash_pool_amount, 'integer');
	///
	// Check to make sure all needed values are still present.
	if ( (!empty($int_cash_pool_amount)) && (!empty($int_cash_pool_month)) && (!empty($int_cash_pool_year)) ) {
		// All values still found.
		///
		// Check if a value for the month and year already exist in the database.
		$str_sql = "SELECT `cash_pool_id` FROM `cash_pool_table` WHERE `cash_pool_month` = '$int_cash_pool_month' AND `cash_pool_year` = '$int_cash_pool_year'";
		$bln_record_for_that_month_year_exist = fun_check_db_for_existing_values($str_sql);
		settype($bln_record_for_that_month_year_exist, "bool");
		unset($str_sql);
		if ( !$bln_record_for_that_month_year_exist ) {
			// Connect to the DB and INSERT the new user record.
			require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
			// Build SQL INSERT Statment.
			$str_sql = "INSERT INTO `cash_pool_table`(`cash_pool_month`, `cash_pool_year`, `cash_pool_value`) 
						VALUES ('$int_cash_pool_month','$int_cash_pool_year','$int_cash_pool_amount')";
			if ( !mysqli_query($str_dbConnect,$str_sql) ) {
				$str_error_code = "ERROR: Failed to insert into the Database. " . mysqli_error($str_dbConnect) . "Mysql command given: --> " . $str_sql;
				unset($str_sql);
				mysqli_close($str_dbConnect);
				fun_error("admin_console", $str_error_code);
			} else { // connetion to DB all good
				unset($str_sql);
				mysqli_close($str_dbConnect);
				// Report the success.
				fun_message_redirect("admin_console","Pool submitted.");
			}
		}else { // an existing record for the month and year were found. 
			fun_error("admin_console", "ERROR: A cash pool already exist for the month given.");	
		}
	} else { // Not all value found. Report Error.
		fun_error("admin_console", "ERROR: One or more field were not complete. Please make sure all fields are filled out.");
		}
}
///
// END ADD Cash to pool Code  ///
//
///////////////////
// START DELETE Cash to pool Code  ///
//   This Code DELETES monthly cash pools. This can be used to fix bad to update values for a month.
///
if ( isset($_POST['submit_cash_pool_delete']) ) {
	$int_cash_pool_id = fun_clean_input_data($_POST['int_cash_pool_id']);
	settype($int_cash_pool_id, 'integer');
	// Check to make sure the value is still full after data clean.
	if ( !empty($int_cash_pool_id) ) {
		// Build DELETE SQL Statment.
		$str_sql = "DELETE FROM `cash_pool_table` WHERE `cash_pool_id` = '$int_cash_pool_id'";
		require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
		if ( !mysqli_query($str_dbConnect,$str_sql) ) {
			$str_error_code = "ERROR: Failed to Delete entry from the Database. " . mysqli_error($str_dbConnect) . "Mysql command given: --> " . $str_sql;
			unset($str_sql);
			mysqli_close($str_dbConnect);
			fun_error("admin_console", $str_error_code);
		} else { // connetion to DB all good
			unset($str_sql);
			mysqli_close($str_dbConnect);
			// Report the success.
			fun_message_redirect("admin_console","Pool Deleted.");
		}
	} else {
		fun_error("admin_console", "ERROR: The delete could not be completed, data was missing.");
	}
}
///
// END DELETE Cash to pool Code ///
//
///
////////////////////////////////
// END Cash Pools Control /////
//////////////////////////////
?>