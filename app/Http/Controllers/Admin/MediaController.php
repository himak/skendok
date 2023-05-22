<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function show(string $uuid)
    {
        $media = Media::where('uuid', $uuid)->firstOrFail();

        $pathToFile = $media->getPath();

        return response()->download($pathToFile);
//        return response()->file($pathToFile);
    }
}
