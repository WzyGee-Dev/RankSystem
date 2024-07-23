<?php

namespace RankSystem\forms\default;

use jojoe77777\FormAPI\CustomForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use RankSystem\Rank\Rank;
use RankSystem\Rank\RankManager;
use RankSystem\Rank\validator\RankValidator;
use RankSystem\Services\ServiceLocator;
use RankSystem\Utils;

class EditForm
{
    public RankValidator $rankValidator;
    public Rank $rank;

    public function __construct(RankValidator $rankValidator, Rank $rank)
    {
        $this->rankValidator = $rankValidator;
        $this->rank = $rank;
    }

    public function getForm(): CustomForm
    {
        $form = new CustomForm(function (Player $player, $data = null)
        {
            if($data !== null) {
                $prefix = $data[0];
                $colorChat = $data[1];
                $permissions = $data[2];
                $validationResult = Utils::validateRankData($this->rank->getName(), $colorChat, $permissions);
                if(!$validationResult->isValid()){
                    $player->sendMessage(TextFormat::RED. $validationResult->getMessage());
                    return;
                }

                $permissionArray = $this->rankValidator->convertPermissionsToArray($permissions);
                $this->rank->setPrefix($prefix);
                $this->rank->setColorChat($colorChat);
                $this->rank->setPermissions($permissionArray);
                $locate = ServiceLocator::getInstance()->get(RankManager::class);
                $locate->updateRank($this->rank);
                $player->sendMessage(TextFormat::GREEN.'Rank '. $this->rank->getName(). ' Update successfully!');
            }
        });

        $form->setTitle('edit rank '. $this->rank->getName());
        $form->addInput('Prefix', ' [owner]',$this->rank->getPrefix());
        $form->addInput('Chat color', 'default: ', $this->rank->getColorChat());
        $form->addInput('Permissions (comma separated)', '', implode(',',$this->rank->getPermissions()));
        return $form;
    }
}