<?php

namespace RankSystem\Rank;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use RankSystem\forms\RankFormFactory;
use RankSystem\Loader;
use RankSystem\Rank\interface\RankRepositoryInterface;
use RankSystem\Services\ServiceLocator;

class RankManager
{

    private RankRepositoryInterface $repository;
    private array $ranks = [];
    private RankFormFactory $formFactory;

    public function __construct(RankRepositoryInterface $repository, RankFormFactory $formFactory) {
        $this->repository = $repository;
        $this->formFactory = $formFactory;
        $this->ranks = $this->repository->loadRanks();
    }

    public function saveRanks(): void {
        $this->repository->saveRanks($this->ranks);
    }

    public function getRank(string $name): ?Rank {
        return $this->ranks[$name] ?? null;
    }

    public function addRank(Rank $rank): void {
        $this->ranks[$rank->getName()] = $rank;
        $this->saveRanks();
    }

    public function updateRank(Rank $rank): void {
        $this->ranks[$rank->getName()] = $rank;
        $this->saveRanks();
    }

    public function addRankToPlayer(Player $player, string $rankName): void {
        $rank = $this->getRank($rankName);
        if ($rank !== null) {
            foreach ($rank->getPermissions() as $permission) {
                $player->addAttachment(Loader::getInstance(), $permission, true);
            }
            $player->sendMessage(TextFormat::GREEN . "You have been assigned the rank: " . $rank->getName());
        } else {
            $player->sendMessage(TextFormat::RED . "Rank $rankName does not exist.");
        }
    }

    public function showCreateRankForm(Player $player): void {
        $form = $this->formFactory->createForm()->getForm();
        $player->sendForm($form);
    }

    public function showEditRankForm(Player $player, string $rankName): void {
        $rank = $this->getRank($rankName);
        if ($rank !== null) {
            $form = $this->formFactory->createEditRankForm($rank)->getForm();
            $player->sendForm($form);
        } else {
            $player->sendMessage(TextFormat::RED . "Rank $rankName does not exist.");
        }
    }
}