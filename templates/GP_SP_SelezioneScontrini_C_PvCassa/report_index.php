<?php if($_GET['ajax'] == 0) : ?>

<div id="main" class="main_center">
	<div id="middle">
		<div id="header">
			<?php include ('../reportdetails/reportdetails-idpvd-data-ora-cassa-scon-codcli-totimport.php'); ?>
		</div>
<?php endif; ?>
<div id="contentholder">
	<div>
<?php print $output; ?>
	</div>
</div>


