<?php

// basic settings section
$sendto = 'johnson.yesubalan@gmail.com';
$subject = 'Portfolio Contact';
$msg = "From: " . $_POST['name'] . "\n";
$msg .= "Email: " . $_POST['email'] . "\n";
$msg .= "Subject: " . $_POST['subject'] . "\n";
$msg .= "Message: \n" . $_POST['message'];
$header = 'From:'. $_POST['from'];

mail($sendto, $subject, $msg, $header); 
?>