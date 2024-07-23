<?php

namespace RankSystem;

use RankSystem\Rank\validator\RankValidator;
use RankSystem\Rank\validator\ValidationResult;

class Utils
{


    public static function validateRankData(string $rankName, string $colorChat, string $permissions): ValidationResult {
        $validator = new RankValidator();

        $nameValidation = $validator->validateRankName($rankName);
        if (!$nameValidation->isValid()) {
            return $nameValidation;
        }

        $colorValidation = $validator->validateColorChat($colorChat);
        if (!$colorValidation->isValid()) {
            return $colorValidation;
        }

        $permissionsValidation = $validator->validatePermissions($permissions);
        if (!$permissionsValidation->isValid()) {
            return $permissionsValidation;
        }

        return new ValidationResult(true, "Valid rank data.");
    }
}