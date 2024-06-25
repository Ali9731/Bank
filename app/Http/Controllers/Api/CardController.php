<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CardRepository;

class CardController extends Controller
{
    private $cardRepository;

    public function __construct()
    {
        $this->cardRepository = new CardRepository();
    }

    public function index()
    {
        return $this->cardRepository->all();
    }
}
