

<?php

//require the autolaoder //

require __DIR__ . '/vendor/autoload.php'; 

$router = new AltoRouter();

$router->setBasePath('/super-week');







// route that going to display home page//

$router->map('GET', '/', function(){

    $welcomeMessage = 'Bienvenue sur la page d accuille!';

    require __DIR__ . '/src/View/home.php';

    

 });

 $router->map('GET', '/users', function(){
    //welcome message:

    $welcomeMessage = "Bienvenue sur la liste des utilisateurs!";
    //require the users page from View:
    require __DIR__ . '/src/View/users.php';
    echo "<h1> Bonjour les utilisateurs </h1>";
    var_dump($welcomeMessage);
 });

$router->map('GET', '/users/1', function(){
    $welcomeMessage = "Bienvenue sur la page user 1";
    require __DIR__ . '/src/View/users.php/1';
});

   // $userController = new $userController();
// });

 $match = $router->match();