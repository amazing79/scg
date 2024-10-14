<?php

namespace App\Domain\Common\Traits;

use App\Domain\Common\Base;

trait EnsureObjectExists
{
    public function assertObjectExist($objectId, Base $repository, $exceptionThrowable): void
    {
        $object = $repository->findById($objectId);
        if(!$object) {
            throw $exceptionThrowable;
        }
    }
}