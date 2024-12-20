<?php

namespace Tests\Application\Categorias;

use App\Application\Categorias\CreateCategoriaCommandHandler;
use App\Application\Categorias\UpdateCategoriaCommandHandler;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Infrastructure\Categorias\InMemoryCategoriasRepository;
use PHPUnit\Framework\TestCase;

class UpdateCategoriaCommandHandlerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new InMemoryCategoriasRepository();
    }

    public function testHandle()
    {
        $values = [];
        $values['descripcion'] = 'Categoria 1';
        $add = new CreateCategoriaCommandHandler($this->repository);
        $response = $add->handle($values);
        $id =  $response['data'];
        $values['id'] = $id;
        $values['descripcion'] = 'Categoria 2';
        $update = new UpdateCategoriaCommandHandler($this->repository);
        $result = $update->handle($values);
        $this->assertEquals(HttpStatusCode::OK, $result['code'], $result['message']);
        $categoria = $this->repository->findById($id);
        $this->assertEquals($values['descripcion'], $categoria->getDescripcion());
    }

    public function testMustFailWhenDescriptionIsEmpty()
    {
        $values = [];
        $values['descripcion'] = 'Categoria 1';
        $add = new CreateCategoriaCommandHandler($this->repository);
        $response = $add->handle($values);
        $id =  $response['data'];
        $values['id'] = $id;
        $values['descripcion'] = '';
        $update = new UpdateCategoriaCommandHandler($this->repository);
        $result = $update->handle($values);
        $this->assertEquals(HttpStatusCode::INTERNAL_SERVER_ERROR, $result['code'], $result['message']);
        $categoria = $this->repository->findById($id);
        $this->assertNotEquals($values['descripcion'], $categoria->getDescripcion());
    }

    public function testMustFailWhenCategoriaDoesNotExist()
    {
        $values = [];
        $values['descripcion'] = 'Categoria 1';
        $add = new CreateCategoriaCommandHandler($this->repository);
        $response = $add->handle($values);
        $id =  $response['data'];
        $values['id'] = 0;
        $values['descripcion'] = 'Categoria 2';
        $update = new UpdateCategoriaCommandHandler($this->repository);
        $result = $update->handle($values);
        $this->assertEquals(HttpStatusCode::NOT_FOUND, $result['code'], $result['message']);

    }

}
