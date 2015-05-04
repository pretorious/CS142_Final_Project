<?php 
	include "top.php";
?>
<?php
	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	//
	// SECTION: 1 Initialize variables
	//
	// SECTION: 1a.
	// variables for the classroom purposes to help find errors.
	$debug = false;

	if (isset($_GET["debug"])) { //ONLY do this in a classroom environment
	    $debug = "false";
	}
	    
	if ($debug)
	    print "<p>DEBUG MODE IS ON</p>";
	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	//
	// SECTION: 1b Security
	//
	// define security variable to be used in SECTIOn 2a
	$yourURL = $domain . $phpSelf;


	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	//
	// SECTION: 1c form variables
	//
	// Initialize variables one for each form element
	// in the order they appear on the form
	$username = "";
	$password = "";
	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	//
	// SECTION: 1d form error flags
	//
	// Initialize Error Flags one for each form element we validate
	// in the order they appear in section 1c
	$usernameERROR = false;
	$passwordERROR = false;

	//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
	//
	// SECTION: 1e misc variables
	//
	// create array to hold error messages filled (if any) in 2d displayed in 3c.
	$errorMsg = array();

	// array used to hold form values that will be written to a CSV file
	$dataRecord = array();
	//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	//
	// SECTION: 2 Process for when the form is submitted
	//
	if (isset($_POST["btnSubmit"])) {

		//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		//
		// SECTION: 2a Security
		//

		if (!securityCheck(true)) {
		    $msg = "<p>Sorry you cannot access this page. ";
		    $msg.= "Security breach detected and reported</p>";
		    die($msg);
		}

		//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		//
		// SECTION: 2b Sanitize (clean) data 
		//  remove any potential JavaScript or html code from users input on the 
		//  form. Note it is best to follow the same order as declared in section 1c.

		$username = htmlentities($_POST["txtUsername"], ENT_QUOTES, "UTF-8");
		$dataRecord[] = $username;

		$password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
		$dataRecord[] = $password;

		//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		//
		// SECTION: 2c Validation
		//
		// Validation section. Check each value for possible errors, empty or
		// not what we expect. You will ned an IF block for each element you will
		// check (see above section 1c and 1d). The if blocks should also be in the
		//  order that the elements appear on your form so that the error messages
		// will be in the order they appear. errorMsg will be displayed on the form
		// see section 3b. The error flag ($emailERROR) will be used in section 3c.

		if ($username == "") {
			$errorMsg[] = "Please enter your username";
			$usernameERROR = true;
		} elseif (!verifyAlphaNum($username)) {
			$errorMsg[] = "Your username  appears to have an extra character or more.";
			$usernameERROR = true;
		}

		if ($password == "") {
			$errorMsg[] = "Please enter your first name";
			$passwordERROR = true;
		} elseif (!verifyAlphaNum($password)) {
			$errorMsg[] = "Your password appears to have an extra character or more.";
			$passwordERROR = true;
		}

		$found = false;

		$passed = false;

		$fileExt = ".csv";

		$myFileName = "data/user";

		$filename = $myFileName . $fileExt;

		// now we just open the file to read

		$file = fopen($filename, 'r');

		while(!feof($file)){

			$user = fgetcsv($file);

			if($user[2] == $username){

				$found = $user[2];

				if($user[3] == $password){

					$password = $user[3];

				}

			}

		}

		// close the file

		fclose($file);

		if(!$found){
			$errorMsg[] = "You have no registered yet. please register";
			$usernameERROR = true;
		}
		else{
			if(!$password){
				$errorMsg[] = "incorrect password for that username";
				$usernameERROR = true;
			}
		}

		//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
		//
		// SECTION: 2d Process Form - Passed Validation
		//
		// Process for when the form passes validation (the errorMsg array is empty)
		//
		if (!$errorMsg) {
			if ($debug)
				print "<P>Login is valid</p>";
			//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
			//
			// SECTION: 2h Cookie Data
			//
			// This block saves the data to a cookie

			$cookie_name = "registerSnag";

			$cookie_value = $dataRecord[2];

			setcookie($cookie_name, "".$cookie_value, time() + 86400, '/');

		} // end form is valid

	}

	//#############################################################################
	//
	// SECTION 3 Display Form
	//
?>

<article id="main">

    <?php
    //####################################
    //
    // SECTION 3a.
    //
    // 
    // 
    // 
    // If its the first time coming to the form or there are errors we are going
    // to display the form
    if (isset($_POST["btnSubmit"]) AND empty($errorMsg)) { // closing of if marked with: end body submit
            print "<h1>You are now logged in. ";
    } else {


        //####################################
        //
        // SECTION 3b Error Messages
        //
        // display  any error messages before we print out the form

        if ($errorMsg) {
            print '<div id="errors">';
            print "<ol>\n";
            foreach ($errorMsg as $err) {
                print "<li>" . $err . "</li>\n";
            }
            print "</ol>\n";
            print '</div>';
        }


        //####################################
        //    
            // SECTION 3c html Form
        //
        /* Display the HTML form. note that the action is to this same page. $phpSelf
          is defined in top.php
          NOTE the line:

          value=<?php print $email; ?>

          this makes the form sticky by displaying either the initial default value (line 35)
          or the value they typed in (line 84)

          NOTE this line:

          <?php if(emailERROR) print 'class="mistake"'; ?>

          this prints out a css class so that we can highlight the background etc. to
          make it stand out that a mistake happened here.

         */
        ?>

        <form action="<?php print $phpSelf; ?>"
              method="post"
              id="frmLogin">

            <fieldset class="wrapper">
                <legend>Login</legend>
                <p>Login to start joining and creating events with fellow UVM students today!</p>

                <fieldset class="intro">
                    <legend>Please enter your username and password</legend>

                    <fieldset class="text">

                        <label for="txtUsername" class="required">Username:
                            <input type="text" id="txtUsername" name="txtUsername"
                                   value="<?php print $username; ?>" required="required"
                                   tabindex="100" maxlength="45" placeholder="Enter your username"
                                   <?php if ($userameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>                       
                        </label>

                        <label for="txtPassword" class="required">Password:
                            <input type="password" id="txtPassword" name="txtPassword"
                                   value="<?php print $password; ?>" required="required"
                                   tabindex="100" maxlength="45" placeholder="Enter your password"
                                   <?php if ($passwordERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus> 
                        </label>
                    </fieldset> <!-- ends contact -->
                    
                </fieldset> <!-- ends wrapper Two (intro wrapper)-->
                
                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Register" tabindex="900" class="button">
                    <input type="reset" id="btnReset" name="btnReset" value="Reset Form" tabindex="992" class="button">
                </fieldset> <!-- ends buttons -->
                
            </fieldset> <!-- Ends Wrapper -->
        </form>

    <?php
    } // end body submit
    ?>

</article>

<?php
	include "footer.php";
?>
