<?
exec("sudo openssl req -x509 -nodes -days 365000 -newkey rsa:2048 -keyout /etc/apache2/ssl/apache.key -out /etc/apache2/ssl/apache.crt");
