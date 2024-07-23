<?php

namespace RankSystem\Rank;

class Rank
{


    private string $name;
    private string $prefix;
    private string $colorChat;
    private array $permissions;

    public function __construct(string $name, string $prefix, string $colorChat, array $permissions) {
        $this->name = $name;
        $this->prefix = $prefix;
        $this->colorChat = $colorChat;
        $this->permissions = $permissions;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrefix(): string {
        return $this->prefix;
    }

    public function getColorChat(): string {
        return $this->colorChat;
    }

    public function getPermissions(): array {
        return $this->permissions;
    }

    public function setPrefix(string $prefix): void {
        $this->prefix = $prefix;
    }

    public function setColorChat(string $colorChat): void {
        $this->colorChat = $colorChat;
    }

    public function setPermissions(array $permissions): void {
        $this->permissions = $permissions;
    }

}