<?php

function ctrlImageEdit(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{

    $response->setTemplate("image-edit.php");

    return $response;
}
