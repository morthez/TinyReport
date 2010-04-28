<div id="main" class="main_center">
		<div id="header">
			<?php include ('report_header.php'); ?>
		</div>
	
	<table id="report" width="100%" border=0>	
		<thead align="left" id="thead">
			<tr>
				<th>Nr. Riga</th>
				<th>Codice Articolo</th>
				<th>Decrizione Articolo</th>
				<th align=right>Quantita</th>
				<th align=right>Vendita IVA</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			// this variable is defined in config.php
			// It contains all the "rendered" data, this is to increse performance
			// when there is longer reports to be made.
			print $reportoutput;
			?>
		</tbody>
	</table>
</div>
