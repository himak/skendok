<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Odosielatel;
use App\Models\Post;
use App\Models\Team;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('post_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $posts = Post::with(['team', 'odosielatel', 'media'])->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        abort_if(Gate::denies('post_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teams = Team::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $odosielatels = Odosielatel::pluck('odosielatel', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.posts.create', compact('odosielatels', 'teams'));
    }

    public function store(StorePostRequest $request)
    {
        
         if (is_numeric($request->odosielatel_id) == false){
            $odosielatel = new Odosielatel;
            $odosielatel->odosielatel = $request->odosielatel_id;
            $requestData=$request->all();
            $odosielatel->save();
            $requestData["odosielatel_id"]=$odosielatel->id;
        }else{
            $requestData=$request->all();
        } 

        $post = Post::create($requestData);

        if ($request->input('scan', false)) {
            $post->addMedia(storage_path('tmp/uploads/' . basename($request->input('scan'))))->toMediaCollection('scan');
        }

        if ($request->input('envelope', false)) {
            $post->addMedia(storage_path('tmp/uploads/' . basename($request->input('envelope'))))->toMediaCollection('envelope');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $post->id]);
        }

        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        abort_if(Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $teams = Team::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $odosielatels = Odosielatel::pluck('odosielatel', 'id')->prepend(trans('global.pleaseSelect'), '');

        $post->load('team', 'odosielatel');

        return view('admin.posts.edit', compact('odosielatels', 'post', 'teams'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {

        if (is_numeric($request->odosielatel_id) == false){
            $odosielatel = new Odosielatel;
            $odosielatel->odosielatel = $request->odosielatel_id;
            $requestData=$request->all();
            $odosielatel->save();
            $requestData["odosielatel_id"]=$odosielatel->id;
        }else{
            $requestData=$request->all();
        } 

        $post->update($requestData);

        if ($request->input('scan', false)) {
            if (! $post->scan || $request->input('scan') !== $post->scan->file_name) {
                if ($post->scan) {
                    $post->scan->delete();
                }
                $post->addMedia(storage_path('tmp/uploads/' . basename($request->input('scan'))))->toMediaCollection('scan');
            }
        } elseif ($post->scan) {
            $post->scan->delete();
        }

        if ($request->input('envelope', false)) {
            if (! $post->envelope || $request->input('envelope') !== $post->envelope->file_name) {
                if ($post->envelope) {
                    $post->envelope->delete();
                }
                $post->addMedia(storage_path('tmp/uploads/' . basename($request->input('envelope'))))->toMediaCollection('envelope');
            }
        } elseif ($post->envelope) {
            $post->envelope->delete();
        }

        return redirect()->route('admin.posts.index');
    }

    public function show(Post $post)
    {
        abort_if(Gate::denies('post_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->load('team', 'odosielatel');

        return view('admin.posts.show', compact('post'));
    }

    public function destroy(Post $post)
    {
        abort_if(Gate::denies('post_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $post->delete();

        return back();
    }

    public function massDestroy(MassDestroyPostRequest $request)
    {
        $posts = Post::find(request('ids'));

        foreach ($posts as $post) {
            $post->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('post_create') && Gate::denies('post_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Post();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
