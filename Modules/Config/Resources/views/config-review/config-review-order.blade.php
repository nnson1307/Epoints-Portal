@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CẤU HÌNH')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="la la-th-list"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CẤU HÌNH ĐÁNH GIÁ')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--<a href="{{route('contract.contract')}}"--}}
                {{--class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">--}}
                {{--<span>--}}
                {{--<i class="la la-arrow-left"></i>--}}
                {{--<span>@lang('HỦY')</span>--}}
                {{--</span>--}}
                {{--</a>--}}
                <a href="{{route('config.config-review.config-order')}}"
                   class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    <span>@lang('ĐƠN HÀNG')</span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-config">
                <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">
                                @lang('Quyền đánh giá'):
                            </div>
                            <div class="col-lg-9">
                                <select class="form-control" id="is_buy" name="is_buy" disabled>
                                    <option value="1">@lang('Khách hàng đã mua sản phẩm')</option>
                                    <option value="0">@lang('Tất cả khách hàng')</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">
                                @lang('Thời hạn đánh giá'):
                            </div>
                            <div class="col-lg-9 input-group m-input-group">
                                <input type="text" class="form-control m-input input_int" id="expired_review"
                                       name="expired_review" value="{{$item['expired_review'] / 30}}">
                                <div class="input-group-append">
                                    <span class="input-group-text">@lang('Tháng')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">
                                @lang('Bình luận'):
                            </div>
                            <div class="col-lg-9 input-group m-input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text">@lang('Giới hạn ký tự')</span>
                                </div>
                                <input type="text" class="form-control m-input input_int" id="max_length_content"
                                       name="max_length_content" value="{{$item['max_length_content']}}">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">

                            </div>
                            <div class="col-lg-9">
                                <div class="m-checkbox-list">
                                    <label class="m-checkbox m-checkbox--success">
                                        <input type="checkbox" id="is_review_image"
                                               name="is_review_image" onclick="view.checkAddImage(this)"
                                                {{$item['is_review_image'] == 1 ? 'checked': ''}}> @lang('Thêm hình ảnh')
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row div_config_image" style="display: {{$item['is_review_image'] == 1 ? 'flex': 'none'}};">
                            <div class="col-lg-3 font-weight-bold">

                            </div>
                            <div class="col-lg-9 row">
                                <div class="col-lg-6 input-group m-input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">@lang('Giới hạn số lượng')</span>
                                    </div>
                                    <input type="text" class="form-control m-input input_int" id="limit_number_image"
                                           name="limit_number_image" value="{{$item['limit_number_image']}}">
                                </div>
                                <div class="col-lg-6 input-group m-input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">@lang('Giới hạn dung lượng (Mb)')</span>
                                    </div>
                                    <input type="text" class="form-control m-input input_int" id="limit_capacity_image"
                                           name="limit_capacity_image" value="{{$item['limit_capacity_image']}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">

                            </div>
                            <div class="col-lg-9">
                                <div class="m-checkbox-list">
                                    <label class="m-checkbox m-checkbox--success">
                                        <input type="checkbox" id="is_review_video"
                                               name="is_review_video" onclick="view.checkAddVideo(this)"
                                                {{$item['is_review_video'] == 1 ? 'checked': ''}}> @lang('Thêm video')
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row div_config_video" style="display: {{$item['is_review_video'] == 1 ? 'flex': 'none'}};">
                            <div class="col-lg-3 font-weight-bold">

                            </div>
                            <div class="col-lg-9 row">
                                <div class="col-lg-6 input-group m-input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">@lang('Giới hạn số lượng')</span>
                                    </div>
                                    <input type="text" class="form-control m-input input_int" id="limit_number_video"
                                           name="limit_number_video" value="{{$item['limit_number_video']}}">
                                </div>
                                <div class="col-lg-6 input-group m-input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">@lang('Giới hạn dung lượng (Mb)')</span>
                                    </div>
                                    <input type="text" class="form-control m-input input_int" id="limit_capacity_video"
                                           name="limit_capacity_video" value="{{$item['limit_capacity_video']}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">
                                @lang('Cú pháp gợi ý đánh giá'):
                            </div>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_suggest" name="is_suggest" type="checkbox" {{$item['is_suggest'] == 1 ? 'checked': ''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                    <div class="col-lg-6 m--margin-top-5">
                                        <i>@lang('Cho phép user chọn gợi ý')</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold"></div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#suggest_5" role="tab"
                                               aria-selected="true">
                                                @lang('5 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#suggest_4" role="tab"
                                               aria-selected="true">
                                                @lang('4 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#suggest_3" role="tab"
                                               aria-selected="true">
                                                @lang('3 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#suggest_2" role="tab"
                                               aria-selected="true">
                                                @lang('2 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#suggest_1" role="tab"
                                               aria-selected="true">
                                                @lang('1 sao')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <div class="tab-content">
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="suggest_5" role="tabpanel">
                                                <select class="form-control" id="content_suggest_5" name="content_suggest_5" style="width:100%;" multiple>
                                                    @foreach($suggest5 as $v)
                                                        <option value="{{$v['content_suggest_id']}}"
                                                                {{in_array($v['content_suggest_id'], $arrSuggest5) ? 'selected' : ''}}>{{$v['content_suggest']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="tab-pane" id="suggest_4" role="tabpanel">
                                                <select class="form-control" id="content_suggest_4" name="content_suggest_4" style="width:100%;" multiple>
                                                    @foreach($suggest4 as $v)
                                                        <option value="{{$v['content_suggest_id']}}"
                                                                {{in_array($v['content_suggest_id'], $arrSuggest4) ? 'selected' : ''}}>{{$v['content_suggest']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="tab-pane" id="suggest_3" role="tabpanel">
                                                <select class="form-control" id="content_suggest_3" name="content_suggest_3" style="width:100%;" multiple>
                                                    @foreach($suggest3 as $v)
                                                        <option value="{{$v['content_suggest_id']}}"
                                                                {{in_array($v['content_suggest_id'], $arrSuggest3) ? 'selected' : ''}}>{{$v['content_suggest']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="tab-pane" id="suggest_2" role="tabpanel">
                                                <select class="form-control" id="content_suggest_2" name="content_suggest_2" style="width:100%;" multiple>
                                                    @foreach($suggest2 as $v)
                                                        <option value="{{$v['content_suggest_id']}}"
                                                                {{in_array($v['content_suggest_id'], $arrSuggest2) ? 'selected' : ''}}>{{$v['content_suggest']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="tab-pane" id="suggest_1" role="tabpanel">
                                                <select class="form-control" id="content_suggest_1" name="content_suggest_1" style="width:100%;" multiple>
                                                    @foreach($suggest1 as $v)
                                                        <option value="{{$v['content_suggest_id']}}"
                                                                {{in_array($v['content_suggest_id'], $arrSuggest1) ? 'selected' : ''}}>{{$v['content_suggest']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">
                                @lang('Nội dung gợi ý đánh giá'):
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#hint_5" role="tab"
                                               aria-selected="true">
                                                @lang('5 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#hint_4" role="tab"
                                               aria-selected="true">
                                                @lang('4 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#hint_3" role="tab"
                                               aria-selected="true">
                                                @lang('3 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#hint_2" role="tab"
                                               aria-selected="true">
                                                @lang('2 sao')
                                            </a>
                                        </li>
                                        <li class="nav-item m-tabs__item">
                                            <a class="nav-link m-tabs__link" data-toggle="tab" href="#hint_1" role="tab"
                                               aria-selected="true">
                                                @lang('1 sao')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <div class="tab-content">
                                        <div class="tab-content">
                                            <div class="tab-pane active show" id="hint_5" role="tabpanel">
                                                <textarea class="form-control" id="content_hint_5" name="content_hint_5">{{$contentHint5}}</textarea>
                                            </div>
                                            <div class="tab-pane" id="hint_4" role="tabpanel">
                                                <textarea class="form-control" id="content_hint_4" name="content_hint_5">{{$contentHint4}}</textarea>
                                            </div>
                                            <div class="tab-pane" id="hint_3" role="tabpanel">
                                                <textarea class="form-control" id="content_hint_3" name="content_hint_5">{{$contentHint3}}</textarea>
                                            </div>
                                            <div class="tab-pane" id="hint_2" role="tabpanel">
                                                <textarea class="form-control" id="content_hint_2" name="content_hint_5">{{$contentHint2}}</textarea>
                                            </div>
                                            <div class="tab-pane" id="hint_1" role="tabpanel">
                                                <textarea class="form-control" id="content_hint_1" name="content_hint_5">{{$contentHint1}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                    <div class="m-portlet__body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">
                                @lang('Hiển thị đánh giá trên google'):
                            </div>
                            <div class="col-lg-9">
                                <div class="row">
                                    <div class="col-lg-1">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label>
                                            <input id="is_review_google" name="is_review_google" type="checkbox"
                                                    {{$item['is_review_google'] == 1 ? 'checked': ''}}>
                                            <span></span>
                                        </label>
                                    </span>
                                    </div>
                                    <div class="col-lg-6 m--margin-top-5">
                                        <i>@lang('Cho phép hiển thị đánh giá trên google')</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3 font-weight-bold">

                            </div>
                            <div class="col-lg-9">
                                <div class="m-checkbox-inline">
                                    <label class="m-checkbox m-checkbox--success">
                                        <input type="checkbox" name="rating_value_google" value="5" {{in_array(5, $arrRatingValue) ? 'checked': ''}}> @lang('5 sao')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--success">
                                        <input type="checkbox" name="rating_value_google" value="4" {{in_array(4, $arrRatingValue) ? 'checked': ''}}> @lang('4 sao')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--success">
                                        <input type="checkbox" name="rating_value_google" value="3" {{in_array(3, $arrRatingValue) ? 'checked': ''}}> @lang('3 sao')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--success">
                                        <input type="checkbox" name="rating_value_google" value="2" {{in_array(2, $arrRatingValue) ? 'checked': ''}}> @lang('2 sao')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--success">
                                        <input type="checkbox" name="rating_value_google" value="1" {{in_array(1, $arrRatingValue) ? 'checked': ''}}> @lang('1 sao')
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <button type="button" onclick="view.saveOrder({{$item['config_review_id']}})"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/config/config-review/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init();
    </script>
@stop


