<?php

namespace App\Http\Utilities;

use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserVerificationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Utility
{
    /**
     * @param string $model
     * @return string|null
     */
    public function getModelClass(string $model): ?string
    {
        $modelClass = 'App\\Models\\' . Str::ucfirst(Str::camel($model));

        if (!class_exists($modelClass)) {
            return null;
        }

        return $modelClass;
    }

    /**
     * @param string $modelClass
     * @param Request $request
     * @return mixed
     */
    public function getQueryView(string $modelClass, Request $request): mixed
    {
        $model = new $modelClass;

        $query = $modelClass::query();

        $with = json_decode($request->get('with'));

        if ($with) {
            foreach ($with as $withQuery) {
                if (method_exists($model, $withQuery)) {
                    $query->with($withQuery);
                }
            }
        }

        return $query;
    }

    /**
     * @param string $modelClass
     * @param Request $request
     * @return mixed
     */
    public function getQueryList(string $modelClass, Request $request): mixed
    {
        $model = new $modelClass;
        $query = $modelClass::query();

        $searchField = $request->get('searchField', 'name');
        $searchQuery = $request->get('searchQuery');
        $with = json_decode($request->get('with'));
        $where = json_decode($request->get('where'));
        $orderBy = $request->get('orderBy', 'created_at');
        $orderType = $request->get('orderType', 'asc');
        $limit = $request->get('limit', false);

        if ($searchQuery) {
            $query->where($searchField, 'LIKE', '%' . $searchQuery . '%');
        }

        if ($with) {
            foreach ($with as $withQuery) {
                if (method_exists($model, $withQuery)) {
                    $query->with($withQuery);
                }
            }
        }

        if ($where) {
            foreach ($where as $whereQuery) {
                $query->where($whereQuery->field, $whereQuery->operator ?? '=', $whereQuery->value);
            }
        }

        if ($limit !== false) {
            $query->limit($limit);
        }

        $query->orderBy($orderBy, $orderType);

        return $query;
    }

    /**
     * @param $query
     * @return TaskResource|UserResource|UserVerificationResource
     */
    public function getResultView($query): TaskResource|UserResource|UserVerificationResource
    {
        $model = $query->getModel()->getTable();
        $result = $query->first();

        return match ($model) {
            'tasks' => new TaskResource($result),
            'users' => new UserResource($result),
            'user_verifications' => new UserVerificationResource($result),
            default => [],
        };
    }

    /**
     * @param $query
     * @param Request $request
     * @return array
     */
    public function getResultList($query, Request $request): array
    {
        $model = $query->getModel()->getTable();

        $paginate = $request->query('paginate', 'false');
        $paginate = $paginate === 'true';

        $results = match ($paginate) {
            true => $query->paginate(env('APP_PAGINATE')),
            default => $query->get(),
        };

        $response = [
            $model => [],
        ];

        if ($paginate) {
            $response['pagination'] = [
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'total_pages' => $results->lastPage(),
            ];
        }

        match ($model) {
            'tasks' => [
                $response[$model] = TaskResource::collection($results),
            ],
            'users' => [
                $response[$model] = UserResource::collection($results),
            ],
            'user_verifications' => [
                $response[$model] = UserVerificationResource::collection($results),
            ],
            default => [],
        };

        return $response;
    }
}
