<?php
header('Content-type: application/json');

if($_POST)
{
    $to_email       = "astro1230vsastro1230@hotmail.com"; 
   
  
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
       
        $output = json_encode(array( 
            'type'=>'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); 

    //Sanitize input data using PHP filter_var().
    $user_name      = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $phone_number   = filter_var($_POST["phone"], FILTER_SANITIZE_NUMBER_INT);
    $message        = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

    
    if(strlen($user_name)<4){ 
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }

    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ 
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }


    if(!filter_var($phone_number, FILTER_SANITIZE_NUMBER_FLOAT)){ 
        $output = json_encode(array('type'=>'error', 'text' => 'Enter only digits in phone number'));
        die($output);
    }

    if(strlen($message)<3){ 
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
        die($output);
    }


    $subject ='New mail via contact form';

    $message_body = $message."\r\n\r\n-".$user_name."\r\n\r\nEmail : ".$user_email."\r\nPhone Number : ". $phone_number ;
 
    $headers = 'From: '.$user_name.'<'.$user_email.'>'."\r\n" .
    'Reply-To: '.$user_name.'<'.$user_email.'>' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
   
    $send_mail = mail($to_email, $subject, $message_body, $headers);
   
    if(!$send_mail)
    {
     
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'success', 'text' => 'Hi '.$user_name .', thank you for your email, we will get back to you shortly.'));
        die($output);
    }
}


?>
