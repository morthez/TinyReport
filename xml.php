<?php
define('FromIndex', true);
define('Debug', false);
define('Language', 'it-IT');

require_once('/libs/includes.php');

	$output = '';

	// Set error reporting for debugging and error tracking
	error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	// Set some debugging variables.
	$vardumps = '';
	
        // Create new object of class DBManager to manage all query's and such.
		$Connection 	= new DBManager($serverName, $dbuser, $dbpass, $database);
		$dateTime		= new datesAndTime();
		if(isset($_GET["ReportId"])){
			// Get template configuration
			$reportId = $_GET["ReportId"];
			
			if(empty($reportId)) {
				die("Please select a report to generate! :-)");
			} else {
			$Connection->ReportConfig($reportId);
			
			// Setup parameters for showing report.
			$templatePath 		= $Connection->repConf->TemplatePath;
			$storedProcedure 	= $Connection->repConf->StoredProcedureName;
			$reportName 		= $Connection->repConf->ReportName;
			$categoryName		= $Connection->repConf->CatName;
			
			
			
			include ($templatePath . 'config.php'); 
			}
			}
		else {
			include('submitionform.php');
		}
?>