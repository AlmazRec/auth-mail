<?php

namespace App\Repositories\Interfaces;

interface BookRepositoryInterface
{
    public function allWithPaginate();
    public function getById(int $id);
}
