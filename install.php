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
	last_login_ip bigint,
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
	category char(20) not null,
	url varchar(100) not null,
	summary varchar(600) not null,
	markdown varchar(30000),
	html varchar(30000),
	tags varchar(100),
	primary key(`id`),
	index(`category`),
	unique(`url`),
	index(`tags`),
	created_at timestamp,
	updated_at timestamp
) default charset=utf8 engine=innodb;
CREATE;

$create_table['category'] = <<<CREATE
create table if not exists category(
	id int not null auto_increment,
	title char(30) not null,
	categoryId char(30) not null,
	postNum int  not null default 0,
	postList varchar(500),
	desp char(180) not null,
	created_at timestamp,
	updated_at timestamp,
	primary key(`id`),
	unique(`categoryId`)
 ) default charset=utf8 engine=innodb;
CREATE;

$create_table['comments'] = <<<CREATE
create table if not exists comments(
	id int not null auto_increment,
	postId int not null,
	name char(20),
	email varchar(100),
	gravatar char(32),
	content varchar(1000),
	created_at timestamp,
	updated_at timestamp,
	primary key(`id`),
	index(`postId`)
) engine=innodb default charset=utf8;
CREATE;

$create_table['siteInfo'] = <<<CREATE
create table if not exists siteInfo(
	id int not null auto_increment,
	meta char(15) not null,
	val varchar(200) not null,
	created_at timestamp,
	updated_at timestamp,
	primary key(`id`),
	unique(`meta`)
)engine=innodb default charset=utf8;
CREATE;

$create_table['feed'] = <<<CREATE
create table if not exists feed(
	id int not null auto_increment,
	name varchar(20),
	email varchar(50),
	gravatar char(32),
	content varchar(1000) not null,
	isread tinyint not null default 0,
	created_at timestamp,
	updated_at timestamp,
	primary key(`id`)
)engine=innodb default charset=utf8;
CREATE;

$create_table['visitors'] = <<<CREATE
create table if not exists visitors(
	id int not null auto_increment,
	page varchar(200) not null,
	referer varchar(1000),
	remote_ip bigint,
	user_agent varchar(200),
	time timestamp,
	created_at timestamp,
	updated_at timestamp,
	primary key(`id`)
)engine=innodb default charset=utf8;
CREATE;

$create_table['message'] = <<<CREATE
create table if not exists message(
	id int not null auto_increment,
	name varchar(20) not null,
	email varchar(100) not null,
	gravatar char(32) not null,
	msgbody varchar(3000) not null,
	isread tinyint not null,
	created_at timestamp,
	updated_at timestamp,
	primary key(`id`)
) engine = innodb default charset=utf8;
CREATE;

foreach ($create_table as $table => $sql) {
	echo "Creating table $table ............................";
	$pdo->query( $sql );
	echo "Done!\n";
}
$pdo->query( "SET NAMES UTF8" );
$now = date("Y-m-d H:i:s");
$insert['users'] = 'insert ignore into users(`name`,`sname`,`email`,`role`,`passwd`,`created_at`, `updated_at`, `last_login_at`, `last_login_ip`) values("longmon","lOngmon Hau","1307995200@qq.com",1, "'.password_hash("kiss", PASSWORD_DEFAULT).'", now(),now(),now(),'.ip2long("127.0.0.1").');';
$insert['category'] = 'insert ignore into category(`title`,`categoryId`,`postNum`,`desp`,`created_at`,`updated_at`) values("时光之里","default",0,"default category",now(),now())';
$insert['siteInfo1'] = 'insert ignore into siteInfo(`meta`,`val`) values("site_name","longmon Hau")';
$insert['siteInfo2'] = 'insert ignore into siteInfo(`meta`,`val`) values("site_domain","zxfc.in")';
$insert['siteInfo3'] = 'insert ignore into siteInfo(`meta`,`val`) values("site_copyright","Copyright(c)2015-2016")';
echo "\n";
foreach ($insert as $name => $sql) 
{
	echo "Insert into $name ..................";
	$in = $pdo->query( $sql );
	echo "Done\n";
}


if( !is_dir("app/log") )
{
	mkdir("app/log",0777,true);
	chmod('app/log', 0777);
}
