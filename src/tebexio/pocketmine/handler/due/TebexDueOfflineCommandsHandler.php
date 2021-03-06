<?php

declare(strict_types=1);

namespace tebexio\pocketmine\handler\due;

use tebexio\pocketmine\api\queue\commands\offline\TebexQueuedOfflineCommand;
use tebexio\pocketmine\api\queue\commands\offline\TebexQueuedOfflineCommandsInfo;
use tebexio\pocketmine\handler\command\TebexCommandSender;
use tebexio\pocketmine\TebexPlugin;
use tebexio\pocketmine\handler\TebexHandler;
use tebexio\pocketmine\thread\response\TebexResponseHandler;
use Closure;
use Ds\Set;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

final class TebexDueOfflineCommandsHandler{

	/** @var TebexPlugin */
	private $plugin;

	/** @var TebexHandler */
	private $handler;

	/** @var Set<int> */
	private $delayed;

	public function __construct(TebexPlugin $plugin, TebexHandler $handler, int $check_period = 60 * 20){
		$this->delayed = new Set();
		$this->plugin = $plugin;
		$this->handler = $handler;
		$plugin->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(int $currentTick) : void{ $this->check(); }), $check_period);
	}

	public function check(?Closure $callback = null) : void{
		$this->plugin->getApi()->getQueuedOfflineCommands(TebexResponseHandler::onSuccess(function(TebexQueuedOfflineCommandsInfo $info) use($callback) : void{
			if($callback !== null){
				$callback(count($info->getCommands()));
			}
			$this->onFetchDueOfflineCommands($info);
		}));
	}

	private function onFetchDueOfflineCommands(TebexQueuedOfflineCommandsInfo $info) : void{
		$commands = $info->getCommands();

		$commands_c = count($commands);
		$this->plugin->getLogger()->debug("[/QUEUE] Fetched " . $commands_c . " offline command" . ($commands_c === 1 ? "" : "s"));

		foreach($commands as $command){
			$this->executeCommand($command, function(bool $success) use($command) : void{
				$command_string = $command->getCommand()->asOfflineFormattedString($command->getPlayer());
				if($success){
					$this->handler->queueCommandDeletion($command->getId());
					$this->plugin->getLogger()->debug("[/QUEUE] Executed offline command: " . $command_string);
				}else{
					$this->plugin->getLogger()->warning("[/QUEUE] Failed to execute offline command: " . $command_string);
				}
			});
		}
	}

	private function executeCommand(TebexQueuedOfflineCommand $command, Closure $callback) : void{
		$delay = $command->getConditions()->getDelay();
		if($delay > 0){
			if(!$this->delayed->contains($id = $command->getId())){
				$this->delayed->add($id);
				$this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function(int $currentTick) use($command, $callback) : void{
					$callback($this->instantlyExecuteCommand($command));
				}), $delay * 20);
			}
		}else{
			$callback($this->instantlyExecuteCommand($command));
		}
	}

	private function instantlyExecuteCommand(TebexQueuedOfflineCommand $command) : bool{
		return Server::getInstance()->dispatchCommand(TebexCommandSender::instance(), $command->getCommand()->asOfflineFormattedString($command->getPlayer()));
	}
}