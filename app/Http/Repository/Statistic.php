<?php

namespace App\Http\Repository;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class Statistic extends Controller
{
    /**
     * @return JsonResponse
     */
    public function statisticAdministration(): JsonResponse
    {
        $statistic = collect([
            'users' => User::all()->count(),
            'tasks' => Task::all()->count(),
        ]);

        return response()->json(["statistic" => $statistic]);
    }
}
