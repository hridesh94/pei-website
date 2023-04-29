<?php
/*
 * This PHP script is an example of how you should handle email messages.
 * You should implement your own way of seding messages and take this only as reference.
 */
            
// Who you want to recieve the emails from the form. (Hint: generally you.)
$sendto = 'youremail@example.com';

// The subject you'll see in your inbox
$subject = 'Message from contact form';

// Message for the user when he/she doesn't fill in the form correctly.
$errormessage = 'There seems to have been a problem. May we suggest:';

// Message for the user when he/she fills in the form correctly.
$thanks = "Thanks for the email! We'll get back to you as soon as possible!";

// Various messages displayed when the fields are empty.
$emptyname =  'Entering your name?';
$emptyemail = 'Entering your email address?';
$emptymessage = 'Entering a message?';

// Various messages displayed when the fields are incorrectly formatted.
$alertname =  'Entering your name using only the standard alphabet?';
$alertemail = 'Entering your email in this format: <em>name@example.com</em>?';
$alertmessage = "Making sure you aren't using any parenthesis or other escaping characters in the message? Most URLS are fine though!";

// Setting used variables.
$alert = '';
$pass = 0;

// Sanitizing the data, kind of done via error messages first. Twice is better!
function clean_var($variable) {
	$variable = strip_tags( stripslashes( trim( rtrim( $variable ) ) ) );
	return $variable;
}

// A bunch of if's for all the fields and the error messages.
if ( empty( $_REQUEST['name'] ) ) {
	$pass = 1;
	$alert .= "<li>" . $emptyname . "</li>";
} elseif ( preg_match( "/[{}()*+?.\\^$|]/", $_REQUEST['name'] ) ) {
	$pass = 1;
	$alert .= "<li>" . $alertname . "</li>";
}
if ( empty( $_REQUEST['email'] ) ) {
	$pass = 1;
	$alert .= "<li>" . $emptyemail . "</li>";
} elseif ( !preg_match( "/^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$/", $_REQUEST['email'] ) ) {
	$pass = 1;
	$alert .= "<li>" . $alertemail . "</li>";
}
if ( empty( $_REQUEST['message'] ) ) {
	$pass = 1;
	$alert .= "<li>" . $emptymessage . "</li>";
} elseif ( preg_match( "/[][{}()*+?\\^$|]/", $_REQUEST['message'] ) ) {
	$pass = 1;
	$alert .= "<li>" . $alertmessage . "</li>";
}

// If the user err'd, print the error messages.
if ( 1 == $pass ) {

	//This first line is for ajax/javascript, comment it or delete it if this isn't your cup o' tea.
	echo "<script>jQuery(\".message\").show(); </script>";
	echo $errormessage;
	echo '<ul class="no-margin-bottom">'.$alert.'</ul>';

// If the user didn't err and there is in fact a message, time to email it.
} elseif ( isset( $_REQUEST['message'] ) ) {

	// Construct the message.
	$message = "From: " . clean_var( $_REQUEST['name'] ) . "\n";
	$message .= "Email: " . clean_var( $_REQUEST['email'] ) . "\n";
	$message .= "Message: \n" . clean_var( $_REQUEST['message'] );

	$header = 'From:'. clean_var( $_REQUEST['email'] );

	// Mail the message - for production
	mail( $sendto, $subject, $message, $header );
	// This is for javascript, 
	echo "<script>jQuery('#contact_form.message').show(); jQuery('#contact_form')[0].reset();</script>";
	echo $thanks;

	die();

	// Echo the email message - for development
	// echo "<br/><br/>" . $message;

}
