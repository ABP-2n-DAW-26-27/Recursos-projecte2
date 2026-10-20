# Llistat d’administració i afegir imatges

En aquest exemple, cada part té una responsabilitat: el controlador coordina la petició, el model treballa amb les dades, la vista les mostra i el contenidor crea els objectes que necessiten les altres parts.

## 1. El contenidor instancia els objectes

El contenidor és responsable d’instanciar les classes i passar-los les dependències necessàries. Primer crea l’objecte `Db`; després, quan un controlador demana `Images()`, crea el model i li passa la connexió.

```php
public function __construct($config)
{
    parent::__construct($config);
    $this->db = $this->Db();
}

public function Images(): \Models\Images
{
    return new \Models\Images($this->db);
}

public function Db(): \Models\Db
{
    return new \Models\Db(
        $this->config["db"]["user"],
        $this->config["db"]["password"],
        $this->config["db"]["dbname"],
        $this->config["db"]["host"],
        $this->config["db"]["port"]
    );
}
```

Això ajuda a separar responsabilitats dins l’arquitectura MVC: el controlador no crea ni configura la connexió; demana al contenidor el model que necessita. El model s’encarrega de les dades i la vista de mostrar-les.

## 2. La classe `Db` obre la connexió

El constructor rep la configuració, construeix el DSN i intenta crear la connexió PDO. `getDb()` retorna la connexió perquè la pugui fer servir el model.

```php
public function __construct($user, $password, $dbName, $host = '127.0.0.1', $port = 3306)
{
    $dsn = "mysql:dbname=$dbName;host=$host;port=$port";
    try {
        $this->db = new \PDO($dsn, $user, $password);
    } catch (\PDOException $e) {
        die('Ha fallat la connexió: ' . $e->getMessage());
    }
}

public function getDb(): \PDO
{
    return $this->db;
}
```

## 3. El model `Images` treballa amb les imatges

El constructor rep l’objecte `Db` i en guarda la connexió PDO. El model pot fer consultes sense haver de conèixer les dades de configuració de MySQL.

```php
public function __construct(Db $db)
{
    $this->sql = $db->getDb();
}
```

`get()` consulta les imatges i retorna el resultat com un array:

```php
public function get(): array
{
    $query = 'select id, url, title from images';
    $result = [];
    foreach ($this->sql->query($query, \PDO::FETCH_ASSOC) as $link) {
        $result[] = $link;
    }
    return $result;
}
```

`add()` insereix una imatge amb una consulta preparada. Retorna `true` si s’ha executat correctament i `false` si hi ha hagut una excepció.

```php
public function add(string $url, string $title, string $description = ''): bool
{
    $stm = $this->sql->prepare('insert into images (url, title, description) values (:url, :title, :description);');
    $result = false;
    try {
        $result = $stm->execute([
            ':url'   => $url,
            ':title'  => $title,
            ':description' => $description,
        ]);
    } catch (\PDOException $e) {
        // Aquí podem gestionar els errors
    }
    return $result;
}
```

Els marcadors `:url`, `:title` i `:description` es vinculen als valors rebuts pel mètode. El model també té un mètode `update()` per modificar una imatge amb el mateix patró de consulta preparada.

## 4. El controlador prepara el llistat d’administració

El controlador segueix aquests passos:

1. Demana al contenidor una instància del model `Images`:

   ```php
   $imagesModel = $container->Images();
   ```

2. Llegeix els missatges d’èxit o d’error de la sessió, els esborra perquè no es repeteixin i els passa a la vista:

   ```php
   $success = $request->get("SESSION", "success") ?? "";
   $response->unsetSession("success");
   $response->set("success", $success);
   ```

3. Demana les imatges al model i les passa a la vista:

   ```php
   $images = $imagesModel->get();
   $response->set("images", $images);
   ```

4. Indica quina plantilla s’ha de mostrar i retorna la resposta:

   ```php
   $response->setTemplate("gallery-admin.php");
   return $response;
   ```

La vista utilitza les dades rebudes per mostrar una fila per imatge:

```php
<?php foreach ($images as $key => $image) { ?>
  <tr>
    <td><img src="img/<?= $image["url"] ?>" alt="<?= $image["title"] ?>"></td>
    <td><?= $image["title"] ?></td>
    <td><?= $image["url"] ?></td>
  </tr>
<?php } ?>
```

## 5. El formulari envia les dades

El formulari envia les dades amb el mètode POST. `multipart/form-data` és necessari per enviar el fitxer:

```html
<form action="index.php?r=image-store" method="post" enctype="multipart/form-data">
  <label for="title">Títol</label>
  <input id="title" name="title" type="text" required>
  <label for="description">Descripció</label>
  <textarea id="description" name="description"></textarea>
  <label for="image">Fitxer d'imatge</label>
  <input id="image" name="image" type="file" required>
  <button type="submit">Desa la imatge</button>
</form>
```

## 6. `ctrlImageStore` rep i desa les dades

El controlador segueix aquests passos:

1. Demana el model al contenidor i llegeix el títol, la descripció i el fitxer rebut:

   ```php
   $imagesModel = $container->Images();
   $title = $request->get(INPUT_POST, "title");
   $description = $request->get(INPUT_POST, "description");
   $file = $request->get("FILES", "image");
   ```

2. Defineix els tipus de fitxer acceptats i consulta el tipus rebut:

   ```php
   $allowedMimeTypes = [
       'image/jpeg' => 'jpg',
       'image/png'  => 'png'
   ];
   $mimeType = $file["type"];
   ```

3. Si el tipus és vàlid, genera un nom per al fitxer i el mou a `img/`:

   ```php
   $extension = $allowedMimeTypes[$mimeType];
   $name = "image" . time() . "." . $extension;
   move_uploaded_file($file["tmp_name"], "img/" . $name);
   ```

4. Desa les dades amb el model, guarda un missatge d’èxit a la sessió i redirigeix al llistat:

   ```php
   $imagesModel->add($name, $title, $description);
   $response->setSession("success", "Fitxer afegit amb èxit");
   $response->redirect("Location: index.php?r=gallery-admin");
   ```

5. Si el tipus no és vàlid, guarda un missatge d’error i torna al formulari:

   ```php
   $response->setSession("error", "Tipus de fitxer no vàlid");
   $response->redirect("Location: index.php?r=image-new");
   ```

En cas correcte, el fitxer es mou a `img/` i el model desa el nom, el títol i la descripció a MySQL. En cas d’error, el controlador torna al formulari amb un missatge.

