# PatrasEcoLife
Web Dev university project

## Setup Instructions
### for Arch Linux / Manjaro

1) Uncomment (delete ';') the following lines in /etc/php/php.ini
```
;extension=pdo_mysql
;extension=mysqli
```
2) Start mariadb/mysql service so that php web server can access the site's database.

`$ systemctl start mariadb.service`

3) cd to src directory and start php web server to emulate http communication:

`$ php -S localhost:8000`
