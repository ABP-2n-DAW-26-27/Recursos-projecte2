# Exemple de MVC


Exemple de galeria amb MVC (EMESET LITE)

M613 - Programació d'aplicacions web.
M614 - Desplegament d'aplicacions web


# DOCKER

Aquest apartat explica com empaquetar i desplegar **la galeria existent** de `gallery/`. L’aplicació utilitza PHP, el framework didàctic del repositori i MySQL. La taula `images` té els camps `id`, `url`, `title` i `description`. No cal cap framework comercial.

## Què aprendrem

- Construir una imatge Docker de l’aplicació i publicar-la a Docker Hub amb una etiqueta de versió.
- Executar l’aplicació i MySQL com a serveis separats amb Docker Compose.
- Inicialitzar una base de dades nova automàticament amb scripts SQL.
- Conservar tant les dades MySQL com les imatges que s’hagin pujat a la galeria.
- Actualitzar l’esquema amb migracions SQL sense esborrar la base de dades ni el volum.

## 1. Imatge i contenidor

Una **imatge** és el paquet que conté l’aplicació i les instruccions per executar-la. Un **contenidor** és una instància en funcionament d’aquesta imatge. La imatge es construeix a partir del fitxer `gallery/Dockerfile`; el codi de l’aplicació no s’ha de copiar manualment al contenidor.

El Dockerfile actual:

```dockerfile
FROM php:8.5-apache
RUN docker-php-ext-install pdo_mysql
COPY public/ /var/www/html/
COPY src/ /var/www/src/
RUN chown www-data:www-data /var/www/html/img
```

- `FROM` parteix de la imatge oficial de PHP amb Apache.
- `RUN docker-php-ext-install pdo_mysql` instal·la el controlador PDO que necessita PHP per connectar-se a MySQL.
- `COPY public/ ...` posa la part pública de la galeria al document root d’Apache.
- `COPY src/ ...` posa el framework i el codi PHP fora del document root. L’`index.php` de la galeria inclou aquests fitxers amb `../src/`.
- `chown` permet que Apache (`www-data`) desi les imatges pujades a `public/img/`. Compose munta aquesta carpeta en un volum persistent.

El **context de construcció** és l’últim argument de `docker build`. Ha de ser `gallery/`, perquè les instruccions `COPY` fan referència a les carpetes `public/` i `src/` que hi ha dins d’aquest directori. Executa la comanda des de l’arrel del repositori:

```bash
docker build -f gallery/Dockerfile -t p02-gallery:1.0 gallery
```

`-f` indica on és el Dockerfile, `-t` assigna el nom i l’etiqueta de versió, i l’últim `gallery` és el context de construcció. Comprova que s’ha creat:

```bash
docker image ls p02-gallery
```

## 2. Publicar la imatge a Docker Hub

Docker Hub és un registre d’imatges. El nom que hi publiquem ha d’incloure el nostre compte i el nom del repositori, amb aquest format: `USUARI_DOCKERHUB/p02-gallery:versió`. Pots crear el repositori `p02-gallery` des del web de Docker Hub abans de pujar la imatge, o deixar que es creï automàticament amb el primer `docker push`, si el teu compte té permís per fer-ho. En aquest cas, s’hi aplica la configuració de privacitat predeterminada del compte. Substitueix `USUARI_DOCKERHUB` pel teu nom d’usuari. [Consulta la documentació de Docker Hub sobre el primer `push`](https://docs.docker.com/docker-hub/quickstart/).

Hi ha dues maneres d’obtenir una imatge amb aquest nom:

### Opció A: construir-la directament amb el nom de Docker Hub

En lloc de construir primer `p02-gallery:1.0`, posa el nom complet del repositori a l’opció `-t`:

```bash
docker build -f gallery/Dockerfile \
  -t USUARI_DOCKERHUB/p02-gallery:1.0 gallery
```

Amb aquesta opció, si vols que Compose faci servir aquesta imatge local, posa `APP_IMAGE=USUARI_DOCKERHUB/p02-gallery:1.0` al fitxer `.env`.

### Opció B: etiquetar la imatge local que ja has construït

Si ja tens la imatge local `p02-gallery:1.0`, afegeix-li una etiqueta amb el nom de Docker Hub:

```bash
docker tag p02-gallery:1.0 USUARI_DOCKERHUB/p02-gallery:1.0
```

Amb aquesta opció pots deixar `APP_IMAGE=p02-gallery:1.0` al `.env`, perquè l’etiqueta local original continua disponible.

`docker tag` no torna a construir ni duplica la imatge: crea un altre nom que apunta a la mateixa imatge local. Pots comprovar els noms amb `docker image ls`.

### Iniciar sessió i publicar

Per fer `push` a un repositori del teu compte, Docker necessita autenticar-te. Executa `docker login` i segueix les instruccions que apareixen al terminal o al navegador. Normalment Docker conserva l’autenticació; repeteix el login si t’has desconnectat o si `push` retorna un error d’autorització. No escriguis la contrasenya directament dins de la comanda.

Un cop iniciada la sessió, publica la imatge amb l’etiqueta del teu compte:

```bash
docker push USUARI_DOCKERHUB/p02-gallery:1.0
```

Per baixar una imatge pública amb `docker pull` no cal iniciar sessió. Per baixar-ne una de privada, sí. `pull` baixa la imatge amb el nom indicat; no canvia l’etiqueta ni reanomena una imatge local.

L’etiqueta `1.0` identifica aquesta versió. Quan publiquis canvis, crea una etiqueta nova, per exemple `1.1`; no reutilitzis una etiqueta per a codi diferent. No posis contrasenyes dins de la imatge ni del Dockerfile.

Per baixar i executar una imatge publicada, es pot fer servir `docker pull` i `docker run`. En aquest projecte, però, farem servir Compose perquè l’aplicació també necessita MySQL.

## 3. Variables d’entorn i fitxer `.env`

Compose necessita les credencials de MySQL per inicialitzar el servei i configurar l’aplicació. Les guardarem en un fitxer `.env` local, que Docker Compose pot llegir per substituir les variables `${...}` del YAML. El repositori inclou `.env.example` com a plantilla; no inclourem el `.env` real al repositori.

Des de l’arrel del repositori:

```bash
cp deploy/docker/.env.example deploy/docker/.env
```

Edita `deploy/docker/.env` i canvia les contrasenyes d’exemple. Protegeix el fitxer perquè només el teu usuari el pugui llegir:

```bash
chmod 600 deploy/docker/.env
```

A Compose, `MYSQL_DATABASE`, `MYSQL_USER` i `MYSQL_PASSWORD` configuren MySQL. El servei web rep els mateixos valors com a `DB_NAME`, `DB_USER` i `DB_PASSWORD`; `DB_HOST=db` indica que la base de dades és el servei anomenat `db`, no `localhost`.

## 4. Docker Compose: aplicació i base de dades

Docker Compose descriu diversos serveis en un únic fitxer. En aquest projecte:

La xarxa `interna` és aïllada de les interfícies de la màquina. `web` i `db` hi comparteixen la comunicació privada; per això l’aplicació es connecta a MySQL amb el nom de servei `db`. Com que el web també ha de rebre peticions al port publicat, `web` s’uneix a `web_access`, una xarxa bridge normal. `db` no s’uneix a aquesta segona xarxa ni publica cap port: només `web` és accessible des de l’amfitrió.

| Servei o recurs | Funció |
|---|---|
| `web` | Imatge PHP/Apache de la galeria; és a `interna` per parlar amb MySQL i a `web_access` per rebre peticions. |
| `db` | Servidor MySQL 8.4; no publica el port 3306 a l’amfitrió. |
| `interna` | Xarxa Docker aïllada que connecta `web` amb `db`; MySQL només és accessible des d’aquesta xarxa. |
| `web_access` | Xarxa bridge normal connectada només a `web`, necessària perquè l’aplicació es pugui publicar a l’amfitrió. |
| `gallery_db_data` | Volum persistent amb els fitxers de dades de MySQL. |
| `gallery_uploads` | Volum persistent amb les imatges de `public/img/`, incloses les pujades des de l’aplicació. |

La regla `8080:80` publica el port 80 del contenidor web com a port 8080 de la màquina. Sense una adreça d’amfitrió explícita, Docker l’enllaça a totes les interfícies de xarxa; per limitar-lo a la màquina local, es pot escriure `127.0.0.1:8080:80`.

Arrenca els serveis des de l’arrel del repositori:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml up -d
```

`up` crea la xarxa, els volums i els contenidors que calguin. `-d` deixa’ls executant-se en segon pla. Comprova l’estat:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml ps
```

Obre [http://localhost:8080](http://localhost:8080). A EC2, configura deliberadament les regles d’accés públic al web; no s’ha d’obrir MySQL a Internet.

Consulta els registres quan hi hagi errors:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml logs web db
```

## 5. Inicialització automàtica de MySQL

**Inicialitzar** una base de dades vol dir crear-ne les taules i carregar les dades inicials quan el directori de dades encara és buit. La imatge oficial de MySQL executa automàticament els fitxers SQL muntats a `/docker-entrypoint-initdb.d/` durant la primera inicialització.

Compose munta aquests scripts:

```text
deploy/docker/db/init/
├── 01-estructura.sql
└── 02-dades.sql
```

`01-estructura.sql` crea la taula `images`; `02-dades.sql` insereix les imatges inicials de la galeria. Els noms numerats fan explícit l’ordre d’execució. Els fitxers corresponen a l’esquema de `gallery/gallery.sql`.

Els scripts d’inicialització **només s’executen quan MySQL troba un directori de dades nou i buit**. Després de la primera arrencada, les dades són al volum `gallery_db_data`; afegir o modificar un fitxer d’inicialització no canvia una base ja creada. Per això, les actualitzacions d’esquema es fan amb migracions, no tornant a inicialitzar.

Comprova que existeixen la taula i les dades inicials:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" "$MYSQL_DATABASE" -e "SHOW TABLES; SELECT COUNT(*) FROM images;"'
```

La comanda executa el client MySQL dins del contenidor `db`. `MYSQL_PWD` utilitza la variable que Compose ja ha injectat al contenidor, i així no cal escriure la contrasenya directament a la comanda.

### Comprovar la persistència

Per verificar que el volum conserva dades, afegeix una fila de prova mitjançant el client MySQL:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" "$MYSQL_DATABASE"'
```

Al prompt `mysql>` executa:

```sql
INSERT INTO images (url, title, description)
VALUES ('prova.jpg', 'Prova de persistència', '');
SELECT COUNT(*) FROM images;
```

Surt del client amb `exit`, reinicia el servei i comprova que el recompte continua sent 7:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml restart db
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" "$MYSQL_DATABASE" -e "SELECT COUNT(*) FROM images;"'
```

El volum de pujades `gallery_uploads` conserva els fitxers que es desen a `public/img/`. Reiniciar o recrear `web` no els esborra. Aturar la pila sense eliminar dades es fa així:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml down
```

No afegeixis `-v`: aquesta opció esborra els volums del projecte.

## 6. Migracions SQL: canviar la base de dades sense esborrar-la

Una **migració** és un fitxer SQL que transforma l’esquema d’una base de dades existent i en conserva les files. No és el mateix que inicialitzar una base nova. Les migracions d’aquest material són SQL escrit per l’alumnat i s’executen manualment; no fem servir cap eina externa.

A la versió 1.0, `images.title` és `VARCHAR(255)`. L’exemple de migració 1.1 amplia el camp a 512 caràcters:

```sql
ALTER TABLE images
MODIFY COLUMN title VARCHAR(512) NOT NULL;
```

Aquest SQL és al fitxer `deploy/docker/db/migrations/001_ampliar_titol.sql`. És compatible amb les dades existents perquè amplia la mida disponible i no elimina ni reinicialitza files.

Executa la migració des de l’arrel del repositori:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec -T db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" exec mysql -u "$MYSQL_USER" "$MYSQL_DATABASE"' \
  < deploy/docker/db/migrations/001_ampliar_titol.sql
```

L’opció `-T` desactiva el terminal interactiu perquè la comanda pugui rebre l’entrada redirigida. La redirecció `<` llegeix el fitxer SQL **a la màquina on executes la comanda** i n’envia el contingut a MySQL dins del contenidor. No cal copiar el fitxer dins del contenidor.

Després comprova l’esquema i les dades:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" "$MYSQL_DATABASE" -e "DESCRIBE images; SELECT COUNT(*) FROM images;"'
```

Aplica cada migració una sola vegada i anota quines s’han executat. No esborris ni recreïs `gallery_db_data` per actualitzar l’estructura: això podria eliminar les dades. No tornis a executar els scripts de `init/` sobre una base existent.

## 7. Actualitzar l’aplicació: versió 1.0 a 1.1

L’actualització té dues parts: publicar una nova imatge de l’aplicació i, si ha canviat l’esquema, executar la migració SQL corresponent. Prova el canvi primer en local o en un entorn de proves amb una còpia de la base de dades( si és necessari)

### Etiquetes de versió: `1.0`, `1.1` i `latest`

Una etiqueta identifica una versió de la imatge. Per als desplegaments, fes servir una etiqueta explícita, com `1.0` o `1.1`, i mantén-la al fitxer `.env`. Així queda clar quin codi s’ha desplegat i és més fàcil repetir o revertir una publicació.

Si omets l’etiqueta d’una imatge, Docker assumeix `latest`. Per exemple, `USUARI_DOCKERHUB/p02-gallery` equival a `USUARI_DOCKERHUB/p02-gallery:latest`. `latest` **no vol dir que Docker detecti automàticament quina versió és més nova**: és només una etiqueta que apunta a la última imatge publicadaica amb aquest nom.

Per fer proves, es pot construir i pujar la imatge sense escriure una etiqueta. Aquestes comandes fan servir `latest` per defecte:

```bash
docker build -f gallery/Dockerfile -t USUARI_DOCKERHUB/p02-gallery gallery
docker push USUARI_DOCKERHUB/p02-gallery
```

Posa `APP_IMAGE=USUARI_DOCKERHUB/p02-gallery` al `.env`; Compose farà servir `latest`. Després de publicar una imatge nova amb aquesta etiqueta, baixa-la i recrea el contenidor web perquè l’aplicació en execució la utilitzi:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml pull web
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml up -d --no-deps web
```

`pull` baixa la imatge, però no canvia el contenidor que ja està en execució; `up` recrea només `web` amb la imatge baixada. En un desplegament estable, és preferible posar una etiqueta explícita, com `1.1`, a `APP_IMAGE`: així queda registrat quin codi s’ha desplegat i es pot repetir o revertir la versió amb més facilitat.

1. Fes un canvi petit a l’aplicació existent per identificar la versió 1.1, per exemple actualitza el títol de la pàgina a `gallery/src/views/home.php`. Crea també el fitxer de migració SQL que necessiti l’esquema; `001_ampliar_titol.sql` n’és l’exemple.
2. Construeix i publica una imatge nova amb una etiqueta nova:

```bash
docker build -f gallery/Dockerfile -t USUARI_DOCKERHUB/p02-gallery:1.1 gallery
docker push USUARI_DOCKERHUB/p02-gallery:1.1
```

Si també vols oferir `latest` com a àlies de la publicació més recent, després de construir la imatge 1.1 pots afegir-li aquesta etiqueta i pujar-la:

```bash
docker tag USUARI_DOCKERHUB/p02-gallery:1.1 USUARI_DOCKERHUB/p02-gallery:latest
docker push USUARI_DOCKERHUB/p02-gallery:latest
```

Al servidor, mantén `APP_IMAGE=USUARI_DOCKERHUB/p02-gallery:1.1`. Si més endavant `latest` apunta a una altra imatge, el servidor continuarà indicant exactament quina versió té configurada.

3. Abans de migrar a l’entorn desplegat, fes una còpia de seguretat. Aquesta comanda desa el bolcat SQL a la màquina on s’executa (per exemple, l’EC2):

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec -T db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" exec mysqldump --no-tablespaces -u "$MYSQL_USER" "$MYSQL_DATABASE"' \
  > backup-$(date +%F-%H%M).sql
```

`--no-tablespaces` evita que `mysqldump` intenti exportar definicions de tablespaces, operació que requereix el privilegi `PROCESS`. Per a aquesta base de dades de l’aplicació no cal concedir aquest privilegi addicional.

Guarda la còpia en un lloc protegit, fora del repositori.

4. Canvia `APP_IMAGE` a `USUARI_DOCKERHUB/p02-gallery:1.1` al fitxer `.env` de l’entorn desplegat. Baixa la imatge i executa la migració compatible amb la versió anterior:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml pull web
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec -T db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" exec mysql -u "$MYSQL_USER" "$MYSQL_DATABASE"' \
  < deploy/docker/db/migrations/001_ampliar_titol.sql
```

5. Actualitza només el contenidor web:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml up -d --no-deps web
```

`pull web` i `up -d --no-deps web` pressuposen que el servei `web` utilitza una imatge publicada i que `.env` ja apunta a l’etiqueta 1.1. `--no-deps` evita recrear MySQL. El volum `gallery_db_data` no s’elimina ni es recrea.

6. Comprova l’aplicació, els logs i que les files antigues continuen presents:

```bash
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml ps
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml logs --tail=100 web db
docker compose --env-file deploy/docker/.env -f deploy/docker/compose.yaml exec db \
  sh -c 'MYSQL_PWD="$MYSQL_PASSWORD" mysql -u "$MYSQL_USER" "$MYSQL_DATABASE" -e "SELECT COUNT(*) FROM images;"'
```

## Errors habituals

- **La galeria no connecta amb MySQL:** a Compose, el host és `db`, no `localhost`. Comprova les variables de `.env` i `docker compose logs web db`.
- **La base de dades no és accessible des de fora:** és intencionat; `db` no publica el port 3306 i només pertany a `interna`. L’aplicació hi arriba pel nom de servei `db`.
- **La web no respon al port 8080:** comprova que `web` està connectat a `web_access`, que el contenidor està actiu amb `docker compose ps` i que has aplicat els canvis amb `docker compose up -d`.
- **Els scripts d’inicialització no s’han executat:** comprova els logs de `db` i que el volum era nou la primera vegada. En un volum ja inicialitzat no s’executen de nou.
- **He canviat un fitxer d’`init/` però la taula no canvia:** és normal; aplica una migració SQL contra la base existent.
- **Les pujades d’imatges fallen:** comprova que la imatge s’ha reconstruït amb el `chown` i que Compose munta `gallery_uploads` a `/var/www/html/img`.
- **He perdut dades en aturar Compose:** evita `docker compose down -v` i no eliminis `gallery_db_data`.
- **La migració falla perquè ja s’havia aplicat:** no la tornis a executar; revisa  l’esquema amb `DESCRIBE images;`.

Autors: Dani Prados dprados@cendrassos.net i Sílvia Lladó sllado1@cendrassos.net
