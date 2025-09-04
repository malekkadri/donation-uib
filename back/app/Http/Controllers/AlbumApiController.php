<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Albums;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AlbumApiController extends Controller
{
    public function index()
    {
        $albums = Albums::where('status', 1)->with('media')->get();
        return response()->json(['data' => $albums]);
    }
    public function publicIndex()
    {
        $albums = Albums::where('status', 1)->select('id', 'name')->get();
        return response()->json($albums);
    }

    public function show($id)
    {
        $album = Albums::where('id', $id)->where('status', 1)->with('media')->first();
        
        if(!$album) {
            return response()->json(['error' => 'Album not found'], 404);
        }
        
        return response()->json(['data' => $album]);
    }

    public function store(Request $request)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You\'re not authorized'], 401);
        }

        $request->validate([
            'title' => 'required|string|min:20|max:100',
            'description' => 'required|min:50',
            'images' => 'required|array',
            'status' => 'nullable',
        ]);

        DB::beginTransaction();
        $saved_images = [];

        try {
            $album = new Albums();
            $album->name = $request->title;
            $album->description = $request->description;
            $album->status = $request->status ? $request->status : 0;
            $album->save();

            if($request->has('images')){
                foreach($request->images as $image){
                    $filename = \Str::random(10) . time().'.'. $image->extension();
                    $image->move(public_path('images/albums'), $filename);
                    $media = new Media();
                    $media->name = $filename;
                    $media->album_id = $album->id;
                    $media->save();
                    $saved_images[] = $filename;
                }
            }

            DB::commit();
            return response()->json(['data' => $album, 'message' => 'Album created successfully'], 201);
        } catch (\Throwable $e) {
            DB::rollback();

            if($saved_images){
                foreach($saved_images as $image){
                    File::delete("images/albums/".$image);
                }
            }

            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You\'re not authorized'], 401);
        }

        $request->validate([
            'title' => 'required|string|min:20|max:100',
            'description' => 'required|min:50',
            'status' => 'nullable',
            'images' => 'nullable|array',
        ]);

        $album = Albums::find($id);
        if (!$album) return response()->json(['error' => 'Album not found'], 404);

        DB::beginTransaction();
        $saved_images = [];
        try {
            if($request->has('images')){
                foreach($request->images as $image){
                    $filename = \Str::random(10) . time().'.'. $image->extension();
                    $image->move(public_path('images/albums'), $filename);
                    $media = new Media();
                    $media->name = $filename;
                    $media->album_id = $id;
                    $media->save();
                    $saved_images[] = $filename;
                }
            }

            $album->name = $request->title;
            $album->description = $request->description;
            $album->status = $request->status ? $request->status : 0;
            $album->save();

            DB::commit();
            return response()->json(['data' => $album, 'message' => 'Album updated successfully']);
        } catch (\Exception $e) {
            DB::rollback();

            if(!empty($saved_images)){
                foreach($saved_images as $image){
                    File::delete("images/albums/".$image);
                }
            }

            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You\'re not authorized'], 401);
        }

        $medias = Media::where("album_id", $id)->get();

        DB::beginTransaction();
        try {
            Albums::where('id', $id)->delete();
            Media::where('album_id', $id)->delete();

            foreach($medias as $media){
                File::delete(public_path('images/albums/').$media->name);
            }

            DB::commit();
            return response()->json(['message' => 'Album deleted successfully']);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Unable to delete album: ' . $e->getMessage()], 500);
        }
    }

    public function deleteMedia($id)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You\'re not authorized'], 401);
        }

        $media = Media::find($id);
        if (!$media) {
            return response()->json(['error' => 'Media not found'], 404);
        }

        DB::beginTransaction();
        try {
            $filename = $media->name;
            $media->delete();
            File::delete(public_path('images/albums/').$filename);

            DB::commit();
            return response()->json(['message' => 'Media deleted successfully']);
        } catch(\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Unable to delete media: ' . $e->getMessage()], 500);
        }
    }

    public function uploadCKEditorMedia(Request $request)
    {
        $request->validate([
            "upload" => "required|mimes:png,jpg,gif|max:1024",
        ]);

        $filename = \Str::random(10) . time().'.'. $request->upload->extension();
        if($request->upload->move(public_path('uploads'), $filename)){
            return response()->json([
                "uploaded" => 1,
                "fileName" => $filename,
                "url" => url('uploads') . "/" . $filename,
            ]);
        }

        return response()->json(['error' => 'Failed to upload image'], 500);
    }
}