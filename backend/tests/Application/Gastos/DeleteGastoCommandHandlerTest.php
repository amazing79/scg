<?php

namespace Tests\Application\Gastos;

use App\Application\Categorias\CreateCategoriaCommandHandler;
use App\Application\Gastos\CreateGastoCommandHandler;
use App\Application\Gastos\DeleteGastoCommandHandler;
use App\Application\Personas\CreatePersonaCommandHandler;
use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Gastos\GastosRepository;
use App\Domain\Personas\PersonasRepository;
use App\Infrastructure\Categorias\InMemoryCategoriasRepository;
use App\Infrastructure\Gastos\InMemoryGastosRepository;
use App\Infrastructure\Personas\InMemoryPersonasRepository;
use PHPUnit\Framework\TestCase;

class DeleteGastoCommandHandlerTest extends TestCase
{
    private GastosRepository $repository;
    private CategoriasRepository $categoriasRepository;
    private PersonasRepository $personasRepository;


    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->repository = new InMemoryGastosRepository();
        $this->categoriasRepository = new InMemoryCategoriasRepository();
        $this->personasRepository = new InMemoryPersonasRepository();
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
        $id = $result['data'];
        $delete = new DeleteGastoCommandHandler($this->repository);
        $result = $delete->handle($id);
        $this->assertEquals(200, $result['code'], $result['message']);
        $this->assertCount(0, $this->repository->getAll());
        $gasto = $this->repository->findById($id);
        $this->assertNull($gasto);
    }

    public function testMustFailWhenGastoDoesNotExist()
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
        $id = $result['data'];
        $delete = new DeleteGastoCommandHandler($this->repository);
        $result = $delete->handle(0);
        $this->assertEquals(404, $result['code'], $result['message']);
        $this->assertCount(1, $this->repository->getAll());
        $gasto = $this->repository->findById($id);
        $this->assertNotNull($gasto);
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
