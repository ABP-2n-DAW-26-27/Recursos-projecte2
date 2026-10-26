<?php

function ctrlImageEdit(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $id = $request->get(INPUT_GET, "id");

    $imageModel = $container->Images();

    $image = $imageModel->getById($id);

    if($image === false) {
        $response->setSession("error", "No s'ha trobat la imatge.");
        $response->redirect("Location: index.php?r=gallery-admin");
        return $response;
    }

    $response->set("image", $image);

    $response->setTemplate("image-edit.php");

    return $response;
}
