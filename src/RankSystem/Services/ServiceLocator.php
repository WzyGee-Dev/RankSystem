<?php

namespace RankSystem\Services;


use InvalidArgumentException;

class ServiceLocator
{
    private static ?ServiceLocator $instance = null;
    private array $services = [];

    public array $serviceType = [];

    public function register(string $serviceName, $service): void {

        if(isset($this->services[$serviceName])){
            throw new InvalidArgumentException('Service '.$serviceName. ' is already registered');
        }
        $this->services[$serviceName] = $service;
        $this->serviceType[$serviceName] = get_class($service);
    }

    public function get(string $serviceName) {
        if(!isset($this->services[$serviceName])){
            throw new InvalidArgumentException('Service '.$serviceName. ' is no registered');
        }

        return $this->services[$serviceName];
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    public function getServiceType(string $serviceName): string
    {
        if(!isset($this->serviceType[$serviceName])){
            throw new InvalidArgumentException('Service '.$serviceName. ' is no registered');
        }
        return $this->serviceType[$serviceName];
    }

    public static function reset(): void
    {
        self::$instance = null;
    }
}