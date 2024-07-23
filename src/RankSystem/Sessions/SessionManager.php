<?php

namespace RankSystem\Sessions;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\format\io\FastChunkSerializer;
use RankSystem\Rank\RankManager;

class SessionManager
{
public array $sessions = [];
    public string $dataFolder;
    protected RankManager $rankManager;


    public function __construct(string $dataFolder, RankManager $rankManager) {
        $this->dataFolder = $dataFolder;
        $this->rankManager = $rankManager;
        if (!is_dir($dataFolder . "User/")) {
            mkdir($dataFolder . "User/", 0777, true);
        }
    }

    public function initializeSession(Player $player): void
    {
        $session = new PlayerSession($player, $this->dataFolder);
        $this->sessions[$player->getName()] = $session;
        $session->applyPermissions();
        $session->updateScoreTag();
    }



    public function getSession(Player $player): ?PlayerSession
    {
        return $this->sessions[$player->getName()] ?? null;
    }




    public function getAllSessions(): void
    {
        foreach ($this->sessions as $session) {
            $session->save();
        }
    }

    public function getPlayerByName(string $name): ?Player
    {
        return Server::getInstance()->getPlayerExact($name);
    }
}