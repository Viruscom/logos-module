@php
    use Modules\Logos\Models\Logo;
@endphp@extends('layouts.admin.app')

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.myadmin-alert .closed').click(function (e) {
                e.preventDefault();
                $(this).parent().addClass('hidden');
            });

            $('[data-toggle="popover"]').popover({
                placement: 'auto',
                trigger: 'hover',
                html: true
            });
        });
    </script>
@endsection
@section('content')
    @include('logos::admin.logos.breadcrumbs')
    @include('admin.notify')

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('logos::admin.logos.after_main_description') }}: <strong>{{ $model->title }}</strong></h3>
            @include('logos::admin.logos.top_buttons', ['formId' => 'headerForm', 'mainPosition' => Logo::LOGOS_AFTER_DESCRIPTION])
            @include('logos::admin.logos.table', ['logos' => $model['Logos'][Logo::LOGOS_AFTER_DESCRIPTION], 'tableClass' => 'table-headerForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('logos::admin.logos.after_additional_description_1') }}: <strong>{{ $model->title }}</strong></h3>
            @include('logos::admin.logos.top_buttons', ['formId' => 'additionalTextOneForm', 'mainPosition' => Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_1])
            @include('logos::admin.logos.table', ['logos' => $model['Logos'][Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_1], 'tableClass' => 'table-additionalTextOneForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('logos::admin.logos.after_additional_description_2') }}: <strong>{{ $model->title }}</strong></h3>
            @include('logos::admin.logos.top_buttons', ['formId' => 'additionalTextTwoForm', 'mainPosition' => Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_2])
            @include('logos::admin.logos.table', ['logos' => $model['Logos'][Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_2], 'tableClass' => 'table-additionalTextTwoForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('logos::admin.logos.after_additional_description_3') }}: <strong>{{ $model->title }}</strong></h3>
            @include('logos::admin.logos.top_buttons', ['formId' => 'additionalTextThreeForm', 'mainPosition' => Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_3])
            @include('logos::admin.logos.table', ['logos' => $model['Logos'][Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_3], 'tableClass' => 'table-additionalTextThreeForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('logos::admin.logos.after_additional_description_4') }}: <strong>{{ $model->title }}</strong></h3>
            @include('logos::admin.logos.top_buttons', ['formId' => 'additionalTextFourForm', 'mainPosition' => Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_4])
            @include('logos::admin.logos.table', ['logos' => $model['Logos'][Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_4], 'tableClass' => 'table-additionalTextFourForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('logos::admin.logos.after_additional_description_5') }}: <strong>{{ $model->title }}</strong></h3>
            @include('logos::admin.logos.top_buttons', ['formId' => 'additionalTextFiveForm', 'mainPosition' => Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_5])
            @include('logos::admin.logos.table', ['logos' => $model['Logos'][Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_5], 'tableClass' => 'table-additionalTextFiveForm'])
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <h3>{{ __('logos::admin.logos.after_additional_description_6') }}: <strong>{{ $model->title }}</strong></h3>
            @include('logos::admin.logos.top_buttons', ['formId' => 'additionalTextSixForm', 'mainPosition' => Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_6])
            @include('logos::admin.logos.table', ['logos' => $model['Logos'][Logo::LOGOS_AFTER_ADDITIONAL_DESCRIPTION_6], 'tableClass' => 'table-additionalTextSixForm'])
        </div>
    </div>

    @include('admin.partials.modals.delete_confirm')
@endsection
