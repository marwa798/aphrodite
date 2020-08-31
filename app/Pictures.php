<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pictures extends Model
{

    protected $table = 'pictures';

    protected $guarded = [];

    public function category()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function collections()
    {
        $data = [];
        $collections = $this->hasMany('App\PictureCollection', 'picture_id', 'id')->get();

        foreach($collections as $collection)
        {

            if($details_collection = Collection::where('id', $collection->collection_id)->select('id', 'name')->first())
                $data[] =  $details_collection;
            
        }

        return $data;
    }

    public function tags()
    {
        $data = [];
        $tags = $this->hasMany('App\PictureTag', 'picture_id', 'id')->get();

        foreach($tags as $tag)
        {

            if($details_tag = Tag::where('id', $tag->tag_id)->select('id', 'name')->first())
                $data[] =  $details_tag;
            
        }

        return $data;
    }
}
