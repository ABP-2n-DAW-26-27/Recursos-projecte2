<?php

function ctrlImageDelete(\Emeset\Request $request, \Emeset\Response $response, \Container $container): \Emeset\Response{
    $images = $container->Images();
    $id = $request->get(INPUT_GET, "id");

    $image = $images->getById((int) $id);
    if ($image === false) {
        $response->setSession("error", "No s'ha trobat la imatge.");
        $response->redirect("Location: index.php?r=gallery-admin");
        return $response;
    }

    if (!$images->delete((int) $id)) {
        $response->setSession("error", "No s'ha pogut esborrar la imatge.");
        $response->redirect("Location: index.php?r=gallery-admin");
        return $response;
    }

    // Les imatges pujades es desen a public/img/. El nom ve de la base de dades,
    // però basename evita que un valor inesperat apunti fora d'aquest directori.
    $filePath = __DIR__ . "/../../public/img/" . basename($image["url"]);
    if (!unlink($filePath)) {
        $response->setSession("error", "El registre s'ha esborrat, però no s'ha pogut eliminar el fitxer.");
        $response->redirect("Location: index.php?r=gallery-admin");
        return $response;
    }

    $response->setSession("success", "Imatge esborrada correctament.");
    $response->redirect("Location: index.php?r=gallery-admin");

    return $response;
}
