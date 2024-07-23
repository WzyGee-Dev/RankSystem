<?php

namespace RankSystem;

use pocketmine\plugin\PluginBase;
use RankSystem\Commands\CreateRankCommand;
use RankSystem\Commands\EditRankCommand;
use RankSystem\Commands\SetRankCommand;
use RankSystem\forms\RankFormFactory;
use RankSystem\Rank\RankManager;
use RankSystem\Rank\RankRepository;
use RankSystem\Rank\validator\RankValidator;
use RankSystem\Services\ServiceLocator;
use RankSystem\Sessions\SessionManager;

class Loader extends PluginBase
{


    public static Loader $instance;

    public static function getInstance(): Loader
    {
        return self::$instance;
    }

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        self::$instance = $this;
        $serviceLocator = ServiceLocator::getInstance();
        $rankRepository = new RankRepository($this->getDataFolder());
        $rankValidator = new RankValidator();
        $rankForm = new RankFormFactory($rankValidator);
        $this->saveCommands($serviceLocator);
        $rankManager = new RankManager($rankRepository, $rankForm);
        $session = new SessionManager($this->getDataFolder(), $rankManager);

        $serviceLocator->register(RankManager::class, $rankManager);
        $serviceLocator->register(SessionManager::class, $session);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    private function saveCommands(ServiceLocator $serviceLocator): void
    {
        $this->getServer()->getCommandMap()->register('createrank', new CreateRankCommand($serviceLocator));
        $this->getServer()->getCommandMap()->register('editrank', new EditRankCommand($serviceLocator));
        $this->getServer()->getCommandMap()->register('setrank', new SetRankCommand($serviceLocator));
    }

    public function onDisable(): void
    {
       $service = ServiceLocator::getInstance();
       $rankManager = $service->get(RankManager::class);
       $session = $service->get(SessionManager::class);
       $rankManager->saveRanks();
       $session->getAllSessions();
    }
}