<?php

function ctrlIndex(\Emeset\Request $request, \Emeset\Response $response, Container $container): \Emeset\Response{

    $imagesModel = $container->Images();
    
    $images = $imagesModel->get();

    $id = $request->get(INPUT_GET, "image") ?? false;

    if($id !== false){
        $current = $imagesModel->getById($id);
    } else {
        $current = $images[0];
    }

    $response->set("images", $images);
    $response->set("currentImage", $current);

    $response->setTemplate("home.php");

    return $response;
    
}
