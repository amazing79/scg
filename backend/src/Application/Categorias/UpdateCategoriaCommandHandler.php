<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\Categoria;
use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Traits\EnsureObjectExists;

class UpdateCategoriaCommandHandler
{
    use EnsureObjectExists;
    private CategoriasRepository $repository;

    public function __construct(CategoriasRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(array $values): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $id = (int) $values['id'] ?? 0;
            $this->assertObjectExist(
                $id,
                $this->repository,
                new NotFoundCategoryException());
            $categoria = Categoria::createFromArray($values);
            $this->repository->update($categoria);
            $response['code'] = HttpStatusCode::OK;
            $response['message'] = "la categoría se ha actualizado correctamente";
        }  catch (NotFoundCategoryException $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }
}