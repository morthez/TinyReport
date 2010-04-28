<?php
// Use the right stylesheet aswell!
echo ('<link rel="stylesheet" href="' . $templatePath . 'style.css" type="text/css" />');
// First check if the data have been submited. 
// If the form have been submited -> Do this!
		// Check if the data is submitted correctly.
		$submiterror = "There is an error with the data provided, or you did not follow the scheme to get the report.";
		if(!($sp_parms->IDScontrino	= $_GET["id"])) {die ($submiterror);}
	
	//	If it is, the call ReportGenerate with 
	//	the paramaters for the storedprocedure.
	$Connection->ReportGenerate($sp_parms);
	
	//	Print any error messages that occured during 
	//	the execution of ReportGenerate.
	print ("<!-- Error messages --> \n");
	print ($Connection->errorMsg);
	
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
				$color = ( ($i & 1) ? 'odd' : 'even' ); 
				$reportoutput .= "<tr class=" . $color . "><td>". $reportdata[$i][0] ."</td><td>".  $reportdata[$i][1] ."</td><td>".  $reportdata[$i][2] ."</td><td class=aright>".  $reportdata[$i][3] ."</td><td class=aright>".  $reportdata[$i][4] ."</td>";
				$i = $i+1;
			}
			$reportoutput .= '<tr class="sum"><td></td><td></td><td></td><td><b>Totale</b></td><td><b>' . $reportdata[0][5] . '</b></td>';
	
	// Include the template file, for the main report body.
	// It's also in the index.tpl where the report_header.tpl
	//	is included. 
	include('report_index.php');

?>