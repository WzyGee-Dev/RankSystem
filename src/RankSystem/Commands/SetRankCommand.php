<?php

namespace RankSystem\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use RankSystem\Rank\RankManager;
use RankSystem\Services\ServiceLocator;
use RankSystem\Sessions\SessionManager;

class SetRankCommand extends Command
{


    public ServiceLocator $locator;

    public function __construct(ServiceLocator $locator)
    {
        parent::__construct('setrank','Set a rank to a player');
        $this->locator = $locator;
        $this->setPermission('set.command.permissions');
    }


    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if(!$sender instanceof Player){
            return false;
        }
        if (count($args) < 2) {
            $sender->sendMessage(TextFormat::RED . "Usage: /setrank <player> <rank>");
            return false;
        }
        $playerName = $args[0];
        $rankName = $args[1];

        $sessionManager = $this->locator->get(SessionManager::class);
        $rankManager = $this->locator->get(RankManager::class);

        $player = $sessionManager->getPlayerByName($playerName);
        if ($player === null) {
            $sender->sendMessage(TextFormat::RED . "Player $playerName not found.");
            return false;
        }


            if ($rankManager->getRank($rankName) === null) {
                $sender->sendMessage(TextFormat::RED . "Rank $rankName does not exist.");
                return false;
            }

        $session = $sessionManager->getSession($player);
        if ($session !== null) {
            $ranks = $session->getRanks();
            if(in_array($rankName, $session->getRanks())){
                $sender->sendMessage(TextFormat::YELLOW . "Player $playerName already has the rank $rankName.");
                return true;
            }
            $ranks[] = $rankName;
            $session->setRanks($ranks);
            $sender->sendMessage(TextFormat::GREEN . "Ranks: ".$rankName . " assigned to player $playerName.");
            $player->sendMessage(TextFormat::GREEN . "You have been assigned the ranks: " . $rankName . ".");
        } else {
            $sender->sendMessage(TextFormat::RED . "Failed to set ranks for player $playerName.");
        }
        return true;
    }
}