<?php

namespace RankSystem\Rank;

use pocketmine\utils\Config;
use RankSystem\Rank\interface\RankRepositoryInterface;

class RankRepository implements RankRepositoryInterface
{


    private $config;

    public function __construct(string $dataFolder) {
        $this->config = new Config($dataFolder . "ranks.yml", Config::YAML, []);
    }

    public function loadRanks(): array {
        $ranks = [];
        foreach ($this->config->getAll() as $name => $data) {
            $ranks[$name] = new Rank(
                $name,
                $data['prefix'],
                $data['colorChat'],
                $data['permissions']
            );
        }
        return $ranks;
    }

    public function saveRanks(array $ranks): void {
        $data = [];
        foreach ($ranks as $rank) {
            $data[$rank->getName()] = [
                'prefix' => $rank->getPrefix(),
                'colorChat' => $rank->getColorChat(),
                'permissions' => $rank->getPermissions()
            ];
        }
        $this->config->setAll($data);
        $this->config->save();
    }
}