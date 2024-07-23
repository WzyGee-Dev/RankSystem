<?php

namespace RankSystem\Rank\validator;

class ValidationResult
{

    private bool $valid;
    private string $message;

    public function __construct(bool $valid, string $message) {
        $this->valid = $valid;
        $this->message = $message;
    }

    public function isValid(): bool {
        return $this->valid;
    }

    public function getMessage(): string {
        return $this->message;
    }
}