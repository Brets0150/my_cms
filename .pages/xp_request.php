<?php
/////////////////////////////////////
// START XP Request Page Content ///
///////////////////////////////////
///
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
/// START Request-XP-FORM Code ///
// Added Page Header
echo '<div class="header"><h1 class="header" align="center">XP Request</h1></div>';
// echo out the AJAX script used to dynamiclly update the form
echo '<script>
///////////////////
/// START Dynamic Job Description Update Function ///
//   Facilitates pulling info the the database with changes on the form occure.
function fun_pull_job_info(str_job_name) {
    if (str_job_name == "") {
        document.getElementById("str_job_info").innerHTML = "";
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
                document.getElementById("str_job_info").innerHTML = this.responseText;
            }
        };
		// Connects to the backend script to get the data about the job. This auto fills the area
		//   Below the form with the info about the job.
        xmlhttp.open("GET",".functions/.xp_request?int_job_info="+str_job_name,true);
        xmlhttp.send();
    }
}
///
// END Dynamic Job Description Update Function  ///
//
///////////////////
/// START Bonus field Dynamic update Function ///
// Template Discription
function fun_show_hide_bonus_xp_form() {
    var obj_form = document.getElementById("bonus_xp_request_form");
    
	if (obj_form.style.display === "none") {
        obj_form.style.display = "block";
		document.getElementById("int_bonus_xp_request_value").required =  true;
		document.getElementById("str_bonus_request_reason").required =  true;
		
    } else {
        obj_form.style.display = "none";
		document.getElementById("int_bonus_xp_request_value").required =  false;
		document.getElementById("str_bonus_request_reason").required =  false;
    }
}
///
// END Bonus field Dynamic update Function  ///
//
</script>';
// Echo the main table that will hold the page cobtects.
echo '
<div class="content">
	<table border="0" cellpadding="10" align="left">
		<!--<tr><td align="center">
			<label >Request XP</label></td></tr>   --> <tr><td>
				<form action="/.functions/.xp_request" method="post" enctype="multipart/form-data" name="submit_xp_request_form" >
					<p><input autofocus="autofocus" tabindex="0" name="int_ticket_number" type="number" 
						maxlength="16" placeholder="Ticket Number" required onkeyup="this.value = this.value.replace(/[^0-9]/,'."''".')"/>
						<select name="ary_jobs_list" onchange="fun_pull_job_info(this.value)" required>
							<option value="">Select a Job</option>';
//////
// Connect to the database build a select box with all active jobs.
require($_SERVER["DOCUMENT_ROOT"].".config/.sql.php");
$str_sql = "SELECT `job_name`, `job_id` FROM `jobs` WHERE `job_active` = 1 ";
$str_result = mysqli_query($str_dbConnect,$str_sql);
// While there are rows in of retreaved username data, echo data into the selcet box.
while($ary_row = mysqli_fetch_array($str_result,MYSQLI_ASSOC)) {
	echo '<option value="' . $ary_row['job_id'] .'">' . $ary_row['job_name'] . '</option>';
}
//
// Finish echoing the form.
/////
echo '						</select></p>
					<input name="var_page" type="hidden" value="xp_request" />
					<!-- START BONUS XP DIV AREA --->
					<!-- Bonus XP Request section. This check box will change the form if Bonus XP is requested --->
					<p><label>Bonus?</label><input name="bln_bonus_xp_request" type="checkbox" onclick="fun_show_hide_bonus_xp_form()" value="true" /></p>
					<!-- Everything inside the "bonus_xp_request_form" DIV tag will be show/hiden based on the above check box state. --->
					
					<div id="bonus_xp_request_form" style="display:none;"> 
						<p><label>Bonus XP Request Value</label><br />
						<input id="int_bonus_xp_request_value" name="int_bonus_xp_request_value" type="number" 
						size="8" maxlength="8" onkeyup="this.value = this.value.replace(/[^0-9]/,'."''".')" /></p>        
						<p><textarea id="str_bonus_request_reason" name="str_bonus_request_reason" cols="30" rows="5" placeholder="Bonus Request Reason"></textarea></p>
					</div>
					<!-- END BONUS XP DIV AREA --->
					
					<input name="user_submit_xp_request" type="submit" value="Submit" />        
        		<div id="str_job_info" class="content"><b>Job Description...</b></div>
    		</form>
		</td></tr>
	</table>
</div>';
///
// END Request-XP-FORM Code ///
//
/////////////////////////////////////
// END XP Request Page Content /////
///////////////////////////////////
?>