<?php

function ctrlAbout(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $response->setTemplate("about.php");

    return $response;
}
