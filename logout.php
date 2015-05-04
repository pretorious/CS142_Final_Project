<?php 
	include "top.php";
?>

<?php

	if (isset($_COOKIE["snagUser"])) {

		setcookie("snagUser", "", time() - 3600, "/");
	}
?>
	<article id="main">

	    <?php
	    	if ($verify == true) {

		    	print "you have been logged out";

		    }
		?>

	</article>
<?php include "footer.php"; ?>