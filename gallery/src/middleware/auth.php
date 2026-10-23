<?php

/**
 * Example function - Exemple d'estructura d'una funció middleware.
 *
 * @param \Emeset\Request $request
 * @param \Emeset\Response $response
 * @param \Container $container
 * @param callable(\Emeset\Request, \Emeset\Response, \Container): \Emeset\Response $next
 * @return \Emeset\Response
 */
function auth(\Emeset\Request $request, \Emeset\Response $response, \Container $container, callable $next): \Emeset\Response{

    $user = $request->get("SESSION", "user");
    if(is_null($user)){
        $response->redirect("Location: index.php?r=login");
        return $response;
    }
    $response->set("user", $user);
    $response = $next($request, $response, $container);

    return $response;
    
}
