<?php namespace lOngmon\Hau\core\component;

use lOngmon\Hau\core\component\Config;

class Mailer
{
	private static $mailer = NULL;

	public static function newInstance()
	{
		if( !	self::$mailer instanceof \PHPMailer )
		{
			self::$mailer = new \PHPMailer;
			self::$mailer->isSMTP();
			$mailConfig = Config::get("MAIL-SERVER-CONFIG");
			self::$mailer->Host = $mailConfig['HOST'];
			self::$mailer->SMTPAuth = true;
			self::$mailer->Username = $mailConfig['USERNAME'];
			self::$mailer->Password = $mailConfig['PASSWORD'];
			self::$mailer->Port 	= $mailConfig['PORT'];
			self::$mailer->setFrom($mailConfig['FROM'],"Root");
		}
		return self::$mailer;
	}
}