<?php

declare(strict_types=1);

namespace tebexio\pocketmine\handler\due\playerslist;

use tebexio\pocketmine\api\queue\TebexDuePlayer;
use tebexio\pocketmine\handler\due\session\TebexPlayerSession;
use Closure;
use pocketmine\Player;

abstract class TebexDuePlayersList{

	/** @var TebexDuePlayerHolder[] */
	protected $due_players = [];

	/**
	 * @var Closure
	 * @phpstan-var Closure(Player, TebexDuePlayerHolder) : void
	 */
	private $on_match;

	/**
	 * @param Closure $on_match
	 * @phpstan-param Closure(Player, TebexDuePlayerHolder) : void $on_match
	 */
	public function __construct(Closure $on_match){
		$this->on_match = $on_match;
	}

	final protected function onMatch(Player $player, TebexDuePlayerHolder $holder) : void{
		($this->on_match)($player, $holder);
	}

	/**
	 * @return TebexDuePlayerHolder[]
	 */
	final public function getAll() : array{
		return $this->due_players;
	}

	/**
	 * @param TebexDuePlayer[] $due_players
	 */
	final public function update(array $due_players) : void{
		$this->due_players = [];
		foreach($due_players as $player){
			$holder = new TebexDuePlayerHolder($player);
			$this->due_players[$player->getId()] = $holder;
			$this->onDuePlayerSet($holder);
		}
	}

	final public function remove(TebexDuePlayerHolder $holder) : void{
		unset($this->due_players[$holder->getPlayer()->getId()]);
		$this->onDuePlayerRemove($holder);
	}

	abstract protected function onDuePlayerSet(TebexDuePlayerHolder $holder) : void;

	abstract protected function onDuePlayerRemove(TebexDuePlayerHolder $holder) : void;

	abstract public function get(Player $player) : ?TebexDuePlayerHolder;

	abstract public function getSession(Player $player) : ?TebexPlayerSession;

	abstract public function onPlayerJoin(Player $player) : void;

	abstract public function onPlayerQuit(Player $player) : void;
}