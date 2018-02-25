<?php

// PHP Grid database connection settings, Only need to update these in new project
//$mysqli = new mysqli("localhost", "admin_user1", "BgTTirh9d1", "admin_dbtablero");
define("PHPGRID_DBTYPE","mysqli"); // mysql,oci8(for oracle),mssql,postgres,sybase
define("PHPGRID_DBHOST","localhost");
define("PHPGRID_DBUSER","admin_user1");
define("PHPGRID_DBPASS","BgTTirh9d1");
//define("PHPGRID_DBUSER","root");
//define("PHPGRID_DBPASS","");
define("PHPGRID_DBNAME","admin_dbtablero");

// Basepath for lib
define("PHPGRID_LIBPATH",dirname(__FILE__).DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR);