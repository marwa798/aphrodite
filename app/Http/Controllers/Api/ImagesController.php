<?php

namespace App\Http\Controllers\Api;

use App\Collection;
use App\Pictures;
use App\PictureCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Likes;
use App\Follow;
use App\Tag;
use App\PictureTag;
use App\Users;
use Illuminate\Support\Facades\DB;

class ImagesController extends Controller
{
    /**
     * This funtion Add Image To Collections
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function saveImageToCollection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id'  => 'required|integer',
            'collection_ids' => 'required'
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }
        
        if(!Pictures::where('id', $request->get('image_id'))->first())
        {
            $json['errors'][] = 'Image Not Found';

            return response()->json($json, 404);
        }

        $image_id = $request->get('image_id');
        foreach($request->get('collection_ids') as $collection_id){
            if(!Collection::where('id', $collection_id)->where('user_id', authApi()->user()->id)->first() || PictureCollection::where('picture_id', $image_id)->where('collection_id', $collection_id)->first())
                continue;
                
            PictureCollection::create([
                'picture_id' => $image_id,
                'collection_id' => $collection_id
            ]);
        }
    

       
        $json['message'] = 'Success add Image to Collection';
        $json['data'] = [];

        return response()->json($json, 200);
    }
    
    /**
     * This funtion Add Image To Collections
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function removeImageToCollection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id'  => 'required|integer',
            'collection_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }
        
        if(!Pictures::where('id', $request->get('image_id'))->first())
        {
            $json['errors'][] = 'Image Not Found';

            return response()->json($json, 404);
        }

        $image_id = $request->get('image_id');
        $collection_id = $request->get('collection_id');
        
        if(Collection::where('id', $collection_id)->where('user_id', authApi()->user()->id)->first() && 
            $collectionPicture = PictureCollection::where('picture_id', $image_id)->where('collection_id', $collection_id)->first()){
            $collectionPicture->delete();
        }else{
            $json['errors'][] = 'You Don\'t Have This Image :(';

            return response()->json($json, 400);
        }
        
       
        $json['message'] = 'Success Remove Image from Collection';
        $json['data'] = [];

        return response()->json($json, 200);
    }
    /**
     * This funtion Create Collection
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function createCollection(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'name'  => 'required|min:3',
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }
        
        $name = $request->get('name');

        $collection = Collection::create([
            'user_id' => authApi()->user()->id,
            'name' => $name
        ]);

        $json['message'] = 'Success Create Collection ' . $name;
        $json['data']['collection'] = [
            'id'   =>  $collection->id,
            'name' =>  $collection->name
        ];

        return response()->json($json, 200);

    }
    
    public function getCollections(Request $request)
    {
        $limit = $request->get('limit') ?? 10;

        $json['message'] = '';
        
        $json['data']['collections'] = Collection::where('user_id', authApi()->user()->id)->paginate($limit);

        return response()->json($json, 200);
    }

    /**
     * This funtion Delete Collection
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function deleteCollection(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'id'  => 'required',
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        if(!$collection = Collection::where('user_id', authApi()->user()->id)->where('id', $request->get('id'))->first())
        {
            $json['errors'][] = 'Collection Not Found';

            return response()->json($json, 404);
        }
        
        $name = $collection->name;

        $collection->delete();

        PictureCollection::where('collection_id', $request->get('id'))->delete();

        $json['message'] = 'Success Delete Collection ' . $name;
        $json['data'] = [];

        return response()->json($json, 200);
    }

    /**
     * This funtion Update Collection Name
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function updateCollection(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'id'  => 'required',
            'name' => 'required|min:3'
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        if(!$collection = Collection::where('user_id', authApi()->user()->id)->where('id', $request->get('id'))->first())
        {
            $json['errors'][] = 'Collection Not Found';

            return response()->json($json, 404);
        }
        
        $collection->name = $request->get('name');

        $collection->save();

        $json['message'] = 'Success updated Collection To ' . $request->get('name');
        $json['data'] = [];

        return response()->json($json, 200);
    }

    /**
     * This funtion Upload Image and add to collection & tags
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'  => 'required|image',
            'name' => 'nullable|min:3',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        $file = $request->file('image');

        $file_change_name =  time() . '_' . $file->getClientOriginalName();

        Storage::disk('public')->put($file_change_name, File::get($file));
        
        $picture = Pictures::create([
            'name' => $request->get('name'),
            'user_id' => authApi()->user()->id,
            'category_id' => $request->get('category_id'),
            'image'       => $file_change_name,
            'caption'     => $request->get('caption') ?? ''
        ]);

        if($request->get('collection_ids')){
            foreach($request->get('collection_ids') as $collection_id){
                PictureCollection::create([
                    'picture_id' => $picture->id,
                    'collection_id' => $collection_id
                ]);
            }
        }

        if($request->get('tags')){
            PictureTag::where('picture_id', $request->get('image_id'))->delete();
            foreach($request->get('tags') as $tag_id){
                if($tag = Tag::where('name', $tag_id)->first()){
                    PictureTag::create([
                        'picture_id' => $picture->id,
                        'tag_id' => $tag->id
                    ]);   
                }else{
                    $tag = Tag::create([
                        'name' => $tag_id,
                    ]);
                    
                    PictureTag::create([
                        'picture_id' => $picture->id,
                        'tag_id' => $tag->id
                    ]);
                }
                
            }
        }
        
        $picture->image = getImage($picture->image);

        $json['message'] = 'Success Upload Image';
        $json['data']['picture'] = $picture;

        return response()->json($json, 200);
    }


    public function enhanceImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'  => 'required|image',
            'name' => 'nullable|min:3',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://api.deepai.org/api/torch-srgan');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'api-key:7046ebaa-0cc8-4613-a0cd-725958ce5c6f'
        ));

        $file = $request->file('image');
        $file_enhanced = curl_exec($ch);

        Storage::disk('public')->put($file_enhanced , File::get($file));

        $picture = Pictures::create([
            'name' => $request->get('name'),
            'user_id' => authApi()->user()->id,
            'category_id' => $request->get('category_id'),
            'image'       => $file_enhanced ,
            'caption'     => $request->get('caption') ?? ''
        ]);

        if($request->get('collection_ids')){
            foreach($request->get('collection_ids') as $collection_id){
                PictureCollection::create([
                    'picture_id' => $picture->id,
                    'collection_id' => $collection_id
                ]);
            }
        }

        if($request->get('tags')){
            PictureTag::where('picture_id', $request->get('image_id'))->delete();
            foreach($request->get('tags') as $tag_id){
                if($tag = Tag::where('name', $tag_id)->first()){
                    PictureTag::create([
                        'picture_id' => $picture->id,
                        'tag_id' => $tag->id
                    ]);
                }else{
                    $tag = Tag::create([
                        'name' => $tag_id,
                    ]);

                    PictureTag::create([
                        'picture_id' => $picture->id,
                        'tag_id' => $tag->id
                    ]);
                }

            }
        }

        $picture->image = getImage($picture->image);

        $json['message'] = 'Success Upload Image';
        $json['data']['picture'] = $picture;
        curl_close($ch);
        return response()->json($json, 200);
    }
    

    /**
     * This funtion update image details
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function updateImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required',
            'name' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        if(!$picture = Pictures::where('id', $request->get('image_id'))->first())
        {
            $json['errors'][] = 'Image Not Found';

            return response()->json($json, 404);
        }

        $dataUpdate = [
            'name' => $request->get('name'),
            'category_id' => $request->get('category_id'),
        ];

        if($request->get('caption'))
            $dataUpdate['caption'] = $request->get('caption');

        if($request->hasFile('image')){
            $file = $request->file('image');

            $file_change_name =  time() . '_' . $file->getClientOriginalName();

            Storage::disk('public')->put($file_change_name, File::get($file));

            $dataUpdate['image'] = $file_change_name;
        }

        Pictures::where('id', $request->get('image_id'))->update($dataUpdate);

        if($request->get('collection_ids')){
            PictureCollection::where('picture_id', $request->get('image_id'))->delete();
            foreach($request->get('collection_ids') as $collection_id){
                PictureCollection::create([
                    'picture_id' => $picture->id,
                    'collection_id' => $collection_id
                ]);
            }
        }

        if($request->get('tags')){
            PictureTag::where('picture_id', $request->get('image_id'))->delete();
            foreach($request->get('tags') as $tag_id){
                if($tag = Tag::where('name', $tag_id)->first()){
                    PictureTag::create([
                        'picture_id' => $picture->id,
                        'tag_id' => $tag->id
                    ]);   
                }else{
                    $tag = Tag::create([
                        'name' => $tag_id,
                    ]);
                    
                    PictureTag::create([
                        'picture_id' => $picture->id,
                        'tag_id' => $tag->id
                    ]);
                }
                
            }
        }
        
        $picture->image = getImage($picture->image);

        $json['message'] = 'Success Upload Image';
        $json['data']['picture'] = $picture;

        return response()->json($json, 200);
    }

    /**
     * This funtion get Image By image_id
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function getImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }

        if(!$picture = $this->getImageById($request->get('id')))
        {
            $json['errors'][] = 'Image Not Found';

            return response()->json($json, 404);
        }
        
        $json['message'] = '';
        $json['data']['picture'] = $picture;

        return response()->json($json, 200);
    }

    /**
     * This funtion Delete Image
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function deleteImage(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'id'  => 'required',
        ]);

        if ($validator->fails()) {
            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 422);
        }
        if(!$image = Pictures::where('user_id', authApi()->user()->id)->where('id', $request->get('id'))->first())
        {
            $json['errors'][] = 'Image Not Found';

            return response()->json($json, 404);
        }
        
        $name = $image->name;

        $image->delete();

        PictureCollection::where('picture_id', $request->get('id'))->delete();
        PictureTag::where('picture_id', $request->get('id'))->delete();


        $json['message'] = 'Success Delete Collection ' . $name;
        $json['data'] = [];

        return response()->json($json, 200);
    }

    /**
     * This funtion get All Images With Fillters
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function getImages(Request $request)
    {
        $sql = '';
        $data = [];
        $join = '';

        if($category_id = $request->get('category_id')){
            $sql .= ' AND category_id = ' . $category_id;
        }

        if($user_id = $request->get('user_id')){
            $sql .= ' AND user_id = ' . $user_id;
        }

        if($collection_id = $request->get('collection_id')){
            $join .= 'RIGHT JOIN picture_collection pc ON p.id = pc.picture_id';

            $sql .= ' AND pc.collection_id = ' . $collection_id;
        }

        if($tag_id = $request->get('tag_id')){
            $join .= ' LEFT JOIN picture_tag pt ON p.id = pt.picture_id';

            $sql .= ' AND pt.tag_id = ' . $tag_id;
        }

        if($word = $request->get('word')){
            $sql .= ' AND caption LIKE "%' . $word . '%"';
        }

        $sql_query = "SELECT p.id FROM pictures p $join WHERE p.id > 0 $sql ORDER BY p.id DESC";

        $query_ids = DB::select($sql_query);

        foreach($query_ids as $id)
        {
            $data[] = $this->getImageById($id->id);
        }
        
        $json['data']['pictures'] = $data;
        return response()->json($json, 200);
    }
    
    /**
     * This funtion like image
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function addLike(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'image_id'  => 'required|integer',
        ]);

        if ($validator->fails()) {

            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 200);
        }
        
        $image_id = $request->get('image_id');
        $current_user_id = authApi()->user()->id;

        if (!Pictures::where('id', $image_id)->exists()) {

            $json['message'] = 'This Image Don\'t Exists';
            $json['isLiked'] = false;
            $json['data'] = [];
            
            return response()->json($json, 200);
        }

        if(Likes::where('user_id', $current_user_id)->where('picture_id', $image_id)->first())
        {
            $json['message'] = 'You Are Already Liked This Image';
            $json['isLiked'] = true;
            $json['data'] = [];
            
            return response()->json($json, 200);
        }

        Likes::create([
            'user_id' => $current_user_id,
            'picture_id' => $image_id
        ]);

        $json['message'] = 'Success Liked';
        $json['isLiked'] = true;
        $json['data'] = [];

        return response()->json($json, 200);
    }

    /**
     * This funtion cancel like image
     * 
     * @param \Illuminate\Http\Request  $request 
     * 
     * @return json
     */
    public function deleteLike(Request $request)
    {
        $json = [];

        $validator = Validator::make($request->all(), [
            'image_id'  => 'required|integer',
        ]);

        if ($validator->fails()) {

            $json['errors'] = $validator->errors()->all();

            return response()->json($json, 200);
        }
        
        $image_id = $request->get('image_id');
        $current_user_id = authApi()->user()->id;

        if (!Pictures::where('id', '=', $image_id)->exists()) {

            $json['message'] = 'This Image Don\'t Exists';
            $json['isLiked'] = false;
            $json['data'] = [];
            
            return response()->json($json, 200);
        }

        if(!$like = Likes::where('user_id', $current_user_id)->where('picture_id', $image_id)->first())
        {
            $json['message'] = 'You Don\'t Liked This Image';
            $json['isLiked'] = false;
            $json['data'] = [];
            
            return response()->json($json, 200);
        }

        $like->delete();

        $json['message'] = 'Success Cancel Like';
        $json['isLiked'] = false;
        $json['data'] = [];

        return response()->json($json, 200);
    }

    /**
     * This funtion all images liked by Login user
     * 
     * @return json
     */
    public function getLikedPictures()
    {
        $liked_images =  Users::find(authApi()->user()->id)->likes()->get();
        
        $images = [];
        
        foreach($liked_images as $image)
        {
            $images[] = $this->getImageByID($image->picture_id);
        }

        $json['data']['pictures'] = $images;
        return response()->json($json, 200);
    }

    /**
     * This funtion all images liked by Login user
     * 
     * @return json
     */
    public function getLiked()
    {
        $json['data']['pictures'] =  Users::find(authApi()->user()->id)->likes()->get();

        return response()->json($json, 200);
    }
    
    /**
     * This funtion all images liked by Login user
     * 
     * @return json
     */
    public function getFollowingImages()
    {
        $pictures = [];
        
        $followings = Users::find(authApi()->user()->id)->followings()->get();

        foreach($followings as $user)
        {
            foreach(Pictures::where('user_id', $user->id)->orderBy('id', 'DESC')->get() as $picture){
                $pictures[] = $this->getImageById($picture->id);
            }
        }
        
        $json['data']['pictures'] = $pictures;

        return response()->json($json, 200);
    }
    
    /**
     * This funtion all images liked by Login user
     * 
     * @return json
     */
    public function getTopLiked()
    {
        $pictures = [];
        
        $pictures_top_liked = DB::table('likes')->select('picture_id', DB::raw('count(*) as total'))
                                ->groupBy('picture_id')
                                ->orderBy('total', 'DESC')
                                ->get();

        foreach($pictures_top_liked as $picture){
            if($pic = $this->getImageById($picture->picture_id))
                $pictures[] = $pic;
        }
    
        
        $json['data']['pictures'] = $pictures;

        return response()->json($json, 200);
    }

    /**
     * This funtion return base image data with id
     * 
     * @param integar $id 
     * 
     * @return json
     */
    private function getImageById($id = 0)
    {
        if(!$picture = Pictures::where('id', $id)->first())
        {
            return [];
        }

        $picture->image = getImage($picture->image);
        $picture['category'] = $picture->category()->select('id', 'name', 'description')->get();
        $picture['collections'] = $picture->collections();
        $picture['tags'] = $picture->tags();
        $picture['isLiked'] = authApi()->user() ? Likes::where('picture_id', $id)->where('user_id', authApi()->user()->id)->first() 
                                                ? true : false : false;
        $picture['likes_count'] = Likes::where('picture_id', $id)->count();
        
        $user = Users::where('id', $picture->user_id)->select('id', 'first_name','last_name', 'profile_pic')->first() ?? [];
        if($user)
            $user->profile_pic = getImage($user->profile_pic);
        
        $user['isFollowed'] = authApi()->user() ? 
                                        Follow::where('followed_id', $picture->user_id)->where('user_id', authApi()->user()->id)->first() 
                                                ? true : false : false;
        
        $picture['user'] = $user;
        

        return $picture;
    }

    public function downloadImage($id){
        
        if(!$picture = Pictures::find($id))
        {
            $json['errors'][] = 'Image Not Found';

            return response()->json($json, 404);
        }

          $pathToFile=storage_path()."/app/public/".$picture->image;
          
          return response()->download($pathToFile);     
     }
}