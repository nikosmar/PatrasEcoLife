# PatrasEcoLife
Web Dev university project

## Setup Instructions
### for Arch Linux / Manjaro

1) Uncomment (delete ';') the following lines in /etc/php/php.ini
```
;extension=pdo_mysql
;extension=mysqli
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

## Upload

Create the table as below:  

```sql
CREATE TABLE upload (
    id INT AUTO_INCREMENT,
    PRIMARY KEY (id),
    username VARCHAR(128),
    file_path VARCHAR(100),
    FOREIGN KEY (username) REFERENCES users(username)
);
```

Edit the below variables in the php.ini file:

```bash
upload_max_filesize = 1G;
post_max_size = 1G;
```
