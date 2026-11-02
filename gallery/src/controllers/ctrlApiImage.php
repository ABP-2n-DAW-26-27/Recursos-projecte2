<?php

function ctrlApiImage(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $id = $request->get(INPUT_GET, "id");

    $imageModel = $container->Images();

    $image = $imageModel->getById($id);
    $response->set("status", "ok");

    if($image === false) {
        $response->set("status", "ko");
        $image = [];
    }

    $response->set("image", $image);

    $response->setJson();

    return $response;
}
