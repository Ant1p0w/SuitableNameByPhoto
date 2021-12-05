<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoTaskRequest;
use App\Models\PhotoTask;

class PhotoTaskController extends Controller
{
    public function getTask(PhotoTaskRequest $request)
    {
        $validated = $request->validated();

        $photoTask = PhotoTask::find($validated['task_id']);

        if ($photoTask) {
            return response([
                "status" => $photoTask->status,
                "result" => $photoTask->result
            ]);
        } else {
            return response([
                "status" => 'not_found',
                "result" => null
            ])->setStatusCode(404);
        }
    }
}
