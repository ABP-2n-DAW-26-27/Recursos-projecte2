<?php

function ctrlImageNew(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

     
    $form = $request->get("SESSION", "form") ?? [ "title" => "", "description" => ""]; 
    $response->set("form", $form);
    $response->unsetSession("form");

    $error = $request->get("SESSION", "error") ?? "";
    $response->unsetSession("error");  

    $response->set("error", $error);

    $response->setTemplate("image-new.php");

    return $response;
}
