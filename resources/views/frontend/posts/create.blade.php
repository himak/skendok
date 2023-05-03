@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.post.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.posts.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label for="date">{{ trans('cruds.post.fields.date') }}</label>
                            <input class="form-control date" type="text" name="date" id="date" value="{{ old('date') }}">
                            @if($errors->has('date'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('date') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.date_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="cislo">{{ trans('cruds.post.fields.cislo') }}</label>
                            <input class="form-control" type="text" name="cislo" id="cislo" value="{{ old('cislo', '') }}">
                            @if($errors->has('cislo'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cislo') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.cislo_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="team_id">{{ trans('cruds.post.fields.team') }}</label>
                            <select class="form-control select2" name="team_id" id="team_id">
                                @foreach($teams as $id => $entry)
                                    <option value="{{ $id }}" {{ old('team_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('team'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('team') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.team_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="odosielatel_id">{{ trans('cruds.post.fields.odosielatel') }}</label>
                            <select class="form-control select2" name="odosielatel_id" id="odosielatel_id">
                                @foreach($odosielatels as $id => $entry)
                                    <option value="{{ $id }}" {{ old('odosielatel_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('odosielatel'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('odosielatel') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.odosielatel_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <div>
                                <input type="hidden" name="accounting" value="0">
                                <input type="checkbox" name="accounting" id="accounting" value="1" {{ old('accounting', 0) == 1 || old('accounting') === null ? 'checked' : '' }}>
                                <label for="accounting">{{ trans('cruds.post.fields.accounting') }}</label>
                            </div>
                            @if($errors->has('accounting'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('accounting') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.accounting_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="scan">{{ trans('cruds.post.fields.scan') }}</label>
                            <div class="needsclick dropzone" id="scan-dropzone">
                            </div>
                            @if($errors->has('scan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('scan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.post.fields.scan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="envelope">{{ trans('cruds.post.fields.envelope') }}</label>
                            <div class="needsclick dropzone" id="envelope-dropzone">
                            </div>
                            @if($errors->has('envelope'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('envelope') }}
                                </div>
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
    url: '{{ route('frontend.posts.storeMedia') }}',
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
    url: '{{ route('frontend.posts.storeMedia') }}',
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