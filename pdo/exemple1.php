<?php

/**************************
 *
 * M613 - Projecte 2
 * Professor: Dani Prados
 *
 * Exemple de com utilitzar l'objecte PDO des de la línia d'ordres.
 ***************************/

$dsn = 'mysql:dbname=pdo;host=127.0.0.1;port=3306;charset=utf8mb4';
$user = 'pdo';
$password = 'pdopass';

try {
    $sql = new PDO($dsn, $user, $password);

    /* -- Consulta bàsica (llistat de links) -- */
    echo "Llistat de links\n";
    echo "----------------\n";

    $query = 'select id, name, short, url, user_id from links order by id desc';
    foreach ($sql->query($query) as $link) {
        echo "{$link['id']}, {$link['name']} ({$link['short']}) — "
            . "{$link['url']} — usuari {$link['user_id']}\n";
    }

    /* -- Inserció amb paràmetres -- */
    echo "\nInserció d'un link\n";
    echo "------------------\n";

    $name = 'Web Institut Cendrassos';
    $short = 'cendrassos';
    $url = 'https://www.cendrassos.net/';
    $userId = 1;

    $query = 'insert into links (url, name, short, user_id)
              values (:url, :name, :short, :user_id)';
    $stm = $sql->prepare($query);

    try {
        $stm->execute([
            ':url' => $url,
            ':name' => $name,
            ':short' => $short,
            ':user_id' => $userId,
        ]);
        echo "Link inserit correctament.\n";
    } catch (PDOException $e) {
        // El short és únic: si ja existeix, no el tornem a inserir.
        if ($e->getCode() !== '23000') {
            throw $e;
        }
        echo "El link amb short '$short' ja existeix.\n";
    }

    /* -- Consulta parametritzada (cerca per short) -- */
    echo "\nCerca per short\n";
    echo "---------------\n";

    $query = 'select id, name, short, url, user_id from links where short = :short';
    $stm = $sql->prepare($query);
    $stm->execute([':short' => $short]);

    $link = $stm->fetch();
    if ($link === false) {
        echo "No s'ha trobat cap link amb aquest short.\n";
    } else {
        echo "{$link['id']}, {$link['name']} ({$link['short']}) — "
            . "{$link['url']} — usuari {$link['user_id']}\n";
    }
} catch (PDOException $e) {
    fwrite(STDERR, 'Ha fallat l’operació amb la base de dades: ' . $e->getMessage() . "\n");
    exit(1);
}
