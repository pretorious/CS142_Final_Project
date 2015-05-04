		<nav>
			<ul>
				<?php
					if ($path_info['filename'] == "index") {
						print "<li class=\"activePage\">Events</li>\n";
					} else {
						print "<li class=\"navigate\"><a class=\"menuLink\" href=\"index.php\">Events</a></li>\n";
		            }
		            if ($path_info['filename'] == "register") {
		            	print "				<li class=\"activePage\">Register</li>\n";
		            } else {
		            	print "				<li class=\"navigate\"><a class=\"menuLink\" href=\"register.php\">Register</a></li>\n";
		            }
		            if(isset($_COOKIE["snagUser"])){
			            if ($path_info['filename'] == "logout") {
			            	print "				<li class=\"activePage \">Logout</li>\n";
			            } else {
			            	print "				<li class=\"navigate\"><a class=\"menuLink\" href=\"logout.php\">Logout</a></li>\n";
			            }
		            	if ($path_info['filename'] == "createEvent") {
						print "<li class=\"activePage\">Create an Event</li>\n";
						} else {
							print "<li class=\"navigate\"><a class=\"menuLink\" href=\"createEvent.php\">Create an Event</a></li>\n";
			            }
			            if ($path_info['filename'] == "profile") {
						print "<li class=\"activePage\">Profile</li>\n";
						} else {
							print "<li class=\"navigate\"><a class=\"menuLink\" href=\"profile.php\">Profile</a></li>\n";
			            }
			            if ($path_info['filename'] == "settings") {
						print "<li class=\"activePage\">Settings</li>\n";
						} else {
							print "<li class=\"navigate\"><a class=\"menuLink\" href=\"settings.php\">Settings</a></li>\n";
			            }
		            }else{
		            	if ($path_info['filename'] == "login") {
			            	print "				<li class=\"activePage \">Login</li>\n";
			            } else {
			            	print "				<li class=\"navigate\"><a class=\"menuLink\" href=\"login.php\">Login</a></li>\n";
			            }
		            }
	            ?>
			</ul>
		</nav>