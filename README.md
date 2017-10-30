# API

Existe un único metodo, el cual recibe una lista de productos y devuelve un presupuesto

Se debe enviar una solicitud `HTTP POST` a la URL `/solicitar-presupuesto`. La solicitud debe incluir en el `body` un array de productos en formato `JSON`, por ejemplo:

```JSON
[
  {
    "name": "puerta",
    "amount": 1
  },
  {
    "name": "ventana",
    "amount": 2
  }
]
```

Esto da como resultado un nuevo array con los productos, para los cuales se incluye ademas un campo de precio:

```JSON
[
    {
        "name": "puerta",
        "amount": 1,
        "price": 1275.92,
        "total": 1275.92
    },
    {
        "name": "ventana",
        "amount": 2,
        "price": 3845.25,
        "total": 7690.5
    }
]
```
# Instalación

1. Se agrega un <Virtual-Host> al servidor de Apache2, en este ejemplo la API se registra en el dominio `api-proveedores.com`

Crear el archivo `/etc/apache2/sites-available/api-proveedores.com` con el contenido:

```xml
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    ServerName api-proveedores.com
    ServerAlias www.api-proveedores.com
    DocumentRoot /var/www/html/api-proveedores.com

    <Directory /var/www/html/api-proveedores.com/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
  </VirtualHost>
```

2. Ejecutar los comandos en una terminal:

```
a2enmod rewrite

a2disite 000-default.conf

a2ensite api-proveedores.com
```

3. Agregar en hosts: `/etc/hosts`

```
127.0.0.1 api-proveedores.com
```
