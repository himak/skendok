<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyOdosielatelRequest;
use App\Http\Requests\StoreOdosielatelRequest;
use App\Http\Requests\UpdateOdosielatelRequest;
use App\Models\Odosielatel;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OdosielatelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('odosielatel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odosielatels = Odosielatel::all();

        return view('admin.odosielatels.index', compact('odosielatels'));
    }

    public function create()
    {
        abort_if(Gate::denies('odosielatel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odosielatels.create');
    }

    public function store(StoreOdosielatelRequest $request)
    {
        $odosielatel = Odosielatel::create($request->all());

        return redirect()->route('admin.odosielatels.index');
    }

    public function edit(Odosielatel $odosielatel)
    {
        abort_if(Gate::denies('odosielatel_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odosielatels.edit', compact('odosielatel'));
    }

    public function update(UpdateOdosielatelRequest $request, Odosielatel $odosielatel)
    {
        $odosielatel->update($request->all());

        return redirect()->route('admin.odosielatels.index');
    }

    public function show(Odosielatel $odosielatel)
    {
        abort_if(Gate::denies('odosielatel_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.odosielatels.show', compact('odosielatel'));
    }

    public function destroy(Odosielatel $odosielatel)
    {
        abort_if(Gate::denies('odosielatel_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $odosielatel->delete();

        return back();
    }

    public function massDestroy(MassDestroyOdosielatelRequest $request)
    {
        $odosielatels = Odosielatel::find(request('ids'));

        foreach ($odosielatels as $odosielatel) {
            $odosielatel->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
