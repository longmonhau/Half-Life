<?php
//*********************** 以下是编辑区 ***********************
date_default_timezone_set("Asia/Shanghai");
/**
 * 
 * 数据库配置
 * @var array
 */
$mysql = array(
	'driver'    	=> 'mysql',
    'host'      	=> 'localhost',
    'database'  	=> 'halflife',
    'username'  	=> 'root',
    'password'  	=> '123456',
    'charset'   	=> 'utf8',
    'collation' 	=> 'utf8_unicode_ci',
    'prefix' 		=> ''
);

/**
 * 站点设置
 * 这些配置可以登陆网站后台编辑
 * @var array
 */
$site = array(
	"site_name" 	=> 'Half-Life',//网站名称
	'site_domain'	=> 'longmonhau.com',//网站域名
	'site_desc'		=> 'Just a php blog program!'//网站简短描述
);

/**
 * 管理员设置
 * @var array
 */
$admin = array(
	'name'			=> 'admin',//管理员账号
	'sname'			=> 'admin',//管理员昵称，也是网站前台显示的名称
	'email'			=> '1307995200@qq.com',//邮箱
	'passwd'		=> 'DWI@D99##DJ'//登陆密码
);

//*************************** 编辑区到处为止 ************************

//*********************** 编辑以下区域可能导致错误，请慎重 *****************

$dsn = $mysql['driver'].':host='.$mysql['host'].';dbname='.$mysql['database'].';';

$pdo = new PDO( $dsn , $mysql['username'], $mysql['password'] );

$create_table['users'] = <<<CREATE
create table  if not exists users(
	id int not null auto_increment,
	name char(24) not null,
	sname char(24) not null,
	email varchar(100) not null,
	avatar varchar(200) not null,
	role tinyint not null default 1,
	passwd char(60) not null,
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00',
	last_login_at char(20),
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
	ptype enum("text","photo","music","video") default "text",
	public tinyint not null default 1,
	title varchar(200) not null,
	category char(20) not null,
	url varchar(100) not null,
	markdown text,
	html text,
	tags varchar(100),
	primary key(`id`),
	index(`category`),
	unique(`url`),
	index(`tags`),
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00'
) default charset=utf8 engine=innodb;
CREATE;

$create_table['category'] = <<<CREATE
create table if not exists category(
	id int not null auto_increment,
	title char(30) not null,
	categoryId char(30) not null,
	postNum int  not null default 0,
	postList varchar(500),
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00',
	primary key(`id`),
	unique(`categoryId`)
 ) default charset=utf8 engine=innodb;
CREATE;

$create_table['comments'] = <<<CREATE
create table if not exists comments(
	id int not null auto_increment,
	resp int not null default 0,
	postId int not null,
	name char(20),
	email varchar(100),
	gravatar varchar(200),
	content varchar(1000),
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00',
	primary key(`id`),
	index(`postId`)
) engine=innodb default charset=utf8;
CREATE;

$create_table['siteInfo'] = <<<CREATE
create table if not exists siteInfo(
	id int not null auto_increment,
	meta char(15) not null,
	val text(20000) not null,
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00',
	primary key(`id`),
	unique(`meta`)
)engine=innodb default charset=utf8;
CREATE;

$create_table['feed'] = <<<CREATE
create table if not exists feed(
	id int not null auto_increment,
	name varchar(20),
	email varchar(50),
	gravatar varchar(200),
	content varchar(1000) not null,
	isread enum("n","y") default "n",
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00',
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
	primary key(`id`)
)engine=innodb default charset=utf8;
CREATE;

$create_table['message'] = <<<CREATE
create table if not exists message(
	id int not null auto_increment,
	resp int not null default 0,
	name varchar(20) not null,
	email varchar(100) not null,
	gravatar varchar(200) not null,
	msgbody varchar(3000) not null,
	isread enum("n","y") default "n",
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00',
	primary key(`id`),
	index(`resp`)
)engine = innodb default charset=utf8;
CREATE;

$create_table['file'] = <<<CREATE
create table if not exists file(
	id int not null auto_increment,
	filename varchar(100) not null,
	filesize bigint not null,
	updated_at timestamp,
	created_at char(20) not null default '0000-00-00 00:00:00',
	primary key(`id`),
	unique(`filename`)
) engine=innodb default charset=utf8;
CREATE;

foreach ($create_table as $table => $sql) {
	echo "Creating table $table ............................";
	if (! $pdo->query( $sql ) )
	{
		$error = $pdo->errorInfo();
		exit($error[2]);
	} else 
	{
		echo "Done!\n";
	}	
}
$pdo->query( "SET NAMES UTF8" );
$now = date("Y-m-d H:i:s");
$insert['users'] = 'insert ignore into users(`name`,`sname`,`email`,`avatar`,`role`,`passwd`,`created_at`, `updated_at`, `last_login_at`, `last_login_ip`) values("'.$admin['name'].'","'.$admin['sname'].'","'.$admin['email'].'","http://www.gravatar.com/avatar/'.md5($admin['email']).'",1, "'.password_hash($admin['passwd'], PASSWORD_DEFAULT).'", now(),now(),now(),'.ip2long("127.0.0.1").');';
$insert['category'] = 'insert ignore into category(`title`,`categoryId`,`postNum`,`created_at`,`updated_at`) values("默认分类","default",0,now(),now())';
$insert['siteInfo1'] = 'insert ignore into siteInfo(`meta`,`val`,`created_at`) values("site_name","'.$site['site_name'].'",now())';
$insert['siteInfo2'] = 'insert ignore into siteInfo(`meta`,`val`,`created_at`) values("site_domain","'.$site['site_domain'].'",now())';
$insert['siteInfo3'] = 'insert ignore into siteInfo(`meta`,`val`,`created_at`) values("site_desc","'.$site['site_desc'].'",now())';
echo "\n";
foreach ($insert as $name => $sql)
{
	echo "Insert into $name ..................";
	if(!$pdo->query( $sql ) )
	{
		$err = $pdo->errorInfo();
		exit($err[2]);
	}
	echo "Done\n";
}

$database = "<?php\n return array(\n";
foreach ($mysql as $k => $v) 
{
	$database .= "'{$k}'=>'{$v}',\n";
}
$database .= ");";

if( !is_dir("static/uploads") )
{
	mkdir("static/uploads");
	chmod("static/uploads", 0777);
}
if( !is_dir("./Blog") )
{
	mkdir("./BLog");
	chmod("./Blog", 0777);
}

file_put_contents("app/config/database.php", $database);

echo "\n安装成功!\n";

echo "管理员账号: ", $admin['name'],"\n";

echo "管理员密码: ", $admin['passwd'],"\n";

echo "请牢记您的管理员账号！\n";

echo "欢迎使用Half-Life博客系统！\n";

echo "安装完成后你可能需要修改app/config/common.php配置文件以适应您的环境！\n";

echo "为了网站安全，安装完成后请删除本脚本\n";

echo "联系作者：www.longmonhau.com 1307995200@qq.com\n\n";