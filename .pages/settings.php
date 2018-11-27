<?php
//////////////////////////////////////////////
// START User Settings controls and Functions //
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
require_once($_SERVER["DOCUMENT_ROOT"].".functions/.global.functions.php");
///
// Confirm the submittion came for a signed in User.
fun_check_user_rights();
///
///////////////////
/// START Update Password Code ///
//   Update a Users(or admins) Password.
if ( isset($_POST['submit_password_update']) ) {
	// Confirm the submittion came for a signed in User.
	fun_check_user_rights();
	// Get variable data
	$str_current_username   = fun_clean_input_data($_SESSION['str_username']);
	$str_users_email        = fun_clean_input_data($_SESSION['str_users_email']);
	$str_new_users_email    = fun_clean_input_data($_POST['str_users_email']);
	$str_old_password       = fun_clean_input_data($_POST['str_old_password']);
	$str_new_password       = fun_clean_input_data($_POST['str_new_password']);
	$str_new_password_check = fun_clean_input_data($_POST['str_new_password_check']);
	///
	// Check if all variables are set.
	if ( (!empty($str_current_username)) && (!empty($str_users_email)) && (!empty($str_new_users_email)) 
		&& (!empty($str_old_password)) && (!empty($str_new_password)) && (!empty($str_new_password_check)) ) {
		// Check if the two new passwords match.
		if ( $str_new_password == $str_new_password_check ) {
			$bln_new_pass_match = true;
		} else {
			fun_error("", "ERROR: The new passwords do not match. Please try again.");
			exit();
		}
		///
		// Check if email address needs to be updated.
		if ( $str_users_email != $str_new_users_email ) {
			$bln_update_user_email = true;	
		}
		///
		// Check if the old password is correct to confirm the User.
		$str_sql = "SELECT `md5_password` FROM `user_data` WHERE `username` = '$str_current_username'";
		$str_db_md5_password = fun_get_one_varabile_from_db($str_sql, 'md5_password');
		if ( $str_db_md5_password == (md5($str_old_password)) ){
			$bln_old_password_matches = true;
		} else {
			fun_error("", "ERROR: Old password confirmation failed.");
		}
		///
		// Check if all test are good. If good commit to DB.
		if ( ($bln_new_pass_match) && ($bln_old_password_matches) ){
			// Yes, test passed
			///
			// MD5 the New Password.
			$str_new_password_md5 = md5($str_new_password);
			///
			// Check if Email update is needed, then set the $str_sql string for data input into the DB.
			if ($bln_update_user_email) {
				// Yes, update the email address.
				$str_sql = "UPDATE `user_data` SET `md5_password`='$str_new_password_md5', `user_email`='$str_new_users_email' WHERE `username` = '$str_current_username' ";
			} else { // User email update not needed.
				$str_sql = "UPDATE `user_data` SET `md5_password`='$str_new_password_md5' WHERE `username` = '$str_current_username' ";
			}
			///
			// Update the DB.
			if ( !mysqli_query($str_dbConnect,$str_sql) ) {
				unset($str_sql, $str_current_username,$str_users_email,$str_new_users_email, $str_new_password,$str_new_password_check,$str_new_password_md5,$str_old_password,$str_db_md5_password,$str_users_email);
				mysqli_close($str_dbConnect);
				fun_error("", "ERROR: Failed to update account to the database." );
			} else { // connetion to DB all good
				unset($str_sql, $str_current_username,$str_users_email,$str_new_users_email, $str_new_password,$str_new_password_check,$str_new_password_md5,$str_old_password,$str_db_md5_password,$str_users_email);
				mysqli_close($str_dbConnect);
				fun_message_redirect("","Successfully updated your password");
			}
			///
		} else { // No, boolean test for old passowrd and new password failed.
			fun_error("", "ERROR: Final password checks failed.");
		}
	} else { // One or more values are empty. Error out.
		fun_error("", "ERROR: One or more valuse are missing from your password change request. Please make sure to fill out all feilds before submitting.");
	}
	///
}
///
// END Update Password Code ///
//
///////////////////
/// END Update Current Users EMail and Password Function FORM ///
//   This will echo out a form that a authenticated User can use to update thier own password.
function fun_update_user_email_or_password_form_only() {
	// Confirm the user is logged in.
	fun_check_user_rights();
	// START Echo out the ADD NEW Users form.
	echo '<table cellpadding="5" style="display: inline;" class="column" align="center">
		<tr align="left">
			<td><label>Update Account</label></td>
		</tr>
		<tr>
			<td>
			<form action="/.pages/settings" method="post" enctype="multipart/form-data" name="admin_submit_new_user_form">
				<input name="str_users_email" type="text" placeholder="Email Address" maxlength="128" required value="' . $_SESSION["str_email_addr"] . '" />
				<br /><input name="str_old_password" type="password" placeholder="Old Password" maxlength="32" required />
				<br /><input name="str_new_password" type="password" placeholder="New Password" maxlength="32" required />
				<br /><input name="str_new_password_check" type="password" placeholder="Repeat New Password" maxlength="32" required />
				<br /><br /><input name="submit_password_update" type="submit" value="Update" />
			</form></td>
		</tr>
	</table>';
	// END Echo out the ADD NEW Users form.
	//
}
///
// END Update Current Users EMail and Password Function FORM ///
//
///////////////////
/// START Settings-Page-FORM Code ///
// Added Page Header
echo '<div class="header"><h1 class="header" align="center">Settings</h1></div>';
///
// Echo the main table that will hold the page cobtects.
echo '<div class="content">';
///
// Show the Update User Details Form.
fun_update_user_email_or_password_form_only();
///
// End Content DIV
echo '</div class="content">';
///
// END Settings-Page-FORM Code ///
///
///
/////////////////////////////////////
// END Settings Page Content ///////
///////////////////////////////////
?>