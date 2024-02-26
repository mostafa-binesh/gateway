<?php

namespace Larabookir\Gateway\Exceptions;

class GatewayConfigRelationshipNotFoundException extends GatewayException
{
	protected $code = -107;
	protected $message = 'ارتباط configGateway در آبجکت مورد نظر یافت نشد. لطفا تریت HasGatewayConfig را به مدل کاربر خود اضافه کنید.';
}
