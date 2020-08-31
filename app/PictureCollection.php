<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PictureCollection extends Model
{

    protected $table = 'picture_collection';

    protected $guarded = [];

    public function collection()
    {
        return $this->hasOne('App\Collection', 'id', 'collection_id');
    }
}
