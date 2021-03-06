<?php

declare(strict_types=1);

namespace tebexio\pocketmine\api\queue\commands\offline;

use tebexio\pocketmine\api\queue\TebexDuePlayer;
use tebexio\pocketmine\api\queue\commands\TebexQueuedCommand;

final class TebexQueuedOfflineCommand extends TebexQueuedCommand{

	/** @var TebexQueuedOfflineCommandConditions */
	private $conditions;

	/** @var TebexDuePlayer */
	private $player;

	public function __construct(int $id, string $command, int $payment_id, int $package_id, TebexQueuedOfflineCommandConditions $conditions, TebexDuePlayer $player){
		parent::__construct($id, $command, $payment_id, $package_id);
		$this->conditions = $conditions;
		$this->player = $player;
	}

	public function getConditions() : TebexQueuedOfflineCommandConditions{
		return $this->conditions;
	}

	public function getPlayer() : TebexDuePlayer{
		return $this->player;
	}
}