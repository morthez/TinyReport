<style type="text/css">
td.desc {
	text-align: right;
}
td.input {
	test-align: right;
	
}
</style>
<form id="reportdetails"  action="">
<input type="hidden" value="2" name="step" />
<input type="hidden" value="1" name="ajax" />
<input type="hidden" name="ReportId" value="<?php echo $_GET["ReportId"] ?>" />
	<fieldset>
		<legend><?php echo $Connection->repConf->ReportName; ?></legend>
		<table class="reportdetcont">
			<thead>
				<tr>
					<th> Punto vendita: </th>
					<th> Type: </th>
					<th> Value: </th>
				<tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<select name="idpdv">
							<?php 
							$Connection->executeSpStatic($Connection->repConf->IdPdvlist);
							print_r (idpdv_select($Connection->StaticSpResult, @$_GET['idpdv'])); ?>
						</select>
					</td>
					<td class="desc">Data:</td>
					<td><input type="text" size="8" class="datepickers" name="data" value="<?php echo( (@$_GET['Data']) ? $_GET['Data'] : $dateTime->yesterday() ); ?>"/></td>
				</tr>
				<tr>
					<td></td>
					<td class="desc">Numero Cassa:</td>
					<td><input type="text" size="8" name="cassa" value="<?php echo( (@$_GET['cassa']) ? $_GET['cassa'] : '1' ); ?>"/></td>
				</tr>
				<tr>
					<td style="float:right;"><input type="submit" id="submit_id" value="Genera report" name="submit_bnt"></td>
					<td class="desc">Numero Scontrino:</td>
					<td><input type="text" size="8" name="scontrino" value="<?php echo( (@$_GET['scontrino']) ? $_GET['scontrino'] : '1' ); ?>"/></td>
				</tr>
			</tbody>
		</table>
	</fieldset>
</form>