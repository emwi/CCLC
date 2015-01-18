<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader.
include(__DIR__.'/config_pagecontroller.php'); 



// Add extra assets to make the plugin work.
$app->theme->addStylesheet('css/slideshow.css')
           ->addJavaScript('js/slideshow.js');



// Prepare the page content
$app->theme->setVariable('title', "Slideshow to show off how JavaScript works in Anax")
           ->setVariable('main', "
    <h1>A slideshow with JavaScript</h1>
    <p>This is a sample pagecontroller to show off how JavaScript works with Anax.</p>
    <div id='slideshow' class='slideshow' data-host='' data-path='img/me/' data-images='[\"img1.jpg\", \"ing2.jpg\", \"img3.jpg\"]'>
        <img src='img/me/img1.jpg' width='950px' height='237px' alt='Me'/>
    </div>
");



// Render the response using theme engine.
$app->theme->render();
