<?php 
	include "top.php";
?>
<section>
	<?php

		$verify = false;

		if (isset($_COOKIE["snagUser"])) {

			$id = $_COOKIE["snagUser"];

		    $fileExt = ".csv";

		    $myFileName = "data/user";

		    $filename = $myFileName . $fileExt;

		    // now we just open the file to read

		    $file = fopen($filename, 'r');

		    $verifiedUser = array();

		    while(!feof($file)){

		    	$user = fgetcsv($file);

		    	if($user[2] == $id){

		    		$verifiedUser = $user;

		    	}

		    }

		    fclose($file);
		    
		    print "<h2>Name : " . $verifiedUser[0] . " " . $verifiedUser[1] . "</h2>\n";

		    print "<h3>Username: " . $verifiedUser[2] . "</h3>\n";

		    print "<h3>Email: " . $verifiedUser[4] . "</h3>\n";

		    print "<h2>Events: </h2> \n";

		    $myFileName = "data/event";

		    $filename = $myFileName . $fileExt;

		    // now we just open the file to read

		    $file = fopen($filename, 'r');

		    $event = array();

		    while(!feof($file)){

		    	$event = fgetcsv($file);

		    	if($event[5] == $verifiedUser[2]){

		    		$verifiedEvent = $event;

		    	}

		    }

			// close the file

			fclose($file);

			print "<article>";
			
			print "<h3>" . $event[0] . "<h3>\n";
			
			print "<h5>made by " . $event[5] . "</h5>\n";
			
			print "<ul>\n";
			
			print "<li><h4>" . $event[1] . "</h4></li>\n";
			
			print "<li><h4>" . $event[2] . ", " . $event[3] . ", " . $event[4] . "</h4></li>\n";
			
			print "</article>\n\n";

		}else{
			print "<h2>How did you get in here? go log in!</h2>";
		}
	?>
</section>
<?php
	include "footer.php"
?>