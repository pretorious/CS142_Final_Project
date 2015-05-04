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
		    print "<h2>you have been logged out</h2>";
		?>

	</article>
<?php
	include "footer.php"; 
?>