<?php

namespace Larabookir\Gateway\Exceptions;

class ConfigSourceEmptyException extends GatewayException {

	protected $code=-107;
	protected $message='منبع کانفیگ درگاه باید از کاربر یا نام کانفیگ تامین شود؛ ولی هیچ کدام ارائه نشده اند.';
}
