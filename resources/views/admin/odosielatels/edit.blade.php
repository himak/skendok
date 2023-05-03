@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.odosielatel.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.odosielatels.update", [$odosielatel->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('odosielatel') ? 'has-error' : '' }}">
                            <label for="odosielatel">{{ trans('cruds.odosielatel.fields.odosielatel') }}</label>
                            <input class="form-control" type="text" name="odosielatel" id="odosielatel" value="{{ old('odosielatel', $odosielatel->odosielatel) }}">
                            @if($errors->has('odosielatel'))
                                <span class="help-block" role="alert">{{ $errors->first('odosielatel') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.odosielatel.fields.odosielatel_helper') }}</span>
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