# Galeria d’imatges

Projecte d’exemple per començar a treballar amb **PHP** i el patró **MVC**. Mostra una galeria d’imatges, una pàgina d’informació i un formulari d’entrada. El codi està pensat per aprendre, no per fer servir en producció.

## Requisits

- PHP instal·lat
- Un navegador web

## Posar-la en marxa

Des de la carpeta del projecte, executa:

```bash
cd public
php -S localhost:8000
```

Obre [http://localhost:8000](http://localhost:8000) al navegador. Atura el servidor amb `Ctrl+C`.

## On és cada cosa?

- `public/`: punt d’entrada (`index.php`), estils i imatges públiques.
- `src/controllers/`: decideixen què fer amb cada petició.
- `src/Models/`: accés i gestió de dades; ara les imatges són una llista d’exemple.
- `src/views/`: plantilles que generen les pàgines HTML.
- `src/Emeset/`: classes bàsiques per gestionar peticions, respostes i dependències.

El fitxer `public/index.php` és el **front controller**: rep les peticions, escull el controlador i envia la resposta. Els controladors preparen les dades amb els models i les passen a les vistes.

