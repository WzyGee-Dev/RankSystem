<?php

namespace RankSystem\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use RankSystem\Rank\Rank;
use RankSystem\Rank\RankManager;
use RankSystem\Services\ServiceLocator;

class CreateRankCommand extends Command
{

    public ServiceLocator $locator;

    public function __construct(ServiceLocator $locator)
    {
        parent::__construct('createrank','Set a rank to a player');
        $this->locator = $locator;
        $this->setPermission('create.command.permissions');
    }


    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {

        if(!$sender instanceof Player){
            return false;
        }
        $rankManager = $this->locator->get(RankManager::class);

        if($rankManager === null){
            $sender->sendMessage(TextFormat::RED.'RankManager service is not found');
            return false;
        }

        $rankManager->showCreateRankForm($sender);
        return true;
    }
}