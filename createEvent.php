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
$name = "";
$description = "";
$location = "";
$time = "";
$date = "";

//%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
//
// SECTION: 1d form error flags
//
// Initialize Error Flags one for each form element we validate
// in the order they appear in section 1c
$nameERROR = false;
$descriptionERROR = false;
$locationERROR = false;
$timeERROR = false;
$dateERROR = false;

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

    $name = htmlentities($_POST["txtName"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $name;

    $description = htmlentities($_POST["txtDescription"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $description;

    $location = htmlentities($_POST["txtLocation"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $location;

    $time = htmlentities($_POST["txtTime"], ENT_QUOTES, "UTF-8");
    $dataRecord[] = $time;
    
    $date = filter_var($_POST["txtDate"], FILTER_SANITIZE_EMAIL);
    $dataRecord[] = $date;

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

        if ($name == "") {
            $errorMsg[] = "Please enter the event name.";
            $nameERROR = true;
        }
        
        if ($description == "") {
            $errorMsg[] = "Please enter your description.";
            $descriptionERROR = true;
        }

        if ($location == "") {
            $errorMsg[] = "Please enter your event location.";
            $usernameERROR = true;
        }

        if ($time == "") {
            $errorMsg[] = "Please enter your event time.";
            $passwordERROR = true;
        }
        
        if ($date == "") {
            $errorMsg[] = "Please enter your event date";
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

        if(isset($_COOKIE["snagUser"])){
        	$ID = $_COOKIE["snagUser"];
        }else{
        	$ID = "Anonymous";
        }

        $dataRecord[] = $ID;

        $fileExt = ".csv";

        $myFileName = "data/event";

        $filename = $myFileName . $fileExt;

        if ($debug)
            print "\n\n<p>filename is " . $filename;

        // now we just open the file to append
        $file = fopen($filename, 'a');

        //write the forms informations
        fputcsv($file, $dataRecord);

        // close the file
        fclose($file);

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
        print "<h1>Your event has ";

        print "been created</h1>";
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
                <legend>Create Event</legend>

                <fieldset class="intro">
                    <legend>Please complete the following form</legend>

                    <fieldset class="text">
                        <legend>Contact Information</legend>
                        <label for="txtName" class="required">Name:
                            <input type="text" id="txtName" name="txtName"
                                   value="<?php print $name; ?>" required="required"
                                   tabindex="100" maxlength="45" placeholder="Enter your event name"
                                   <?php if ($nameERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>
                        </label>
                        
                        <label for="txtDescription" class="required">Description:
                            <input type="text" id="txtDescription" name="txtDescription"
                                   value="<?php print $description; ?>" required="required"
                                   tabindex="101" placeholder="Enter your event description"
                                   <?php if ($descriptionERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>                       
                        </label>

                        <label for="txtLocation" class="required">Location:
                            <input type="text" id="txtLocation" name="txtLocation"
                                   value="<?php print $username; ?>" required="required"
                                   tabindex="102" maxlength="80" placeholder="Enter a location"
                                   <?php if ($loacationERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus>                       
                        </label>

                        <label for="txtTime" class="required">Time:
                            <input type="text" id="txtTime" name="txtTime"
                                   value="<?php print $time; ?>" required="required"
                                   tabindex="103" maxlength="45" placeholder="Enter a time"
                                   <?php if ($timeERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()"
                                   autofocus> 
                        </label>
                        
                        <label for="txtDate" class="required">Date:
                            <input type="text" id="txtDate" name="txtDate"
                                   value="<?php print $date; ?>" required="required"
                                   tabindex="104" maxlength="45" placeholder="Enter a date"
                                   <?php if ($dateERROR) print 'class="mistake"'; ?>
                                   onfocus="this.select()" 
                                   >
                        </label>
                    </fieldset> <!-- ends contact -->
                    
                </fieldset> <!-- ends wrapper Two (intro wrapper)-->
                
                <fieldset class="buttons">
                    <legend></legend>
                    <input type="submit" id="btnSubmit" name="btnSubmit" value="Create Event" tabindex="900" class="button">
                    <input type="reset" id="btnReset" name="btnReset" value="Reset Form" tabindex="992" class="button">
                </fieldset> <!-- ends buttons -->
                
            </fieldset> <!-- Ends Wrapper -->
        </form>

    <?php
    } // end body submit
    ?>

</article>

<?php
	include "footer.php"
?>