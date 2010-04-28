<?php 
// File: submitionform.php
/*
This script should create the form to select available reports, 
and submit the correct data to index.php
Arthor: Marius Davidsen
*/
//print_r ($_POST);
if(@$_GET["ReportId"]){
	// Get template configuration
	$reportId = $_GET["ReportId"];
	$Connection->ReportConfig($reportId);
	
	// Setup parameters for showing report.
	$templatePath 		= $Connection->repConf->TemplatePath;
	$storedProcedure 	= $Connection->repConf->StoredProcedureName;
	$reportName 		= $Connection->repConf->ReportName;
	$CategoryName		= $Connection->repConf->CatName;
	
	
	include ($templatePath . 'config.php');
}	
?>
<form id="reportselector" action="index.php">
    <label for="ReportName">Report Name: </label>
		<select name="ReportId" >
			<?php 
			$Connection->ReportSelect();
			// Setup parameters for showing report.
			echo '<option value="">Scegliere un report</option>';
			$i = 0;
			foreach ($Connection->repSelect as $row){
				echo '<option value="' . $Connection->repSelect[$i][0] . '">' . $Connection->repSelect[$i][1] . '</option>';
				$i ++;
				}
							
			/** 
			 *	Array description: 
			 *	$ReportId	= $Connection->repSelect[$i][0];
			 * 	$ReportName = $Connection->repSelect[$i][1];
			 *	$CatName	= $Connection->repSelect[$i][2];
			 *	$CatId 		= $Connection->repSelect[$i][3];
			 */				
			 
				
			?>
		</select>
		<input type="hidden" name="step" value="1" />
		<input type="hidden" name="ajax" value="1" />
		<input type="submit" id="submitbtn1" />
</form>
<div id="response">
</div>
<div id="output">
</div>
