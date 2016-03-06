# Half-Life 博客系统
- 演示：<http://longmonhau.com>
- 联系作者：<longmon.hau@gmail.com>

### Requirement
- PHP5.5+
- fileinfo php extension
- PDO PHP extension 及相应的数据库扩展
- 基于`LAMP`或`LNMP`，可选`Redis`作缓存,建议安装在unix系列主机
- WEB服务器必须支持URL重写
- Composer 管理依赖
```
#Apache .htaccess 例子 其他请自行Google
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ /index.php/$1 [L]
```

### Install
1. 编辑install.php，修改相应的选项
2. 运行install.php，如果一切正常会输出相关信息


### 鸣谢
- [nikic/fast-route](https://github.com/nikic/FastRoute) 	一个快速路由器
- [symfony/http-foundation](https://github.com/symfony/http-foundation) symfony出品的组件
- [twig](http://twig.sensiolabs.org) 	模板引擎
- [PHPMailer ](https://github.com/PHPMailer/PHPMailer)	邮件库
- [Laravel Eloquent](https://github.com/illuminate/database) Laravel的ORM
- [monolog](https://github.com/Seldaek/monolog) 日志
- [codeguy/upload](https://github.com/brandonsavage/Upload) 文件上传组件
- [Jquery](http://www.jquery.com)
- [mousewheel](https://github.com/jquery/jquery-mousewheel) 基于Jquery的滚轮事件插件
- [dropzone](http://www.dropzonejs.com/) 超级好用的JS上传库