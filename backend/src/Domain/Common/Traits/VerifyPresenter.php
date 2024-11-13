<?php

namespace App\Domain\Common\Traits;

trait VerifyPresenter
{
    private function hasPresenter($class): bool
    {
        return !is_null($class->presenter);
    }

    private function convertDataWithPresenter($class, $data): array
    {
        $converted = [];
        foreach ($data as $key => $value) {
            $converted[$key] = $class->presenter->convert($value);
        }
        return $converted;
    }
}