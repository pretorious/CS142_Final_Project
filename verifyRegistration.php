<?php 
	include "top.php";
?>

<?php

	$verify = false;

	if (isset($_COOKIE["registerSnag"])) {

		$id = $_COOKIE["registerSnag"];

	    $fileExt = ".csv";

	    $myFileName = "data/registration";

	    $filename = $myFileName . $fileExt;

	    // now we just open the file to read

	    $file = fopen($filename, 'r');

	    $verifiedUser = array();

	    while(!feof($file)){

	    	$user = fgetcsv($file);

	    	if($user[5] == $id){

	    		$verify = true;

	    		$verifiedUser[0] = $user[0];

	    		$verifiedUser[1] = $user[1];

	    		$verifiedUser[2] = $user[2];

	    		$verifiedUser[3] = $user[3];

	    		$verifiedUser[4] = $user[4];

	    	}

	    }

		// close the file

		fclose($file);

        $fileExt = ".csv";

        $myFileName = "data/user";

        $filename = $myFileName . $fileExt;

        // now we just open the file to append
        $file = fopen($filename, 'a');

        //write the forms informations
        fputcsv($file, $verifiedUser);

        // close the file
        fclose($file);
	}
?>
	<article id="main">

	    <?php
	    	if ($verify == true) {

		    	print "thank you for registering! your account has been made";

		    }
		    else 
		    {

		    	print "for some reason your registration failed. please try again.";

		    }
		?>

	</article>
<?php include "footer.php"; ?>