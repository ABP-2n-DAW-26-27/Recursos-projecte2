<?php

function ctrlDoLogin(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{

    $name = $request->get(INPUT_POST, "name") ?? "No name";
    $surname = $request->get(INPUT_POST, "surname") ?? "No surname";    

    $response->setSession("name", $name);
    $response->setSession("surname", $surname);

    $response->redirect("Location: index.php");
    
    return $response;
}