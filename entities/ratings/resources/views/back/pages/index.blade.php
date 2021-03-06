@extends('admin::back.layouts.app')

@php
    $title = 'Рейтинг';
@endphp

@section('title', $title)

@section('content')

    @push('breadcrumbs')
        @include('admin.module.ratings-package.ratings::back.partials.breadcrumbs')
    @endpush

    <div class="wrapper wrapper-content">

        @include('admin.module.ratings-package.ratings::back.partials.analytics.statistic')

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            {{ $table->table(['class' => 'table table-striped table-bordered table-hover dataTable']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@pushonce('scripts:datatables_rating_package_ratings_index')
    {!! $table->scripts() !!}
@endpushonce
