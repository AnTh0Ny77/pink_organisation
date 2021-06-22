<?php
require "vendor/autoload.php";
require "src/Database.php";
use src\Database;
$loader = new \Twig\Loader\FilesystemLoader('public/templates/');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$Database = new Database('pink') ;
$Pdo = $Database->DbConnect();

$request = $_SERVER['REQUEST_URI'];



if (empty($_SESSION)) 
{
    session_start();
}





// switch sur URI 
switch($request)
{
		
	case '/pink/':

        if (!isset($_SESSION['lng']))
            $_SESSION['lng'] = 'fr';
        
        if (!empty($_POST['switch']))
            $_SESSION['lng'] = $_POST['switch'];
           
        $text_content = $Database->get_content($Pdo, $_SESSION['lng']); 
       
        echo $twig->render(
            'accueil.html.twig', 
            [
                'content' => $text_content ,
                'lng' => $_SESSION['lng']
            ]
        );
        break;


	default:

		header('HTTP/1.0 404 not found');

        if (!isset($_SESSION['lng']))
            $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
            $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']); 
        
        echo $twig->render(
            'accueil.html.twig',[

                'content' => $text_content,
                'lng' => $_SESSION['lng']
            ]
           


        );	
		break;
}
