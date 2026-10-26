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
    <?php if(isset($error) && $error !== ''){?><p class="notice"><?= $error ?></p><?php } ?>
    <?php if(isset($success) && $success !== ''){?><p class="notice"><?= $success ?></p><?php } ?>
    <div class="actions"><a class="button" href="index.php?r=image-new">Afegeix una imatge</a></div>
    <section class="content-panel" aria-label="Llistat d'imatges">
      <div class="table-wrap"><table class="data-table">
        <thead><tr><th scope="col">Imatge</th><th scope="col">Títol</th><th scope="col">Fitxer</th><th scope="col">Accions</th></tr></thead>
        <tbody>
          <?php foreach($images as $key => $image) { ?>
          <tr>
            <td><img class="table-image" src="img/<?= $image["url"] ?>" alt="<?= $image["title"] ?>"></td>
            <td><?= $image["title"] ?></td>
            <td><?= $image["url"] ?></td>
            <td>
              <div class="inline-actions">
                <a class="button button-secondary" href="index.php?image=<?= $image["id"] ?>">Veure</a>
                <a class="button button-secondary" href="index.php?r=image-edit&amp;id=<?= $image["id"] ?>">Edita</a>
                <a class="button button-danger" href="index.php?r=image-delete&amp;id=<?= $image["id"] ?>">Elimina</a>
              </div>
            </td>
          </tr>
          
          <?php } ?>
        </tbody>
      </table></div>
    </section>
  </main>
  <footer class="site-footer"><div class="footer-card"><h2>M613 · Desenvolupament web entorn servidor</h2><p>Professor: Dani Prados</p><p>Exemple de galeria amb MVC</p></div></footer>
</body>
</html>
