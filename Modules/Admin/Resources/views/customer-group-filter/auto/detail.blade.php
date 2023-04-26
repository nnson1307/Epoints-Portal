@extends('layout')

@section('header')
    @include('components.header',['title'=> __('user::user-group-notification.create.TITLE')])
@stop
@section('content')
    <div id="form-adds">
        <div class="kt-subheader   kt-grid__item" id="kt_subheader">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    <span class="kt-subheader__title" id="kt_subheader_total">
                        @lang('user::user-group-notification.edit.DETAIL')
                                    </span>
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__group" id="kt_subheader_search">
									<span class="kt-subheader__desc text-capitalize" id="kt_subheader_total">
										{{$data['name']}}
                                    </span>
                </div>
            </div>
            <div class="kt-subheader__toolbar">

                <div class="btn-group">
                    <a href="{{route('user.user-group-notification')}}" class="btn btn-secondary">
                        @lang('user::user-group-notification.create.CANCEL')
                    </a>
                </div>
            </div>
        </div>
        <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
            <div class="kt-portlet kt-portlet--tabs">
                <div class="kt-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="kt_apps_user_edit_tab_1" role="tabpanel">
                            <div class="kt-form__body">
                                <div class="kt-section kt-section--first">
                                    <div class="kt-section__body">
                                        {{--Start A--}}
                                        <div class="form-group row">
                                            <div class="col-lg-2 kt-padding-0">
                                                <div class="form-group">
                                                    <label class="col-xl-12 col-lg-12 col-form-label">
                                                        @lang('user::user-group-notification.create.USER_GROUP_NAME')
                                                        <span class="color_red"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input disabled type="text" class="form-control"
                                                           id="name"
                                                           name="name"
                                                           placeholder=""
                                                           value="{{$data['name']}}">
                                                    <span class="text-danger error-name"></span>
                                                </div>
                                            </div>
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-2">
                                                <div class="kt-portlet ss--border-1px">
                                                    <div class="kt-widget14">
                                                        <div class="kt-widget14__content">
                                                            <div class="kt-widget14__legends ss--padding-left-0">
                                                                <div class="kt-widget14__legend">
                                                                    <span class="kt-widget14__stats kt-portlet__head-title"
                                                                          style="color: #656569;">
                                                                       {{$userInvalid}} @lang('user::user-group-notification.create.USER_INVALID')
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12 ss-font-size-13rem">
                                                @lang('user::user-group-notification.create.DEFINE_USER_GROUP')
                                            </div>
                                        </div>
                                        <div class="form-group row kt-margin-l-0">
                                            <label class="kt-margin-r-15">
                                                @lang('user::user-group-notification.create.A')
                                            </label>
                                            <select disabled name="A-or-and" id="A-or-and"
                                                    class="form-control select-2 ss-width-100pt"
                                                    style="width: 15%">
                                                <option value="or"
                                                        {{$data['filter_condition_rule_A']=='or'?'selected':''}}>
                                                    @lang('user::user-group-notification.create.OR')
                                                </option>
                                                <option value="and"
                                                        {{$data['filter_condition_rule_A']=='and'?'selected':''}}>
                                                    @lang('user::user-group-notification.create.AND')
                                                </option>
                                            </select>
                                            <label class="kt-margin-l-15">
                                                @lang('user::user-group-notification.create.FOLLOWING_CONDITION')
                                            </label>
                                        </div>
                                        <div class="div-A-condition">
                                            @if(isset($userGroupDetail))
                                                @if(count($userGroupDetail)>0)
                                                    @foreach($userGroupDetail as $detail)
                                                        @if($detail['mystore_filter_group_detail_type']=='A')
                                                            <div class="form-group row A-condition-1 div-A-1-condition">
                                                                <div class="col-lg-3">
                                                                    <select disabled onchange="userGroupAuto.chooseConditionA(this)"
                                                                            class="form-control select-2 ss-width-100pt condition-A">
                                                                        @if(isset($filterConditionType))
                                                                            @foreach($filterConditionType as $item)
                                                                                @if($detail['filter_condition_type_id'] == $item['id'])
                                                                                    <option value="{{$item['id']}}"
                                                                                            {{$detail['filter_condition_type_id'] == $item['id'] ? 'selected' : '' }}>
                                                                                        {{$item['name']}}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 div-content-condition">
                                                                    @if($detail['filter_condition_type_id']==1)
                                                                        <select disabled name="" id=""
                                                                                class="form-control select-2 ss-width-100pt col-lg-6 select-user-group">
                                                                            @if(isset($myStoreFilterGroup))
                                                                                <option value="" selected>
                                                                                    @lang('user::user-group-notification.create.CHOOSE_USER_GROUP_CREATED')
                                                                                </option>
                                                                                @foreach($myStoreFilterGroup as $item)
                                                                                    <option value="{{$item['id']}}"
                                                                                            {{$detail['user_group_id'] == $item['id'] ? 'selected':''}}>
                                                                                        {{$item['name']}}
                                                                                    </option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    @elseif($detail['filter_condition_type_id']==3)
                                                                        <input disabled type="text" im-insert="true"
                                                                               class="form-control day-A col-lg-6"
                                                                               min="1"
                                                                               value="{{$detail['group_self_open_app']}}">
                                                                    @elseif($detail['filter_condition_type_id']==4)
                                                                        <input disabled type="text" im-insert="true"
                                                                               class="form-control day-A col-lg-6"
                                                                               min="1"
                                                                               value="{{$detail['group_self_not_open_app']}}">
                                                                    @elseif($detail['filter_condition_type_id']==5)
                                                                        @php
                                                                            $most = json_decode ($detail['group_most_active'])
                                                                        @endphp
                                                                        <div class="row">
                                                                            <div class="col-lg-6 kt-padding-r-0">
                                                                                <select disabled name="" id=""
                                                                                        class="form-control select-2 kt-margin-r-10 most-pt-A"
                                                                                        style="width: 100%">
                                                                                    <option value="25" {{$most->top == 25 ? 'selected':''}}>
                                                                                        Top 25%
                                                                                    </option>
                                                                                    <option value="50" {{$most->top == 75 ? 'selected':''}}>
                                                                                        Top 50%
                                                                                    </option>
                                                                                    <option value="75" {{$most->top == 75 ? 'selected':''}}>
                                                                                        Top 75%
                                                                                    </option>
                                                                                    <option value="100" {{$most->top == 100 ? 'selected':''}}>
                                                                                        Top 100%
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-1">khoảng</div>
                                                                            <div class="col-lg-4">
                                                                                <input disabled type="text" im-insert="true"
                                                                                       min="1"
                                                                                       class="form-control day-A kt-margin-l-10 kt-margin-r-10"
                                                                                       value="{{$most->day}}">
                                                                            </div>
                                                                            <div class="col-lg-1">ngày</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    {{--<button type="button"--}}
                                                                            {{--onclick="userGroupAuto.removeConditionA(this)"--}}
                                                                            {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                                                                        {{--<i class="la la-close"></i>--}}
                                                                    {{--</button>--}}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="form-group row A-condition-1 div-A-1-condition">
                                                        <div class="col-lg-3">
                                                            <select disabled onchange="userGroupAuto.chooseConditionA(this)"
                                                                    class="form-control select-2 ss-width-100pt condition-A">
                                                                @if(isset($filterConditionType))
                                                                    @foreach($filterConditionType as $item)
                                                                        <option value="{{$item['id']}}">
                                                                            {{$item['name']}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6 div-content-condition">
                                                            <select disabled name="" id=""
                                                                    class="form-control select-2 ss-width-100pt col-lg-6 select-user-group">
                                                                @if(isset($myStoreFilterGroup))
                                                                    <option value="" selected>
                                                                        @lang('user::user-group-notification.create.CHOOSE_USER_GROUP_CREATED')
                                                                    </option>
                                                                    @foreach($myStoreFilterGroup as $item)
                                                                        <option value="{{$item['id']}}">
                                                                            {{$item['name']}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            {{--<button  type="button"--}}
                                                                    {{--onclick="userGroupAuto.removeConditionA(this)"--}}
                                                                    {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                                                                {{--<i class="la la-close"></i>--}}
                                                            {{--</button>--}}

                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="form-group row A-condition-1 div-A-1-condition">
                                                    <div class="col-lg-3">
                                                        <select disabled onchange="userGroupAuto.chooseConditionA(this)"
                                                                class="form-control select-2 ss-width-100pt condition-A">
                                                            @if(isset($filterConditionType))
                                                                @foreach($filterConditionType as $item)
                                                                    <option value="{{$item['id']}}">
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 div-content-condition">
                                                        <select disabled name="" id=""
                                                                class="form-control select-2 ss-width-100pt col-lg-6 select-user-group">
                                                            @if(isset($myStoreFilterGroup))
                                                                <option value="" selected>
                                                                    @lang('user::user-group-notification.create.CHOOSE_USER_GROUP_CREATED')
                                                                </option>
                                                                @foreach($myStoreFilterGroup as $item)
                                                                    <option value="{{$item['id']}}">
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        {{--<button type="button"--}}
                                                                {{--onclick="userGroupAuto.removeConditionA(this)"--}}
                                                                {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                                                            {{--<i class="la la-close"></i>--}}
                                                        {{--</button>--}}

                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                {{--<button class="btn btn-label-primary"--}}
                                                        {{--onclick="userGroupAuto.addConditionA()">--}}
                                                    {{--@lang('user::user-group-notification.create.ADD_CONDITION')--}}
                                                {{--</button>--}}
                                            </div>
                                        </div>
                                        {{--End A--}}
                                        {{--BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB--}}
                                        {{--BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB--}}
                                        {{--BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB--}}
                                        {{--BBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB--}}
                                        <div class="form-group row kt-margin-l-0">
                                            <label class="kt-margin-r-15">
                                                @lang('user::user-group-notification.create.B')
                                            </label>
                                            <select disabled name="B-or-and" id="B-or-and"
                                                    class="form-control select-2 ss-width-100pt"
                                                    style="width: 15%">
                                                <option value="or" {{$data['filter_condition_rule_B']=='or'?'selected':''}}>
                                                    @lang('user::user-group-notification.create.OR')
                                                </option>
                                                <option value="and" {{$data['filter_condition_rule_B']=='or'?'selected':''}}>
                                                    @lang('user::user-group-notification.create.AND')
                                                </option>
                                            </select>
                                            <label class="kt-margin-l-15">
                                                @lang('user::user-group-notification.create.FOLLOWING_CONDITION')
                                            </label>
                                        </div>
                                        <div class="div-B-condition">
                                            @if(isset($userGroupDetail))
                                                @if(count($userGroupDetail)>0)
                                                    @foreach($userGroupDetail as $detail)
                                                        @if($detail['mystore_filter_group_detail_type']=='B')
                                                            <div class="form-group row B-condition-1 div-B-1-condition">
                                                                <div class="col-lg-3">
                                                                    <select disabled name="" id=""
                                                                            onchange="userGroupAuto.chooseConditionB(this)"
                                                                            class="form-control select-2 ss-width-100pt condition-B">
                                                                        @if(isset($filterConditionType))
                                                                            @foreach($filterConditionType as $item)
                                                                                @if($detail['filter_condition_type_id'] == $item['id'])
                                                                                    <option value="{{$item['id']}}"
                                                                                            {{$detail['filter_condition_type_id'] == $item['id'] ? 'selected' : '' }}>
                                                                                        {{$item['name']}}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-6 div-content-condition">
                                                                    @if($detail['filter_condition_type_id']==1)
                                                                    <select disabled name="" id=""
                                                                            class="form-control select-2 ss-width-100pt col-lg-6 select-user-group">
                                                                        @if(isset($myStoreFilterGroup))
                                                                            <option value="" selected>
                                                                                @lang('user::user-group-notification.create.CHOOSE_USER_GROUP_CREATED')
                                                                            </option>
                                                                            @foreach($myStoreFilterGroup as $item)
                                                                                <option value="{{$item['id']}}"
                                                                                        {{$detail['user_group_id'] == $item['id'] ? 'selected':''}}>
                                                                                    {{$item['name']}}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    @elseif($detail['filter_condition_type_id']==3)
                                                                        <input disabled type="text" im-insert="true"
                                                                               class="form-control day-B col-lg-6"
                                                                               min="1"
                                                                               value="{{$detail['group_self_open_app']}}">
                                                                    @elseif($detail['filter_condition_type_id']==4)
                                                                        <input disabled type="text" im-insert="true"
                                                                               class="form-control day-B col-lg-6"
                                                                               min="1"
                                                                               value="{{$detail['group_self_not_open_app']}}">
                                                                    @elseif($detail['filter_condition_type_id']==5)
                                                                        @php
                                                                            $most = json_decode ($detail['group_most_active'])
                                                                        @endphp
                                                                        <div class="row">
                                                                            <div class="col-lg-6 kt-padding-r-0">
                                                                                <select disabled name="" id=""
                                                                                        class="form-control select-2 kt-margin-r-10 most-pt-B"
                                                                                        style="width: 100%">
                                                                                    <option value="25" {{$most->top == 25 ? 'selected':''}}>
                                                                                        Top 25%
                                                                                    </option>
                                                                                    <option value="50" {{$most->top == 75 ? 'selected':''}}>
                                                                                        Top 50%
                                                                                    </option>
                                                                                    <option value="75" {{$most->top == 75 ? 'selected':''}}>
                                                                                        Top 75%
                                                                                    </option>
                                                                                    <option value="100" {{$most->top == 100 ? 'selected':''}}>
                                                                                        Top 100%
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-lg-1">{{__('khoảng')}}</div>
                                                                            <div class="col-lg-4">
                                                                                <input disabled type="text" im-insert="true"
                                                                                       min="1"
                                                                                       class="form-control day-B kt-margin-l-10 kt-margin-r-10"
                                                                                       value="{{$most->day}}">
                                                                            </div>
                                                                            <div class="col-lg-1">{{__('ngày')}}</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="col-lg-3">
                                                                    {{--<button type="button"--}}
                                                                            {{--onclick="userGroupAuto.removeConditionB(this)"--}}
                                                                            {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                                                                        {{--<i class="la la-close"></i>--}}
                                                                    {{--</button>--}}

                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="form-group row B-condition-1 div-B-1-condition">
                                                        <div class="col-lg-3">
                                                            <select disabled onchange="userGroupAuto.chooseConditionB(this)"
                                                                    class="form-control select-2 ss-width-100pt condition-B">
                                                                @if(isset($filterConditionType))
                                                                    @foreach($filterConditionType as $item)
                                                                        <option value="{{$item['id']}}">
                                                                            {{$item['name']}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6 div-content-condition">
                                                            <select disabled name="" id=""
                                                                    class="form-control select-2 ss-width-100pt col-lg-6 select-user-group">
                                                                @if(isset($myStoreFilterGroup))
                                                                    <option value="" selected>
                                                                        @lang('user::user-group-notification.create.CHOOSE_USER_GROUP_CREATED')
                                                                    </option>
                                                                    @foreach($myStoreFilterGroup as $item)
                                                                        <option value="{{$item['id']}}">
                                                                            {{$item['name']}}
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-3">
                                                            {{--<button type="button"--}}
                                                                    {{--onclick="userGroupAuto.removeConditionB(this)"--}}
                                                                    {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                                                                {{--<i class="la la-close"></i>--}}
                                                            {{--</button>--}}

                                                        </div>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="form-group row A-condition-1 div-A-1-condition">
                                                    <div class="col-lg-3">
                                                        <select disabled onchange="userGroupAuto.chooseConditionA(this)"
                                                                class="form-control select-2 ss-width-100pt condition-A">
                                                            @if(isset($filterConditionType))
                                                                @foreach($filterConditionType as $item)
                                                                    <option value="{{$item['id']}}">
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 div-content-condition">
                                                        <select disabled name="" id=""
                                                                class="form-control select-2 ss-width-100pt col-lg-6 select-user-group">
                                                            @if(isset($myStoreFilterGroup))
                                                                <option value="" selected>
                                                                    @lang('user::user-group-notification.create.CHOOSE_USER_GROUP_CREATED')
                                                                </option>
                                                                @foreach($myStoreFilterGroup as $item)
                                                                    <option value="{{$item['id']}}">
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        {{--<button type="button"--}}
                                                                {{--onclick="userGroupAuto.removeConditionA(this)"--}}
                                                                {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                                                            {{--<i class="la la-close"></i>--}}
                                                        {{--</button>--}}

                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                {{--<button class="btn btn-label-primary"--}}
                                                        {{--onclick="userGroupAuto.addConditionB()">--}}
                                                    {{--@lang('user::user-group-notification.create.ADD_CONDITION')--}}
                                                {{--</button>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_script')
    {{--<script type="text/template" id="condition-A-tpl">--}}
        {{--<div class="form-group row A-condition-1 div-A-1-condition">--}}
            {{--<div class="col-lg-3">--}}
                {{--<select name="A-" id="" onchange="userGroupAuto.chooseConditionA(this)"--}}
                        {{--class="form-control select-2 condition-A" style="width: 100%">--}}
                    {{--<option value="">--}}
                        {{--@lang('user::user-group-notification.create.CHOOSE_CONDITION')--}}
                    {{--</option>--}}
                    {{--{option}--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="col-lg-6 div-content-condition">--}}
            {{--</div>--}}
            {{--<div class="col-lg-3">--}}
                {{--<button type="button" onclick="userGroupAuto.removeConditionA(this)"--}}
                        {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                    {{--<i class="la la-close"></i>--}}
                {{--</button>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</script>--}}
    {{--<script type="text/template" id="condition-A-1-tpl">--}}
        {{--<select name="A-" id=""--}}
                {{--class="form-control select-2 col-lg-6 select-user-group" style="width: 50%">--}}
            {{--<option value="">--}}
                {{--@lang('user::user-group-notification.create.CHOOSE_USER_GROUP')--}}
            {{--</option>--}}
            {{--{option}--}}
        {{--</select>--}}
    {{--</script>--}}
    {{--<script type="text/template" id="condition-A-2-3-4-tpl">--}}
        {{--<input type="text" im-insert="true" class="form-control day-A col-lg-6" min="1" value="30">--}}
    {{--</script>--}}
    {{--<script type="text/template" id="condition-A-5-tpl">--}}
        {{--<div class="row">--}}
            {{--<div class="col-lg-6 kt-padding-r-0">--}}
                {{--<select name="" id=""--}}
                        {{--class="form-control select-2 kt-margin-r-10 most-pt-A" style="width: 100%">--}}
                    {{--<option value="25">--}}
                        {{--Top 25%--}}
                    {{--</option>--}}
                    {{--<option value="50">--}}
                        {{--Top 50%--}}
                    {{--</option>--}}
                    {{--<option value="75">--}}
                        {{--Top 75%--}}
                    {{--</option>--}}
                    {{--<option value="100">--}}
                        {{--Top 100%--}}
                    {{--</option>--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="col-lg-1">khoảng</div>--}}
            {{--<div class="col-lg-4">--}}
                {{--<input type="text" im-insert="true" min="1" class="form-control day-A kt-margin-l-10 kt-margin-r-10"--}}
                       {{--value="30">--}}
            {{--</div>--}}
            {{--<div class="col-lg-1">ngày</div>--}}
        {{--</div>--}}
    {{--</script>--}}





    {{--<script type="text/template" id="condition-B-tpl">--}}
        {{--<div class="form-group row B-condition-1 div-B-1-condition">--}}
            {{--<div class="col-lg-3">--}}
                {{--<select name="B-" id="" onchange="userGroupAuto.chooseConditionB(this)"--}}
                        {{--class="form-control select-2 condition-B" style="width: 100%">--}}
                    {{--<option value="">--}}
                        {{--@lang('user::user-group-notification.create.CHOOSE_CONDITION')--}}
                    {{--</option>--}}
                    {{--{option}--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="col-lg-6 div-content-condition">--}}

            {{--</div>--}}
            {{--<div class="col-lg-3">--}}
                {{--<button type="button" onclick="userGroupAuto.removeConditionB(this)"--}}
                        {{--class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                    {{--<i class="la la-close"></i>--}}
                {{--</button>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</script>--}}
    {{--<script type="text/template" id="condition-B-1-tpl">--}}
        {{--<select name="B-" id=""--}}
                {{--class="form-control select-2 condition-B col-lg-6 select-user-group" style="width: 50%">--}}
            {{--<option value="">--}}
                {{--@lang('user::user-group-notification.create.CHOOSE_USER_GROUP')--}}
            {{--</option>--}}
            {{--{option}--}}
        {{--</select>--}}
    {{--</script>--}}
    {{--<script type="text/template" id="condition-B-2-3-4-tpl">--}}
        {{--<input type="text" im-insert="true" min="1" class="form-control day-B col-lg-6" value="30">--}}
    {{--</script>--}}
    {{--<script type="text/template" id="condition-B-5-tpl">--}}
        {{--<div class="row">--}}
            {{--<div class="col-lg-6 kt-padding-r-0">--}}
                {{--<select name="B-" id=""--}}
                        {{--class="form-control select-2 kt-margin-r-10 most-pt-B" style="width: 100%">--}}
                    {{--<option value="25">--}}
                        {{--Top 25%--}}
                    {{--</option>--}}
                    {{--<option value="50">--}}
                        {{--Top 50%--}}
                    {{--</option>--}}
                    {{--<option value="75">--}}
                        {{--Top 75%--}}
                    {{--</option>--}}
                    {{--<option value="100">--}}
                        {{--Top 100%--}}
                    {{--</option>--}}
                {{--</select>--}}
            {{--</div>--}}
            {{--<div class="col-lg-1">khoảng</div>--}}
            {{--<div class="col-lg-4">--}}
                {{--<input type="text" im-insert="true"--}}
                       {{--class="form-control day-B kt-margin-l-10 kt-margin-r-10" min="1" value="30">--}}
            {{--</div>--}}
            {{--<div class="col-lg-1">ngày</div>--}}
        {{--</div>--}}
    {{--</script>--}}
    <script src="{{asset('static/backend/js/user/user-group/edit.js')}}" type="text/javascript"></script>
@stop
