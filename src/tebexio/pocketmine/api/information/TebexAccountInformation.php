<?php

declare(strict_types=1);

namespace tebexio\pocketmine\api\information;

final class TebexAccountInformation{

	/** @var int */
	private $id;

	/** @var string */
	private $domain;

	/** @var string */
	private $name;

	/** @var TebexAccountCurrencyInformation */
	private $currency;

	/** @var bool */
	private $online_mode;

	/** @var string */
	private $game_type;

	/** @var bool */
	private $log_events;

	public function __construct(int $id, string $domain, string $name, TebexAccountCurrencyInformation $currency, bool $online_mode, string $game_type, bool $log_events){
		$this->id = $id;
		$this->domain = $domain;
		$this->name = $name;
		$this->currency = $currency;
		$this->online_mode = $online_mode;
		$this->game_type = $game_type;
		$this->log_events = $log_events;
	}

	public function getId() : int{
		return $this->id;
	}

	public function getDomain() : string{
		return $this->domain;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getCurrency() : TebexAccountCurrencyInformation{
		return $this->currency;
	}

	public function isOnlineModeEnabled() : bool{
		return $this->online_mode;
	}

	public function getGameType() : string{
		return $this->game_type;
	}

	public function isLogEventsEnabled() : bool{
		return $this->log_events;
	}
}