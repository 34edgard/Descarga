<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'documentable_type',
        'documentable_id',

        'file_name',
        'file_path',
        'mime_type',
        'size',
        'status',
        'comments'
    ];

    public function documentable()
    {
        return $this->morphTo();
    }
}
