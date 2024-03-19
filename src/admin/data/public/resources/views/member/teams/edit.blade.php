@extends('layouts.member')
@section('pageTitle','Teams')

@section('innerTitle')
    @lang('site.edit') @lang('admin.team') :{{ $team->name }}
@endsection

@section('breadcrumb')
    <li><a href="{{ route('member.dashboard') }}">@lang('admin.dashboard')</a>
    </li>
    <li><a href="{{ url('/member/teams') }}">@lang('admin.teams')</a>
    </li>
    <li><span>@lang('site.edit') @lang('admin.team')</span>
    </li>
@endsection

@section('content')
    <div class="single-pro-review-area mt-t-30 mg-b-15">


        <div class="container-fluid">
            <div class="product-payment-inner-st form-content">


            <form method="POST" autocomplete="off" action="{{ url('/member/teams/' . $team->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}

                @include ('member.teams.form', ['formMode' => 'edit'])

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
    <script type="text/javascript">
        $(function(){
            $('.select2').select2();
        });
    </script>
@endsection
