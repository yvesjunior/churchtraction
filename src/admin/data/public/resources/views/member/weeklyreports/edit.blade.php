@extends('layouts.member')
@section('pageTitle',__('admin.weeklyreports'))

@section('innerTitle')
    @lang('site.edit') @lang('admin.download') #{{ $download->id }}
@endsection

@section('breadcrumb')
    <li><a href="{{ route('member.dashboard') }}">@lang('admin.dashboard')</a>
    </li>
    <li><a href="{{ url('/member/weeklyreports') }}">@lang('admin.weeklyreports')</a>
    </li>
    <li><span>@lang('site.edit') @lang('admin.download')</span>
    </li>
@endsection

@section('content')
    <div class="single-pro-review-area mt-t-30 mg-b-15">


        <div class="container-fluid">
            <div class="product-payment-inner-st form-content">


            <form method="POST" autocomplete="off" action="{{ url('/member/weeklyreports/' . $download->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}

                @include ('member.weeklyreports.form', ['formMode' => 'edit'])

            </form>




        </div>
    </div>


    </div>

@endsection