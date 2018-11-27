<?php
/////////////////////////////////
// START Header for all pages///
///////////////////////////////
///
// Header for all pages
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- START HTML HEADER -->
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>DC-XP Tracker</title>
<style> @import "/styles/main_template.css"; </style>
<script>
// START Side Navbar control functions ///
function fun_openNav() {
    document.getElementById("Sidenav").style.width = "250px";
	document.getElementById("main").style.marginLeft = "250px";
} 
function fun_closeNav() {
    document.getElementById("Sidenav").style.width = "0";
	document.getElementById("main").style.marginLeft= "0";
}
// End Side Navbar control functions //
</script>
</head>
<!-- END HTML HEADER -->
<body>
'
///
/////////////////////////////////
// END Header for all pages/////
///////////////////////////////
?>