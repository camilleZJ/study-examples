<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|
|			'mysql' (deprecated), 'sqlsrv' and 'pdo/sqlsrv' drivers accept TRUE/FALSE
|			'mysqli' and 'pdo/mysql' drivers accept an array with the following options:
|
|				'ssl_key'    - Path to the private key file
|				'ssl_cert'   - Path to the public key certificate file
|				'ssl_ca'     - Path to the certificate authority file
|				'ssl_capath' - Path to a directory containing trusted CA certificats in PEM format
|				'ssl_cipher' - List of *allowed* ciphers to be used for the encryption, separated by colons (':')
|				'ssl_verify' - TRUE/FALSE; Whether verify the server certificate or not ('mysqli' only)
|
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['ssl_options']	Used to set various SSL options that can be used when making SSL connections.
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = 'default';  //
$query_builder = TRUE;   //开启查询构造器，启用设成 TRUE，禁用设成 FALSE，默认是 TRUE

//系统默认数据库配置$this->db,若想默认是自己定义的则要把$active_group的只修改为自己定义的$active_group = 'test';
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => 'root',
	'database' => 'news',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',  //表前缀，当使用 查询构造器 查询时，可以选择性的为表加个前缀
	'pconnect' => FALSE,  //是否使用持续连接boolean
	'db_debug' => (ENVIRONMENT !== 'production'),  //是否显示数据库错误信息boolean
	'cache_on' => FALSE,  //是否开启数据库查询缓存boolean
	'cachedir' => '',  //数据库查询缓存目录所在的服务器绝对路径
	'char_set' => 'utf8',  //与数据库通信时所使用的字符集
	'dbcollat' => 'utf8_general_ci',  //与数据库通信时所使用的字符规则,只适用于 'mysql' 和 'mysqli' 数据库驱动
	'swap_pre' => '',  //替换默认的 dbprefix 表前缀，该项设置对于分布式应用是非常有用的， 你可以在查询中使用由最终用户定制的表前缀。
	'encrypt' => FALSE,  //是否使用加密连接
	'compress' => FALSE, //是否使用客户端压缩协议（只用于MySQL）boolean
	'stricton' => FALSE,  //是否强制使用 "Strict Mode" 连接, 在开发程序时，使用 strict SQL 是一个好习惯
	'failover' => array(),  //故障转移数据库配置，array(array(数据库配置)，array(数据库配置)...),若是该数据库调用时发生故障，可以使用配置的故障转移里的数据库
	'save_queries' => TRUE   //开启数据库查询分析器，FALSE关闭，默认开启，也可以在config/profiler.php中配置也可以在控制器里调用分析器的方法进行开启或关闭
);

//自己添加的数据库配置$this->test_db
$db['order_db'] = array(
		'dsn'	=> '',
		'hostname' => 'localhost',
		//'port'	=> 3306,
		'username' => 'root',
		'password' => 'root',
		'database' => 'orders',
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => (ENVIRONMENT !== 'production'),   //ENVIRONMENT是开发环境，取值：development、testing、production
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8',
		'dbcollat' => 'utf8_general_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
);
//以上连接的是同一数据库位置同一账号下的不同数据库，其实没必要再配置一遍，使用时$this->db->db_select($database2_name);即可

/*除了上面的配置也可以在使用的时候配置：
$config['hostname'] = 'localhost';
$config['username'] = 'myusername';
$config['password'] = 'mypassword';
$config['database'] = 'mydatabase';............
$this->load->database($config);
 */
