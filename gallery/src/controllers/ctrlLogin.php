<?php

function ctrlLogin(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $response->setTemplate("login.php");

    return $response;
}
