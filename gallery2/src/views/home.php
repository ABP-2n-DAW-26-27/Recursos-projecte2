<!doctype html>
<html lang="ca">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Galeria | Exemple MVC</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include "components/header.php"; ?>  

  <main class="container">
    <h1 class="page-heading">Galeria d'imatges</h1>
    <p class="intro">Exemple de galeria amb MVC i middleware.</p>
    <p class="notice">Hola, <?= $name ?> <?= $surname ?>! Ja has visitat 3 imatges de la galeria.</p>
    <section class="gallery-layout" aria-label="Galeria">
      <nav class="image-list" aria-label="Selecciona una imatge">
        <?php foreach($images as $key => $image) { ?>
        <a href="index.php?image=<?= $key ?>"><?= $image["title"] ?></a>
        <?php } ?>
      </nav>
      <div>
        <img class="featured-image" src="img/<?= $images[$currentImage]["url"] ?>" alt="<?= $images[$currentImage]["title"] ?>">
      </div>
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
