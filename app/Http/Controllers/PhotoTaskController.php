<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoTaskRequest;
use App\Models\PhotoTask;

class PhotoTaskController extends Controller
{
    public function getTask(PhotoTaskRequest $request)
    {
        $validated = $request->validated();

        //Ищем результат обработки
        $photoTask = PhotoTask::find($validated['task_id']);

        if ($photoTask) {

            //Возвращаем результат
            return response([
                "status" => $photoTask->status,
                "result" => $photoTask->result
            ]);
        } else {

            //Если не нашли, то 404
            return response([
                "status" => 'not_found',
                "result" => null
            ])->setStatusCode(404);
        }
    }
}
