<?php
// Use the right stylesheet aswell!
echo ('<link rel="stylesheet" href="' . $templatePath . 'style.css" type="text/css" />');
// First check if the data have been submited. 
// If the form have been submited -> Do this!
	if($_GET["step"] == 2) {
		
			// This is the variables that's needed for the StoredProcedure to be executed. (_ALL_ Is needed.)
			// 	All should have default values defined in the submitionform, giving the user little chance
			// 	to prodce any errors because of missing datainput. If this should happen, we dont want to 
			// 	bother the SQL-Server with this, so we produce an error, and display it nicly to the user (hopefully!)
						
			//	@IdPdv	Char(4) ,												Point of Sale -> No to or from here, as we want to be abit spesific.
			//	@DaData	SmallDateTime ,			@A_Data	SmallDateTime ,			From Date -> To Date
			//	@DaOra	SmallDateTime ,			@A_Ora	SmallDateTime ,			From Time -> To Time
			//	@DaCassa	TinyInt ,			@A_Cassa	TinyInt ,			From CaschRegister -> To CaschRegister
			//	@DaNumSc	SmallInt ,			@A_NumSc	SmallInt ,			From ReciptNumber -> To ReciptNumber
			//	@DaCodCli	Int ,				@A_CodCli	Int ,				From CustomerNumber -> To CustomerNumber
			//	@DaSVendIva	Money ,				@A_SVendIva	Money				From TotalPrice -> To TotalPrice
			
		// Check if the data is submitted correctly and push the variables into an array, that's used in 
		// $Connectoion->ReportGenerate to produse the SQL-resultset.
		//$vardumps .= (var_dump ($_GET));

		
		// Validate that the number ranges are correct, e.g. DaCassa 11 & A_Cassa 2. As this would result in a "bad" SQL-query.
		// Also check dobble check that the variables are OK.
		// This should also be checked in the JavaScript part, but to be 100% sure, we need to validate it on server-side aswell!
		$submiterror = "There is a problem with your submition. Go back, and try again.";
		echo "<pre>";
		try {
			// '<value to verify>', <Type of value>, <extra parameters if needed (can be several, split with ,)>, null, (always end with null)
			validate_request($_GET, 
				'DaData', TYPE_DATE, null, 
				'A_Data', TYPE_DATE, null,
				'DaOra', TYPE_TIME, null, 
				'A_Ora', TYPE_TIME, null,
				'DaCassa', TYPE_NUMERIC, null,
				'A_Cassa', TYPE_NUMERIC, null,
				'DaNumSc', TYPE_NUMERIC, null, 
				'A_NumSc', TYPE_NUMERIC, null,
				'DaCodCli', TYPE_NUMERIC, null,
				'A_CodCli', TYPE_NUMERIC, null,
				'DaSVendIva', TYPE_NUMERIC, null,
				'A_SVendIva', TYPE_NUMERIC, null);
				
		} catch (MissingFieldException $e) {
			//echo "MissingFieldException \n";		// Uncomment for debugging only.
			//var_dump($e->getTrace());				// Uncomment for debugging only.
			//var_dump ($e->getMessage());			// Uncomment for debugging only.
			die($error_validate);
		} catch (MissingArgsException $e) {
			//echo "MissingArgsException \n";		// Uncomment for debugging only.
			//var_dump($e->getTrace());				// Uncomment for debugging only.
			//var_dump ($e->getMessage());			// Uncomment for debugging only.
			die($error_validate);
		} catch (Exception $e) {
			//echo "Exception \n";					// Uncomment for debugging only.
			//var_dump($e->getTrace());				// Uncomment for debugging only.
			//var_dump ($e->getMessage());			// Uncomment for debugging only.
			die($error_validate);
		} 
		echo "</pre>";
		// if there is no errors in the validate part, bind the $_GET variables we need, to a new array.
		$sp_parms->IdPdv		= $_GET["IdPdv"];
		$sp_parms->DaData		= $_GET["DaData"];
		$sp_parms->A_Data		= $_GET["A_Data"];
		$sp_parms->DaOra		= $_GET["DaOra"];
		$sp_parms->A_Ora		= $_GET["A_Ora"];
		$sp_parms->DaCassa		= $_GET["DaCassa"];
		$sp_parms->A_Cassa		= $_GET["A_Cassa"];
		$sp_parms->DaNumSc		= $_GET["DaNumSc"];
		$sp_parms->A_NumSc		= $_GET["A_NumSc"];
		$sp_parms->DaCodCli		= $_GET["DaCodCli"];
		$sp_parms->A_CodCli		= $_GET["A_CodCli"];
		$sp_parms->DaSVendIva	= $_GET["DaSVendIva"];
		$sp_parms->A_SVendIva	= $_GET["A_SVendIva"];

		
	//	If it is, the call ReportGenerate with 
	//	the paramaters for the storedprocedure.
	try {
		$Connection->ReportGenerate($sp_parms);
	} catch (DataBaseException $e) {
			//echo "DataBaseException \n";
			echo($e->getMessage());
			die();
		}
	//echo ('<pre>');
	//print_r ($sp_parms);
	//print_r ($Connection->repData);
	//echo ('</pre>');
	
	
	//	Prepere the array used for the recipt details. 
	//	This data have to run by a foreach loop, 
	//	since there is several rows of data.
	
	$i = 0;
	foreach ($Connection->repData as $array){	
		$reportdata[] = array($Connection->repData[$i][0],$Connection->repData[$i][1],$Connection->repData[$i][2],$Connection->repData[$i][4],$Connection->repData[$i][5],$Connection->repData[$i][6],$Connection->repData[$i][13],$Connection->repData[$i][14],$Connection->repData[$i][15],$Connection->repData[$i][16]);
		$i = $i+1;
	}
			// Array description:		
			// [Trimmed - We dont need every field in the array for our report as of now.]
			// [0] => 57439731			// IDScontrino			// Needed for getting the single recipt(Not showing directly!)
            // [1] => 07/02/2010		// Data					
            // [2] => 08:45				// Ora					 
            // [3] => 003 				// IdPdv
            // [4] => 1					// Nummero Cassa
            // [5] => 1					// Nummero Scontrino
            // [6] => 0					// CodiceCliente		
            // [13] => 0,99				// VenditaIVA		
            // [14] => 0,00				// RettificaScontrino 	Rename [Ret] // Shows if there has been any modification on a recipt.
            // [15] => 5				// TempoScontrino		Rename [Sec] // Measures the time it took to complete this recipt.
            // [16] => 0				// Annullato			Rename [Ann] // Shows if the hole recipt has been annulated.
			$reportoutput = '';
			$i = 0;
			$odd = 'odd';
			$even = 'even';
			foreach ($reportdata as $row) {
				//$reportoutput .= '<tr id=' . ( ($i & 1) ? 'odd' : 'even' ) . '>';
				$color = ( ($i & 1) ? 'odd' : 'even' ); 
				$reportoutput .= "<tr class=" . $color . "><td>". $reportdata[$i][0] ."</td><td>".  $reportdata[$i][1] ."</td><td>".  $reportdata[$i][2] ."</td><td class=aright>".  $reportdata[$i][3] ."</td><td class=aright>".  $reportdata[$i][4] ."</td>";
				$i = $i+1;
			}
			$reportoutput .= '<tr class="sum"><td></td><td></td><td></td><td><b>Totale</b></td><td><b>' . $reportdata[0][5] . '</b></td>';
		
	// Include the template file, for the main report body.
	// It's also in the index.php where the report_header.php
	//	is included. 
	include('report_index.php');

	}
	
	// If report details have not been submitted, do this->
	elseif($_GET["step"] == 1) {	

	include ('/templates/reportdetails/form_1.php');
	}
	
?>