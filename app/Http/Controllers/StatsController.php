<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStatRequest;
use App\Repositories\StatsRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class   StatsController extends Controller
{
    private StatsRepository $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new StatsRepository();
    }

    public function create(CreateStatRequest $request): JsonResponse
    {
        try {
            $playerId = $request->input("player_id");
            $stats    = $request->input("stats");

            $res = $this->repository->createNoCache($playerId, $stats);

            return $this->json($res, Response::HTTP_ACCEPTED);
        } catch( Exception $e ) {
            return $this->jsonException($e);
        }
    }

    public function all(): JsonResponse
    {
        try {
            $res = $this->repository->all();

            return $this->json($res, Response::HTTP_OK);
        } catch( Exception $e ) {
            return $this->jsonException($e);
        }
    }

    public function allTemp(): JsonResponse
    {
        try {
            $res = $this->repository->allTemp();

            return $this->json($res, Response::HTTP_OK);
        } catch( Exception $e ) {
            return $this->jsonException($e);
        }
    }

    public function createCache(CreateStatRequest $request): JsonResponse
    {
        try {
            $playerId = $request->input("player_id");
            $stats    = $request->input("stats");

            $this->repository->createCache($playerId, $stats);

            return $this->json([], Response::HTTP_ACCEPTED);
        } catch( Exception $e ) {
            return $this->jsonException($e);
        }
    }

    public function getCached(int $playerId)
    {
        try {
            $res = $this->repository->getCached($playerId);

            return $this->json($res, Response::HTTP_OK);
        } catch( Exception $e ) {
            return $this->jsonException($e);
        }
    }

    public function getAllCached()
    {
        try {
            $res = $this->repository->getAllCached();

            return $this->json($res, Response::HTTP_OK);
        } catch( Exception $e ) {
            return $this->jsonException($e);
        }
    }
}
