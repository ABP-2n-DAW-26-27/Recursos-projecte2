<?php

function ctrlAbout(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{

    $response->setTemplate("about.php");

    return $response;
}