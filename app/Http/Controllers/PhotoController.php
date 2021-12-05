<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Jobs\DoPhotoTask;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function create(PhotoRequest $request)
    {
        $validated = $request->validated();
        $fileMd5 = md5($request->file('photo')->getContent());
        $path = $request->file('photo')->store('storage/uploads');

        //Создаем или находим запись
        $photo = Photo::firstOrCreate(
            ['user_name' => $validated['name'], 'file_md5' => $fileMd5],
            ['user_name' => $validated['name'], 'file_md5' => $fileMd5, 'file_name' => $path]
        )->load('photo_task');

        //Создаем запись результата
        if(is_null($photo->photo_task)){
            $photo->photo_task()->create(['status' => 'received']);
            $photo->load('photo_task');

            //Ставим задачу в очередь на обработку
            DoPhotoTask::dispatch($photo->photo_task);
        }

        return [
            "status" => $photo->photo_task->status,
            "task"   => $photo->photo_task->id,
            "result" => $photo->photo_task->result
        ];
    }
}
