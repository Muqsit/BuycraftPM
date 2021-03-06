<?php

declare(strict_types=1);

namespace tebexio\pocketmine\api\queue\commands\online;

use tebexio\pocketmine\api\queue\commands\TebexQueuedCommandConditions;

final class TebexQueuedOnlineCommandConditions extends TebexQueuedCommandConditions{

	/** @var int */
	private $slots;

	public function __construct(int $delay, int $slots){
		parent::__construct($delay);
		$this->slots = $slots;
	}

	public function getInventorySlots() : int{
		return $this->slots;
	}
}