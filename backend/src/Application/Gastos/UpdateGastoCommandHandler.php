<?php

namespace App\Application\Gastos;

use App\Domain\Categorias\CategoriasRepository;
use App\Domain\Categorias\Exceptions\NotFoundCategoryException;
use App\Domain\Common\Traits\EnsureObjectExists;
use App\Domain\Gastos\Exceptions\GastoNotFoundExceptions;
use App\Domain\Gastos\Gastos;
use App\Domain\Gastos\GastosRepository;
use App\Domain\Personas\Exceptions\PersonaNotFoundException;
use App\Domain\Personas\PersonasRepository;

class UpdateGastoCommandHandler
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
            $id = $values['id'];
            $this->assertObjectExist(
                $id,
                $this->repository,
                new GastoNotFoundExceptions());
            $gasto = Gastos::fromArray($values);
            $this->repository->update($gasto);
            $response['code'] = 200;
            $response['message'] = 'Gasto actualizado correctamente';
        } catch (NotFoundCategoryException $e) {
            $response['message'] = "No se encontro la categoria para la cual quiere registrar el gasto";
        } catch (PersonaNotFoundException $e) {
            $response['message'] = "No se encontro la persona para la cual quiere registrar el gasto";
        } catch (GastoNotFoundExceptions $e) {
            $response['code'] = $e->getCode();
            $response['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $response['code'] = 500;
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }
}