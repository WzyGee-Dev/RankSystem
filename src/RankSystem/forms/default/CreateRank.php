<?php

namespace RankSystem\forms\default;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use RankSystem\Rank\Rank;
use RankSystem\Rank\RankManager;
use RankSystem\Rank\validator\RankValidator;
use RankSystem\Services\ServiceLocator;
use RankSystem\Sessions\PlayerSession;
use RankSystem\Utils;

class CreateRank
{

    public RankValidator $rankValidator;
    public function __construct(RankValidator $rankValidator)
    {
        $this->rankValidator = $rankValidator;
    }

    public function getForm(): CustomForm
    {
        $form =  new CustomForm(function (Player $player, $data = null){
            if($data !== null){
                $rankName = $data[0];
                $prefix = $data[1];
                $colorChat = $data[2];
                $permissions = $data[3];
                $validationResult = Utils::validateRankData($rankName, $colorChat, $permissions);
                if(!$validationResult->isValid()){
                    $player->sendMessage(TextFormat::RED. $validationResult->getMessage());
                    return;
                }
                $permissionArray = $this->rankValidator->convertPermissionsToArray($permissions);
                $rank = new Rank($rankName, $prefix, $colorChat, $permissionArray);
                $rankManager = ServiceLocator::getInstance()->get(RankManager::class);
                $rankManager->addRank($rank);
                $player->sendMessage(TextFormat::GREEN.' Rank: '. $rankName . ' created successfully.');
            }
        });
        $form->setTitle('create rank');
        $form->addInput('Rank name', 'owner');
        $form->addInput('Prefix', '[Owner]');
        $form->addInput('Chat Color', '§c');
        $form->addInput('Permissions (comma separated', 'permissions.1 , permissions.2');
        return $form;
    }
}