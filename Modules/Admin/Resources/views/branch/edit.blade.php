@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ CHI NHÁNH')}}</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                         {{__('CHỈNH SỬA CHI NHÁNH')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">


            </div>
        </div>
        @include('admin::branch.modal-add-image')
        <form id="edit">
            {!! csrf_field() !!}
            <input type="hidden" id="branch_id" name="branch_id" value="{{$branch['branch_id']}}">
            <input type="hidden" id="district-hidden" value="{{$branch['districtid']}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tên chi nhánh')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="branch_name" class="form-control m-input"
                                   id="branch_name" value="{{$branch['branch_name']}}"
                                   placeholder="{{__('Nhập tên chi nhánh')}}...">
                            <span class="error-name"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Mã đại diện')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="representative_code" class="form-control m-input"
                                   id="representative_code" value="{{$branch['representative_code']}}"
                                   placeholder="{{__('Nhập mã đại diện')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="phone" class="form-control m-input" id="phone"
                                   placeholder="{{__('Hãy nhập số điện thoại')}}..." value="{{$branch['phone']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Email')}}:
                            </label>
                            <input type="text" name="email" class="form-control m-input" id="email"
                                   placeholder="{{__('Hãy nhập email')}}..." value="{{$branch['email']}}">
                            <span class="error_email" style="color: red"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Hot line')}}:
                            </label>
                            <input type="text" name="hot_line" class="form-control m-input" id="hot_line"
                                   placeholder="{{__('Hãy nhập hot line')}}..." value="{{$branch['hot_line']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="m-checkbox m-checkbox--air">
                                        @if($branch['is_representative']==1)
                                            <input id="is_representative" name="example_3" type="checkbox" checked>
                                        @else
                                            <input id="is_representative" name="example_3" type="checkbox">
                                        @endif
                                        {{__('Là trụ sở chính')}}
                                        <span></span>
                                    </label>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-2">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label>
                        <input id="is_actived" name="is_actived" type="checkbox"
                                {{$branch['is_actived']==1?'checked':''}}>
                        <span></span>
                    </label>
                </span>
                                        </div>
                                        <div class="col-lg-10 m--margin-top-5">
                                            <i>{{__('Select to activate status')}}</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <a href="javascript:void(0)" onclick="branch.modal_image()"
                                       class="btn btn-sm m-btn--icon color">
                                            <span class="">
                                                <i class="la la-plus"></i>

                                                    {{__('Thêm ảnh chi nhánh')}}

                                            </span>
                                    </a>
                                </div>
                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <div class="image-show ">
                                        @foreach($list_img as $item)
                                            <div class="wrap-img image-show-child">
                                                <input type="hidden" name="branch_image" class="branch_image"
                                                       value="{{$item->name}}">
                                                <img class='m--bg-metal m-image img-sd '
                                                     src='{{$item->name}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
                                                <span class="delete-img" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="branch.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tỉnh/ Thành phố')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control width-select" id="provinceid" name="provinceid">
                                    <option></option>
                                    @foreach($optionProvince as $key=>$value)
                                        @if($branch['provinceid']==$key)
                                            <option value="{{$key}}" selected>{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Quận/ Huyện')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control width-select" id="districtid" name="districtid">
                                    @if($branch['districtid'] != null)
                                        <option value="{{$branch['districtid']}}" selected>{{$branch['district_type'] . ' '. $branch['district_name']}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="address" class="form-control m-input" id="address"
                                   placeholder="{{__('Hãy nhập địa chỉ')}}..." value="{{$branch['address']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                Latitude:
                            </label>
                            <input type="text" name="latitude" class="form-control m-input" id="latitude"
                                   placeholder="{{__('Hãy nhập latitude...')}}" value="{{$branch['latitude']}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                Longitude:
                            </label>
                            <input type="text" name="longitude" class="form-control m-input" id="longitude"
                                   placeholder="{{__('Hãy nhập longitude...')}}" value="{{$branch['longitude']}}">
                        </div>
                        <div class="form-group">
                            <label>
                                {{__('Giới thiệu')}}:
                            </label>
                            <textarea placeholder="{{__('Nhập mô tả')}}" rows="5" cols="40"
                                      name="description" id="description"
                                      class="form-control">{{$branch['description']}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.branch')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>

                        <button type="button" onclick="branch.edit()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-edit"></i>
                                <span>{{__('CẬP NHẬT')}}</span>
                        </span>
                        </button>

                        {{--<button type="submit" onclick="branch.add(0)"--}}
                        {{--class="btn btn-success color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">--}}
                        {{--<span>--}}
                        {{--<i class="fa fa-plus-circle"></i>--}}
                        {{--<span>{{__('LƯU & TẠO MỚI')}}</span>--}}
                        {{--</span>--}}
                        {{--</button>--}}


                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="imgeShow">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img" value="{link_hidden}">
            <img class='m--bg-metal m-image img-sd '
                 src='{{'{link}'}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="branch.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
        </div>

    </script>
    <script src="{{asset('static/backend/js/admin/branch/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/branch/dropzone.js?v='.time())}}" type="text/javascript"></script>

@stop


