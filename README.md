# YABS

yabs is yet another blog system written in php.

## Installation

* Copy all files to the webserver

* Copy `/app/config.php.default` to `/app/config.php`

* Adjust `config.php` values

    * `$config['database']` set 
        * In `dsn` set `dbname` to your database name
        * `username` to your database user
        * `password` to your database password
		* Only change the other settings if you know what you are doing.

    * `$config['uri']`
        * `scheme` is `http` or `https`
        * `host` is your sites domain or `null`
        * `port` set to your sites port number, if you use a different port then the default http or https port.
        * `path` directory path you placed yabs in
        * `script` set to `true` if mod_rewrite doesn't work. I that case `index.php` will be added to all URIs.

* Execute `install/mysql.sql` on you database server

* Make sure the webserver user can write tot he following folders
    * `cache`
    * `cache/HTMLPurifier`
    * `images`

Congratulation, the installation is complete.

## Administration

Go to `yoursite/admin` to log in. The default username is **Admin** and the default password is **password**. Change this as soon as possible.