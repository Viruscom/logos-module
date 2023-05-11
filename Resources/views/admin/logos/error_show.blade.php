@extends('layouts.admin.app')

@section('content')
    @include('logos::admin.logos.breadcrumbs')
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">{!! __('logos::admin.logos.warning_no_icons_methods_in_eloquent_model') !!}</div>
        </div>
    </div>
@endsection
