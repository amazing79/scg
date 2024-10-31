<?php

namespace App\Application\Categorias;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;
use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Presenter;
use App\Domain\Common\Traits\EnsureObjectExists;

class GetCategoriaById
{
    use EnsureObjectExists;
    private CategoriasRepository $repository;
    private ?Presenter $presenter;

    public function __construct(CategoriasRepository $repository, Presenter $presenter = null)
    {
        $this->repository = $repository;
        $this->presenter = $presenter;
    }

    private function hasPresenter(): bool
    {
        return !is_null($this->presenter);
    }

    public function handle(int $id): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            $this->assertObjectExist(
                $id,
                $this->repository,
                new NotFoundCategoryException());
            $categoria = $this->repository->findById($id);
            if($this->hasPresenter()) {
                $categoria = $this->presenter->convert($categoria);
            }
            $response['code'] = HttpStatusCode::OK;
            $response['data'] = $categoria;
            $response['message'] = 'Categoria obtenida con exito!';
        } catch (NotFoundCategoryException $e) {
            $response['message'] = $e->getMessage();
            $response['code'] = $e->getCode();
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        }
        return $response;
    }
}