<?php

 include "../src/Models/Images.php";

function ctrlIndex(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{
    
    $imagesModel = new Images();
    
    $images = $imagesModel->get();

    $name = $request->get("SESSION", "name") ?? "No name";
    $surname = $request->get("SESSION", "surname") ?? "No surname";
    $currentImage = $request->get(INPUT_GET, "image") ?? false;

    $response->set("images", $images);
    $response->set("currentImage", $currentImage);
    $response->set("name", $name);
    $response->set("surname", $surname);


    $response->setTemplate("home.php");

    return $response;
    
}
