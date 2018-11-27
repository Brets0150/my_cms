<?php
///////////////////////////////////////
// START Admin Control Page Content //
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
require_once($_SERVER["DOCUMENT_ROOT"].".functions/.global.functions.php");
///
//  Confirm the User has Admin Rights.
fun_check_admin_rights();
///
///////////////////
/// START Admin-Sub-Navbar-FORM Funtion Code ///
function fun_admin_navbar() {
	echo '
	<div class="header"><h1 class="header" align="center">Admin Console</h1></div>
	   <div class="sub-header">
			<div class="sub-navbar" align="center">
			<!-- Sub-Navbar button to load user_contols form -->
				<form action="/" method="post" enctype="multipart/form-data" name="open_admin_user_control_form">
					<input name="var_page" type="hidden" value="admin_console" />
					<input name="var_admin_page" type="hidden" value="admin_user_control_page" />
					<input name="admin_user_controls" type="submit" value="User Controls" style="border-left-width:1px;background-color: #172FB2;" />
				</form>
			<!-- Sub-Navbar button to load review_tickets form -->
				<form action="/" method="post" enctype="multipart/form-data" name="open_admin_review_tickets_form">
					<input name="var_page" type="hidden" value="admin_console" />
					<input name="var_admin_page" type="hidden" value="admin_review_ticket_page" />
					<input name="admin_review_tickets" type="submit" value="Review Tickets" />
				</form>
		   <!-- Sub-Navbar button to load pool_data_form form -->
				<form action="/" method="post" enctype="multipart/form-data" name="open_admin_cash_pool_form">
					<input name="var_page" type="hidden" value="admin_console" />
					<input name="var_admin_page" type="hidden" value="admin_cash_pool_page" />
					<input name="admin_cash_pool" type="submit" value="Cash Pool" />
				</form>
		   <!-- Sub-Navbar button to load jobs_form form -->
				<form action="/" method="post" enctype="multipart/form-data" name="open_admin_jobs_form">
					<input name="var_page" type="hidden" value="admin_console" />
					<input name="var_admin_page" type="hidden" value="admin_jobs_page" />
					<input name="admin_jobs" type="submit" value="Jobs" />
				</form>
		   <!-- Sub-Navbar button to load admin_other_setting form -->
				<form action="/" method="post" enctype="multipart/form-data" name="open_admin_other_setting_form">
					<input name="var_page" type="hidden" value="admin_console" />
					<input name="var_admin_page" type="hidden" value="admin_other_setting_page" />
					<input name="admin_other_setting" type="submit" value="Settings" />
				</form>
			</div>
    </div>
	';	
}
///
// END Admin-Sub-Navbar-FORM Funtion Code ///
//
///////////////////
// START Admin-User-Control-FORM Funtion Code ///
function fun_admin_user_control_form() {
	// Import required files to run and start the seasion.
	require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
	///
	// START Echo out the DISABLE Users form.
 	echo '<table cellpadding="5" style="display: inline;" class="column" align="center">
        <tr><td><label>Disable a User Account</label></td>
        </tr>
        <tr>
        <td><form action="/.functions/.admin_user_controls" method="post" enctype="multipart/form-data" name="admin_disable_user_form">
            <select name="ary_user_list">';
	// The echo stoped at the "Select form, so a databaser call can be made to build the select box the all the "username"s and "user_id"s.	
	// Connect to the database and look up if a Username and password match the provided credentials.
	$str_sql = "SELECT `username` FROM `user_data` WHERE `account_active`=1";
	$str_result = mysqli_query($str_dbConnect,$str_sql);
	// While there are rows in of retreaved username data, echo data into the selcet box.
	while($ary_row = mysqli_fetch_array($str_result,MYSQLI_ASSOC)) {
		echo '<option value="' . $ary_row['username'] .'">' . $ary_row['username'] . '</option>';
	}
	// All users added to select box. Finish echoing the form out.
	echo '</select>
            <br /><input name="admin_submit_disable_user" type="submit" value="Disable User" />
        </form></td>
        </tr>
	</table>' ;
	// END Echo out the DISABLE Users form.
	///
	// START Echo out the ENABLE Users form.
 	echo ' <table cellpadding="5" style="display: inline;" class="column" align="center">
        <tr><td><label>Enable a User Account</label></td>
        </tr>
        <tr>
        <td>
		<form action="/.functions/.admin_user_controls" method="post" enctype="multipart/form-data" name="admin_enable_user_form">
            <select name="ary_user_list">';
	// The echo stoped at the "Select form, so a databaser call can be made to build the select box the all the "username"s and "user_id"s.	
	// Connect to the database and look up if a Username and password match the provided credentials.
	$str_sql = "SELECT `username` FROM `user_data` WHERE `account_active`=0";
	$str_result = mysqli_query($str_dbConnect,$str_sql);
	// While there are rows in of retreaved username data, echo data into the selcet box.
	while($ary_row = mysqli_fetch_array($str_result,MYSQLI_ASSOC)) {
		echo '<option value="' . $ary_row['username'] .'">' . $ary_row['username'] . '</option>';
	}
	// All users added to select box. Finish echoing the form out.
	echo '</select>
            <br /><input name="admin_submit_enable_user" type="submit" value="Enable User" />
        </form></td>
        </tr>
	</table>' ;
	// END Echo out the ENABLE Users form.
	///
	// START Echo out the ADD NEW Users form.
 	echo '<table cellpadding="5" style="display: inline;" class="column" align="center">
        <tr>
        	<td><label>Add a New User</label></td>
        </tr>
        <tr>
        <td><form action="/.functions/.admin_user_controls" method="post" enctype="multipart/form-data" name="admin_submit_new_user_form">
        	<input name="str_new_username" type="text" placeholder="New Username" maxlength="15" required />
            <br /><input name="str_new_users_email" type="text" placeholder="New Users Email Address" maxlength="128" required />
            <br /><label>Admin Rights</label><input name="bln_admin_rights" type="checkbox" value="True" />
            <br /><br /><input name="admin_submit_add_user" type="submit" value="Add User" />
        </form></td>
        </tr>
	</table>';
	// END Echo out the ADD NEW Users form.
	//
}
///
/// END Admin-User-Control-FORM Funtion Code ///
//
///////////////////
/// START Admin-Review-Tickets-FORM Funtion Code ///
function fun_admin_review_tickets_form() {
	///
	// echo JS function the page needs to control the DIV boxes.
	echo '
		<script>
		///// START Function to expand a tab ////
		function fun_expand_tab(str_tab_name) {
			var i, x;
			x = document.getElementsByClassName("container-tab");
			for (i = 0; i < x.length; i++) {
				x[i].style.display = "none";
			}
			document.getElementById(str_tab_name).style.display = "block";
		}
		///// END Function to expand a tab ////
		///
		///// START Function to add Base XP and Bonus XP on Button Click ////
		///
		function fun_add_bonus_xp(str_bonus_xp_field_id,str_base_xp_field_id) {
			var int_bonus_xp = document.getElementById(str_bonus_xp_field_id).value;
			var int_base_xp  = document.getElementById(str_base_xp_field_id).value;
			var x = +int_bonus_xp + +int_base_xp;
			document.getElementById(str_base_xp_field_id).value = x;
			///document.getElementById(accept_button).style.display = "none";
		}
		///
		</script>
	';
	// Echo out the header/first row of the DIV-table that will follow.
	echo '<div class="row" style="background-color:black;">
			<div class="column" >
				<p>Username</p>
			</div>
			
			<div class="column" >
				<p>Ticket Number</p>
			</div>
			
			<div class="column" >
				<p>Job Name</p>
			</div>
			
			<div class="column" >
				<p>Date Submitted</p>
			</div>
		</div>';
	///
	// Connect to the database and pull all the records of ticket/XP-Request that need to be reviewed.
	////// !!!!!FEATURE NEEDED!!!!! Change to to limit the number of rows pulled, and create a "page" system.
	require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
	$str_sql = "SELECT `xp_id`, `user_id`, `job_id`, `ticket_number`, `date_submitted`, `requested_xp`, `bonus_xp`, `bonus_reason` FROM `xp_data` WHERE `reviewed_status` = false ORDER BY `date_submitted` DESC";
	$str_result = mysqli_query($str_dbConnect,$str_sql);
	///
	/// Start a counter. This conter is increment for each loop made in the next WHILE statment. This counter is used to alternate the colors of the rows(if odd do this, if even do this).
	$int_row_count = 0;
	/// 
	// While loop takes the MySQL data returned from the "$str_sql" request and is then used to biuld the on page form/layout.
	while ( $ary_row = mysqli_fetch_array($str_result,MYSQLI_ASSOC) ) {
		$int_row_count += 1; // Used to count the numeber of row, and used to alternate the colors of the DIV table.
		if ($int_row_count % 2 == 0) { // Check if the numebr is odd or even
			// Even# == TRUE. Change the ROW color.
			$str_color_number = "1755B2";
		} else {
			// Even#(odd#) == FALSE Change the ROW color.
			$str_color_number = "57677f";
		}
		///
		// Convert the id number values from the database entrie to human readable names
		$str_sql2                 = "SELECT `username` FROM `user_data` WHERE `user_id` = ". $ary_row['user_id'] ;
		$str_username             = fun_get_one_varabile_from_db($str_sql2, 'username');
		$str_sql2                 = "SELECT `job_name` FROM `jobs` WHERE `job_id` = ".$ary_row['job_id'] ;
		$str_job_name             = fun_get_one_varabile_from_db($str_sql2, 'job_name');
		$str_sql2                 = "SELECT `setting_value`FROM `app_settings` WHERE `setting_name` = 'ticketing_system_url' " ;
		$str_ticketing_system_url = fun_get_one_varabile_from_db($str_sql2, 'setting_value');
		$str_request_submit_date  = date("m-d-Y", strtotime($ary_row['date_submitted']));
		///
		// Start using the data to build rows on the page.
		echo '<div class="row">
		<div class="row" onclick="fun_expand_tab(' . $ary_row['xp_id'] .');" style="background:#' . $str_color_number. ';"">
				<div class="column" >
					<p>'. $str_username . '</p>
				</div>
				<div class="column" >
					<p><a href="'.$str_ticketing_system_url.$ary_row['ticket_number']  . '" target="_blank" >' . $ary_row['ticket_number'] . '</a></p>
				</div>
				<div class="column" >
					<p>'. $str_job_name .'</p>
				</div>
				<div class="column" >
					<p>'. $str_request_submit_date . '</p>
				</div>
			</div>';
		// END of the visable ROW
		///
		// START Building the hiden ROW that can be expanded.
		echo '<div id="' . $ary_row['xp_id'] .'" class="container-tab" style="display:none;background:#' . $str_color_number. '">
				<span style="text-align:left" onclick="this.parentElement.style.display=' . "'none'" . '" class="row-closebtn">&times;</span><br /><br />
				<form action="/.functions/.admin_review" method="post" enctype="multipart/form-data" name="submit_xp_review_form" class="data-conlumn">';
		///
		// If there is a request for Extra XP add the below to the form
		if ( $ary_row['bonus_xp'] > 0 ) {
			echo '<p style="border-radius: 5px; border: 1px solid black; padding:5px;width:50%;" > 
			<input id="int_bonus_value_'.$ary_row['xp_id'].'" name="int_bonus_xp_requested" type="hidden" value="'.$ary_row['bonus_xp'].'" />
			<label>Bonus XP Reuested</label><br/>
			<input name="bonus_xp_requested_display" type="text" value="'.$ary_row['bonus_xp'].'" size="4" maxlength="8" readonly />
			<input name="accept_bonus_xp_request" type="button" value="Accept Bonus"
			onclick="fun_add_bonus_xp('."'".'int_bonus_value_'.$ary_row['xp_id']."'".",'".'int_base_xp_value_'.$ary_row['xp_id']."'".'); this.style.visibility=' . "'hidden'" . '";" />
			<br /><br />
			' . nl2br($ary_row['bonus_reason'], true) . '</p>';
			}
		/// Finish Echo-ing the form out.
		echo '<input name="xp_id" type="hidden" value="'.$ary_row['xp_id'].'" />
				<textarea name="str_admin_feedback" id="str_admin_feedback" cols="" rows="" placeholder="Feedback on work preformance." style="width:80%;padding:5px;border-radius:5px;" ></textarea><br/>
				<input id="int_base_xp_value_'.$ary_row['xp_id'].'"  maxlength="8" name="int_total_xp_value" type="number" size="8" value="'.$ary_row['requested_xp'].'" style="border-radius:5px;" />
				<br/><br/><input name="admin_submit_xp_review" type="submit" value="Accept" />
				<input name="admin_submit_xp_review" type="submit" value="Denied" />
			</form>
		</div> 
		</div>
		';
	}
}
///
/// END Admin-Review-Tickets-FORM Funtion Code ///
//
///////////////////
/// START Admin-Cash_Pool-FORM Funtion Code ///
function fun_admin_cash_pool_form() {
	///////////////////
	// START SUBMIT To Cash Pool Form ///
	//   This form will allow the Admin is submit cash to the cash pool.
	echo '
	<table border="0" cellpadding="10" align="center"><tr align="left"><td>
	<form action="/.functions/.pool_controls" method="post" enctype="multipart/form-data" name="admin_submit_cash_pool">';
	// Create a select box that auto sets to the current month.
	echo '
	<p><label> Cash Pool Date<label><br/>
	<select name="int_cash_pool_month">';
	foreach(range('1', '12') as $int_month) {
		echo '<option value="'. $int_month . '"';
		if ( date('n') == $int_month ) { 
			echo 'selected="selected"'; 
		}
		echo '>'. $int_month . '</option>';
	}
	echo '</select>';
	///
	// Create a select box that auto sets to the current year.
	echo '<select name="int_cash_pool_year">';
	foreach(range('2015', '2025') as $int_year) {
		echo '<option value="'. $int_year . '"';
		if ( date('Y') == $int_year ) { 
			echo 'selected="selected"'; 
		}
		echo '>'. $int_year . '</option>';
	}
	/// Finish echoiung the select box and add the number box for the $USD$ Pool value.
	echo '</select></p>
	<p><label>Cash Pool Amount<label><br />
	<input name="int_usd_cash_amount" type="number" size="8" maxlength="8" placeholder="$$$$"/></p>
	<input name="submit_cash_pool_add" type="submit" />
	</td></tr></table>
	</form>';
	// Connect to the database build a select box with all active jobs.
	require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
	$str_sql = "SELECT `cash_pool_id`, `cash_pool_month`, `cash_pool_year`, `cash_pool_value` FROM `cash_pool_table` LIMIT 25";
	$str_result = mysqli_query($str_dbConnect,$str_sql);
	///
	// Echo the start of the table with the existing pool data.
	echo '<table border="0" cellpadding="10" align="center">
		  <tr>
			<td>Date</td>
			<td>$Cash Amount$</td>
			<td>&nbsp;</td>
		  </tr>';
	// While there are rows in of retreaved username data, echo data into the selcet box.
	while($ary_row = mysqli_fetch_array($str_result,MYSQLI_ASSOC)) {
		echo '	
		  <tr>
			<td>'.$ary_row['cash_pool_month']. '/' .$ary_row['cash_pool_year'] .'</td>
			<td>&dollar;'.$ary_row['cash_pool_value']. '</td>
			<td>
			<form action="/.functions/.pool_controls" method="post" enctype="multipart/form-data" name="delete_cash_pool">
			<input name="int_cash_pool_id" type="hidden" value="'.$ary_row['cash_pool_id'].'" />
			<input name="submit_cash_pool_delete" type="submit" value="Delete" />
			</form>
			</td>
		  </tr>';
	}
	echo '</table>';
	/// 
	// END of the SUBMIT Cash Pool.
}
///
/// END Admin-Cash_Pool-FORM Funtion Code ///
//
///////////////////
/// START Admin-JOBS-FORM Funtion Code ///
function fun_admin_jobs_form() {
	echo '<table border="0" cellpadding="10" align="center">
  <tr><td>
    <label >Add New Job</label>
   </td></tr>
  <tr><td>
    <form action="/.functions/.jobs_controller" method="post" enctype="multipart/form-data" name="submit_new_job_form" >
        <p><input autofocus="autofocus" tabindex="1" name="str_job_name" type="text" maxlength="127" placeholder="New Job Name" required /></p>
        <p><textarea  tabindex="2" name="str_job_description" cols="50" rows="15" placeholder="New Job Description" required></textarea></p>
        <p><label>XP Value</label>
        <input tabindex="3" name="int_xp_value" type="text" size="8" maxlength="8" required /><br />
		<input tabindex="4" name="admin_submit_new_job" type="submit" value="Submit" />
    </form>
    </td></tr>
</table>';
}
///
/// END Admin-JOBS-FORM Funtion Code ///
//
///////////////////
/// START Admin-Other-Setting-FORM Funtion Code ///
function fun_admin_other_setting_form() {
	echo "admin_other_settings_form <br/> In progress...
	<br/> Need control to change Ticket Settings Link.
	<br/> 
	";
}
///
/// END Admin-Other-Setting-FORM Funtion Code ///
//
///////////////////
// START ACTUAL Page Content //
/// Add DIV wraper for style and content layout controls.
echo '<div class="homepage-container">';
// Input this pages sub-navbar
fun_admin_navbar();
///
echo '<div class="content">'; /// START of the div class="content"
/// Main form will be loaded below.
////
// Check if "$_POST["var_admin_page"]" empty. If yes, give it the "default" for next swicth statment.
if(!isset($_POST["var_admin_page"])) {
    $_POST["var_admin_page"] = "default";
}
// Check the "$_POST["var_admin_page"]" to see what form to load.
switch ($_POST["var_admin_page"]) {
	case "admin_user_control_page":
		fun_admin_user_control_form();
		break;
	case "admin_review_ticket_page":
		fun_admin_review_tickets_form();
		break;
	case "admin_cash_pool_page":
		fun_admin_cash_pool_form();
		break;
	case "admin_jobs_page":
		fun_admin_jobs_form();
		break;
	case "admin_other_setting_page":
		fun_admin_other_setting_form();
		break;
	default:
		fun_admin_review_tickets_form();
}
///
echo '</div></div>'; /// END of the div class="content"
///
///////////////////////////////////////
// END Admin Control Page Content ////
/////////////////////////////////////
///
?>