<?php
			// This is the variables that's needed for the StoredProcedure to be executed. (_ALL_ Is needed.)
			// 	All should have default values defined in the submitionform, giving the user little chance
			// 	to prodce any errors because of missing datainput. If this should happen, we dont want to 
			// 	bother the SQL-Server with this, so we produce an error, and display it nicly to the user (hopefully!)
			
			//	@DaData	SmallDateTime ,			@A_Data	SmallDateTime ,			From Date -> To Date
			//	@DaOra	SmallDateTime ,			@A_Ora	SmallDateTime ,			From Time -> To Time
			//	@IdPdv	Char(4) ,												Point of Sale -> No to or from here, as we want to be abit spesific.
			//	@DaCassa	TinyInt ,			@A_Cassa	TinyInt ,			From CaschRegister -> To CaschRegister
			//	@DaNumSc	SmallInt ,			@A_NumSc	SmallInt ,			From ReciptNumber -> To ReciptNumber
			//	@DaCodCli	Int ,				@A_CodCli	Int ,				From CustomerNumber -> To CustomerNumber
			//	@DaSVendIva	Money ,				@A_SVendIva	Money				From TotalPrice -> To TotalPrice

	try {
		$Connection->executeSpStatic($Connection->repConf->IdPdvlist);
	} catch (DataBaseException $e) {
			//echo "DataBaseException \n";
			echo($e->getMessage());
			die();
		}
			?>

<form id="reportdetails" action="" >
<fieldset>
	<legend><?php echo $Connection->repConf->ReportName; ?></legend>
	
	<?php // Some hidden fields, just to tell the server where we are! ?>
		<input type="hidden" name="ReportId" value="<?php echo $_GET["ReportId"] ?>" />
		<input type="hidden" name="step" value="2" />
		<input type="hidden" name="ajax" value="1" />
	
	<?php // Lets start colleting some real data! ?>
		<div class="reportline">
			<div class="reportdetail IdPuv">
				<label for="IdPdv">PuntoVendita:</label>
				<select name="IdPdv" >
				<?php 
				
				// Setup parameters for showing Point of sales.	
				$puntiVendita = "<option value=\"" . ( (@$_GET['IdPdv']) ? $_GET['IdPdv'] : '' ) ."\">Punto Vendita</option>";
				$i = 0;
				foreach ($Connection->StaticSpResult as $row){
					$puntiVendita .= ('<option value="' . $Connection->StaticSpResult[$i][0] . '">' . $Connection->StaticSpResult[$i][0] . ' - ' . $Connection->StaticSpResult[$i][1] . '</option>');
					$i ++;
					}
				print $puntiVendita;
				?>
				</select>
			</div>
			<div class="reportdetail" style="clear: both;">
				<div>
				</div>
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail DaData">
				<label id="DaData" for="DaData">Da Data: </label>
				<input type="text" class="datepickers" name="DaData" value="<?php echo( (@$_GET['DaData']) ? $_GET['DaData'] : $dateTime->yesterday() ); ?>"/>
			</div>
			<div class="reportdetail A_Data">
				<label id="A_Data" for="A_Data">A Data: </label>
				<input type="text" class="datepickers" name="A_Data" value="<?php echo( (@$_GET['A_Data']) ? $_GET['A_Data'] : $dateTime->yesterday() ); ?>"/>
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail DaOra">
				<label id="DaOra" for="DaOra">Da Ora: </label>
				<input type="text" class="datepickers" name="DaOra" value="<?php echo( (@$_GET['DaOra']) ? $_GET['DaOra'] : '00:00' ); ?>"/>
			</div>
			<div class="reportdetail A_Ora">
				<label id="A_Ora" for="A_Ora">A Ora: </label>
				<input type="text" class="datepickers" name="A_Ora" value="<?php echo( (@$_GET['A_Ora']) ? $_GET['A_Ora'] : '23:59' ); ?>"/>
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail DaCassa">
				<label id="DaCassa" for="DaCassa">Da Cassa: </label>
				<input type="text" name="DaCassa" size="2" maxlength="2" value="<?php echo( (@$_GET['DaCassa']) ? $_GET['DaCassa'] : '0' ); ?>" />
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail A_Cassa">
				<label id="A_Cassa" for="A_Cassa">A Cassa: </label>
				<input type="text" name="A_Cassa" size="2" maxlength="2" value="<?php echo( (@$_GET['A_Cassa']) ? $_GET['A_Cassa'] : '99' ); ?>" />
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail DaNumSc">
				<label id="DaNumSc" for="DaNumSc">Da Scontrino: </label>
				<input type="text" name="DaNumSc" size="3" maxlength="6" value="<?php echo( (@$_GET['DaNumSc']) ? $_GET['DaNumSc'] : '0' ); ?>" />
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail A_NumSc">
				<label id="A_NumSc" for="A_NumSc">A Scontrino: </label>
				<input type="text" name="A_NumSc" size="3" maxlength="6"  value="<?php echo( (@$_GET['A_NumSc']) ? $_GET['A_NumSc'] : '30000' ); ?>" />
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail DaCodCli">
				<label id="DaCodCli" for="DaCodCli">Da Codice Cliente: </label>
				<input type="text" name="DaCodCli" size="8" maxlength="10" value="<?php echo( (@$_GET['DaCodCli']) ? $_GET['DaCodCli'] : '0' ); ?>" />
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail A_CodCli">
				<label id="A_CodCli" for="A_CodCli">A Codice Cliente: </label>
				<input type="text" name="A_CodCli" size="8" maxlength="10"  value="<?php echo( (@$_GET['A_CodCli']) ? $_GET['A_CodCli'] : '9999999' ); ?>" />
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail DaSVendIva">
				<label id="DaSVendIva" for="DaSVendIva">Da Totale: </label>
				<input type="text" name="DaSVendIva" size="10"  maxlength="12" value="<?php echo( (@$_GET['DaSVendIva']) ? $_GET['DaSVendIva'] : '0' ); ?>" />
			</div>
		</div>
		<div class="reportline">
			<div class="reportdetail A_SVendIva">
				<label id="A_SVendIva" for="A_SVendIva">A Totale: </label>
				<input type="text" name="A_SVendIva" size="10"  maxlength="12" value="<?php echo( (@$_GET['A_SVendIva']) ? $_GET['A_SVendIva'] : '9999999' ); ?>" />
			</div>
		</div>

		<div class="reportline">
			<div class="reportdetail submit">
				<input type="submit" name="submit_bnt" value="Genera report" id="submit_id" />
			</div>
		</div>
	</fieldset>
</form>