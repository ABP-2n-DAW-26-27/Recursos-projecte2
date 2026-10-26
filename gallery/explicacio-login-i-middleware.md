# Login i middleware

En aquest exemple, el login comprova les credencials i desa l’usuari a la sessió. El middleware `auth` protegeix les rutes d’administració: si no hi ha cap usuari a la sessió, redirigeix al formulari; si n’hi ha, deixa continuar la petició.

## 1. El formulari envia les credencials

La vista `login.php` envia l’usuari i la contrasenya amb POST. El camp ocult `r` indica al Front Controller quina acció ha de processar el formulari:

```html
<form action="index.php" method="post">
  <input type="hidden" name="r" value="do-login">
  <label for="user">Usuari</label>
  <input id="user" name="user" type="text" placeholder="El teu usuari">
  <label for="password">Clau</label>
  <input id="password" name="password" type="password">
  <button type="submit">Iniciar sessió</button>
</form>
```

Quan s’envia, `r=do-login` fa que `public/index.php` cridi `ctrlDoLogin()`.

## 2. El controlador mostra el formulari de login

Quan es visita la ruta `login`, `ctrlLogin()` llegeix els missatges temporals de la sessió, els esborra perquè no es tornin a mostrar i els prepara per a la vista:

```php
$error = $request->get("SESSION", "error") ?? "";
$success = $request->get("SESSION", "success") ?? "";
$response->unsetSession("error");
$response->unsetSession("success");
$response->set("error", $error);
$response->set("success", $success);
$response->setTemplate("login.php");

return $response;
```

La vista mostra el missatge d’error si n’hi ha. El controlador també li passa `success`, tot i que la plantilla actual no el mostra.

## 3. El controlador demana el model d’usuaris

`ctrlDoLogin()` llegeix l’usuari i la contrasenya del formulari. Després demana al contenidor el model `Users` i hi crida `getByUser()` amb el nom d’usuari:

```php
$user = $request->get(INPUT_POST, "user");
$password = $request->get(INPUT_POST, "password");

$userModel = $container->Users();
$currentUser = $userModel->getByUser($user);
```

## 4. El model consulta la base de dades

El mètode `getByUser()` és dins de `src/Models/User.php`. Rep el nom d’usuari, el vincula al marcador `:user` d’una consulta preparada i retorna la fila trobada. Si no hi ha cap coincidència, `fetch()` retorna `false`:

```php
public function getByUser(string $user): array|bool
{
    $query = "select * from users where user = :user;";
    $stm = $this->db->prepare($query);
    $stm->execute([':user' => $user]);
    return $stm->fetch(\PDO::FETCH_ASSOC);
}
```

El resultat torna a `ctrlDoLogin()` i queda guardat a `$currentUser`. El controlador el fa servir al pas següent per decidir si les credencials són correctes.

## 5. El controlador valida el login i desa la sessió

Abans de comprovar les credencials, esborra qualsevol usuari anterior de la sessió. Si no troba l’usuari o la contrasenya no coincideix, desa un missatge genèric i redirigeix al login:

```php
$response->setSession("user", null);

if ($currentUser === false) {
    $response->setSession("error", "Error de login!");
    $response->redirect("Location: index.php?r=login&error=1");
    return $response;
}

if ($currentUser["password"] !== $password) {
    $response->setSession("error", "Error de login!");
    $response->setSession("login.user", $user);
    $response->redirect("Location: index.php?r=login&error=2");
    return $response;
}
```

El mateix missatge en tots dos casos evita revelar si un nom d’usuari existeix. Si les credencials coincideixen, desa l’usuari complet a la sessió i redirigeix al panell:

```php
$response->setSession(
    "success",
    "Sessió iniciada correctament " . $currentUser["name"]
);
$response->setSession("user", $currentUser);
$response->redirect("Location: index.php?r=gallery-admin");

return $response;
```

La sessió s’inicia quan es crea `Request`. `setSession()` i `unsetSession()` treballen amb `$_SESSION`.

## 6. El middleware `auth` comprova la sessió

Un middleware és una funció que s’executa abans del controlador. Pot comprovar o preparar alguna cosa sobre la petició i després decidir si deixa continuar. `auth` comprova que l’usuari hagi iniciat sessió:

```php
function auth(
    \Emeset\Request $request,
    \Emeset\Response $response,
    \Container $container,
    callable $next
): \Emeset\Response {
    $user = $request->get("SESSION", "user");

    if (is_null($user)) {
        $response->redirect("Location: index.php?r=login");
        return $response;
    }

    $response->set("user", $user);
    $response = $next($request, $response, $container);

    return $response;
}
```

El paràmetre `$next` és declarat com a `callable`. En PHP, això vol dir que pot rebre una funció que es pugui cridar. En aquest projecte se li passa el nom d’una funció de controlador, per exemple `"ctrlGalleryAdmin"`. Dins del middleware, aquesta línia la crida i li passa els objectes que necessita:

```php
$response = $next($request, $response, $container);
```

El flux de `auth` és el següent:

1. Si `user` no és a la sessió, prepara una redirecció a `login` i no crida el controlador protegit.
2. Si `user` existeix, el passa a la resposta amb `set("user", $user)`.
3. Crida el controlador indicat per `$next` i retorna la resposta que aquest genera.

## 7. El Front Controller aplica el middleware

`public/index.php` decideix quin controlador s’executa segons el paràmetre `r`. Les rutes públiques criden el controlador directament. Per a les rutes d’administració, el Front Controller crida `auth` i li passa el nom del controlador com a `$next`:

```php
if ($r == "do-login") {
    $response = ctrlDoLogin($request, $response, $container);
} elseif ($r == "login") {
    $response = ctrlLogin($request, $response, $container);
} elseif ($r == "gallery-admin") {
    $response = auth($request, $response, $container, "ctrlGalleryAdmin");
} elseif ($r == "image-new") {
    $response = auth($request, $response, $container, "ctrlImageNew");
}
```

Per exemple, amb `r=gallery-admin`, el recorregut és: el Front Controller crida `auth`; si hi ha un usuari a la sessió, `auth` crida `ctrlGalleryAdmin`; si no, redirigeix a `login` i no arriba a cridar el controlador. El mateix patró protegeix `image-edit`, `image-delete`, `image-store` i `image-update`.

