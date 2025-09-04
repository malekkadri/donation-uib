<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MembersApiController extends Controller
{
    public function index()
    {
        $members = Members::all();
        return response()->json(['data' => $members]);
    }

    public function store(Request $request)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You\'re not authorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'designation' => 'required',
            'quote' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $filename = time().'.'. $request->image->extension();
            if (!$request->image->move(public_path('images/members'), $filename)) {
                return response()->json(['error' => 'Unable to save image'], 500);
            }

            $member = Members::create([
                'name' => $request->name,
                'designation' => $request->designation,
                'quote' => $request->quote,
                'image' => $filename
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Member added successfully',
                'data' => $member
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You\'re not authorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'designation' => 'required',
            'quote' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $member = Members::find($id);
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        DB::beginTransaction();
        try {
            if($request->hasFile('image')) {
                $filename = time().'.'. $request->image->extension();
                $oldImage = $member->image;
                
                if ($request->image->move(public_path('images/members'), $filename)) {
                    $member->image = $filename;
                } else {
                    File::delete(public_path('images/members/').$oldImage);
                }
            }

            $member->name = $request->name;
            $member->designation = $request->designation;
            $member->quote = $request->quote;
            $member->save();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Member updated successfully',
                'data' => $member
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        if(auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'You\'re not authorized'], 401);
        }

        $member = Members::find($id);
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        try {
            $imagePath = public_path('images/members/').$member->image;
            $member->delete();
            
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Member deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}