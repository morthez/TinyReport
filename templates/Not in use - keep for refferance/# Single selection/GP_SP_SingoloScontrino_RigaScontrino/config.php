<?php
// Use the right stylesheet aswell!
//echo ('<script type="javascript">$("head").append("<link rel="stylesheet" href="' . $templatePath . 'style.css" type="text/css" />");</script>');

echo ('<link rel="stylesheet" href="' . $templatePath . 'style.css" type="text/css" />');

// First check if the data have been submited. 
// If the form have been submited -> Do this!
	if($_GET["step"] == 2) {
		// Check if the data is submitted correctly.
		try {
			validate_request($_GET, 
				'data', TYPE_DATE, null,
				'cassa', TYPE_NUMERIC, null,
				'scontrino', TYPE_NUMERIC, null);
				
		} catch (MissingFieldException $e) {
			echo ($e->getMessage());
			//var_dump($e->getTrace());
			die($error_validate);
		} catch (MissingArgsException $e) {
			echo ($e->getMessage());
			die($error_validate);
		} catch (Exception $e) {
			echo ($e->getMessage());
			die($error_validate);
		} 
		
		$sp_parms->IdPuntoVendita = $_GET["idpdv"];
		$sp_parms->DataScontrino 	= $_GET["data"];
		$sp_parms->NumeroCassa 	= $_GET["cassa"]; 
		$sp_parms->NumeroScontrino	= $_GET["scontrino"];
		
		
	//	If it is, the call ReportGenerate with 
	//	the paramaters for the storedprocedure.
	try {
		$Connection->ReportGenerate($sp_parms);
	} catch (DataBaseException $e) {
			//echo "DataBaseException \n";
			echo($e->getMessage());
			die();
	}
	
	//print_r ($sp_parms);
	//	Print any error messages that occured during 
	//	the execution of ReportGenerate.
	//print ("<!-- Error messages ");
	//print ($Connection->errorMsg);
	//print ("<br />");
	
	//print_r ($Connection->temp01);
	//die("end of script for now..");
	//	Define the array used for getting the data for
	//	the header of the recipt. This data is taken from 
	//	the array returned from $Connetion->repData.
	$headerinfo =array('date' =>$Connection->repData[0][0], 'time' => $Connection->repData[0][1], 'idpuv' => $Connection->repData[0][2], 'numcassa' => $Connection->repData[0][3], 'numscontrino' => $Connection->repData[0][4], 'numclient' => $Connection->repData[0][5], 'nameclient' => $Connection->repData[0][6]);
	$i = 0;
	
	//	Prepere the array used for the recipt details. 
	//	This data have to run by a foreach loop, 
	//	since there is several rows of data.
	foreach ($Connection->repData as $array){	
		$reportdata[] = array($Connection->repData[$i][14],$Connection->repData[$i][16],$Connection->repData[$i][17],$Connection->repData[$i][18],$Connection->repData[$i][19],$Connection->repData[$i][11]);
		$i = $i+1;
	}
			// Array description:
			//	Riga Nummero		= $reportdata[$i][0];
			//	Articolo Nummero	= $reportdata[$i][1];
			//	Art. Decrizione		= $reportdata[$i][2];
			//	Quantità			= $reportdata[$i][3];
			//	VenditaIVA 			= $reportdata[$i][4];
			//	Totale				= $reportdata[$i][5];
			//	-> This is the total of the whole reciept, 
			//	stored in every row.
			
			$reportoutput = '';
			$i = 0;
			$odd = 'odd';
			$even = 'even';
			foreach ($reportdata as $row) {
				//$reportoutput .= '<tr id=' . ( ($i & 1) ? 'odd' : 'even' ) . '>';
				$color = ( ($i & 1) ? 'even' : 'odd' ); 
				$reportoutput .= "<tr class=" . $color . "><td>". $reportdata[$i][0] ."</td><td>".  $reportdata[$i][1] ."</td><td>".  $reportdata[$i][2] ."</td><td class=aright>".  $reportdata[$i][3] ."</td><td class=aright>".  $reportdata[$i][4] ."</td>";
				$i = $i+1;
			}
			$reportoutput .= '<tr class="sum"><td></td><td></td><td></td><td><b>Totale</b></td><td><b>' . $reportdata[0][5] . '</b></td>';
	
	// Include the template file, for the main report body.
	// It's also in the index.tpl where the report_header.tpl
	//	is included. 
	include('report_index.php');

	}
	
	// If report details have not been submitted, do this->
	elseif($_GET["step"] == 1) {
		echo ("<script type=\"javascript\">$(<link>).appendTo('head').attr({rel: 'stylesheet',type: 'text/css',href: '" . $templatePath . "style.css'});</script>");
		include ('/templates/reportdetails/form_2.php');
	}
	
?>