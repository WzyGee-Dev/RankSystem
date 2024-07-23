<?php

namespace RankSystem;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use RankSystem\Services\ServiceLocator;
use RankSystem\Sessions\SessionManager;

class EventListener implements Listener
{


    public function onJoin(PlayerJoinEvent $event): void
    {
        $serviceLocator = ServiceLocator::getInstance();
        $session = $serviceLocator->get(SessionManager::class);
        $player = $event->getPlayer();
        $session->initializeSession($player);
    }


    public function onChat(PlayerChatEvent $event): void
    {
        $serviceLocator = ServiceLocator::getInstance();
        $sessionManager = $serviceLocator->get(SessionManager::class);

        $player = $event->getPlayer();
        $session = $sessionManager->getSession($player);
        if ($session !== null) {
            $session->handlePlayerChat($event);
        }
    }
}