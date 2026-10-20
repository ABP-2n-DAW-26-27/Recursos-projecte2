<?php

function ctrlImageEdit(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $response->setTemplate("image-edit.php");

    return $response;
}
