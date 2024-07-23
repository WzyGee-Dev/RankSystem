<?php

namespace RankSystem\Sessions;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\player\chat\LegacyRawChatFormatter;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use RankSystem\Rank\RankManager;
use RankSystem\Services\ServiceLocator;

class PlayerSession
{

    public Config $config;
    public Player $player;

    public string $defaultChat = TextFormat::WHITE;

    public function __construct(Player $player, string $dataFolder) {
        $this->player = $player;
        $this->config = new Config($dataFolder . "User/" . $player->getName() . ".yml", Config::YAML, [
            'rank' => []
        ]);
    }


    public function getRanks(): array
    {
       return $this->config->get('rank', []);
    }

    public function setRanks(array $ranks): void
    {
        $this->config->set('rank', array_unique($ranks));
        $this->save();
        $this->applyPermissions();
        $this->updateScoreTag();
    }

    /**
     * @throws \JsonException
     */
    public function save(): void
    {
        $this->config->save();
    }


    public function getChatColor(): string
    {
        $ranks = $this->getRanks();
        $rankManager = ServiceLocator::getInstance()->get(RankManager::class);
        foreach ($ranks as $rankName) {
            $rank = $rankManager->getRank($rankName);
            if($rank !== null){
                return $rank->getColorChat();
            }
       }
        return $this->defaultChat;
    }

    public function applyPermissions(): void {
        $rankManager = ServiceLocator::getInstance()->get(RankManager::class);
        foreach ($this->getRanks() as $rankName) {
            $rankManager->addRankToPlayer($this->player, $rankName);
        }
    }

    public function updateScoreTag(): void {
        $rankManager = ServiceLocator::getInstance()->get(RankManager::class);
        $prefixes = [];
        foreach ($this->getRanks() as $rankName) {
            $rank = $rankManager->getRank($rankName);
            if ($rank !== null) {
                $prefixes[] = $rank->getPrefix();
            }
        }
        $this->player->setScoreTag(implode(TextFormat::RESET.TextFormat::WHITE." - ", $prefixes));
        }
    public function handlePlayerChat(PlayerChatEvent $event): void
    {
        $color = $this->getChatColor();
        $event->setFormatter(new LegacyRawChatFormatter($event->getPlayer()->getName().$color.': '.$event->getMessage()));
    }
}