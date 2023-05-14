<?php

//require the autoloader //

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/vendor/autoload.php'; 

$router = new AltoRouter();

$router->setBasePath('/super-week');







// route that going to display home page//

$router->map('GET', '/', function(){

    $welcomeMessage = 'Bienvenue sur la page d accueille!';

    require __DIR__ . '/src/View/home.php';

    echo "<h1> $welcomeMessage </h1>";
    

 }, 'home');



$router->map('GET', '/users', function(){

   $welcomeMessage = 'Bienvenue sur la liste des utilisateurs!';

   require __DIR__ . '/src/View/users.php';

echo "<h1> $welcomeMessage </h1>";

});



$router->map('GET', '/users/[i:user_id]', function($user_id){
 $welcomeMessage = "Hello user" . $user_id;

 echo "<h1>$welcomeMessage</h1>";
 });

// $userController = new $userController();



 $match = $router->match();


// call closure or throw 404 status
if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	// no route was matched
	//header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}
