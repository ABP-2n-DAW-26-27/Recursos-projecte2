<!doctype html>
<html lang="ca">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gestiona la galeria | Galeria</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include "components/header.php"; ?>
  <main class="container">
    <h1 class="page-heading">Gestiona la galeria</h1>
    <p class="intro">Consulta les imatges, edita'n les dades o afegeix-ne de noves.</p>
    <p class="notice">Hi ha 6 imatges a la galeria.</p>
    <div class="actions"><a class="button" href="index.php?r=image-new">Afegeix una imatge</a></div>
    <section class="content-panel" aria-label="Llistat d'imatges">
      <div class="table-wrap"><table class="data-table">
        <thead><tr><th scope="col">Imatge</th><th scope="col">Títol</th><th scope="col">Fitxer</th><th scope="col">Accions</th></tr></thead>
        <tbody>
          <tr><td><img class="table-image" src="img/calella.jpg" alt="Calella"></td><td>Calella</td><td>calella.jpg</td><td><div class="inline-actions"><a class="button button-secondary" href="index.php">Veure</a><a class="button button-secondary" href="index.php?r=image-edit">Edita</a><a class="button button-danger" href="index.php?r=image-delete">Elimina</a></div></td></tr>
          <tr><td><img class="table-image" src="img/ny.jpg" alt="Nova York"></td><td>Nova York</td><td>ny.jpg</td><td><div class="inline-actions"><a class="button button-secondary" href="index.php">Veure</a><a class="button button-secondary" href="index.php?r=image-edit">Edita</a><a class="button button-danger" href="index.php?r=image-delete">Elimina</a></div></td></tr>
          <tr><td><img class="table-image" src="img/platja.jpg" alt="La platja"></td><td>La platja</td><td>platja.jpg</td><td><div class="inline-actions"><a class="button button-secondary" href="index.php">Veure</a><a class="button button-secondary" href="index.php?r=image-edit">Edita</a><a class="button button-danger" href="index.php?r=image-delete">Elimina</a></div></td></tr>
          <tr><td><img class="table-image" src="img/llac.jpg" alt="El llac"></td><td>El llac</td><td>llac.jpg</td><td><div class="inline-actions"><a class="button button-secondary" href="index.php">Veure</a><a class="button button-secondary" href="index.php?r=image-edit">Edita</a><a class="button button-danger" href="index.php?r=image-delete">Elimina</a></div></td></tr>
          <tr><td><img class="table-image" src="img/figueres.jpg" alt="Figueres"></td><td>Figueres</td><td>figueres.jpg</td><td><div class="inline-actions"><a class="button button-secondary" href="index.php">Veure</a><a class="button button-secondary" href="index.php?r=image-edit">Edita</a><a class="button button-danger" href="index.php?r=image-delete">Elimina</a></div></td></tr>
          <tr><td><img class="table-image" src="img/via-lactea.jpg" alt="Via Làctia"></td><td>Via Làctia</td><td>via-lactea.jpg</td><td><div class="inline-actions"><a class="button button-secondary" href="index.php">Veure</a><a class="button button-secondary" href="index.php?r=image-edit">Edita</a><a class="button button-danger" href="index.php?r=image-delete">Elimina</a></div></td></tr>
        </tbody>
      </table></div>
    </section>
  </main>
  <footer class="site-footer"><div class="footer-card"><h2>M613 · Desenvolupament web entorn servidor</h2><p>Professor: Dani Prados</p><p>Exemple de galeria amb MVC</p></div></footer>
</body>
</html>
