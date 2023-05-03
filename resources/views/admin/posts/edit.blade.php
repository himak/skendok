@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.post.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.posts.update", [$post->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                            <label for="date">{{ trans('cruds.post.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date', $post->date) }}">
                            @if($errors->has('date'))
                                <span class="help-block" role="alert">{{ $errors->first('date') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('cislo') ? 'has-error' : '' }}">
                            <label for="cislo">{{ trans('cruds.post.fields.cislo') }}</label>
                            <input class="form-control" type="text" name="cislo" id="cislo" value="{{ old('cislo', $post->cislo) }}">
                            @if($errors->has('cislo'))
                                <span class="help-block" role="alert">{{ $errors->first('cislo') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.cislo_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('team') ? 'has-error' : '' }}">
                            <label for="team_id">{{ trans('cruds.post.fields.team') }}</label>
                            <select class="form-control select2" name="team_id" id="team_id">
                                @foreach($teams as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('team_id') ? old('team_id') : $post->team->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('team'))
                                <span class="help-block" role="alert">{{ $errors->first('team') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.team_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('odosielatel') ? 'has-error' : '' }}">
                            <label for="odosielatel_id">{{ trans('cruds.post.fields.odosielatel') }}</label>
                            <select class="form-control select2Tags" name="odosielatel_id" id="odosielatel_id">
                                @foreach($odosielatels as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('odosielatel_id') ? old('odosielatel_id') : $post->odosielatel->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('odosielatel'))
                                <span class="help-block" role="alert">{{ $errors->first('odosielatel') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.odosielatel_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('accounting') ? 'has-error' : '' }}">
                            <div>
                                <input type="hidden" name="accounting" value="0">
                                <input type="checkbox" name="accounting" id="accounting" value="1" {{ $post->accounting || old('accounting', 0) === 1 ? 'checked' : '' }}>
                                <label for="accounting" style="font-weight: 400">{{ trans('cruds.post.fields.accounting') }}</label>
                            </div>
                            @if($errors->has('accounting'))
                                <span class="help-block" role="alert">{{ $errors->first('accounting') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.accounting_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('scan') ? 'has-error' : '' }}">
                            <label for="scan">{{ trans('cruds.post.fields.scan') }}</label>
                            <div class="needsclick dropzone" id="scan-dropzone">
                            </div>
                            @if($errors->has('scan'))
                                <span class="help-block" role="alert">{{ $errors->first('scan') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.scan_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('envelope') ? 'has-error' : '' }}">
                            <label for="envelope">{{ trans('cruds.post.fields.envelope') }}</label>
                            <div class="needsclick dropzone" id="envelope-dropzone">
                            </div>
                            @if($errors->has('envelope'))
                                <span class="help-block" role="alert">{{ $errors->first('envelope') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.envelope_helper') }}</span>
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

@section('scripts')
<script>
    Dropzone.options.scanDropzone = {
    url: '{{ route('admin.posts.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="scan"]').remove()
      $('form').append('<input type="hidden" name="scan" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="scan"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($post) && $post->scan)
      var file = {!! json_encode($post->scan) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="scan" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    Dropzone.options.envelopeDropzone = {
    url: '{{ route('admin.posts.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="envelope"]').remove()
      $('form').append('<input type="hidden" name="envelope" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="envelope"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($post) && $post->envelope)
      var file = {!! json_encode($post->envelope) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="envelope" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
@endsection