<?php

function ctrlImageNew(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $error = $request->get("SESSION", "error") ?? "";
    $response->unsetSession("error");  

    $response->set("error", $error);

    $response->setTemplate("image-new.php");

    return $response;
}
