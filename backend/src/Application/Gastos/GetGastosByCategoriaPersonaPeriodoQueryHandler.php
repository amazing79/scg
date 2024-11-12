<?php

namespace App\Application\Gastos;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Conts\Periodos;
use App\Domain\Common\Presenter;
use App\Domain\Gastos\GastosRepository;

class GetGastosByCategoriaPersonaPeriodoQueryHandler
{
    /**
     * @param GastosRepository $repository
     * @param Presenter|null $presenter
     */
    public function __construct(
        private GastosRepository $repository,
        private ?Presenter $presenter = null
    )
    {
    }

    public function handle(array $filter): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            //creo periodo para filtro
            $periodo = $filter['periodo'];
            $anio = $filter['anio'];
            $filterPeriodo = Periodos::makePeriodoFilter($periodo, $anio);
            $gastos = $this->repository->getGastosByCategoriaPersonaPeriodo($filterPeriodo);

            if ($this->hasPresenter()) {
                $gastos = $this->convertDataWithPresenter($gastos);
            }
            $response['code'] = HttpStatusCode::OK;
            $response['data'] = $gastos;
            $response['message'] = 'Gastos obtenidos correctamente';
            $response['totalFound'] = count($gastos);
        } catch (\Exception $e) {
            $response['message'] = "Code error: {$e->getCode()} - descripcion: {$e->getMessage()}";
        } finally {
            return $response;
        }
    }

    private function hasPresenter(): bool
    {
        return isset($this->presenter);
    }

    private function convertDataWithPresenter(array $gastos): array
    {
        $converted = [];
        foreach ($gastos as $gasto) {
            $converted[] = $this->presenter->convert($gasto);
        }
        return $converted;
    }
}