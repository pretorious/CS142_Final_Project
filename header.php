		<h1>
		<?php
			print "Snag - ";
            if ($path_info['filename'] == "index")
            {
            	print "Events";
            }
            elseif ($path_info['filename'] == "register")
            {
                print "Registration";
            }
            elseif ($path_info['filename'] == "login")
            {
                print "Login";
            }
            elseif ($path_info['filename'] == "verifyRegistration")
            {
                print "Verification";
            }
            elseif ($path_info['filename'] == "profile")
            {
            	print "Profile";
            }
            elseif ($path_info['filename'] == "createEvent")
            {
                print "Captain an Event!";
            }
            elseif ($path_info['filename'] == "settings")
            {
            	print "Settings";
            }
            else
            {
            	print "unknown page";
            }
        ?>
		</h1>
		<h2>Get Connected with UVM Students and Events</h2>
