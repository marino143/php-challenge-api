<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Repository\Statistic;
use Illuminate\Http\JsonResponse;
use Exception;

class StatisticController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function statistic(): JsonResponse
    {
        try {
            $response = (new Statistic())->statisticAdministration();

            return response()->json($response->getOriginalContent(), $response->getStatusCode());

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Nešto je pošto po krivu',
            ], 400);
        }
    }
}
