<?php

/**
 * This is a Anax pagecontroller.
 *
 */
// Get environment & autoloader and the $app-object.
require __DIR__ . '/config_with_app.php';

// Set configuration for theme
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
   //$app->theme->addStylesheet('css/anax-grid/style.less');
   $app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');
   
// Set url cleaner url links
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);

    $di->set('CommentController', function() use ($di) {
    $controller = new Phpmvc\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
}); 

// Set routing options



$app->router->add('', function() use ($app) {
    $app->theme->setTitle("Theme");
    $app->theme->addJavaScript('js/toggle.js');

    $content = $app->fileContent->get('theme.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');


    $app->theme->setTitle("Regioner");
 
    $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('main', 'main')
               ->addString('sidebar', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');
 
 
    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
 
           
        $app->views->add('me/page', [
        'content' => $content,
            ]); 
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
          'params'     => ['',],
    ]);

           
       
        $app->views->add('me/page', [
        'byline' => $byline,
    ]);
 
});



$app->router->add('typography', function() use ($app) {
 
    $app->theme->setTitle("Typography");
    $content = $app->fileContent->get('typography.html');

    $app->views->addString($content, 'main')
               ->addString($content, 'sidebar');
 
});




$app->router->add('redovisning', function() use ($app) {
 
    $app->theme->setTitle("Redovisning");
  

    $content = $app->fileContent->get('redovisning.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
 
    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
 
    $app->views->add('me/page', [
        'content' => $content,
        'byline' => $byline,
    ]);
 
});

$app->router->add('font', function() use ($app) {
 
    $app->theme->setTitle("Font Awesome");
 
    $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('<i class="fa fa-arrow-circle-o-right"></i>', 'featured-2')
               ->addString('<i class="fa fa-arrow-circle-o-right"></i>', 'featured-3')
               ->addString('<i class="fa fa-child fa-5x"></i>', 'main')
               ->addString('<p><i class="fa fa-coffee fa-2x"></i> fa-coffee</p>
<p><i class="fa fa-home fa-2x"></i> fa-home</p>
<p><i class="fa fa-pinterest-square fa-2x"></i> fa-pinterest</p>
<p><i class="fa fa-linkedin fa-2x"></i></i> fa-linkedin</p>
<p><i class="fa fa-instagram fa-2x"></i> fa-insta</p>
<p><i class="fa fa-camera fa-2x"></i> fa-camera</p>', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');
 
});

$app->router->add('kommentarer', function() use ($app) {
 
    $app->theme->setTitle("Kommentarer");
  $app->theme->addJavaScript('js/toggle.js');
    $content = $app->fileContent->get('kommentarer.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
 
    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');
 
               
        $app->views->add('me/page', [
        'content' => $content,
            ]); 
    
    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
       'params'     => ['kommentarer',],
    ]);

           

       
        $app->views->add('me/page', [
        'byline' => $byline,
    ]);
 
});
 
$app->router->add('source', function() use ($app) {
 
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Kï¿½llkod");
 
    $source = new \Mos\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
 
    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);
 
});

 
$app->router->handle();

$app->theme->render();
