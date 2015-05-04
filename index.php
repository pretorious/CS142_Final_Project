<?php 
	include "top.php";
?>

		<section>
		<h2 style="color : red;">Don't grade this yet. It isn't ready. wait until tomorrow!</h2>
			<?php
				$fileExt = ".csv";

				$myFileName = "data/event";

				$filename = $myFileName . $fileExt;

				// now we just open the file to read

				$file = fopen($filename, 'r');

				while(!feof($file)){

					$event = fgetcsv($file);
					if($event[0] != ""){
						print "<article>\n";
						print "				<h3>" . $event[0] . "</h3>\n";
						print "				<h5>made by " . $event[5] . "</h5>\n";
						print "				<ul>\n";
						print "					<li><h4>" . $event[1] . "</h4></li>\n";
						print "					<li><h4>" . $event[2] . ", " . $event[3] . ", " . $event[4] . "</h4></li>\n";
						print "				</ul>\n";
						print "			</article>\n\n 			";
					}
				}

				print "\n";

				// close the file

				fclose($file);
			?>
		</section>

<?php
	include "footer.php"
?>