<?php

namespace RankSystem\forms;

use RankSystem\forms\default\CreateRank;
use RankSystem\forms\default\EditForm;
use RankSystem\Rank\Rank;
use RankSystem\Rank\validator\RankValidator;

class RankFormFactory
{

    public RankValidator $rankValidator;

    public function __construct(RankValidator $rankValidator)
    {
        $this->rankValidator = $rankValidator;
    }


    public function createForm(): CreateRank
    {
        return new CreateRank($this->rankValidator);
    }


    public function createEditRankForm(Rank $rank): EditForm
    {
        return new EditForm($this->rankValidator, $rank);
    }
}