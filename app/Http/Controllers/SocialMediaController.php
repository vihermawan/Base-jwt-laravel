<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SocialMedia;
use JWTAuth;

class SocialMediaController extends Controller
{
    protected $user;
 
    public function __construct()
    {
    $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
    return $this->user
        ->socialMedias()
        ->get(['id','user_id', 'social_media', 'username'])
        ->toArray();
    }

    public function show($id)
    {
    $socialmedia = $this->user->socialMedias()->find($id);
 
    if (!$socialmedia) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, social media with id ' . $id . ' cannot be found'
        ], 400);
    }
    return $socialmedia;
    }

    public function store(Request $request)
{
    $this->validate($request, [
        'social_media' => 'required',
        'username' => 'required'
    ]);
 
    $socialmedia = new SocialMedia();
    $socialmedia->social_media = $request->social_media;
    $socialmedia->username = $request->username;
 
    if ($this->user->socialMedias()->save($socialmedia)){
        return response()->json([
            'success' => true,
            'social_media' => $socialmedia
        ]);
    }else{
        return response()->json([
            'success' => false,
            'message' => 'Sorry, social media could not be added'
        ], 500);
    }
    
    return $socialmedia;
    }

    public function update(Request $request, $id)
{
    $socialmedia = $this->user->socialMedias()->find($id);
 
    if (!$socialmedia) {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, social media with id ' . $id . ' cannot be found'
        ], 400);
    }
 
    $updated = $socialmedia->fill($request->all())
        ->save();
 
    if ($updated) {
        return response()->json([
            'success' => true
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Sorry, social media could not be updated'
        ], 500);
    }
}

    public function destroy($id)
    {
        $socialmedia = $this->user->socialMedias()->find($id);

        if (!$socialmedia) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, social media with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($socialmedia->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Social Media could not be deleted'
            ], 500);
        }
    }

}
