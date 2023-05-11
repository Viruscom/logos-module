@extends('layouts.admin.app')
@section('styles')
    <link href="{{ asset('admin/assets/css/select2.min.css') }}" rel="stylesheet"/>
@endsection
@section('scripts')
    <script src="{{ asset('admin/assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2({language: "bg"});
            $('.select2').on('change', function () {
                var select = $('.select2').find('option:selected');
                $.ajax({
                    url: $('.base-url').text() + '/admins/logos/get-path',
                    type: 'POST',
                    data: {
                        _token: $('div.form-token').text(),
                        moduleName: select.attr('module'),
                        modelPath: select.attr('model'),
                        modelId: select.attr('model_id'),
                    },
                    async: false,
                    success: function (response) {
                        window.location.href = $('.base-url').text() + '/admins/logos/load-logos/' + response;
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            });
        });
    </script>
@endsection
@section('content')
    @include('logos::admin.logos.breadcrumbs')
    @include('admin.notify')
    <div class="alert alert-warning">{!! __('logos::admin.logos.first_choose_from_list') !!}</div>
    <div class="col-md-12">
        <div class="form form-horizontal form-bordered ">
            <div class="form-group">
                <label for="page_select" class="control-label col-md-3">{{ __('admin.gallery.page') }}:</label>
                <div class="col-md-5">
                    <select id="page_select" name="page" class="form-control select2" style="width: 100%;">
                        <option value="">@lang('admin.common.please_select')</option>
                        @foreach($internalLinks as $keyModule => $module)
                            <optgroup label="{{ $module['name'] }}">
                                @foreach($module['links'] as $link)
                                    <option value="{{ old('url') ?: $link->url }}" module="{{Str::plural($keyModule, 1)}}" model="{{ get_class($link) }}" model_id="{{ $link->id }}">{{ $link->title }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
@endsection
