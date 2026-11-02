<!doctype html>
<html lang="ca">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Galeria | Exemple MVC</title>
  <link rel="stylesheet" href="css/styles.css">
  <script>
    function show(id) {
      fetch('index.php?r=api-image&id=' + id)
        .then(response => {
          if (!response.ok) {
            console.error(`Error HTTP: ${response.status}`);
            return null;
          }
          return response.json();
        })
        .then(data => {
          if (!data) return;

          if (data.status === 'ok') {
            console.log('Imatge:', data.image);
            console.log('URL:', data.image.url);
            console.log('Títol:', data.image.title);
            console.log('Descripció:', data.image.description);
          } else {
            console.error('No s’ha pogut obtenir la imatge:', data);
          }
          let image = document.getElementById("image");
          image.src = "/img/" + data.image.url;
        })
        .catch(error => {
          console.error('Error en la petició:', error);
        });
    }
  </script>
</head>

<body>
  <?php include "components/header.php"; ?>

  <main class="container">
    <h1 class="page-heading">Galeria d'imatges</h1>
    <p class="intro">Exemple de galeria amb MVC i middleware.</p>
    <p class="notice">Ja has visitat 3 imatges de la galeria.</p>
    <section class="gallery-layout" aria-label="Galeria">
      <nav class="image-list" aria-label="Selecciona una imatge">
        <?php foreach ($images as $key => $image) { ?>
          <a href="#" onclick="show(<?= $image['id'] ?>)"><?= $image["title"] ?></a>
        <?php } ?>
      </nav>
      <div>
        <img class="featured-image" id="image" src="img/<?= $currentImage["url"] ?>" alt="<?= $currentImage["title"] ?>">
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