<?php

function ctrlImageEdit(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $id = $request->get(INPUT_GET, "id");

    $imageModel = $container->Images();

    $image = $imageModel->getById($id);

    $response->set("image", $image);

    $response->setTemplate("image-edit.php");

    return $response;
}
