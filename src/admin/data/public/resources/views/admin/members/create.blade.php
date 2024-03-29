@extends('layouts.admin')
@section('pageTitle',__('admin.members'))

@section('innerTitle')
    @lang('site.create-new')  {{ ucfirst(__('admin.member')) }}
@endsection

@section('breadcrumb')
    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a>
    </li>
    <li><a href="{{ url('/admin/members') }}">@lang('admin.members')</a>
    </li>
    <li><span>@lang('site.create-new') @lang('admin.member')</span>
    </li>
@endsection

@section('content')
    <div class="single-pro-review-area mt-t-30 mg-b-15">


        <div class="container-fluid">
            <div class="product-payment-inner-st form-content">


            <form method="POST" autocomplete="off" action="{{ url('/admin/members') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                {{ csrf_field() }}

                @include ('admin.members.form', ['formMode' => 'create'])

            </form>

            </div>
        </div>


    </div>

@endsection

@section('header')
    <link rel="stylesheet" href="{{ asset('vendor/select2/css/select2.min.css') }}">
@endsection


@section('footer')
    <script src="{{ asset('vendor/select2/js/select2.min.js') }}"></script>
    <script>
        $(function(){
            $('.select2').select2();
        });
    </script>
@endsection
