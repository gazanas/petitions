# Minimal Petitions Application

An application used to create petitions. This application is built upon a minimal mvc framework I built.

Real time voting results using long polling.

# Setup Database

Create a database.

Navigate to the directory of the app and execute on command line:

mysql -u <database_user> -p <database_password> <database_name> < petitions.mysql

# Setup Application

Complete database credentials in file config.php.

Example:
```
define('TIMEZONE', 'Europe/London');
define('DBMS', 'mysql');
define('HOST', 'localhost');
define('DATABASE', 'petition');
define('USER', 'database_user');
define('PASSWORD', 'database_password');
```

# TODO

Database migrations system
