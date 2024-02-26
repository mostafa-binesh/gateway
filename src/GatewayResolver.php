<?php

namespace Larabookir\Gateway;

use Larabookir\Gateway\Irankish\Irankish;
use Larabookir\Gateway\Parsian\Parsian;
use Larabookir\Gateway\Paypal\Paypal;
use Larabookir\Gateway\Sadad\Sadad;
use Larabookir\Gateway\Mellat\Mellat;
use Larabookir\Gateway\Pasargad\Pasargad;
use Larabookir\Gateway\Saman\Saman;
use Larabookir\Gateway\Asanpardakht\Asanpardakht;
use Larabookir\Gateway\Zarinpal\Zarinpal;
use Larabookir\Gateway\Payir\Payir;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Illuminate\Support\Facades\DB;
use Larabookir\Gateway\Exceptions\ConfigSourceEmptyException;
use Larabookir\Gateway\Exceptions\DBConfigNotFoundException;
use Larabookir\Gateway\Exceptions\GatewayConfigRelationshipNotFoundException;
use Larabookir\Gateway\Exceptions\SourceNotFoundException;
use Larabookir\Gateway\Exceptions\UserGatewayConfigNotFoundExceptions;
use Larabookir\Gateway\Models\GatewayConfig;

class GatewayResolver
{

	protected $request;

	/**
	 * @var Config
	 */
	public $config;

	/**
	 * @var string
	 */
	public $gatewaySource;

	/**
	 * Keep current port driver
	 *
	 * @var Mellat|Saman|Sadad|Zarinpal|Payir|Parsian
	 */
	protected $port;

	/**
	 * Gateway constructor.
	 * @param null $config
	 * @param null $port
	 * @param null $gatewaySource
	 */
	public function __construct($config = null, $port = null, $gatewaySource = null)
	{
		$this->config = app('config');
		$this->request = app('request');

		if ($this->config->has('gateway.timezone'))
			date_default_timezone_set($this->config->get('gateway.timezone'));

		if (!is_null($port)) $this->make($port);

		if (!is_null($gatewaySource))
			$this->gatewaySource = $gatewaySource;
		else
			$this->gatewaySource = config('gateway.source');
	}

	/**
	 * Get supported ports
	 *
	 * @return array
	 */
	public function getSupportedPorts()
	{
		return (array) Enum::getIPGs();
	}

	/**
	 * Call methods of current driver
	 *
	 * @return mixed
	 */
	public function __call($name, $arguments)
	{

		// calling by this way ( Gateway::mellat()->.. , Gateway::parsian()->.. )
		if(in_array(strtoupper($name),$this->getSupportedPorts())){
			return $this->make($name);
		}

		return call_user_func_array([$this->port, $name], $arguments);
	}

	/**
	 * Gets query builder from you transactions table
	 * @return mixed
	 */
	function getTable()
	{
		return DB::table($this->config->get('gateway.table'));
	}

	/**
	 * Callback
	 *
	 * @return $this->port
	 *
	 * @throws InvalidRequestException
	 * @throws NotFoundTransactionException
	 * @throws PortNotFoundException
	 * @throws RetryException
	 */
	public function verify()
	{
		if (!$this->request->has('transaction_id') && !$this->request->has('iN'))
			throw new InvalidRequestException;
		if ($this->request->has('transaction_id')) {
			$id = $this->request->get('transaction_id');
		}else {
			$id = $this->request->get('iN');
		}

		$transaction = $this->getTable()->whereId($id)->first();

		if (!$transaction)
			throw new NotFoundTransactionException;

		if (in_array($transaction->status, [Enum::TRANSACTION_SUCCEED, Enum::TRANSACTION_FAILED]))
			throw new RetryException;

		$this->make($transaction->port);

		return $this->port->verify($transaction);
	}

	public function makeWithUser($port, $user)
	{
		return $this->make($port, $user);
	}

	public function makeWithConfigName($port, $configName)
	{
		return $this->make($port, null, $configName);
	}
	/**
	 * Create new object from port class
	 *
	 * @param int $port
	 * @param Model $user
	 * @param string $configName
	 * @throws PortNotFoundException|SourceNotFoundException|ConfigSourceEmptyException|DBConfigNotFoundException
	 */
	function make($port, $user = null, $configName = null)
    {
        if ($port InstanceOf Mellat) {
            $name = Enum::MELLAT;
        } elseif ($port InstanceOf Parsian) {
            $name = Enum::PARSIAN;
        } elseif ($port InstanceOf Saman) {
            $name = Enum::SAMAN;
        } elseif ($port InstanceOf Zarinpal) {
            $name = Enum::ZARINPAL;
        } elseif ($port InstanceOf Sadad) {
            $name = Enum::SADAD;
        } elseif ($port InstanceOf Asanpardakht) {
            $name = Enum::ASANPARDAKHT;
        } elseif ($port InstanceOf Paypal) {
            $name = Enum::PAYPAL;
        } elseif ($port InstanceOf Payir) {
            $name = Enum::PAYIR;
        } elseif ($port InstanceOf Pasargad) {
            $name = Enum::PASARGAD;
        } elseif ($port InstanceOf Irankish) {
            $name = Enum::IRANKISH;
        } elseif (in_array(strtoupper($port), $this->getSupportedPorts())) {
            $port = ucfirst(strtolower($port));
            $name = strtoupper($port);
            $class = __NAMESPACE__ . '\\' . $port . '\\' . $port;
            $port = new $class;
        } else
            throw new PortNotFoundException;

		$this->port = $port;
		$config = $this->getConfig($port, $user, $configName);

		$this->port->setConfig($config); // injects config
		$this->port->setPortName($name); // injects config

		$this->port->boot();

		return $this;
	}
	protected function getConfig($portInstance, $user = null, $configName = null)
	{
		switch ($this->gatewaySource) {
			case 'database':
				return $this->getConfigFromDatabase($portInstance, $user, $configName);
			case 'config':
				return $this->config; // Assuming this->config is already set appropriately
			default:
				throw new SourceNotFoundException;
		}
	}

	protected function getConfigFromUser($user)
	{
		if (is_null($user->gatewayConfig())) {
			throw new GatewayConfigRelationshipNotFoundException;
		}
		$userGatewayConfig = $user->gatewayConfig;
		if (!$userGatewayConfig)
			throw new UserGatewayConfigNotFoundExceptions;
		return $userGatewayConfig->config;
	}

	protected function getConfigFromDBUsingConfigName($configName, $port)
	{
		$gatewayConfig = GatewayConfig::query()->where([
			'port_name' => $port,
			'name' => $configName,
		])->first();
		if (!$gatewayConfig) {
			// config was not found on the database
			throw new DBConfigNotFoundException($configName, $port);
		}
		return $gatewayConfig->config;
	}

	/**
	 * Retreives the config from the database 
	 * using user or configName
	 *
	 * @param int $port
	 * @param Model $user
	 * @param string $configName
	 * @throws PortNotFoundException|SourceNotFoundException|ConfigSourceEmptyException|DBConfigNotFoundException
	 */
	protected function getConfigFromDatabase($portInstance, $user = null, $configName = null)
	{
		if (!is_null($user)) {
			return $this->getConfigFromUser($user);
		} elseif (!is_null($configName)) {
			return $this->getConfigFromDBUsingConfigName($configName, $portInstance);
		} else {
			throw new ConfigSourceEmptyException;
		}
	}
}
