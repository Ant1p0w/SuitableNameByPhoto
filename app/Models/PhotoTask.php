<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoTask extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'result', 'retry_id', 'photo_id'];
    protected $hidden = ['id', 'photo_id', 'created_at', 'updated_at'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}
