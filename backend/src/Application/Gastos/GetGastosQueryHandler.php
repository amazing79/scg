<?php

namespace App\Application\Gastos;

use App\Domain\Common\Conts\HttpStatusCode;
use App\Domain\Common\Conts\HttpStatusMessages;
use App\Domain\Common\Conts\Periodos;
use App\Domain\Common\Presenter;
use App\Domain\Gastos\GastosRepository;
class GetGastosQueryHandler
{
    public function __construct(
        private GastosRepository $repository,
        private ?Presenter $presenter = null
    )
    {

    }

    private function hasPresenter(): bool
    {
        return !is_null($this->presenter);
    }

    public function handle(): array
    {
        $response = [];
        $response['code'] = HttpStatusCode::INTERNAL_SERVER_ERROR;
        $response['message'] = HttpStatusMessages::getMessage(HttpStatusCode::INTERNAL_SERVER_ERROR);
        try {
            //creo periodo para filtro
            $periodo = Periodos::getPeriodoActual();
            $anio = Periodos::getAnioActual();
            $filterPeriodo = Periodos::makePeriodoFilter($periodo, $anio);
            $gastos = $this->repository->getAll($filterPeriodo);
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

    private function convertDataWithPresenter($gastos): array
    {
        $converted = [];
        foreach ($gastos as $gasto) {
            $converted[] = $this->presenter->convert($gasto);
        }
        return $converted;
    }
}