<!doctype html>
<html lang="ca">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Afegeix una imatge | Galeria</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <?php include "components/header.php"; ?>
  <main class="container">
    <h1 class="page-heading">Afegeix una imatge</h1>
    <p class="intro">Omple les dades per incorporar una imatge a la galeria.</p>
    <?php if(isset($error) && $error !== ''){?><p class="notice"><?= $error ?></p><?php } ?>
    <section class="card form-card">
      <h2>Nova imatge</h2>
      <form action="index.php?r=image-store" method="post" enctype="multipart/form-data">
        <label for="title">Títol</label><input id="title" name="title" type="text" placeholder="Ex.: Calella" required>
        <label for="description">Descripció</label><textarea id="description" name="description" placeholder="Descriu breument la imatge"></textarea>
        <label for="image">Fitxer d'imatge</label><input id="image" name="image" type="file" accept="image/*" required>
        <div class="actions"><button type="submit">Desa la imatge</button><a class="button button-secondary" href="index.php?r=gallery-admin">Cancel·la</a></div>
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