<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageUploadController extends Controller
{
    public function __construct()
    {
        $this->archivesService = \App::make('\App\Services\Archives\ArchivesServiceInterface');
    }

    public function process(Request $request)
    {
        $results = $this->archivesService->getAll($request->all());

        $this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $input['imagename'] = time().'.'.$image->getClientOriginalExtension();
   
        $destinationPath = public_path('/thumbnails');
        $img = \Image::make($image->getRealPath());
        $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save($destinationPath.'/'.$input['imagename']);

        $destinationPath = public_path('/images');
        $image->move($destinationPath, $input['imagename']);

        return response()->json([
            'success' => true, 
            'name' => $input['imagename'],
            'source' => config('app.url') . '/images/' . $input['imagename'],
            'sourceThumbnails' => config('app.url') . '/thumbnails/' . $input['imagename']
        ], 200);
    }
}
