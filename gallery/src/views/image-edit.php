<!doctype html>
<html lang="ca">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edita una imatge | Galeria</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <?php include "components/header.php"; ?>
  <main class="container">
    <h1 class="page-heading">Edita una imatge</h1>
    <p class="intro">Actualitza les dades de la imatge seleccionada.</p>
    <section class="card form-card">
      <h2><?= $image["title"] ?></h2>
      <form action="index.php?r=image-update" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $image["id"] ?>">
        <label for="title">Títol</label><input id="title" name="title" type="text" value="<?= $image["title"] ?>" required>
        <label for="description">Descripció</label><textarea id="description" name="description"><?= $image["description"] ?></textarea>
        <label for="image">Canvia el fitxer (opcional)</label><input id="image" name="image" type="file" accept="image/*"><img class="preview-image" src="img/<?= $image["url"] ?>" alt="<?= $image["title"] ?>">
        <div class="actions"><button type="submit">Desa els canvis</button><a class="button button-secondary" href="index.php?r=gallery-admin">Cancel·la</a></div>
      </form>
    </section>
  </main>
  <footer class="site-footer">
    <div class="footer-card">
      <h2>M613 · Desenvolupament web entorn servidor</h2>
      <p>Professor: Dani Prados</p>
      <p>Exemple de galeria amb MVC</p>
    </div>
  </footer>
</body>

</html>