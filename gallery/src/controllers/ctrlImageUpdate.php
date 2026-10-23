<?php

function ctrlImageUpdate(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $imagesModel = $container->Images();

    $id = $request->get(INPUT_POST, "id");
    $title = $request->get(INPUT_POST, "title");
    $description = $request->get(INPUT_POST, "description");

    $image = $imagesModel->getById($id);

    $name = $image["url"];  //obtenim l'imatge actual per mantenir-la si l'usuari no a "pujat" una imatge nova o la nova no és vàlida.
    $file = $request->get("FILES", "image"); 
    if($file["name"] !== ''){
        unlink("img/" . $name);  //esborrem l'imatge anterior

        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png'
        ];
        $mimeType = $file["type"];

        if (isset($allowedMimeTypes[$mimeType])) {
            $extension = $allowedMimeTypes[$mimeType];
            $name = "image" . time() . "." . $extension;
            move_uploaded_file($file["tmp_name"], "img/" . $name);     
        } else {
            $response->setSession("error",  "Tipus de fitxer no vàlid");
            $response->redirect("Location: index.php?r=image-edit&id={$id}");
        }
    }

    $response->setSession("success", "Imatge actualitzada amb èxit");
    $response->redirect("Location: index.php?r=gallery-admin");   
    $imagesModel->update($id, $name, $title, $description);

    return $response;
}
