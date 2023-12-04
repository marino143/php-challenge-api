<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Ability;
use App\Http\Middleware\ShowOwner;
use App\Http\Repository\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class ModelController extends Controller
{
    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function view(Request $request, string $model): JsonResponse
    {
        try {
            $response = (new Model())->view($request, $model);

            return response()->json($response->getOriginalContent(), $response->getStatusCode());

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function list(Request $request, string $model): JsonResponse
    {
        try {
            $response = (new Model())->list($request, $model);

            return response()->json($response->getOriginalContent(), $response->getStatusCode());

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function create(Request $request, string $model): JsonResponse
    {
        try {
            $response = (new Model())->create($request, $model);

            return response()->json($response->getOriginalContent(), $response->getStatusCode());

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function update(Request $request, string $model): JsonResponse
    {
        try {
            $response = (new Model())->update($request, $model);

            return response()->json($response->getOriginalContent(), $response->getStatusCode());

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function delete(Request $request, string $model): JsonResponse
    {
        try {
            $response = (new Model())->delete($request, $model);

            return response()->json($response->getOriginalContent(), $response->getStatusCode());

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }
}
