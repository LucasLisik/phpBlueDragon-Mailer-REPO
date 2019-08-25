<?php
error_reporting(0);

session_start();

if($_SESSION['user_id'] == "")
{
    echo 'LOGIN';   
}
else
{
    //print_r($_POST);
    require 'PHPMailer/PHPMailerAutoload.php';
    
    $mail = new PHPMailer();
    
    $mail->SMTPDebug = 0;
                    
    if($_POST['send_access'] == 'tls')
    {
        //$mail->isSMTP();
        $mail->SMTPSecure = "tls"; 
        
    }
    else
    {
        $mail->isSMTP();
    }
    
    
    $mail->Host = $_POST['send_smtp_serwer'];
    if($_POST['send_auth'] == 'yes')
    {
        $mail->SMTPAuth = true;
        $mail->Username = $_POST['send_login'];
        $mail->Password = $_POST['send_pswd'];
    }
    $mail->Port = $_POST['send_port'];
    
    if($mail->smtpConnect())
    {
        $mail->smtpClose();
        echo "OK";
    }
    else
    {
        echo "NO";
    }
}
?>