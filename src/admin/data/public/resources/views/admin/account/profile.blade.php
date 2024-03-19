@extends('layouts.site')
@section('pageTitle',__('admin.profile'))

@section('innerTitle')
   @lang('admin.profile')
@endsection

@section('breadcrumb')
    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a>
    </li>
    <li><span>@lang('admin.profile')</span>
    </li>
@endsection

@section('content')
    <div class="single-pro-review-area mt-t-30 mg-b-15">


        <div class="container-fluid">
            <div class="product-payment-inner-st form-content">


                <form id="sendForm" method="post" action="{{ route('account.save-profile') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                    {{ csrf_field() }}




                    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        <label for="name" class="control-label">@lang('admin.name')</label>
                        <input required class="form-control" name="name" type="text" id="name" value="{{ old('name',isset($user->name) ? $user->name : '') }}" >
                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                        <label for="email" class="control-label">@lang('admin.email')</label>
                        <input  required class="form-control" name="email" type="text" id="email" value="{{ old('email',isset($user->email) ? $user->email : '') }}" >
                        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                    </div>

                    <div class="form-group {{ $errors->has('telephone') ? 'has-error' : ''}}">
                        <label for="telephone" class="control-label">@lang('admin.telephone')</label>
                        <input  class="form-control" name="telephone" type="text" id="telephone" value="{{ old('telephone',isset($user->telephone) ? $user->telephone : '') }}" >
                        {!! $errors->first('telephone', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
                        <label for="gender" class="control-label">@lang('admin.gender')</label>
                        <select required  name="gender" class="form-control" id="gender" required>
                            <option></option>
                            @foreach (getGenders() as $optionKey => $optionValue)
                                <option value="{{ $optionKey }}" {{ ((null !== old('gender',@$user->gender)) && old('gender',@$user->gender) == $optionKey) ? 'selected' : ''}}>{{ $optionValue }}</option>
                            @endforeach
                        </select>
                        {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="form-group {{ $errors->has('about') ? 'has-error' : ''}}">
                        <label for="about" class="control-label">@lang('admin.about')</label>
                        <textarea class="form-control" rows="5" name="about" type="textarea" id="about" >{{ old('about',isset($user->about) ? $user->about : '') }}</textarea>
                        {!! $errors->first('about', '<p class="help-block">:message</p>') !!}
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('picture') ? 'has-error' : ''}}">
                                <label for="picture" class="control-label">@lang('admin.change') @lang('admin.picture')</label>


                                <input class="form-control" name="picture" type="file" id="picture" value="{{ isset($user->picture) ? $user->picture : ''}}" >
                                {!! $errors->first('picture', '<p class="help-block">:message</p>') !!}
                            </div>

                        </div>
                        <div class="col-md-6">
                            @if(!empty($user->picture))

                                <div><img src="{{ asset($user->picture) }}" style="max-width: 300px" /></div> <br/>
                                <a onclick="return confirm('@lang('admin.delete-prompt')')" class="btn btn-danger" href="{{ route('account.remove-picture') }}"><i class="fa fa-trash"></i> @lang('admin.delete') @lang('admin.picture')</a>

                            @endif
                        </div>
                    </div>

                    @foreach(\App\Field::orderBy('sort_order','asc')->get() as $field)
                        @php
                            if(isset($member)){
                            $value = old($field->id,($member->fields()->where('field_id',$field->id)->first()) ? $member->fields()->where('field_id',$field->id)->first()->pivot->value:'');

                            }
                            else{
                            $value='';
                            }
                        @endphp
                        @if($field->type=='text')
                            <div class="form-group{{ $errors->has($field->id) ? ' has-error' : '' }}">
                                <label for="{{ $field->id }}">{{ $field->name }}:</label>
                                <input placeholder="{{ $field->placeholder }}" @if(!empty($field->required))required @endif  type="text" class="form-control" id="{{ $field->id }}" name="{{ $field->id }}" value="{{ $value }}">
                                @if ($errors->has($field->id))
                                    <span class="help-block">
                                            <strong>{{ $errors->first($field->id) }}</strong>
                                        </span>
                                @endif
                            </div>
                        @elseif($field->type=='select')
                            <div class="form-group{{ $errors->has($field->id) ? ' has-error' : '' }}">
                                <label for="{{ $field->id }}">{{ $field->name }}:</label>
                                <?php
                                $options = nl2br($field->options);
                                $values = explode('<br />',$options);
                                $selectOptions = [];
                                foreach($values as $value2){
                                    $selectOptions[$value2]=trim($value2);
                                }
                                ?>
                                {{ Form::select($field->id, $selectOptions,$value,['placeholder' => $field->placeholder,'class'=>'form-control']) }}
                                @if ($errors->has($field->id))
                                    <span class="help-block">
                                        <strong>{{ $errors->first($field->id) }}</strong>
                                    </span>

                                @endif
                            </div>
                        @elseif($field->type=='textarea')
                            <div class="form-group{{ $errors->has($field->id) ? ' has-error' : '' }}">
                                <label for="{{ $field->id }}">{{ $field->name }}:</label>
                                <textarea placeholder="{{ $field->placeholder }}" class="form-control" name="{{ $field->id }}" id="{{ $field->id }}" @if(!empty($field->required))required @endif  >{{ $value }}</textarea>
                                @if ($errors->has($field->id))
                                    <span class="help-block">
                                            <strong>{{ $errors->first($field->id) }}</strong>
                                        </span>
                                @endif
                            </div>
                        @elseif($field->type=='checkbox')
                            <div class="checkbox">
                                <label>
                                    <input name="{{ $field->id }}" type="checkbox" value="1" @if($value==1) checked @endif> {{ $field->name }}
                                </label>
                            </div>

                        @elseif($field->type=='radio')
                            <?php
                            $options = nl2br($field->options);
                            $values = explode('<br />',$options);
                            $radioOptions = [];
                            foreach($values as $value3){
                                $radioOptions[$value3]=trim($value3);
                            }
                            ?>
                            <h5><strong>{{ $field->name }}</strong></h5>
                            @foreach($radioOptions as $value2)
                                <div class="radio">
                                    <label>
                                        <input type="radio" @if($value==$value2) checked @endif  name="{{ $field->id }}" id="{{ $field->id }}-{{ $value2 }}" value="{{ $value2 }}" >
                                        {{ $value2 }}
                                    </label>
                                </div>
                            @endforeach
                        @endif


                    @endforeach



                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="{{  __('site.update') }}">
                        <a class="btn btn-warning" href="{{ url('/') }}">@lang('site.back-home')</a>

                    </div>


                </form>




            </div>
        </div>


    </div>

@endsection
