<?php

namespace App\Domain\Common;

interface Base
{
    public function create($data);
    public function update($data);
    public function delete($id);
    public function findById($id);
    public function getAll();
}