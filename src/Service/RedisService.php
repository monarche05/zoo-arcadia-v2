<?php

namespace App\Service;

use Predis\Client;

class RedisService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function incrementAnimalViewCount(string $animalName)
    {
        // Vérifier si l'animal existe déjà dans Redis
        if (!$this->client->exists('animal:' . $animalName)) {
            // Ajouter l'animal à Redis avec un compteur de vue initial de 1
            $this->client->set('animal:' . $animalName, 1);
        } else {
            // Incrémenter le compteur de vue de l'animal dans Redis
            $this->client->incr('animal:' . $animalName);
        }
    }
    public function getAnimalNames(): array
    {
        $keys = $this->client->keys('animal:*');
        $names = [];

        foreach ($keys as $key) {
            $names[] = str_replace('animal:', '', $key);
        }

        return $names;
    }

    public function getAnimalViewCounts()
    {
        $keys = $this->client->keys('animal:*');
        $viewCounts = [];

        foreach ($keys as $key) {
            $viewCounts[$key] = $this->client->get($key);
        }

        return $viewCounts;
    }


}

