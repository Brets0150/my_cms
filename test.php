<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<style> @import "/styles/main_template.css";</style>
<div class="homepage-container">

  <div class="header"><h1 class="header" align="center">Admin Console</h1></div>
   <div class="header">
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
  	<div class="content">

	

</div>
<div class="footer" align="center">DC XP Tracker &copy; <a href="https://BretStaton.com"  style="color:white;">Bret Staton</a></div>
</div>


<!-----

///////////////////
// START Pull Job Info from GET Requestto Auto fill Form ///
//   This function allows a Admin to Disable or enable a Account.
if ( isset($_GET['int_cash_pool_date']) ){
	$int_cash_pool_date = intval($_GET['int_cash_pool_date']);
	fun_check_db_for_existing_values($str_sql) 
	// Pull the Data from the Database
	require(".config/.sql.php");
	$str_sql = "SELECT `cash_pool_id` FROM `cash_pool_table` WHERE `cash_pool_month` = '' AND `cash_pool_year` = ''";
	
	$str_sql      = "SELECT `job_id`, `job_name`, `job_description`, `job_xp_value` FROM `jobs` WHERE `job_id` = '$int_job_info' ";
	$str_result   = mysqli_query($str_dbConnect,$str_sql);
	// Build a table with the data given.
	while($ary_row = mysqli_fetch_array($str_result)) {
		
	}
}
/// END Pull Job Info from GET Requestto Auto fill Form  ///
//

echo '<script>
///////////////////
/// START Dynamic Cash Pool Update Function ///
//   Facilitates pulling info the the database with changes on the form occure.
///
function fun_pull_pool_data(int_cp_month,int_cp_year ) {
    if (int_cash_pool_date == "") {
        document.getElementById("int_cash_pool_date").innerHTML = "";
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
                document.getElementById("int_cash_pool_date").innerHTML = this.responseText;
            }
        };
		// Connects to the backend script to get the data about the job. This auto fills the area
		//   Below the form with the info about the job.
        xmlhttp.open("GET","?int_cp_month=" +int_cp_month +"&int_cp_year="+ int_cp_month,true);
        xmlhttp.send();
    }
}
///
// END Dynamic Job Description Update Function  ///
//
</script>';




$str_sql = "SELECT `user_id`, `date_submitted`, `final_xp_score` FROM `xp_data`
			WHERE MONTH(`date_submitted`) = MONTH(CURRENT_DATE())
			AND YEAR(`date_submitted`) = YEAR(CURRENT_DATE()) 
			AND `reviewed_status` = 1
			AND `xp_accepted` = 1";


//$ary_xp_data = fun_array_varabile_from_db($str_tmp_sql);
echo '
<div id="piechart"></div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
// Load google charts
google.charts.load(""current", {"packages":["corechart"]});
google.charts.setOnLoadCallback(drawChart);
///
// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
	  ["User", "XP"],
';
while($ary_xp_data = mysqli_fetch_array($str_result,MYSQLI_ASSOC)) {
	///
	// 
	$str_sql_2 = "SELECT `username` FROM `user_data` WHERE `user_id` = '$ary_xp_data['user_id']'";
	$str_username = fun_get_one_varabile_from_db($str_sql_2, 'username');
	echo "['" . $str_username . ',' . $ary_xp_data['final_xp_score']
	
}
	
  
  ['Work', 8],
  ['Eat', 2],
  ['TV', 4],
  ['Gym', 2],
  ['Sleep', 15]
echo "]);
  // Optional; add a title and set the width and height of the chart
  var options = {'title':'My Average Day', 'width':550, 'height':400};
  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
"




///////////////////
// START Pull Job Info from GET Requestto Auto fill Form ///
//   This function allows a Admin to Disable or enable a Account.
if ((isset($_GET['int_scoreboard_month'])) && (isset($_GET['int_scoreboard_year'])) ){
	$int_job_info = intval($_GET['int_job_info']);
	// Pull the Data from the Database
	require(".config/.sql.php");
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



--->