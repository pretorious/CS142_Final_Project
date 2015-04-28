<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
        // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
        //
        // PATH SETUP
        //
        // $domain = "https://www.uvm.edu" or http://www.uvm.edu;
            $domain = "http://";
            if (isset($_SERVER['HTTPS'])) {
                if ($_SERVER['HTTPS']) {
                    $domain = "https://";
                }
            }
            $server = htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES, "UTF-8");
            $domain .= $server;
            $phpSelf = htmlentities($_SERVER['PHP_SELF'], ENT_QUOTES, "UTF-8");
            $path_info = pathinfo($phpSelf);
            if ($debug){
                print "<p>Domain" . $domain;
                print "<p>php Self". $phpSelf;
                print "<p>Path Parts<pre>";
                print_r($path_info);
                print "</pre>";
            }
            // %^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%^%
            //
            // include all libraries
            //
            require_once('lib/security.php');
            if ($path_info['filename'] == "form") {
	            include "lib/validation-functions.php";
	            include "lib/mail-message.php";
            }
        ?>
		<title>
		<?php
			print "Snag - "; 
            if ($path_info['filename'] == "index")
            { 
            	print "Events\n";
            }
            else if ($path_info['filename'] == "profile")
            {
            	print "Profile\n";
            }
            else if ($path_info['filename'] == "settings")
            {
            	print "Settings\n";
            }
            else
            {
            	print "unknown page";
            }
        ?>
        </title>
		<meta charset="utf-8">
		<meta name="authors" content="Preston Libby, Jasper Davis">
		<meta name="description" content="Snag Social Media Site, Helping Connect UVM Students
											to events and interests all over campus.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="stylesheet.css" type="text/css" media="screen" title="Main">
	</head>
	<body>
		<?php 
			include "header.php";
			include "nav.php";
		?>