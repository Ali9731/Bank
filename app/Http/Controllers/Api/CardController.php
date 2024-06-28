<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Repositories\Card\CardRepositoryInterface;

class CardController extends Controller
{
    public function __construct(protected CardRepositoryInterface $cardRepository) {}

    public function index()
    {
        return response()->json(CardResource::collection($this->cardRepository->all()));
    }
}
