<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class MediaController extends Controller
{
    public function show(string $uuid)
    {
        // Check authorize if user can view this media
        $media = Media::where('uuid', $uuid)->firstOrFail();
        $post = Post::query()->where('id', $media->model_id)->firstOrFail();

        abort_unless(in_array($post->team_id, auth()->user()->teams->modelKeys()), Response::HTTP_UNAUTHORIZED);

        $pathToFile = $media->getPath();

        return response()->download($pathToFile);
    }
}
