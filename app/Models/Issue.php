<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        "id",
        "subject",
        "detail",
        "status",
        'created_at',
        'updated_at',
    ];
}
