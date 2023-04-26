@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ NHÓM HỖ TRỢ')}}</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        {{__('THÊM NHÓM NỘI DUNG')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form action="{{ route('admin.faq-group.store') }}" method="POST" id="form-submit">
            {{ csrf_field() }}
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Tên nhóm nội dung (VI)')}}</label>
                    <div class="col-10">
                        <input class="form-control{{ $errors->has('faq_group_title_vi') ? ' is-invalid' : '' }}" required
                               type="text" name="faq_group_title_vi" id="faq_group_title_vi" placeholder="{{__('Nhập tên nhóm hỗ trợ (VI)')}}" value="{{ old('faq_group_title_vi') }}">
                        @if ($errors->has('faq_group_title_vi'))
                            <div class="invalid-feedback">
                                {{ $errors->first('faq_group_title_vi') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Tên nhóm nội dung (EN)')}}</label>
                    <div class="col-10">
                        <input class="form-control{{ $errors->has('faq_group_title_en') ? ' is-invalid' : '' }}" required
                               type="text" name="faq_group_title_en" id="faq_group_title_en" placeholder="{{__('Nhập tên nhóm hỗ trợ (EN)')}}" value="{{ old('faq_group_title_en') }}">
                        @if ($errors->has('faq_group_title_en'))
                            <div class="invalid-feedback">
                                {{ $errors->first('faq_group_title_en') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">Tên {{__('Nhóm nội dung cha')}}</label>
                    <div class="col-10">
                        <select class="form-control" name="parent_id"
                                id="parent_id">
                            <option value="0">{{__('Nhóm nội dung cha')}}</option>
                            @if (isset($parentList) && count($parentList) > 0)
                                @foreach ($parentList as $item)
                                    <option value="{{ $item['faq_group_id'] }}" {{ old('parent_id') == $item['faq_group_id'] ? 'selected' : '' }}>
                                        {{ __($item[getValueByLang('faq_group_title_')]) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Thứ tự hiển thị')}}</label>
                    <div class="col-10">
                        <input type="number" min="1" value="{{ old('faq_group_position', 1) }}"
                               class="form-control{{ $errors->has('faq_group_position') ? ' is-invalid' : '' }}"
                               id="faq_group_position" name="faq_group_position"
                               placeholder="{{__('Thứ tự hiển thị')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Trạng thái')}}</label>
                    <div class="col-10">
{{--                            <span class="kt-switch kt-switch--success">--}}
{{--                                <label>--}}
{{--                                    <input type="checkbox" checked="checked" name="is_actived">--}}
{{--                                    <span></span>--}}
{{--                                </label>--}}
{{--							</span>--}}
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn" checked name="is_actived">
                                    <span></span>
                                </label>
                         </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.faq-group.index')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>
                        <button type="button" onclick="faqGroup.save(0)"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU & TẠO MỚI')}}</span>
                        </span>
                        </button>
                        <button type="button" onclick="faqGroup.save(1)"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU VÀ THOÁT')}}</span>
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
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/faq-group/script.js') }}"></script>
@stop


