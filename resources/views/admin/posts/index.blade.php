@extends('layouts.admin')
@section('content')
<div class="content">
    @can('post_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.posts.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.post.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.post.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Post">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.date') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.cislo') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.team') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.odosielatel') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.accounting') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.scan') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.post.fields.envelope') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $key => $post)
                                    <tr data-entry-id="{{ $post->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $post->date ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->cislo ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->team->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $post->odosielatel->odosielatel ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $post->accounting ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $post->accounting ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            @if($post->scan)
                                                <a href="{{ $post->scan->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->envelope)
                                                <a href="{{ $post->envelope->getUrl() }}" target="_blank">
                                                    {{ trans('global.view_file') }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @can('post_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.posts.show', $post->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('post_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.posts.edit', $post->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('post_delete')
                                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('post_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.posts.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 2, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Post:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection