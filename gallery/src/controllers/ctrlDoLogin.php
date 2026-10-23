<?php

function ctrlDoLogin(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{

    $user = $request->get(INPUT_POST, "user");
    $password =  $request->get(INPUT_POST, "password");
    $userModel = $container->Users();

    $currentUser = $userModel->getByUser($user);

    $response->setSession("user", null);

    if($currentUser === false){
        $response->redirect("Location: index.php?r=login&error=1");
        return($response);
    }

    if($currentUser["password"] !== $password) {
        $response->setSession("login.user", $user);
        $response->redirect("Location: index.php?r=login&error=2");
        return $response;
    }

    $response->setSession("user", $currentUser);
    $response->redirect("Location: index.php?r=gallery-admin");
    
    return $response;
}
