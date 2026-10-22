<?php

function ctrlImageDelete(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{
    $images = $container->Images();
    $id = $request->get(INPUT_GET, "id");

    $images->delete($id);

    $response->setSession("success", "Imatge esborrada correctament!!!");
    $response->redirect("Location: index.php?r=gallery-admin");


    return $response;
}
