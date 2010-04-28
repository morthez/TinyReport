<?php if (!defined ("FromIndex") && FromIndex !== true) { header ("HTTP/1.0 404 Not Found"); header ("Location: ../../index.php"); die (); } ?>
<?php
echo $Connection->errorMsg;
if (defined ("Debug") && Debug == true) : 
	// Place debugging info a place where it's easy to manage.
	?>
	<div id="debug_footer">
		<div id="debug_get">
			<pre>
				<span>var_dump ($_GET)
				<?php 
				var_dump ($_GET); 
				echo $vardumps;
				?></span>
			</pre>
		</div>
		<div id="debug_sp_parms">
			<pre>
				<?php if(isset($sp_parms)) {var_dump($sp_parms);} ?>
			<pre>
		</div>
	</div>

<?php endif; ?>
</body>
</html>