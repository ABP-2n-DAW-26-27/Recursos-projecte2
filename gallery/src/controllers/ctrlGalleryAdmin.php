<?php

function ctrlGalleryAdmin(\Emeset\Request $request, \Emeset\Response $response, \Emeset\Container $container): \Emeset\Response{

    $response->setTemplate("gallery-admin.php");
    return $response;
}
