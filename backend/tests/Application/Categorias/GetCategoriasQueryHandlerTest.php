<?php

namespace Tests\Application\Categorias;

use App\Application\Categorias\GetCategoriasQueryHandler;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Infrastructure\Categorias\InMemoryCategoriasRepository;
use PHPUnit\Framework\TestCase;

class GetCategoriasQueryHandlerTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();
        $this->repository = new InMemoryCategoriasRepository();
    }

    public function testHandle()
    {
        $query = new GetCategoriasQueryHandler($this->repository);
        $result = $query->handle();
        $this->assertEquals(HttpStatusCode::OK, $result['code'], $result['message']);
    }
}
