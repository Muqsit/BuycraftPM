<?php

declare(strict_types=1);

namespace tebexio\pocketmine\handler\due\playerslist;

use tebexio\pocketmine\api\queue\TebexDuePlayer;

final class TebexDuePlayerHolder{

	/** @var float */
	private $created;

	/** @var TebexDuePlayer */
	private $player;

	public function __construct(TebexDuePlayer $player){
		$this->player = $player;
		$this->created = microtime(true);
	}

	public function getPlayer() : TebexDuePlayer{
		return $this->player;
	}

	public function getCreated() : float{
		return $this->created;
	}
}