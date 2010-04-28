<?php // Set header information
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
<meta content="text/html;charset=UTF-8" http-equiv="Content-type" />
<title> Delta G.S</title>
<link type="text/css" href="css/jqueryui/jquery.ui.all.css" rel="stylesheet" />
<link rel="stylesheet" href="style.css" type="text/css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js" ></script>
<script type="text/javascript" src="js/jquery.form.js" ></script>
<script type="text/javascript" src="js/jquery.ui.core.js" ></script>
<script type="text/javascript" src="js/jquery.ui.widget.js" ></script>
<script type="text/javascript" src="js/jquery.ui.datepicker.js" ></script>
<script type="text/javascript">
$(document).ready(function() {
	// do stuff when DOM is ready
	$('<img src="img/move-spinner.gif" id="spinner" />').css('position','absolute').hide().appendTo('body');
	
	$('#reportselector').ajaxForm({
		target: '#response',
		url: 'ajax.php',
		beforeSubmit: function() {loading(); clearOutput(); },
		success:  function(data) {output(); doneloading(); }
	});
	
	function output() {
		$('#reportdetails').ajaxForm({
		target: '#output',
		url: 'ajax.php',
		beforeSubmit:  function() {loading(); },
		success:  function(data) {output(); showOutput(); doneloading(); activate_export(); 	}
	});
		
	}
	function clearOutput () {
		$('#output').hide();
	}
	
	function showOutput () {
		$('#output').show();
	}
	
	function loading(data, $form, options) {
		$('#spinner').show().appendTo($form); 
	}
	
	function doneloading(data, $form, options) {
		$('#spinner').hide($form);
	}
});
</script>
</head>
<body>