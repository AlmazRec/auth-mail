<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;

class BookRepository implements BookRepositoryInterface
{

    public function allWithPaginate()
    {
        return Book::paginate(15);
    }

    public function getById(int $id)
    {
        return Book::find($id);
    }
}
