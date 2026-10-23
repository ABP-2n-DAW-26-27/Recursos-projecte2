<!doctype html>
<html lang="ca">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Identificació | Exemple MVC</title>
  <link rel="stylesheet" href="css/styles.css">
</head>
<body>
  <?php include "components/header.php"; ?>  

  <main class="container">
    <h1 class="page-heading">Identifica't per entrar</h1>
    <p class="intro">Et donem la benvinguda a la galeria.</p>
    <section class="card form-card">
      <h2>Exemple · Sessions</h2>
      <p>Abans d'entrar, què et sembla si em dius el teu nom?</p>
      <form action="index.php" method="post">
        <input type="hidden" name="r" value="do-login">
        <label for="user">Usuari</label>
        <input id="name" name="user" type="text" placeholder="El teu usuari">
        <label for="password">Clau</label>
        <input id="password" name="password" type="password">
        <button type="submit">Iniciar sessió</button>
      </form>
    </section>
  </main>

  <footer class="site-footer"><div class="footer-card"><h2>M613 · Desenvolupament web entorn servidor</h2><p>Professor: Dani Prados</p><p>Exemple de galeria amb MVC</p></div></footer>
</body>
</html>
