<?php

namespace Tests\Fixtures;

use App\Domain\Personas\Persona;
use App\Infrastructure\Personas\InMemoryPersonasRepository;

class PersonasFixture extends InMemoryPersonasRepository
{
    private array $personas = [];

    public function __construct()
    {
        parent::__construct();
        $this->personas = [
            1 => new Persona(1, 'Ignacio', 'Jauregui', 'Nacho'),
            2 => new Persona(2, 'Gio', 'Jauregui', 'Gio'),
            3 => new Persona(3, 'Amanda', 'Vidal', 'Essen'),
        ];
        $this->initialize();
    }

    private function initialize():void
    {
        foreach ($this->personas as $persona) {
            $this->create($persona);
        }
    }
}