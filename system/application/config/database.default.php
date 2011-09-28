<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|   ['hostname'] The hostname of your database server.
|   ['username'] The username used to connect to the database
|   ['password'] The password used to connect to the database
|   ['database'] The name of the database you want to connect to
|   ['dbdriver'] The database type. ie: mysql.  Currently supported:
                 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|   ['dbprefix'] You can add an optional prefix, which will be added
|                to the table name when using the  Active Record class
|   ['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|   ['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|   ['cache_on'] TRUE/FALSE - Enables/disables query caching
|   ['cachedir'] The path to the folder where cache files should be stored
|   ['char_set'] The character set used in communicating with the database
|   ['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "mysql";
$active_record = TRUE;

// configure access to your existing manaserv database!

//sqlite
$db['sqlite']['hostname'] = "localhost";
$db['sqlite']['username'] = "bob";
$db['sqlite']['password'] = "secret";
$db['sqlite']['database'] = "sqlite:/link/to/your/database/mana.db";
$db['sqlite']['dbdriver'] = "pdo";
$db['sqlite']['dbprefix'] = "";
$db['sqlite']['pconnect'] = FALSE;
$db['sqlite']['db_debug'] = TRUE;
$db['sqlite']['cache_on'] = FALSE;
$db['sqlite']['cachedir'] = "";
$db['sqlite']['char_set'] = "utf8";
$db['sqlite']['dbcollat'] = "utf8_general_ci";

//mysql
$db['mysql']['hostname'] = "localhost";
$db['mysql']['username'] = "bob";
$db['mysql']['password'] = "secret";
$db['mysql']['database'] = "mysql:dbname=manawebdb";
$db['mysql']['dbdriver'] = "pdo";
$db['mysql']['dbprefix'] = "";
$db['mysql']['pconnect'] = FALSE;
$db['mysql']['db_debug'] = TRUE;
$db['mysql']['cache_on'] = FALSE;
$db['mysql']['cachedir'] = "";
$db['mysql']['char_set'] = "utf8";
$db['mysql']['dbcollat'] = "utf8_general_ci";


/* End of file database.php */
/* Location: ./system/application/config/database.php */
