<?php

function ctrlIndex(\Emeset\Request $request, \Emeset\Response $response, Container $container): \Emeset\Response{

    $imagesModel = $container->Images();
    
    $images = $imagesModel->get();

    $name = $request->get("SESSION", "name") ?? "No name";
    $surname = $request->get("SESSION", "surname") ?? "No surname";
    $id = $request->get(INPUT_GET, "image") ?? false;

    if($id !== false){
        $current = $imagesModel->getById($id);
    } else {
        $current = $images[0];
    }

    $response->set("images", $images);
    $response->set("currentImage", $current);
    $response->set("name", $name);
    $response->set("surname", $surname);


    $response->setTemplate("home.php");

    return $response;
    
}
