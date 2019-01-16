# Minimal Petitions Application

An application used to create petitions. 

Real time voting results using long polling.

# Setup Database

Create a database.

Navigate to the directory of the app and execute on command line:

mysql -u root -p (database_name) < petitions.mysql

# Setup Application

Complete database credentials in file config.php.

Example:
```
define('DBMS', 'mysql');
define('HOST', 'localhost');
define('DATABASE', 'petition');
define('USER', 'database_user');
define('PASSWORD', 'database_password');
```

# TODO 

Create a minimal mvc framework to setup the application on

That will make setting up and extending the application much easier