<?php


// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';


// Create services and inject into the app. 
$di  = new \Anax\DI\CDIFactoryDefault();
  
// Starts/injects the database Model 
$di->set('db', function () use ($di) {
    $db = new \Mos\Database\CDatabaseBasic(); 
    $db->setOptions(require ANAX_APP_PATH.'config/config_mysql.php');
    $db->connect();
    return $db;
}); 


// Starts/injects the Controller for the Users model 
$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});



// Use this instead of CAnax since it uses trait TRedirectHelpers 
$app = new \Anax\MVC\CApplicationBasic($di);
 


// Setting page titel
$app->theme->setTitle('CDatabase test');
 
 

// Test form route
$app->router->add('', function () use ($app) {
    $app->theme->setVariable('main', 
                "<a href='".$app->url->create('setup')."'>Create user</a><br/>".
                "<a href='".$app->url->create('add-user')."'>Add users</a><br/>".
                "<a href='".$app->url->create('user/list-all')."'>List users</a><br/>".
                "<a href='".$app->url->create('user/id/1')."'>List 1 user</a><br/>"
                ); 
});



//  
$app->router->add('setup', function () use ($app) {
 
    //$app->db->setVerbose();
 
    $app->db->dropTableIfExists('user')->execute();
 
    $app->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
        ]
    );

    $app->db->execute(); 
    $app->redirectTo(''); 

}); 


  
$app->router->add('add-user', function () use ($app) {
 
    $app->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active']
    );
 
    $now = date("Y-m-d H:i:s");
 
    $app->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
 
    $app->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        $now
    ]);

    $app->redirectTo(''); 

});



// Dump a list of all users
$app->router->add('user/list-all', function () use ($app) {  
  
    $app->UsersController->listAction();
});




/* Dump a list of all users
$app->router->add('user/id/:number', function () use ($app) {  
  
    $app->UsersController->idAction();
});

 


// 
$app->router->add('users/add/:acronym', function () use ($app) {  
  
    $app->UsersController->addAction();
});

*/



// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();


// Render the page
$app->theme->render(); 
