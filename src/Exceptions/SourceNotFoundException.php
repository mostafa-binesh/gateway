<?php

namespace Larabookir\Gateway\Exceptions;

class SourceNotFoundException extends GatewayException {

	protected $code=-106;
	protected $message='منبعی برای دریافت کانفیگ درگاه یافت نشد | منابع تعریف شده database و config هستند.';
}
