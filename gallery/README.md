# Galeria d’imatges

Projecte d’exemple per començar a treballar amb **PHP** i el patró **MVC**. Mostra una galeria d’imatges, una pàgina d’informació i un formulari d’entrada. El codi està pensat per aprendre, no per fer servir en producció.

## Requisits

- PHP amb l’extensió PDO MySQL
- MySQL
- Un navegador web

## Base de dades

El fitxer [`gallery.sql`](gallery.sql) conté la taula `images` i unes dades d’exemple. Crea primer una base de dades anomenada `gallery` al servidor MySQL de l’entorn on vulguis executar el projecte i importa-hi el fitxer, per exemple amb phpMyAdmin o amb el client de línia d’ordres:

```bash
mysql -u usuari -p -e "CREATE DATABASE gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;"
mysql -u usuari -p gallery < gallery.sql
```

Després, configura les dades d’accés al servidor a `src/config.php` (`user`, `password`, `dbname`, `host` i `port`). El projecte necessita poder connectar-se a aquesta base de dades.

## Posar-la en marxa

Des de la carpeta del projecte, executa:

```bash
cd public
php -S localhost:8000
```

Obre [http://localhost:8000](http://localhost:8000) al navegador. Atura el servidor amb `Ctrl+C`.

Per entendre com funciona el llistat d’administració i l’alta d’imatges, consulta [l’explicació de l’administració](explicacio-admin-i-afegir.md).

## On és cada cosa?

- `public/`: punt d’entrada (`index.php`), estils i imatges públiques.
- `src/controllers/`: decideixen què fer amb cada petició.
- `src/Models/`: accés i gestió de les dades de la base de dades.
- `src/views/`: plantilles que generen les pàgines HTML.
- `src/Emeset/`: classes bàsiques per gestionar peticions, respostes i dependències.

El fitxer `public/index.php` és el **front controller**: rep les peticions, escull el controlador i envia la resposta. Els controladors preparen les dades amb els models i les passen a les vistes.

