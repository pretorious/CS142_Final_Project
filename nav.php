	<nav>
			<ul>
				<?php 
					if ($path_info['filename'] == "index") {
		                print "<li class=\"activePage\">Events</li>\n";
		            } else {
		                print "<li class=\"navigate\"><a class=\"menuLink\" href=\"index.php\">Events</a></li>\n";
		            }
		            if ($path_info['filename'] == "profile") {
		                print "            <li class=\"activePage\">Profile</li>\n";
		            } else {
		                print "            <li class=\"navigate\"><a class=\"menuLink\" href=\"profile.php\">Profile</a></li>\n";
		            }
		            if ($path_info['filename'] == "settings") {
		                print "            <li class=\"activePage \">Settings</li>\n";
		            } else {
		                print "            <li class=\"navigate\"><a class=\"menuLink\" href=\"settings.php\">Settings</a></li>\n";
		            }
	            ?>
			<ul>
		</nav>