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
$firstName = "";
$lastName = "";
$username = "";
$password = "";
$passwordVerify = "";
$email = "";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c
$firstNameERROR = false;
$lastNameERROR = false;
$emailERROR = false;
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

$mailed=false; // have we mailed the information to the user?
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

    $firstName = htmlentities($_POST["txtFirstName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $firstName;

    $lastName = htmlentities($_POST["txtLastName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $lastName;

    $username = htmlentities($_POST["txtUsername"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $username;

    $password = htmlentities($_POST["txtPassword"], ENT_QUOTES, "UTF-8");
    $passwordVerify = htmlentities($_POST["txtPasswordVerify"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $password;
    
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $email;

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

        if ($firstName == "") {
            $errorMsg[] = "Please enter your first name";
            $firstNameERROR = true;
        } elseif (!verifyAlphaNum($firstName)) {
            $errorMsg[] = "Your first name  appears to have extra character or more.";
            $firstNameERROR = true;
        }    
        
        if ($lastName == "") {
            $errorMsg[] = "Please enter your last name";
            $lastNameERROR = true;
        } elseif (!verifyAlphaNum($lastName)) {
            $errorMsg[] = "Your last name  appears to have an extra character or more.";
            $lastNameERROR = true;
        }

        if ($username == "") {
            $errorMsg[] = "Please enter your username";
            $usernameERROR = true;
        } elseif (!verifyAlphaNum($username)) {
            $errorMsg[] = "Your username  appears to have extra character or more.";
            $usernameERROR = true;
        }    

        if ($password == "") {
            $errorMsg[] = "Please enter your first name";
            $passwordERROR = true;
        } elseif (!verifyAlphaNum($password)) {
            $errorMsg[] = "Your password appears to have an extra character or more.";
            $passwordERROR = true;
        }elseif ($password != $passwordVerify) {
            $errorMsg[] = "Your passwords do not match.";
            $passwordERROR = true;
        }
        
        if ($email == "") {
            $errorMsg[] = "Please enter your email address";
            $emailERROR = true;
        } elseif (!verifyEmail($email)) {
            $errorMsg[] = "Your email address appears to be incorrect.";
            $emailERROR = true;
        }
    
        
    //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    //
    // SECTION: 2d Process Form - Passed Validation
    //
    // Process for when the form passes validation (the errorMsg array is empty)
    //
        if (!$errorMsg) { 
            if ($debug)
                print "<P>Form is valid</p>";

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2e Save Data
        //
        // This block saves the data to a CSV file

        $ID = uniqid();

        $dataRecord[] = $ID;

        $fileExt = ".csv";

        $myFileName = "data/registration";

        $filename = $myFileName . $fileExt;

        if ($debug)
            print "\n\n<p>filename is " . $filename;

        // now we just open the file to append
        $file = fopen($filename, 'a');

        //write the forms informations
        fputcsv($file, $dataRecord);

        // close the file
        fclose($file);

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2f Create message
        //
        // build a message to display on the screen in section 3a and to mail
        // to the person filling out the form (sectino 2g)

        $message = '<h2>Thank you signing up!</h2>';

        $message .= "<p> If you wish to sign up, click on the following link: <a href='https://jdavis30.w3.uvm.edu/cs142/assignment7/verifyRegistration.php'>https://jdavis30.w3.uvm.edu/cs142/assignment7/verifyRegistration.php</a>";


        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2g Mail to user
        //
        // Process for mailing a message which contains the forms data
        // the message was built in section 2f
        $to = $email; // the person who filled out the form
        $cc = "";
        $bcc = "";
        $from = "Snag Profile Setup <noreply@snagsocialmedia.com>";

        // subject of mail should make sense to your form
        $todaysDate = strftime("%x");
        $subject = "Snag Profile Setup " . $todaysDate;

        $mailed = sendMail($to, $cc, $bcc, $from, $subject, $message);

        //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
        //
        // SECTION: 2h Cookie Data
        //
        // This block saves the data to a cookie

        $cookie_name = "registerSnag";

        $cookie_value = $dataRecord[count($dataRecord) - 1];

        setcookie($cookie_name, "".$cookie_value, time() + 3600, '/');

    } // end form is valid
    
} // ends if form was submitted

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
            print "<h1>Your Request has ";

        if (!mailed) {
            print "not ";
        }

        print "been processed</h1>";
        print "<p>A message has ";
        print "been sent</p>";
        print "<p>To: " . $email . " Please check it to continue registration</p>";
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
              id="frmRegister">

            <fieldset class="wrapper">
                <legend>Sign Up Today!</legend>
                <p>Sign up to start joining and creating events with fellow UVM students today!</p>

                <fieldset class="intro">
                    <legend>Please complete the following form</legend>

                    <fieldset class="text">
                        <legend>Contact Information</legend>
                        <label for="txtFirstName" class="required">First Name:
                            <input type="text" id="txtFirstName" name="txtFirstName"
                                   value="<?php print $firstName; ?>" required="required"
                                   tabindex="100" maxlength="45" placeholder="Enter your first name"
                                   <?php if ($firstNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
                        <label for="txtLastName" class="required">Last Name:
                            <input type="text" id="txtLastName" name="txtLastName"
                                   value="<?php print $lastName; ?>" required="required"
                                   tabindex="100" maxlength="45" placeholder="Enter your last name"
                                   <?php if ($lastNameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>                       
                        </label>

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

                        <label for="txtPasswordVerify" class="required">Re-enter Password:
                            <input type="password" id="txtPasswordVerify" name="txtPasswordVerify"
                                   value="<?php print $passwordVerify; ?>" required="required"
                                   tabindex="100" maxlength="45" placeholder="Re-enter your password"
                                   <?php if ($passwordERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>                       
                        </label>
                        
                        <label for="txtEmail" class="required">Email:
                            <input type="text" id="txtEmail" name="txtEmail"
                                   value="<?php print $email; ?>" required="required"
                                   tabindex="100" maxlength="45" placeholder="Enter a valid email address"
                                   <?php if ($emailERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" 
                                   >
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
