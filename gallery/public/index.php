<?php

/**
 * Aquest fitxer és un exemple de Front Controller, pel qual passen totes les requests.
 */

 include "../src/config.php";
 include "../src/controllers/ctrlIndex.php";
 include "../src/controllers/ctrlAbout.php";
 include "../src/controllers/ctrlLogin.php";
 include "../src/controllers/ctrlDoLogin.php";
 include "../src/controllers/ctrlGalleryAdmin.php";
 include "../src/controllers/ctrlImageNew.php";
 include "../src/controllers/ctrlImageEdit.php";
 include "../src/controllers/ctrlImageDelete.php";
 include "../src/controllers/ctrlImageStore.php";
 include "../src/controllers/ctrlImageUpdate.php";

 /* models */
 include "../src/Models/Db.php";
 include "../src/Models/Images.php";


/**
  * Carreguem les classes del Framework Emeset
*/
  
 include "../src/Emeset/Container.php";
 include "../src/Emeset/Request.php";
 include "../src/Emeset/Response.php";
 include "../src/Container.php";

 $request = new \Emeset\Request();
 $response = new \Emeset\Response();
 $container = new Container($config);

 /* 
  * Aquesta és la part que fa que funcioni el Front Controller.
  * Si no hi ha cap paràmetre, carreguem la pàgina d'inici.
  * Si hi ha paràmetre, carreguem la pàgina que correspongui.
  * Si no existeix la pàgina, carreguem la pàgina d'error.
  */
 $r = $request->get("INPUT_REQUEST", "r") ?? '';
  
 /* Front Controller, aquí es decideix quina acció s'executa */
 if($r == "do-login") {
     $response = ctrlDoLogin($request, $response, $container);
 } elseif($r == "login") {
     $response = ctrlLogin($request, $response, $container);
 } else if($r == "gallery-admin") {
     $response = ctrlGalleryAdmin($request, $response, $container);
 } else if($r == "image-new") {
     $response = ctrlImageNew($request, $response, $container);
 } else if($r == "image-edit") {
     $response = ctrlImageEdit($request, $response, $container);
 } else if($r == "image-delete") {
     $response = ctrlImageDelete($request, $response, $container);
 } else if($r == "image-store") {
     $response = ctrlImageStore($request, $response, $container);
 } else if($r == "image-update") {
     $response = ctrlImageUpdate($request, $response, $container);
 }  else if($r == "about") {
     $response = ctrlAbout($request, $response, $container);
 }  else if($r == "") {
     $response = ctrlIndex($request, $response, $container);
 }  else {
     echo "No existeix la ruta";
 }

 /* Enviem la resposta al client. */
 $response->execute();
