<?php

function ctrlImageNew(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{

    $response->setTemplate("image-new.php");

    return $response;
}
