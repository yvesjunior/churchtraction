@extends('layouts.member')
@section('pageTitle',__('admin.events'))

@section('innerTitle')
    @lang('admin.event') : {{ $event->name }}
@endsection

@section('breadcrumb')
    <li><a href="{{ route('member.dashboard') }}">@lang('admin.dashboard')</a>
    </li>
    <li><a href="{{ url('/member/events/roster') }}">@lang('admin.roster')</a>
    </li>
    <li><span>@lang('admin.event')</span>
    </li>
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h4>{{ $event->name }} ({{ \Carbon\Carbon::parse($event->event_date)->format('D d/M/Y') }})</h4>
        </div>
        <div class="card-body">

            <ul class="nav nav-pills" id="myTab{{ $event->id }}" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab{{ $event->id }}" data-toggle="tab" href="#home{{ $event->id }}" role="tab" aria-controls="home{{ $event->id }}" aria-selected="true">@lang('admin.info')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab{{ $event->id }}" data-toggle="tab" href="#profile{{ $event->id }}" role="tab" aria-controls="profile{{ $event->id }}" aria-selected="false">@lang('admin.shifts')</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent{{ $event->id }}">
                <div class="tab-pane fade show active" id="home{{ $event->id }}" role="tabpanel" aria-labelledby="home-tab{{ $event->id }}">
                    <table class="table table-bordered table-striped" style="margin-top: 10px">
                        <tbody>
                        <tr>
                            <td style="border-top: none"><strong>@lang('admin.starts'):</strong></td>
                            <td style="border-top: none">{{ \Carbon\Carbon::parse($event->event_date)->format('D d/M/Y') }} ({{ \Carbon\Carbon::parse($event->event_date)->diffForHumans() }})</td>
                        </tr>
                        @if(!empty($event->venue))
                            <tr>
                                <td><strong>@lang('admin.venue'):</strong></td>
                                <td>{{ $event->venue }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td><strong>@lang('admin.shifts'):</strong></td>
                            <td>{{ $event->shifts()->count() }}</td>
                        </tr>
                        <?php
                        $users = [];
                        ?>
                        @foreach($event->shifts as $shift)
                            @foreach($shift->users as $user)
                                <?php
                                $users[$user->id] = $user;
                                ?>
                            @endforeach
                        @endforeach
                        @if(!empty($users))
                            <tr>
                                <td><strong>@lang('admin.members'):</strong></td>
                                <td>

                                    <ul class="comma-tags">
                                        @foreach($users as $user)
                                            <li>{{ $user->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    @if(!empty($event->description))
                        <div class="alert alert-success" role="alert">{!! nl2br(clean($event->description)) !!}</div>
                    @endif
                </div>
                <div class="tab-pane fade" id="profile{{ $event->id }}" role="tabpanel" aria-labelledby="profile-tab{{ $event->id }}">
                    @foreach($event->shifts()->orderBy('starts')->get() as $shift)
                        <div style="border: solid 1px #CCCCCC; padding-left: 15px; padding-right: 15px; margin-bottom: 30px">
                            <h4 style="margin-top: 20px">{{ \Illuminate\Support\Carbon::parse($shift->starts)->format('h:i A') }} to {{ \Illuminate\Support\Carbon::parse($shift->ends)->format('h:i A') }} <span class="float-right">{{ $shift->name }}</span></h4>

                            <table class="table">
                                <thead>
                                <tr>
                                    <th>@lang('admin.members')</th>
                                    <th>@lang('admin.tasks')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shift->users()->orderBy('name')->get() as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->pivot->tasks }}</td>
                                    </tr>

                                @endforeach
                                @if($shift->users()->where('user_id',\Illuminate\Support\Facades\Auth::user()->id)->first())
                                    <tr>
                                        <td colspan="2">
                                            <a style="color: white" class="btn btn-danger btn-lg" href="#"  data-toggle="modal" data-target="#myModal{{ $shift->id }}"><i class="fa fa-sign-out-alt"></i> @lang('admin.opt-out')</a>

                                            <!-- Modal -->
                                            <div class="modal fade" id="myModal{{ $shift->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel{{ $shift->id }}">
                                                <div class="modal-dialog" role="document">
                                                    <form action="{{ route('member.events.opt-out',['shift'=>$shift->id]) }}" method="post">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel{{ $shift->id }}">@lang('admin.shift') {{ \Illuminate\Support\Carbon::parse($shift->starts)->format('h:i A') }} to {{ \Illuminate\Support\Carbon::parse($shift->ends)->format('h:i A') }} ({{ $shift->name }}) @lang('admin.opt-out')</h4>

                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="message">@lang('admin.reject-reason')</label>
                                                                    <textarea required class="form-control"
                                                                              name="message" id="message{{ $shift->id }}"
                                                                              rows="4"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.close')</button>
                                                                <button type="submit" class="btn btn-danger">@lang('admin.opt-out')</button>
                                                            </div>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>





                                        </td>
                                    </tr>
                                @elseif($shift->event->accept_volunteers==1)
                                    <tr>
                                        <td colspan="2">
                                            <a style="color: white" class="btn btn-success btn-lg" href="{{ route('member.events.volunteer',['shift'=>$shift->id]) }}"  ><i class="fa fa-user-plus"></i> @lang('admin.volunteer')</a>



                                        </td>
                                    </tr>

                                @endif
                                </tbody>
                            </table>
                            @if(!empty($shift->description))
                                <div class="alert alert-success" role="alert">
                                    {{ $shift->description }}
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>

            </div>

        </div>
    </div>

@endsection
