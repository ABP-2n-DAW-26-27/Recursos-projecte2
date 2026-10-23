<?php

function ctrlLogin(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $error = $request->get("SESSION", "error") ?? "";
    $success = $request->get("SESSION", "success") ?? "";
    $response->unsetSession("error");  
    $response->unsetSession("success");
    $response->set("error", $error);
    $response->set("success", $success);
    $response->setTemplate("login.php");

    return $response;
}
