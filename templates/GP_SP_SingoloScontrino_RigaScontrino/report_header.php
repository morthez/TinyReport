<!-- Report Header -->
<?php
//print_r ($headerinfo);
?>

<div id="datetime">
	Del <b><?php echo $headerinfo['date']; ?></b> ore <b><?php echo $headerinfo['time']; ?></b>
</div>
<div id="scontrino">
	Scontrino Nr: <br><b><?php echo $headerinfo['numscontrino']; ?></b>
</div>
<div id="cassa">
	Cassa: <br><b><?php echo $headerinfo['numcassa']; ?></b>
</div>
<div id="numclient">
	Codice Cliente: <br><b><?php echo $headerinfo['numclient']; ?></b>
</div>
<div id="idpuv">
	Punto Vendita Nr: <br><b><?php echo $headerinfo['idpuv']; ?></b>
</div>
<div id="nameclient">
	Nome Cliente: <br><b><?php echo $headerinfo['nameclient']; ?></b>
</div>