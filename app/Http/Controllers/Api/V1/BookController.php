<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    protected BookRepositoryInterface $bookRepository;

    public function __construct(BookRepositoryInterface $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->bookRepository->allWithPaginate());
    }


    public function show($book): JsonResponse
    {
        return response()->json($this->bookRepository->getById($book));
    }
}
