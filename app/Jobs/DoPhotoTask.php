<?php

namespace App\Jobs;

use App\Models\PhotoTask;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DoPhotoTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $photoTask;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PhotoTask $photoTask)
    {
        $this->photoTask = $photoTask;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Если у задачи есть retry_id, то запрашиваем по нему
       if($this->photoTask->retry_id){
           $response = Http::post('http://merlinface.com:12345/api/', [
               'retry_id' => $this->photoTask->retry_id,
           ])->json();
       }else{

           //Если первый раз, то шлем запрос с файлом
           $response = Http::attach(
               'photo', Storage::get( $this->photoTask->photo->file_name),  $this->photoTask->photo->file_name
           )->post('http://merlinface.com:12345/api/', ['name' =>  $this->photoTask->photo->user_name])->json();
       }

       //Если в ответ получили retry_id
       if($response['retry_id']){

           //Обновляем в текущей задаче retry_id
           $this->photoTask->update(['retry_id' => $response['retry_id']]);

           //Ставим повторно задачу в очередь
           DoPhotoTask::dispatch($this->photoTask);
       }else{

           //Сохраняем результат
           $this->photoTask->update(['status' => $response['status'], 'result' => $response['result']]);
       }

       return $response;
    }
}
