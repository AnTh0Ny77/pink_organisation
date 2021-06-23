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


//recup le get pour les adapter au routing :
$get_request = explode('?', $request, 2);
if (isset($get_request[1]))
    $get_data = '?' . $get_request[1];
else
    $get_data = "";

$global_request = $get_request[0] . $get_data; 


if (empty($_SESSION)) 
{
    session_start();
}





// switch sur URI 
switch($global_request)
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


    case '/pink/portfolio':

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


    case '/pink/references':

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


    case '/pink/galerie'.$get_data:

        if (!isset($_SESSION['lng']))
        $_SESSION['lng'] = 'fr';

        if (!empty($_POST['switch']))
        $_SESSION['lng'] = $_POST['switch'];

        $text_content = $Database->get_content($Pdo, $_SESSION['lng']);

        $data = $_GET['data'];
        $dir = __DIR__.'/public/web/assets/img/'.$data;
        $files = scandir($dir);
        
      
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
