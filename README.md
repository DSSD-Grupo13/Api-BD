 # API de Incidentes

  Esta API consiste en un webservice REST que brinda un servicio de solicitud de presupuestos

  Esta implementado utilizando las tecnologias PHP, Apache y el framework Slim

 # Endpoints

 *
 *
 *

  # Instalación

1. Se agrega un <Virtual-Host> al servidor de Apache2, en este ejemplo la API se registra en el dominio `api-incidentes.com`

  Crear el archivo `/etc/apache2/sites-available/api-incidentes.com.conf` con el contenido:

  ```xml
  <VirtualHost *:80>
      ServerAdmin webmaster@localhost
      ServerName api-incidentes.com
      ServerAlias www.api-incidentes.com
      DocumentRoot /var/www/html/api-incidentes.com

      <Directory /var/www/html/api-incidentes.com/>
          Options Indexes FollowSymLinks
          AllowOverride All
          Require all granted
      </Directory>
    </VirtualHost>
  ```

  `Nota: El directorio /var/www/html/api-incidentes.com debe existir`

2. Ejecutar los comandos en una terminal (los dos primeros pueden no ser necesarios si ya se ejecutaron alguna vez):

  ```
  sudo a2enmod rewrite

  sudo a2dissite 000-default.conf

  sudo a2ensite api-incidentes.com
  ```

3. Agregar en hosts: `/etc/hosts`

  ```
  127.0.0.1 api-incidentes.com
  ```

4. Luego reiniciar el servicio de Apache

 ```
   systemctl restart apache2
```