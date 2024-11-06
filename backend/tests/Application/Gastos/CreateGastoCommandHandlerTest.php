<?php

namespace Tests\Application\Gastos;

use App\Application\Categorias\CreateCategoriaCommandHandler;
use App\Application\Gastos\CreateGastoCommandHandler;
use App\Application\Personas\CreatePersonaCommandHandler;
use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;
use App\Domain\Personas\PersonasRepository;
use App\Infrastructure\Gastos\InMemoryGastosRepository;
use Tests\Fixtures\CategoriasFixture;
use Tests\Fixtures\PersonasFixture;
use PHPUnit\Framework\TestCase;

class CreateGastoCommandHandlerTest extends TestCase
{
    private GastosRepository $repository;
    private CategoriasRepository $categoriasRepository;
    private PersonasRepository $personasRepository;
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repository = new InMemoryGastosRepository();
        $this->categoriasRepository = new CategoriasFixture();
        $this->personasRepository = new PersonasFixture();
        $this->categoria = $this->addCategoria();
        $this->persona = $this->addPersona();
    }

    public function testHandle()
    {
        $values = [];
        $values['descripcion'] = 'Gasto de luz';
        $values['fecha'] = '2024-01-01';
        $values['monto'] = 100.00;
        $values['categoria'] = $this->categoria;
        $values['persona'] = $this->persona;
        $add = new CreateGastoCommandHandler(
            $this->repository,
            $this->categoriasRepository,
            $this->personasRepository
        );
        $result = $add->handle($values);
        $this->assertEquals(HttpStatusCode::CREATED, $result['code'], $result['message']);
        $this->assertCount(1, $this->repository->getAll());
        $gasto = $this->repository->findById($result['data']);
        $this->assertObjectValues($values, $gasto);
    }

    private function assertObjectValues(array $values, ?Gastos $gasto): void
    {
        $this->assertEquals($values['descripcion'], $gasto->getDescripcion(), 'No se guardo correctamente la descripcion');
        $this->assertEquals($values['fecha'], $gasto->getFecha(), 'No se guardo correctamente la fecha');
        $this->assertEquals($values['monto'], $gasto->getMonto(), 'No se guardo correctamente el monto');
        $this->assertEquals($values['categoria'], $gasto->getCategoria(), 'No se guardo correctamente la categoria');
        $this->assertEquals($values['persona'], $gasto->getPersona(), 'No se guardo correctamente la persona');
    }

    public function testMustFailWhenDescriptionIsEmpty()
    {
        $values = [];
        $values['descripcion'] = '';
        $values['fecha'] = '2024-01-01';
        $values['monto'] = 0.0;
        $values['categoria'] = $this->categoria;
        $values['persona'] = $this->persona;
        $add = new CreateGastoCommandHandler(
            $this->repository,
            $this->categoriasRepository,
            $this->personasRepository
        );
        $result = $add->handle($values);
        $this->assertEquals(HttpStatusCode::INTERNAL_SERVER_ERROR, $result['code'], $result['message']);
        $this->assertCount(0, $this->repository->getAll());
    }

    public function testMustFailWhenMontoIsBelowZeroOrEqualsZero()
    {
        $values = [];
        $values['descripcion'] = 'Gasto Cochino';
        $values['fecha'] = '2024-01-01';
        $values['monto'] = 0.0;
        $values['categoria'] = $this->categoria;
        $values['persona'] = $this->persona;
        $add = new CreateGastoCommandHandler(
            $this->repository,
            $this->categoriasRepository,
            $this->personasRepository
        );
        $result = $add->handle($values);
        $this->assertEquals(HttpStatusCode::INTERNAL_SERVER_ERROR, $result['code'], $result['message']);
        $this->assertCount(0, $this->repository->getAll());
    }

    public function testMustFailWhenCategoriaDoesNotExist()
    {
        $values = [];
        $values['descripcion'] = 'Gasto Cochino';
        $values['fecha'] = '2024-01-01';
        $values['monto'] = 100.0;
        $values['categoria'] = 0;
        $values['persona'] = $this->persona;
        $add = new CreateGastoCommandHandler(
            $this->repository,
            $this->categoriasRepository,
            $this->personasRepository
        );
        $result = $add->handle($values);
        $this->assertEquals(HttpStatusCode::INTERNAL_SERVER_ERROR, $result['code'], $result['message']);
        $this->assertCount(0, $this->repository->getAll());
    }

    public function testMustFailWhenPersonaDoesNotExist()
    {
        $values = [];
        $values['descripcion'] = 'Gasto Cochino';
        $values['fecha'] = '2024-01-01';
        $values['monto'] = 100.0;
        $values['categoria'] = $this->categoria;
        $values['persona'] = 0;
        $add = new CreateGastoCommandHandler(
            $this->repository,
            $this->categoriasRepository,
            $this->personasRepository
        );
        $result = $add->handle($values);
        $this->assertEquals(HttpStatusCode::INTERNAL_SERVER_ERROR, $result['code'], $result['message']);
        $this->assertCount(0, $this->repository->getAll());
    }

    private function addCategoria()
    {
        $values = [];
        $values['descripcion'] = 'Categoria 1';
        $command = new CreateCategoriaCommandHandler($this->categoriasRepository);
        $result = $command->handle($values);
        return $result['data'];
    }

    private function addPersona()
    {
        $values = [];
        $values['apellido'] = 'Pankie';
        $values['nombre'] = 'Marcos';
        $values['apodo'] = 'El Pankie';
        $add = new CreatePersonaCommandHandler($this->personasRepository);
        $result = $add->handle($values);
        return $result['data'];
    }
}
