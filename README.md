# PatrasEcoLife
Web Dev university project

## Setup Instructions
### for Arch Linux / Manjaro

1) Uncomment (delete ';') the following line in /etc/php/php.ini
```
;extension=mysqli
```
and change these:
```
upload_max_filesize = 1G;
post_max_size = 1G;
```

2) Start mariadb/mysql service so that apache/php can access the site's database.

`# systemctl start mariadb.service`

3) Configure apache to run php scripts (see archwiki).

4) Configure apache to run html files as php scripts. Append the following line:

`AddType application/x-httpd-php .html` to `/etc/httpd/conf/httpd.conf`

5) Set apache server directory to src.

6) Start apache daemon:

`# systemctl start httpd.service`

7) Access PEL via http://localhost/
