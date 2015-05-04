<?php 
	include "top.php";
?>

		<section>
			<?php
				$fileExt = ".csv";

				$myFileName = "data/event";

				$filename = $myFileName . $fileExt;

				// now we just open the file to read

				$file = fopen($filename, 'r');

				while(!feof($file)){

					print "<article>\n";

					$event = fgetcsv($file);

					print "<h3>" . $event[0] . "<h3>\n";
					if(isset($_COOKIE["snagUser"])){
						print "<h5>made by" . $_COOKIE[5] . "</h5>\n";
					}else {
						print "<h5>made by Anonymous</h5>";
					}
					print "<ul>\n";
					print "<li><h4>" . $event[1] . "</h4></li>\n";
					print "<li><h4>" . $event[2] . ", " . $event[3] . ", " . $event[4] . "</h4></li>\n";
					print "</article>\n\n";
				}

				// close the file

				fclose($file);
			?>
		</section>

<?php
	include "footer.php"
?>