FROM php:8.5-apache

# Instal·la el controlador PDO que necessita l'aplicació per connectar-se a MySQL.
RUN docker-php-ext-install pdo_mysql

# Apache publica el contingut de public/ com a document root.
COPY public/ /var/www/html/

# El framework didàctic queda fora del document root i s'inclou des de ../src/.
COPY src/ /var/www/src/

# L'aplicació desa les imatges pujades a public/img/; Apache s'executa com www-data.
# Per tant, cal que aquest directori sigui propietat de www-data.
# Si no es fa això, Apache no podrà escriure-hi i l'aplicació fallarà, no pujarà les imatges
RUN chown www-data:www-data /var/www/html/img
