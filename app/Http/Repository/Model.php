<?php

namespace App\Http\Repository;

use App\Http\Utilities\Utility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Model
{
    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function view(Request $request, string $model): JsonResponse
    {
        $modelClass = (new Utility())->getModelClass($model);

        if (!$modelClass) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        $query = (new Utility())->getQueryView($modelClass, $request);
        $result = (new Utility())->getResultView($query);

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function list(Request $request, string $model): JsonResponse
    {
        $modelClass = (new Utility())->getModelClass($model);

        if (!$modelClass) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $query = (new Utility())->getQueryList($modelClass, $request);
        $result = (new Utility())->getResultList($query, $request);

        return response()->json($result);
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function create(Request $request, string $model): JsonResponse {
        $modelClass = (new Utility())->getModelClass($model);

        if (!$modelClass) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $model = new $modelClass;
        $result = $model::create($request->all());

        return response()->json(['result' => $result, 'message' => 'Successfully saved'], 200);
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function update(Request $request, string $model): JsonResponse {
        $modelClass = (new Utility())->getModelClass($model);

        if (!$modelClass) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $model = new $modelClass;
        $result = $model::where('id', '=', $request->__get('id'))->first();

        if (!$result) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        switch ($modelClass) {
            case 'App\Models\User':
                $result->update($request->except(['password']));
                break;
            default:
                $result->update($request->all());
        }

        return response()->json(['message' => 'Successfully updated'], 200);
    }

    /**
     * @param Request $request
     * @param string $model
     * @return JsonResponse
     */
    public function delete(Request $request, string $model): JsonResponse {
        $modelClass = (new Utility())->getModelClass($model);

        if (!$modelClass) {
            return response()->json(['message' => 'Model not found'], 404);
        }

        $model = new $modelClass;
        $result = $model::where('id', '=', $request->__get('id'))->first();

        if (!$result) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $result->delete();

        return response()->json(['message' => 'Successfully removed'], 200);
    }
}
