<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CardRepository;
use App\Http\Resources\CardResource;

class CardController extends Controller
{
    private $cardRepository;

    public function __construct()
    {
        $this->cardRepository = new CardRepository();
    }

    public function index()
    {
        return response()->json(CardResource::collection($this->cardRepository->all()));
    }
}
