<?php
// Use the right stylesheet aswell!
//echo ('<link rel="stylesheet" href="' . $templatePath . 'style.css" type="text/css" />');
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
		
		try {
		if(empty($_GET["IdPdv"])) {
			throw new Exception("Scegliere un punto-vendita...");
		}
		} catch(Exception $e) {
			die($e->getMessage());
		}
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
			echo "DataBaseException \n";
			echo($e->getMessage());
			die();
		}
	//echo ('<pre>');
	//print_r ($sp_parms);
	//print_r ($Connection->repData[4]);
	//echo ('</pre>');
	
	// Calculate the % of all the width values in the array.
	// The first row, must always be numbers for the col. width.
		// Get the BGcolor value, and remove the value from the array. (cleaning up)
	$bgcolor = array_shift($Connection->repData[0]);
	$fontatt = array_shift($Connection->repData[0]);
	$array_total = array_sum ($Connection->repData[0]);
	
	$colspan = count($Connection->repData[0]);
	$percent = array();
	foreach ($Connection->repData[0] as $key => $Value) {
		array_push($percent, round(($Value / ($array_total * 1.10) * 100 ),2));
	}
	
	// The 2nd row is always the alignment for the tabledata. 
	$alignment = array();
	$bgcolor = array_shift($Connection->repData[1]);
	$fontatt = array_shift($Connection->repData[1]);
	foreach ($Connection->repData[1] as $key => $Value) {
		array_push($alignment, $Value);
		
	}	
	
	//print_r ($Connection->repConf);
	$output = '<style type="text/css">'. $Connection->repConf->Style .'</style>';
	
	// Get the BGcolor value, and remove the value from the array. (cleaning up)
	$bgcolor = array_shift($Connection->repData[2]);
	$fontatt = array_shift($Connection->repData[2]);
	$output  .='<table class="contenttable" style="border: 1;">';
	
	// Add some printing header info to the thead property.
	$Connection->executeSpStatic($Connection->repConf->IdPdvlist);
	try {
		$idpdv_name = $Connection->getIdpdv($_GET["IdPdv"]);
	} catch (Exception $e) {
		//echo "Exception \n";					// Uncomment for debugging only.
		//var_dump($e->getTrace());				// Uncomment for debugging only.
		var_dump ($e->getMessage());			// Uncomment for debugging only.
		die();
	} 

	
	$output .= '
	<thead>
		<tr>
		<th class="filterinfo" colspan="'. $colspan .'" width="100%">
			<table width="100%">
				<tr>
					<th align="left" colspan="3" class="descreportname" width="50%">
					Report: '. $Connection->repConf->ReportName .'
					</th>
					<th align="left" colspan="3" class="descreportidpv" width="50%">
					Punto-vendita: '. $idpdv_name .'
					</th>
				</tr>
				<tr>
					<th align="left" width="15%">
						Data:<br /> '. $sp_parms->DaData .' - '. $sp_parms->A_Data .' 
					</th>
					<th align="left" width="15%">
						Ora: <br />'. $sp_parms->DaOra .' - '. $sp_parms->A_Ora .'
					</th>
					<th align="left" width="15%">
						N.Cassa: <br />'. $sp_parms->DaCassa .' - ' . $sp_parms->A_Cassa .'
					</th>
					<th align="left" width="15%">
						N.Scontr:<br /> '. $sp_parms->DaNumSc .' - '. $sp_parms->A_NumSc .' 
					</th>
					<th align="left" width="15%">
						Codice Cliente: <br />'. $sp_parms->DaCodCli .' - '. $sp_parms->A_CodCli .'
					</th>
					<th align="left" width="15%">
						Totale importo: <br />'. $sp_parms->DaSVendIva .' - ' . $sp_parms->A_SVendIva .'
					</th>
				</tr>
			</table>
		</th>
		</tr>
	<tr bgcolor="'.$bgcolor.'">';
	
	//The 3 result row is always the header info. hence [2] (arrays start a 0)
	$i = 0;
	foreach ($Connection->repData[2] as $header) { 
		$output .='<th class="data" align="' . $alignment[$i] . '" width="' . $percent[$i] . '%">'. $header .'</th>';
		$i++;
	}
	$output .= '</tr></thead> <tbody>';
	
 	
	$counter = count($Connection->repData) ;
	
	for($i=3; $i<$counter; $i++)
	{
		// Get the BGcolor value, and remove the value from the array. (cleaning up)
		$bgcolor = array_shift($Connection->repData[$i]);
		$fontatt = array_shift($Connection->repData[$i]);
		$output .='<tr bgcolor="'.$bgcolor.'">';
		$c = 0;
		foreach ($Connection->repData[$i] as $field){	
			$output .='<td align="' . $alignment[$c] . '">'. $field .'</td>';
			$c++;
			}
			
		$output .='</tr>';
	}	
	$output .='</tbody></table>';
	
	if (@$_GET['export'] == "Export") {
		
		if (@$_GET['export'] == "1") {
		
			$ods_generator = new Export_ODS('UTF-8');
			reset($Connection->repData);
			array_shift($Connection->repData);
			array_shift($Connection->repData);
			parse_numeric($Connection->repData);
			/* echo '<pre>';
			var_dump($Connection->repData);
			echo '</pre>';	
			die(); */
			try {
				$ods_generator->generate_xml($Connection->repData, 'My Test Worksheet');
			} catch (Exception $e) {
			echo "Exception \n";					// Uncomment for debugging only.
			var_dump($e->getTrace());				// Uncomment for debugging only.
			var_dump ($e->getMessage());			// Uncomment for debugging only.
			die();
			}

			try {
				$ods_generator->compress('content.xml', $ods_generator->content_xml);
			} catch (Exception $e) {
				echo "Exception \n";					// Uncomment for debugging only.
				var_dump($e->getTrace());				// Uncomment for debugging only.
				var_dump ($e->getMessage());			// Uncomment for debugging only.
				die();
			}
			//$ods_generator->save();
			//echo '<pre>';
			//var_dump( $ods_generator->content_xml );
			//echo '</pre>'; 
		} else {
		echo ($_SERVER['PHP_SELF'] .'&'. $_SERVER['QUERY_STRING'] .'&export=1');
		die();
		}
	} else {
		include('report_index.php');
	}
}
	
	
	
	// If report details have not been submitted, do this->
	elseif($_GET["step"] == 1) {	
		include ('/templates/reportdetails/form_1.php');
	}
	
?>
