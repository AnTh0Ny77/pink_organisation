<?php
require "vendor/autoload.php";
require "src/Database.php";
use src\Database;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

$loader = new \Twig\Loader\FilesystemLoader('public/templates/');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$Database = new Database('pink') ;
$Pdo = $Database->DbConnect();

$request = $_SERVER['REQUEST_URI'];


//recup le get pour les adapter au routing :
$get_request = explode('?', $request, 2);
if (isset($get_request[1]))
    $get_data = '?' . $get_request[1];
else
    $get_data = "";

$global_request = $_SERVER['HTTP_HOST'] . $get_request[0] . $get_data; 


if (empty($_SESSION)) 
{
    session_start();
}

// switch sur URI 
switch($global_request)
{
		
	case $_SERVER['HTTP_HOST'].'':

        if (!isset($_SESSION['lng']))
            $_SESSION['lng'] = 'fr';
        
        if (!empty($_POST['switch']))
            $_SESSION['lng'] = $_POST['switch'];
           
        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);

        $dir = __DIR__ . '/public/web/assets/img/Accueil/';
        $carroussel_pic = scandir($dir);

        echo $twig->render(
            'accueil.html.twig', 
            [
                'content' => $text_content ,
                'lng' => $_SESSION['lng'] , 
                'carroussel_pic' => $carroussel_pic
            ]
        );
        break;

    case $_SERVER['HTTP_HOST'].'/portfolio':

        if (!isset($_SESSION['lng']))
            $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
        $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);

        echo $twig->render(
            'portfolio.html.twig',
            [
                'content' => $text_content,
                'lng' => $_SESSION['lng']
            ]
        );
        break;

    case $_SERVER['HTTP_HOST'].'/references':

        if (!isset($_SESSION['lng']))
        $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
        $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);
        $ref_list = $Database->get_reference($Pdo, $_SESSION['lng']);

        echo $twig->render(
            'reference.html.twig',
            [
                'content' => $text_content,
                'lng' => $_SESSION['lng'] , 
                'ref_list' => $ref_list 
            ]
        );
        break;

    case $_SERVER['HTTP_HOST'].'/galerie'.$get_data:

        if (!isset($_SESSION['lng']))
        $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
        $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);

        $data = $_GET['data'];
        $dir = __DIR__.'/public/web/assets/img/'.$data;
        $files = scandir($dir );
        
     
        echo $twig->render(
            'galerie.html.twig',
            [
                'content' => $text_content,
                'lng' => $_SESSION['lng'] ,
                'categorie' => $data,
                'image_array' =>  $files
            ]
        );
        break;

    case $_SERVER['HTTP_HOST'].'/contact':

        if (!isset($_SESSION['lng']))
        $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
        $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);

        echo $twig->render(
            'contact.html.twig',
            [
                'content' => $text_content,
                'lng' => $_SESSION['lng']
            
            ]
        );
        break;

    case $_SERVER['HTTP_HOST'].'/contactform':

        $alert_mail = false ;
        $alert_double = false ;
        if (!isset($_SESSION['lng']))
        $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
        $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);
        $dir = __DIR__ . '/public/web/assets/img/Accueil/';
        $carroussel_pic = scandir($dir);

        if (!empty($_POST['name']) && !empty($_POST['email']) && $_SESSION['alert_mail'] != 1 )
         {
            $mail = new PHPMailer(true);
            try {
                //Server settings
                $mail->SMTPDebug = false;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'pink.organisation.mailer@gmail.com';                     //SMTP username
                $mail->Password   = 'pink-organisation';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('pink.organisation.mailer@gmail.com', 'Mailer');
                $mail->addAddress('caroline.randu@pink-organisation.com', 'Message depuis le site');     //Add a recipient

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'message de : '. $_POST['name'] ;
                $mail->Body    = ''. $_POST['message']. '<br> Envoyé depuis: ' . $_POST['email']  ;

                $mail->send();
                $alert_mail =  'Message envoyé avec succès ';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
           
            $_SESSION['alert_mail'] = 1 ;
        }
        
       

        echo $twig->render(
            'accueil.html.twig',
            [
                'content' => $text_content,
                'lng' => $_SESSION['lng'] , 
                'alert_mail' => $alert_mail ,
                'carroussel_pic' => $carroussel_pic
                
            ]
        );
        break;
  
	default:

		// header('HTTP/1.0 404 not found');

        if (!isset($_SESSION['lng']))
            $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
            $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);
        $accroche = [];

        for ($i=0; $i < 3 ; $i++) 
        {
            foreach ($text_content as  $value) {
                if ($value->name_field == 'w_text') {
                    array_push($accroche, $value->content);
                }
            }
        }
        
        
    
        $dir = __DIR__ . '/public/web/assets/img/Accueil/';
        $carroussel_pic = scandir($dir);
        
        echo $twig->render(
            'accueil.html.twig',[

                'content' => $text_content,
                'lng' => $_SESSION['lng'] ,
                'carroussel_pic' => $carroussel_pic , 
                'accroche' => $accroche
            ]
           


        );	
		break;
}
