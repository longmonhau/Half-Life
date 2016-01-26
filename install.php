<?php

$dsn = 'mysql:host=127.0.0.1;dbname=halflife;';

$pdo = new PDO( $dsn , "longmon", "123456" );

$create_table['users'] = <<<CREATE
create table  if not exists users(
	id int not null auto_increment,
	name char(24) not null,
	sname char(24) not null,
	email varchar(100) not null,
	role tinyint not null default 1,
	passwd char(60) not null,
	created_at timestamp,
	updated_at timestamp,
	last_login_at timestamp,
	last_login_ip long,
	primary key(`id`),
	unique(`name`),
	unique(`sname`),
	unique(`email`)) default charset=utf8 engine=innodb;
CREATE;
$create_table['posts'] = <<<CREATE
create table  if not exists posts(
	id int not null auto_increment,
	top tinyint not null default 0,
	public tinyint not null default 1,
	title varchar(200) not null,
	category tinyint not null default 0,
	url varchar(100) not null,
	summary varchar(600) not null,
	primary key(`id`),
	index(`category`),
	unique(`url`),
	created_at timestamp,
	updated_at timestamp
) default charset=utf8 engine=innodb;
CREATE;

foreach ($create_table as $table => $sql) {
	echo "Creating table $table ............................";
	$pdo->query( $sql );
	echo "Done!\n\n";
}

$now = date("Y-m-d H:i:s");
$insert = 'insert ignore into users(`name`,`sname`,`email`,`role`,`passwd`,`created_at`, `updated_at`, `last_login_at`, `last_login_ip`) values("longmon","lOngmon Hau","1307995200@qq.com",1, "'.password_hash("kiss", PASSWORD_DEFAULT).'", now(),now(),now(),'.ip2long("127.0.0.1").');';

$in = $pdo->query( $insert );

if( !is_dir("app/log") )
{
	mkdir("app/log",0777,true);
}
