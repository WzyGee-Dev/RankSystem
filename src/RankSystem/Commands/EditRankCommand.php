<?php

namespace RankSystem\Commands;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use RankSystem\Rank\RankManager;
use RankSystem\Services\ServiceLocator;

class EditRankCommand extends \pocketmine\command\Command
{

    public ServiceLocator $locator;

    public function __construct(ServiceLocator $locator)
    {
        parent::__construct('editrank','Set a rank to a player');
        $this->locator = $locator;
        $this->setPermission('edit.command.permissions');
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
        if (!isset($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Usage: /editrank <rankName>");
            return false;
        }
        $rankManager->showEditRankForm($sender, $args[0]);
        return true;
    }
}