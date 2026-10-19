<?php

 include "../src/Models/Images.php";

function ctrlIndex(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{
    
    $dsn = 'mysql:dbname=gallery;host=127.0.0.1;port=3306;charset=utf8mb4';
    $user = 'gallery';
    $password = '1234';

    $imagesModel = new Images($dsn, $user, $password);
    
    $images = $imagesModel->get();

    //print_r($images);

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
