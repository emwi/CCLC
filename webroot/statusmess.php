<?php
/**
* This is a Anax/emwi pagecontroller to test Flashingmessage module.
*/

require __DIR__.'/config_with_app.php';

//With anax
$app->theme->addStylesheet('../webroot/css/Flashingmessage.css');
//without anax
//$app->theme->addStylesheet('Flashingmessage.css');

//With anax
$status = new Anax\Flashingmessage\CStatusMessage($di);
//without anax
//$status = new Anax\Flashingmessage\CStatusMessage();

$status->addDebugMessage("Debug: #1");
$status->addErrorMessage("Error: #2");
$status->addWarningMessage("Warning: #3");
$status->addSuccessMessage("Success: #4");

$status->retrieveMessages();

// Prepare the page content
$app->theme->setVariable('title', "Test page for Flashingmessage")
->setVariable('main', "
<h1>Test page for Flashingmessage</h1>
<p>If four different messages how up below, it works!</p>
" . $status->messagesHtml());

// Render the response using theme engine.
$app->theme->render();
