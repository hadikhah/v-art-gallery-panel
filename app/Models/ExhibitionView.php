<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExhibitionView extends Model
{
    protected $fillable = [
        "exhibition_id",
        "user_id",
        "view_date",
        "view_hour",
        "views",
    ];

    public function exhibition()
    {
        return $this->belongsTo(Exhibition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
