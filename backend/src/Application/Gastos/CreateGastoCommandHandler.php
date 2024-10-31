<?php

namespace App\Application\Gastos;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;
use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;
use App\Domain\Personas\Exceptions\PersonaNotFoundException;
use App\Domain\Personas\PersonasRepository;

class CreateGastoCommandHandler
{
    use EnsureObjectExists;
    public function __construct(
        private GastosRepository $repository,
        private CategoriasRepository $categoriasRepository,
        private PersonasRepository $personasRepository
    )
    {

    }

    public function handle($values): array
    {
        $response = [];
        $response['code'] = 500;
        $response['message'] = 'Error inesperado, por favor intente de nuevo';
        try{
            $this->assertObjectExist(
                $values['categoria'],
                $this->categoriasRepository,
                new NotFoundCategoryException()
            );
            $this->assertObjectExist(
                $values['persona'],
                $this->personasRepository,
                new PersonaNotFoundException()
            );
            $gasto = Gastos::fromArray($values);
            $idGasto = $this->repository->create($gasto);
            $response['data'] = $idGasto;
            $response['code'] = 201;
            $response['message'] = "Gasto registrado correctamente con el id {$idGasto}";
        } catch (NotFoundCategoryException $e) {
            $response['message'] = "No se encontro la categoria para la cual quiere registrar el gasto";
        } catch (PersonaNotFoundException $e) {
            $response['message'] = "No se encontro la persona para la cual quiere registrar el gasto";
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }
}