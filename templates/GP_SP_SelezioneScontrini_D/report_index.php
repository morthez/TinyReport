<?php if(@$_GET['ajax'] == 0) : ?>

<div id="main" class="main_center">
	<div id="middle">
		<div id="header">
			<?php include ('/templates/reportdetails/form_1.php'); ?>
		</div>
<?php endif; ?>
		<div id="content">				
			<ul id="report-list">
		<?php 
		
		$reportoutput = '';
		$i = 0;
		$odd = 'odd';
		$even = 'even';
		foreach ($reportdata as $row) { 
			
			// fix some variables that we use to define colors etc on some text-objects
			$color = ( ($i & 1) ? 'even' : 'odd' );  // Every 2nd row in an other color.
			
			// Is the recipt modified in any way?
			$ret = ( ($row[7] > 0) ? '<span class="red smalltext">[Ret] ' . $row[7] . '</span>': '<span class="green smalltext">[Ret] ' . $row[7] .'</span>' );

			// Did it take longer than x sec to complete the recipt?
			$sec = ( ($row[8] > 180) ? '<span class="red smalltext">[Sec] ' . $row[8] . '</span>': '<span class="green smalltext">[Sec] ' . $row[8] .'</span>' );
			
			// Is the recipt annulated?
			$ann = ( ($row[9] > 0) ? '<span class="red smalltext">[Ann] ' . $row[9] . '</span>': '<span class="green smalltext">[Ann] ' . $row[9] .'</span>' );			
			
			$link = $_SERVER['PHP_SELF'] . '?ReportId=2&id=' . $row[0];
			$reportoutput .= <<<EOF
		<li id="id$row[0]" class="$color link">
			<a href="$link" target="tab">
			<ul class="objectcontainer">
				<li class="object data">
					Data: $row[1]
				</li>
				<li class="object ora">
					Ora: $row[2]
				</li>
				<li class="object vendiva">
					Cassa: $row[3]
				</li>
				<li class="object numscon">
					Scontrino: $row[4]
				</li>
				<li class="object codcli">
					Codice Cliente: $row[5]
				</li>
				<li class="object cassa">
					Vendita IVA: $row[6]
				</li>
				<li class="object ret">
					$ret
				</li>
				<li class="object sec">
					$sec
				</li>
				<li class="object ann">
					$ann
				</li>
			</ul>
			</a>
		</li>
EOF;
			$i = $i+1;
			}

			print $reportoutput; ?>
			</ul>

		</div>
	</div>
</div>
