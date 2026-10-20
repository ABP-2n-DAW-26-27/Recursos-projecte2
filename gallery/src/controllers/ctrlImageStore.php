<?php

function ctrlImageStore(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $imagesModel = $container->Images();
    
    $title = $request->get(INPUT_POST, "title");
    $description = $request->get(INPUT_POST, "description");

    $file = $request->get("FILES", "image"); 
    $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png'
    ];
    $mimeType = $file["type"];

    if (isset($allowedMimeTypes[$mimeType])) {
        $extension = $allowedMimeTypes[$mimeType];
        $name = "image" . time() . "." . $extension;
        move_uploaded_file($file["tmp_name"], "img/" . $name);
        $imagesModel->add($name, $title, $description);
        $response->setSession("success", "Fitxer afegit amb èxit");
        $response->redirect("Location: index.php?r=gallery-admin");        
    } else {
        $response->setSession("error",  "Tipus de fitxer no vàlid");
        $response->redirect("Location: index.php?r=image-new");
    }
    
    return $response;
}
