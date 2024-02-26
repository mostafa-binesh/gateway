<?php

namespace Larabookir\Gateway\Exceptions;

class DBConfigNotFoundException extends GatewayException
{
	protected $code = -106;
	protected $message;

	public function __construct($configName, $port)
	{
		$this->message = "کانفیگی با نام {$configName} برای پورت {$port} در دیتابیس یافت نشد. ";
	}
}
