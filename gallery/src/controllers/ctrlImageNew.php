<?php

function ctrlImageNew(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

$user = $request->get("SESSION", "user");
    if(is_null($user)){
        $response->redirect("Location: index.php?r=login");
        return $response;
    }
    $error = $request->get("SESSION", "error") ?? "";
    $response->unsetSession("error");  

    $response->set("error", $error);

    $response->setTemplate("image-new.php");

    return $response;
}
