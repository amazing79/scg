<?php

namespace Tests\Application\Gastos;

use App\Application\Personas\CreatePersonaCommandHandler;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Infrastructure\Gastos\InMemoryGastosRepository;
use App\Application\Gastos\GetGastosByPersonaQueryHandler;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures\GastosFixture;
use Tests\Fixtures\PersonasFixture;

class GetGastosByPersonaQueryHandlerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repository = new InMemoryGastosRepository();
        $this->personaRepository = new PersonasFixture();
        $this->persona = $this->addPersona();
    }

    public function testHandler()
    {
        $query = new GetGastosByPersonaQueryHandler(
            $this->repository,
            $this->personaRepository,
        );
        $result = $query->handle($this->persona);
        $this->assertEquals(HttpStatusCode::OK, $result['code'], $result['message']);
    }

    public function testQueryGastosPersonasInFixture()
    {
        $gastos = new GastosFixture();
        $query = new GetGastosByPersonaQueryHandler(
            $gastos,
            $this->personaRepository,
        );
        $persona = $this->personaRepository->findById(2);
        $result = $query->handle($persona->getId());
        $this->assertEquals(HttpStatusCode::OK, $result['code'], $result['message']);
        $totalDB = count($gastos->getGastosByPersona($persona->getId()));
        $this->assertEquals($totalDB, $result['totalFound']);
    }

    public function testMustFailWhenPersonaDoesNotExist()
    {
        $query = new GetGastosByPersonaQueryHandler(
            $this->repository,
            $this->personaRepository,
        );
        $result = $query->handle(0);
        $this->assertEquals(HttpStatusCode::BAD_REQUEST, $result['code'], $result['message']);
    }

    private function addPersona()
    {
        $values = [];
        $values['apellido'] = 'Pankie';
        $values['nombre'] = 'Marcos';
        $values['apodo'] = 'El Pankie';
        $add = new CreatePersonaCommandHandler($this->personaRepository);
        $result = $add->handle($values);
        return $result['data'];
    }

}
