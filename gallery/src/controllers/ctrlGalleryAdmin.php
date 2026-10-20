<?php

function ctrlGalleryAdmin(\Emeset\Request $request, \Emeset\Response $response, Container $container): \Emeset\Response
{
    $imagesModel = $container->Images();

    /* Recuperem missatges desats a la sessió */
    $error = $request->get("SESSION", "error") ?? "";
    $success = $request->get("SESSION", "success") ?? "";
    $response->unsetSession("error");  
    $response->unsetSession("success");
    $response->set("error", $error);
    $response->set("success", $success);  

    /* Obtenim el llistat d'imatges*/
    $images = $imagesModel->get();
    $response->set("images", $images);

    /* Definim la vista */
    $response->setTemplate("gallery-admin.php");

    /* Ja podem retornar la resposta */
    return $response;
}
