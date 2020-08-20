# PatrasEcoLife
Web Dev university project

## Setup Instructions
### for Arch Linux / Manjaro

1) Uncomment the following lines in /etc/php/php.ini
```
;extension=pdo_mysql
;extension=mysqli
```
2) Mariadb/mysql service has to be running so that php web server can access it. Start it with:

`$ systemctl start mariadb.service`

3) cd to src directory and start php web server to emulate http communication:

`$ php -S localhost:8000`
