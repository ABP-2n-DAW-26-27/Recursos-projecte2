# Exemple de cookies i de docblock

Exemple de MVC
=============

Exemple de galeria amb MVC

M613 - Programació d'aplicacions web.
M614 - Desplegament d'aplicacions web


# DOCKER 

Per iniciar un contenidor docker amb Mysql 
``` 
docker run --name mysqlemeset -e MYSQL_DATABASE=galeriadaw -e MYSQL_USER=galeriadaw -e MYSQL_PASSWORD=daw2020 -e MYSQL_ROOT_PASSWORD=daw2020 -p 3306:3306 -d mysql:latest 
```
| Ordre           | Funció                                  | Estat del Contenidor | Consum de Recursos          |
|-----------------|------------------------------------------|-----------------------|------------------------------|
| `docker stop`   | Atura el contenidor completament         | Exited                | No consumeix CPU ni RAM     |
| `docker start`  | Inicia un contenidor aturat              | Up                    | Consumeix CPU i RAM         |
| `docker pause`  | Congela un contenidor                    | Paused                | Consumeix RAM, però no CPU  |
| `docker unpause`| Descongela un contenidor en pausa        | Up                    | Consumeix CPU i RAM         |

## Com crear una imatge de Docker i pujar-la al Docker hub

### 1. Crear una imatge a partir del Dockerfile
Per construir una imatge, assegura't que estàs en el mateix directori on es troba el teu fitxer Dockerfile. Aquest comandament llegeix les instruccions del fitxer i crea una nova imatge amb el nom que li indiquis.
``` 
docker build -t nom-de-la-teva-imatge . 
```

- ```docker build```: El comandament principal per iniciar el procés de construcció.

- ```-t nom-de-la-teva-imatge:tag```: El flag -t (de "tag") serveix per posar un nom i una etiqueta a la imatge. Pots posar un nom que t'ajudi a identificar-la, per exemple la-meva-aplicacio:1.0.

- ```.```: El punt final indica a Docker que el context de la construcció és el directori actual, on suposadament es troba el Dockerfile. Si volem que comenci que el punt de partida de les rutes del Dockerfile sigui un altre que l'actual hem de canviar aquest paràmetre. 

En el nostre cas des de la carpeta ```crud```
```bash 
docker build -t etapa2:1.0 .    
```
### 2. Posar la imatge en funcionament
Un cop la imatge està creada, pots iniciar un contenidor a partir d'ella amb el comandament ```docker run```

```bash 
docker run -d --name provasqllite -p 8080:80 etapa2:1.
```

### 3. Crear el repositori al Dockerhub
### 4. Pujar la imatge al Dockerhub
Si la imatge que hem creat amb el Docker build no es correspon amb el nom del repositori (username/*) en el meu cas ```sllado1/prova1:tagname```hem de canviar el nom de la imatge abans de poder-la pujar al repositori.
```bash
docker tag etapa2:1.1 sllado1/prova1:1.1-sqllite
 ```
I un cop la imatge es diu igual que el repositori ja es pot fer un push al repositori
```bash 
docker push sllado1/prova1:1.1-sqllite 
```

## Com utilitzar una imatge del Docker hub
Per utilitzar una imatge que hi ha al Docker hub
Si la imatge ja s'ha baixat prèviament no la tornarà  a baixar.
```bash 
docker run -d --name provasqllite -p 8080:80 sllado1/prova1:1.1-sqllite
```
## Com executar múltiples serveis (microserveis) amb Docker
El Docker està pensat per treballar amb microserveis, és a dir, quan l’aplicació necessita més d’un servei, com per exemple un servidor web i una base de dades MySQL, habitualment es creen dos contenidors Docker ('Dues màquines independents') que es comuniquen entre ells.

Perquè els contenidors es puguin veure, cal que estiguin dins de la mateixa xarxa Docker.

### 1. Crear una xarxa pròpia per als serveis
```bash
docker network create xarxa_web
```
### 2. Engegar el contenidor de mysql
És important indicar que la màquina mysql està dins de la xarxa_web
```bash
docker run --name mysqlemeset --network xarxa_web -p 3306:3306 -e MYSQL_DATABASE=galeriadaw -e MYSQL_USER=galeriadaw -e MYSQL_PASSWORD=daw2020 -e MYSQL_ROOT_PASSWORD=daw2020  -d mysql:latest 
```
- ```--network```: Afegeix el contenidor a la xarxa perquè es pugui comunicar amb els altres serveis.
- ```-d```: Mode detached, perquè s'executi en segon pla. Així es pot continuar utilitzant el mateix terminal.
- Fixa't que no hi ha el paràmetre ```-p```, ja que al tenir una xarxa interna no cal que exposi cap port a la màquina amfitriona.
- ⚠️ Al posar un nom fixe al contenidor només en podem tenir un amb aquest nom. Per tant, si el vol tornar a crear primer s'ha d'esborrar
```bash
docker rm -f mysqlemeset
```
Afegim un contenidor amb phpmyadmin per gestionar la bdd amb interfície gràfica
```bash 
docker run --name phpmyadminemeset --network xarxa_web -e PMA_HOST=mysqlemeset -e PMA_USER=galeriadaw -e PMA_PASSWORD=daw2020 -p 8080:80 -d phpmyadmin:latest
```
```--name phpmyadminemeset```: nom del contenidor.
```--network xarxa_web```: el posa dins la mateixa xarxa que el contenidor MySQL.
```-e PMA_HOST=mysqlemeset```: indica el host MySQL (el nom del contenidor mysql).
```-e PMA_USER i -e PMA_PASSWORD```: usuari i contrasenya per connectar-se a la bdd.
```-p 8080:80```: exposa phpMyAdmin al port 8080 del teu host → accés via http://localhost:8080
```-d ```: S'executa en segon pla, allibera la línia de comandes
```phpmyadmin:latest```: imatge oficial de phpMyAdmin al Docker hub.
### Quin és el cicle de vida de la base de dades del contenidor?
Tota la informació que s’escriu dins del contenidor (Bdd) es guarda dins del filesystem del propi contenidor. Això vol dir que mentre el contenidor existeixi, les dades es mantenen.
- Si el pares (docker stop), el contenidor queda aturat, però la informació encara hi és.
- Si el tornes a arrencar (docker start), trobaràs les dades tal com estaven.

⚠️ Quan les dades es perden és quan destrueixes el contenidor amb docker rm (o amb docker run --rm, que l’esborra automàticament quan s’atura).
En aquest cas, el sistema de fitxers associat al contenidor desapareix i amb ell totes les dades que no estaven en un volum o un bind mount.
#### Per evitar que es perdin les dades quan es destruieix un contenidor es poden fer volums
#####  Volum gestionat per Docker (recomanat per a ús normal)

És el més senzill i segur si només vols que les dades es mantinguin, sense preocupar-te de rutes locals.
```bash
docker run --name mysqlemeset --network xarxa_web -p 3306:3306 -e MYSQL_DATABASE=galeriadaw -e MYSQL_USER=galeriadaw -e MYSQL_PASSWORD=daw2020 -e MYSQL_ROOT_PASSWORD=daw2020 -v mysql_data:/var/lib/mysql -d mysql:latest
```


- Amb això, Docker crearà automàticament un volum anomenat mysql_data i el muntarà dins /var/lib/mysql (Ruta on mysql guarda les dades)
- Si destrueixes el contenidor (docker rm), el volum no s’esborra, i les dades segueixen disponibles.- Pots reutilitzar el volum amb un altre contenidor, només has d'afegir el paràmetre al docker run (-v mysql_data:/var/lib/mysql).

#####  Bind mount (recomanat per a desenvolupament o control directe)

Si vols veure o manipular els fitxers de la base de dades des del teu host, pots fer servir un bind mount:
```bash
docker run --name mysqlemeset --network xarxa_web -p 3306:3306 -e MYSQL_DATABASE=galeriadaw -e MYSQL_USER=galeriadaw -e MYSQL_PASSWORD=daw2020 -e MYSQL_ROOT_PASSWORD=daw2020 -v "D:\USUARI\SLLADO\DOCUMENTS\2625 DAW\Recursos-Projecte2\mvc\mysql_data:/var/lib/mysql" -d mysql:latest
```

- Aquí /ruta/local/mysql_data és una carpeta al teu host (p. ex. D:\USUARI\SLLADO\DOCUMENTS\2625 DAW\Recursos-Projecte2\mvc\mysql_data). *Consideracions* S'ha posat entre cometes perquè hi ha espais en blanc i també perquè al Docker no li agrada gaire l'ús de rutes windows /
- Les dades s’hi guarden físicament, i pots veure-les o fer-ne còpies.
- És molt útil per a desenvolupament o per tenir còpies de seguretat fàcilment.

### 3. Engegar el contenidor del servidor web (PHP + Apache)
Abans d'engegar el contenidor web, en el cas d'aquesta aplicació s'han de tenir en compte diversos aspectes.
- Canviar el fitxer config perquè es connecti a mysql enlloc de sqllite
- Canviar la connexió del fitxer dbini.php, perquè es el fitxer que crea la taula de nou.
- Canviar la configuració de connexió a la base de dades perquè es connecti al contenidor que hem engegat previament.
  - ⚠️ El host de connexió a MySQL ha de ser **mysqlemeset**, que és el nom del contenidor, no localhost ni 127.0.0.1
  - ⚠️ L'usuari i la contrasenya també han de ser les mateixes que el contenidor que has engegat
- Crear la imatge amb les configuracions anteriors
- Engegar el contenidor
```bash
docker run -d --name provamysql --network xarxa_web -p 8080:80 sllado1/prova1:1.1-mysql
```
## Bones pràctiques

- Utilitza variables d'entorn per evitar posar valors sensibles (usuari, password) al codi. Això permet reutilitzar la mateixa imatge en diferents entorns (dev, prod, test).
  - Es poden definir amb `-e`
  - En PHP es pot utilitzar la funció `getenv(variable)` per accedir a les variables d'entorn

- Utilitza un script en sql per inicialitzar la bdd la primera vegada que es crea el contenidor mysql
  - Busca informació de la Ruta màgica: `/docker-entrypoint-initdb.d/`

```bash
docker run --name mysql-exemple -e MYSQL_ROOT_PASSWORD=1234 -e MYSQL_DATABASE=galeria -e MYSQL_USER=usuari  -e MYSQL_PASSWORD=contrasenya  -v ruta_on_hi_ha_script_sql:/docker-entrypoint-initdb.d -d mysql:latest
```

Autors: Dani Prados dprados@cendrassos.net i Sílvia Lladó sllado1@cendrassos.net