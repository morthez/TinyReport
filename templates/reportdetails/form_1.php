<style type="text/css">
td.desc {
	text-align: right;
}
</style>
<script type="text/javascript">
	$(function() {
		$("#filter_from_date").datepicker( { dateFormat: 'dd/mm/yy', altField: '#filter_to_date' } );
		$("#filter_to_date").datepicker( { dateFormat: 'dd/mm/yy'} );		
	});

	function updateAltField(from, to) {
		$(to).val($(from).val())
	};
	

	$(document).ready(function(){
		$("#reportdetails").validate({
			errorClass: "error",
			event:	"blur",
			rules: {
				filter_from_date: {
					required: true,
					date: true
				},
				filter_to_date: {
					required: true,
					date: true
				},
				filter_from_time: {
					required: true,
					min: 5,
					max: 5
				},
				filter_to_time: {
					required: true,
					min: 5,
					max: 5
				},
				filter_from_register: {
					required: true,
					numberDE: true
				}
			}
		});
		
	});
	function export_stuff(){ 
			console.log($(this).parent('form').serialize());
			return false;
		};
    function validate_input(from_id, to_id) {
		var from=$(from_id).val();
        var to=$(to_id).val();
		
        if(parseInt(from) > parseInt(to)) {
			$(to_id).parent().append('<span class="error">Please enter a correct range.</span>');
		};
		
        if(parseInt(from) < parseInt(to)) {
			$(to_id).parent().find('span').remove();
		};
	};
	 function activate_export() {
        $('#export').attr("disabled", false);
       
    };
</script>
<form id="reportdetails">
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
					<th> Da </th>
					<th> A </th>
				<tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<select name="IdPdv">
							<?php 
							$Connection->executeSpStatic($Connection->repConf->IdPdvlist);
							print_r (idpdv_select($Connection->StaticSpResult, @$_GET['idpdv'])); ?>
						</select>
					</td>
					<td class="desc">Data:</td>
					<td><input type="text" size="8" id="filter_from_date" name="DaData" value="<?php echo( (@$_GET['DaData']) ? $_GET['DaData'] : $dateTime->yesterday() ); ?>"/> </td>
					<td><input type="text" size="8" id="filter_to_date" name="A_Data" value="<?php echo( (@$_GET['A_Data']) ? $_GET['A_Data'] : $dateTime->yesterday() ); ?>"/></td>
				</tr>
				<tr>
					<td></td>
					<td class="desc">Ora:</td>
					<td class="input"><input type="text" size="8" id="filter_from_time" onChange="updateAltField('#filter_from_time', '#filter_to_time');" class="ora" name="DaOra" value="<?php echo( (@$_GET['DaOra']) ? $_GET['DaOra'] : '00:00' ); ?>"/></td>
					<td class="input"><input type="text" size="8" id="filter_to_time" onChange="validate_input('#filter_from_time', '#filter_to_time');" class="ora" name="A_Ora" value="<?php echo( (@$_GET['A_Ora']) ? $_GET['A_Ora'] : '23:59' ); ?>"/></td>
				</tr>
				<tr>
					<td></td>
					<td class="desc">Numero cassa:</td>
					<td class="input"><input type="text" size="8" name="DaCassa" id="filter_from_register" onChange="updateAltField('#filter_from_register', '#filter_to_register');" maxlength="2" value="<?php echo( (@$_GET['DaCassa']) ? $_GET['DaCassa'] : '0' ); ?>" /></td>
					<td class="input"><input type="text" size="8" name="A_Cassa" id="filter_to_register" onChange="validate_input('#filter_from_register', '#filter_to_register');" maxlength="2" value="<?php echo( (@$_GET['A_Cassa']) ? $_GET['A_Cassa'] : '99' ); ?>" /></td>
				</tr>
				<tr>
					<td></td>
					<td class="desc">Numero scontrino:</td>
					<td class="input"><input type="text" size="8" name="DaNumSc" id="filter_from_recipt" onChange="updateAltField('#filter_from_recipt', '#filter_to_recipt');" maxlength="6" value="<?php echo( (@$_GET['DaNumSc']) ? $_GET['DaNumSc'] : '0' ); ?>" /></td>
					<td class="input"><input type="text" size="8" name="A_NumSc" id="filter_to_recipt" onChange="validate_input('#filter_from_recipt', '#filter_to_recipt');" maxlength="6"  value="<?php echo( (@$_GET['A_NumSc']) ? $_GET['A_NumSc'] : '9999' ); ?>" /></td>
				</tr>
				<tr>
					<td style="float:right;"><input type="submit" id="submit_id" value="Genera report" name="submit_bnt" ></td>
					<td class="desc">Codice Cliente:</td>
					<td class="input"><input type="text" size="8" name="DaCodCli" id="filter_from_client" onChange="updateAltField('#filter_from_client', '#filter_to_client');" maxlength="10" value="<?php echo( (@$_GET['DaCodCli']) ? $_GET['DaCodCli'] : '0' ); ?>" /></td>
					<td class="input"><input type="text" size="8" name="A_CodCli" id="filter_to_client" onChange="validate_input('#filter_from_client', '#filter_to_client');" maxlength="10"  value="<?php echo( (@$_GET['A_CodCli']) ? $_GET['A_CodCli'] : '9999999' ); ?>" /></td>
				</tr>
				<tr>
					<td style="float:right;"><input disabled="true" type="submit" id="export" value="Export" name="export" onClick="export_stuff()" ></td>
					<td class="desc">Totale importo:</td>
					<td class="input"><input type="text" size="8" name="DaSVendIva" id="filter_from_price" onChange="updateAltField('#filter_from_price', '#filter_to_price');"  maxlength="12" value="<?php echo( (@$_GET['DaSVendIva']) ? $_GET['DaSVendIva'] : '0' ); ?>" /></td>
					<td class="input"><input type="text" size="8" name="A_SVendIva" id="filter_to_price" onChange="validate_input('#filter_from_price', '#filter_to_price');"  maxlength="12" value="<?php echo( (@$_GET['A_SVendIva']) ? $_GET['A_SVendIva'] : '9999999' ); ?>" /></td>
				</tr>
			</tbody>
		</table>
	</fieldset>
</form>