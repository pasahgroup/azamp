<?php
$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if (strpos($url, 'dashboard') !== false) {
    $path = "../PHPMailer/";
}else{
    $path = "PHPMailer/";
}
include(''.$path.'/PHPMailerAutoload.php');
 include_once(''.$path.'/src/PHPMailer.php');
  include_once(''.$path.'/src/SMTP.php');
  include_once(''.$path.'/src/Exception.php');
class Mail {
    public static $security = "ssl";
    public static $host = "mail.rhonds.co.tz";
    public static $port = "465";
    public static $username = "info@rhonds.co.tz";
    public static $password = "pasah12345!";
    public static $setFrom = "info@rhonds.co.tz";

    public static function sendMail($subject, $body, $address) {
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = self::$security;
            $mail->Host = self::$host;
            $mail->Port = self::$port;
            $mail->isHTML();
            $mail->Username = self::$username;
            $mail->Password = self::$password;
            $mail->SetFrom(self::$setFrom );
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AddAddress($address);

            $mail->Send();
    }
}

