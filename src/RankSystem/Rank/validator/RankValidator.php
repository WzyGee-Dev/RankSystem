<?php

namespace RankSystem\Rank\validator;

class RankValidator
{

    public function validateRankName(string $rankName): ValidationResult {
        if (empty($rankName) || !preg_match('/^[a-zA-Z0-9]+$/', $rankName)) {
            return new ValidationResult(false, "Invalid rank name.");
        }
        return new ValidationResult(true, "Valid rank name.");
    }

    public function validateColorChat(string $colorChat): ValidationResult {
        if (!preg_match('/^§[0-9a-f]$/i', $colorChat)) {
            return new ValidationResult(false, "Invalid chat color.");
        }
        return new ValidationResult(true, "Valid chat color.");
    }

    public function validatePermissions(string $permissions): ValidationResult {
        $permsArray = explode(",", $permissions);
        foreach ($permsArray as $perm) {
            if (empty(trim($perm))) {
                return new ValidationResult(false, "Invalid permissions format.");
            }
        }
        return new ValidationResult(true, "Valid permissions format.");
    }

    public function convertPermissionsToArray(string $permissions): array {
        return array_map('trim', explode(",", $permissions));
    }
}