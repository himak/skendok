@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.team.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.teams.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.team.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.team.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="aktivna" value="0">
                                <input type="checkbox" name="aktivna" id="aktivna" value="1" {{ old('aktivna', 0) == 1 || old('aktivna') === null ? 'checked' : '' }}>
                                <label for="aktivna">{{ trans('cruds.team.fields.aktivna') }}</label>
                            </div>
                            @if($errors->has('aktivna'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('aktivna') }}
                                </div>
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