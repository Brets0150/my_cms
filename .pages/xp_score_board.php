<?php
/////////////////////////////////////
// START Score Board Page Content //
///////////////////////////////////
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
///////////////////////////////////
/// START ScoreBoard-FORM Code ///
/////////////////////////////////
///
///////////////////
/// START Dynamicly Update the scoreboard base on select boxes Function ///
//    Facilitates pulling info from the database when changes on the form occure.
//    echo out the AJAX script used to dynamiclly update the form
///
echo '<script>
function fun_pull_xp_data(int_tmp_month, int_tmp_year) {
	if (int_tmp_month == "" || int_tmp_year == "" ) {
		document.getElementById("span_display_board").innerHTML = "";
		return;
	} else { 
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp = new XMLHttpRequest();
		} else { // Browser older. I should not even support this.
			// code for IE6, IE5
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("span_display_board").innerHTML = this.responseText;
			}
		};
		// Connects to the backend script to get the data about the job. This auto fills the area
		//   Below the form with the info about the job.
		xmlhttp.open("GET",".functions/.xp_scoreboard?int_scoreboard_month=" +int_tmp_month+ "&int_scoreboard_year="+int_tmp_year,true);
		xmlhttp.send();
	}
}';
///
// END Dynamicly Update the scoreboard base on select boxes Function ///
///
///////////////////
// START Function to expand a tab ////
//     echo JS function the page needs to control the DIV boxes.
echo'
function fun_expand_tab(str_tab_name) {
	var i, x;
	x = document.getElementsByClassName("container-tab");
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	document.getElementById(str_tab_name).style.display = "block";
}
</script>';
///
///// END Function to expand a tab ////
//
/// END AJAX / JavaScript Functions /////
///
///////////////////
// Start the page content area. ///
///
// Get the current month and year for the MySQL next mysql statments.
$int_month_selected = date('m');
$int_year_selected  = date('Y');
///
///////////////////
// START Get Total of all Users XP ///
///
// Connect to the database.
require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
///
$str_sql_all_users_xp =    "SELECT SUM(`final_xp_score`) FROM `xp_data` 
							WHERE (MONTH(`date_submitted`) = $int_month_selected )
							AND (YEAR(`date_submitted`) = $int_year_selected )
							AND (`reviewed_status` = True )
							AND (`xp_accepted` = True )
							AND (`user_id` IN (
								SELECT `user_id` FROM `user_data`
								WHERE `account_active` = True ) )";
//
// Uses the function to return total of all users XP for the selected month.
$int_all_users_xp_amount = fun_get_one_varabile_from_db($str_sql_all_users_xp, 'SUM(`final_xp_score`)');
settype($int_all_users_xp_amount, 'integer');
///
// END Get Total of all Users XP ////
///
///////////////////
// Start DISPLAY Cash Pool Amount and Date Select Control Boxes ////
///
// Added Page Header
echo '<div class="header"><h1 class="header" align="center">Scoreboard</h1></div>';
///
//  Now we have the Cash Pool value for the month given, Display this infomation on page.
echo '
	<div class="sub-header">
		<div class="sub-navbar" align="center" style="width:100%;">
				<form action="/.pages/xp_score_board" method="get" enctype="multipart/form-data" name="admin_submit_cash_pool">';
///
// Create a select box that auto sets to the current month.
echo 'Date Select: <select name="int_scoreboard_month" id="int_scoreboard_month" onchange="fun_pull_xp_data(this.value,document.getElementById('."'".'int_scoreboard_year'."'".').value)">';
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
echo '<select name="int_scoreboard_year" id="int_scoreboard_year" onchange="fun_pull_xp_data(document.getElementById('."'".'int_scoreboard_month'."'".').value,this.value)">';
foreach(range('2015', '2025') as $int_year) {
	echo '<option value="'. $int_year . '"';
	if ( date('Y') == $int_year ) { 
		echo 'selected="selected"'; 
	}
	echo '>'. $int_year . '</option>';
}
echo '		</select>
		</form> 
	</div class="sub-navbar">
</div class="sub-header">';
///
// End the year and month select box.
///
///////////////////
// Start Page Contects that will be Dynamically updated ////
//    The Below DIV is linked to the AJAX function that will Dynamiclly update the data on the page. 
//    From here to the end of the DIV, the Content will be changed as the year and month select change.
echo '<span class="span_display_board" id="span_display_board" >';
// START Page Display content ///
echo '<div class="content" style="border-bottom-left-radius:0px;border-bottom-right-radius:0px;" >';
///
///////////////////
// START Get Cash Pool Amount
///
// Pull the current cash pool amount to calculate user percentages.
$str_sql_cash_pool =   "SELECT `cash_pool_value` FROM `cash_pool_table` 
						WHERE (`cash_pool_month` = $int_month_selected )
						AND (`cash_pool_year` = $int_year_selected )";
// Uses the function to return the cash amount for the month.
$int_cash_pool_amount = fun_get_one_varabile_from_db($str_sql_cash_pool, 'cash_pool_value');
settype($int_cash_pool_amount, 'integer');
//
// Echo Out the Cash Pool amount.
echo '<p align="center"<lablel style="align:center;"><strong>Cash Pool This Month is $'. $int_cash_pool_amount . '</strong></label></p>';
///
// END Get Cash Pool Amount ////
///
// String used to pull all active users from the database.
$str_sql = "SELECT `user_id`, `username` FROM `user_data` WHERE `account_active` = 1";
$str_result = mysqli_query($str_dbConnect,$str_sql);
///
///////////////////
// START Score-Card Builder Code ////
//   WHOLE loop builds the score cards for each Active User in the database.
///
while( $ary_row_users = mysqli_fetch_array($str_result,MYSQLI_ASSOC) ) {
	/// Start a Count of the Users XP
	$int_total_xp_for_selected_month = "0" ;
	// Build SQL statment to pull the current users XP for the current month and year.
	$str_sql_users_xp_data =   "SELECT `final_xp_score` 
								FROM `xp_data` 
								WHERE (MONTH(`date_submitted`) = $int_month_selected) 
								AND (YEAR(`date_submitted`) = $int_year_selected) 
								AND (`reviewed_status` = True ) 
								AND (`xp_accepted` = True ) 
								AND (`user_id` = '$ary_row_users[user_id]')" ;
	///
	//  Build SQL query to pull XP based on the Users ID.
	$str_result_xp_data = mysqli_query($str_dbConnect,$str_sql_users_xp_data);
	// Get the Users total score per xp request entry in the database and add them all up.
	while( $ary_row_users_xp_data = mysqli_fetch_array($str_result_xp_data,MYSQLI_ASSOC) ) {
		$int_total_xp_for_selected_month += $ary_row_users_xp_data['final_xp_score'];
	}
	///
	// Check if the User has 0 XP. If zero skip the math(will through error). 
	if ($int_total_xp_for_selected_month == 0 ){
		// User has 0 percemt of the pool.
		$int_total_cash_for_user = 0;
		$int_percentage_of_pool_for_user = 0;
	} else { // User has greater than 0 percent. Calculate his total $$$.
	// Calcualte THIS Users % of the cash pool.
		$int_percentage_of_pool_for_user = round((($int_total_xp_for_selected_month / $int_all_users_xp_amount) * 100), 2);
		$int_total_cash_for_user = round(( ($int_total_xp_for_selected_month / $int_all_users_xp_amount) * $int_cash_pool_amount ), 2);
	}
	// Echo out the header/first row of the DIV-table that will follow.
	echo '	<div class="column" >
				<p>'.  $ary_row_users['username'] . '</p>
				<p> Total XP: '. $int_total_xp_for_selected_month . '</p>
				<p>% of Pool: '. $int_percentage_of_pool_for_user . '% </p>
				<p>Cash : $'. $int_total_cash_for_user . '</p>
			</div>';		
}
///
//  The Below END DIV is linked to the AJAX function that will Dynamiclly update the data on the page. 
//    From here up the the Start of the DIV, the Content will be changed as the year and month select change.
echo ' </b></div class="content" >';
///
///////////////////////////////////
/// END ScoreBoard-FORM Code /////
/////////////////////////////////
///
///
///
///
//////////////////////////////////////////////////////
/// START User Ticket/XP request Review page Code ///
////////////////////////////////////////////////////
///
// Echo out the header/first row of the DIV-table that will follow.
	echo '
	<div class="content" >
	<div class="row" align="center">
		<lablel><strong>Your Ticket History For the Month</strong></label>
	</div class="row">
	<div class="row" style="background-color:black;">
			<div class="column" >
				<p>Ticket Number</p>
			</div>
			
			<div class="column" >
				<p>Job Name</p>
			</div>
			
			<div class="column" >
				<p>XP Total</p>
			</div>

			<div class="column" >
				<p>Status</p>
			</div>
		</div>';
	///
// String used to pull all active users from the database.
$str_current_login_username = $_SESSION["str_username"];
$str_sql = "SELECT `user_id`, `username` FROM `user_data` WHERE `username` = '$str_current_login_username' ";
$str_result = mysqli_query($str_dbConnect,$str_sql);
///
// For each active user in the database create a scorecard.
while( $ary_row_users = mysqli_fetch_array($str_result,MYSQLI_ASSOC) ) {
	// Connect to the database and pull all the records of ticket/XP-Request that need to be reviewed.
	////// !!!!!FEATURE NEEDED!!!!! Change to to limit the number of rows pulled, and create a "page" system.
	require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
	$str_sql = "SELECT `xp_id`, `user_id`, `job_id`, `ticket_number`, `date_submitted`, 
				`date_reviewed`, `requested_xp`, `bonus_xp`, `bonus_reason`, `reviewed_status`,
				`xp_accepted`, `final_xp_score`, `review_feedback` FROM `xp_data`
				WHERE (MONTH(`date_submitted`) = $int_month_selected) 
				AND (YEAR(`date_submitted`) = $int_year_selected)
				AND(`user_id` = '$ary_row_users[user_id]')";
	///				
	$str_result = mysqli_query($str_dbConnect,$str_sql);
	///
	// Start a counter. This conter is increment for each loop made in the next WHILE statment. 
	// This counter is used to alternate the colors of the rows(if odd do this, if even do this).
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
		// Get the total score of XP earned from a Job. If the job is not reviewed yet then no XP is applied to the total.
		$int_total_xp = $ary_row['final_xp_score'];
		if ( empty($int_total_xp) ) {
			$int_total_xp = 0;	
		}
		///
		// Start using the data to build rows on the page.
		echo '<div class="row">
		<div class="row" onclick="fun_expand_tab(' . $ary_row['xp_id'] .');" style="background:#' . $str_color_number. ';"">
				<div class="column" >
					<p><a href="'.$str_ticketing_system_url.$ary_row['ticket_number']  . '" target="_blank" >' . $ary_row['ticket_number'] . '</a></p>
				</div>
				<div class="column" >
					<p>'. $str_job_name .'</p>
				</div>
				<div class="column" >
					<p>' . $int_total_xp. '</p>
				</div>';
		// The Status bar changes baed on the status of the Job. Approved == Greeen, Waiting Review == Yellow, Denied == Red;
		if  ( $ary_row['reviewed_status'] == false ) { // The Request has not been reviewed.
			echo '	<div class="column" style="background-color:DarkOrange;">
						<p>Waiting Review</p>
					</div>
				 </div>';
		} elseif ( $ary_row['xp_accepted'] == true ) { // The Request for XP was reviewed and Approved.
			echo '	<div class="column" style="background-color:DarkGreen;">
					<p>Approved</p>
				</div>
			 </div>';
		} elseif ( $ary_row['xp_accepted'] == false ) {  // The request for XP was reviewed, but denied.
			echo '	<div class="column" style="background-color:DarkRed;">
					<p>Denied</p>
				</div>
			 </div>';
		} else { // Error, data did not load or none of the vaules are registering as booleans
			echo '	<div class="column" style="background-color:black;color:red;">
					<p>Error</p>
				</div>
			 </div>';
		}
		// END of the visable ROW
		///
		// START Building the hiden ROW that can be expanded.
		echo '<div id="' . $ary_row['xp_id'] .'" class="container-tab" style="display:none;background:#' . $str_color_number. ';">
				<span style="text-align:left" onclick="this.parentElement.style.display=' . "'none'" . '" class="row-closebtn">&times;</span><br /><br />
					<br /><label><strong>Date Submitted:</strong>'.$ary_row['date_submitted'].'</label><br />
					<br /><label><strong>Date Reviewed:</strong>' . $ary_row['date_reviewed'] .'</label><br />
					<p style="border-radius:5px; border: 1px solid black; padding:10px;width:80%;" > 
					<u><label>Preformance Feedback</label></u><br/>
					<textarea readonly="readonly" rows="4" wrap="hard" style="display:block;width:99%" >' .nl2br($ary_row['review_feedback'], true).'</textarea></p>';
		///
		// If there is a request for Extra XP add the below to the form
		if ( $ary_row['bonus_xp'] > 0 ) {
			echo '<p style="border-radius:5px;border:1px solid black;padding:10px;width:80%;">
			<label><u>Bonus XP Reuested:</u><strong>' . $ary_row['bonus_xp'] .'</strong></label><br/>
			<textarea readonly="readonly" rows="4" wrap="hard" style="display:block;width:99%" >' . nl2br($ary_row['bonus_reason'], true) . '</textarea></p>';
			}
		/// Admin Feedback section 
		echo '</div>
		</div>';
	}
}
echo '	
	</span class="span_display_board" id="span_display_board">
</span class="content" >';
///
//////////////////////////////////////////////////////
/// END User Ticket/XP request Review page Code /////
////////////////////////////////////////////////////
///
///
/////////////////////////////////////
// END Score Board Page Content ////
///////////////////////////////////
///
?>