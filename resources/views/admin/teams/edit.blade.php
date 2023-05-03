@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.team.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.teams.update", [$team->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">{{ trans('cruds.team.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $team->name) }}">
                            @if($errors->has('name'))
                                <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.team.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('aktivna') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="aktivna" value="0">
                                <input type="checkbox" name="aktivna" id="aktivna" value="1" {{ $team->aktivna || old('aktivna', 0) === 1 ? 'checked' : '' }}>
                                <label for="aktivna" style="font-weight: 400">{{ trans('cruds.team.fields.aktivna') }}</label>
                            </div>
                            @if($errors->has('aktivna'))
                                <span class="help-block" role="alert">{{ $errors->first('aktivna') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.team.fields.aktivna_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection