<?php declare(strict_types=1);

namespace App\Model;

class Config
{

	/** @var array */
	public $emailTo;

	/** @var string */
	public $emailFrom;

	/** @var string */
	public $apiKey;

	/** @var string */
	public $apiPassword;

	/** @var string */
	public $eshop;

	/** @var string */
	public $packetaCountry;

	/** @var int */
	public $defaultValue;

	public function __construct(array $parameters) {

		$this->emailFrom = $parameters['emailFrom'];
		$this->emailTo = $parameters['emailTo'];
		$this->apiKey = $parameters['apiKey'];
		$this->apiPassword = $parameters['apiPassword'];
		$this->eshop = $parameters['eshop'];
		$this->packetaCountry = $parameters['packetaCountry'];
		$this->defaultValue = $parameters['defaultValue'];
	}

	/**
	 * @return array
	 */
	public function getEmailTo(): array {
		return $this->emailTo;
	}

	/**
	 * @return string
	 */
	public function getApiKey(): string {
		return $this->apiKey;
	}

	/**
	 * @return string
	 */
	public function getApiPassword(): string {
		return $this->apiPassword;
	}

	/**
	 * @return string
	 */
	public function getPacketaCountry(): string {
		return $this->packetaCountry;
	}

	/**
	 * @return string
	 */
	public function getEmailFrom(): string {
		return  $this->emailFrom;
	}

	/**
	 * @return int
	 */
	public function getDefaultValue(): int {
		return $this->defaultValue;
	}

}
