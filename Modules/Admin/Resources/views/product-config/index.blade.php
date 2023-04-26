@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
    </span>
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
                        @lang('CẤU HÌNH SẢN PHẨM')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Hình thức hiển thị sản phẩm theo danh mục'):
                    </label>
                    <div class="input-group">
                        <select class="form-control" id="display_view_category" name="display_view_category">
                            <option value="H" {{$config['display_view_category'] == 'H' ? 'selected' : ''}}>@lang('Nằm ngang')</option>
                            <option value="V" {{$config['display_view_category'] == 'V' ? 'selected' : ''}}>@lang('Nằm dọc')</option>
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Hiển thị sản phẩm kèm theo'):
                    </label>
                    <div class="input-group">
                         <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onchange="view.changeDisplay(this)"
                                           class="manager-btn" name="is_display_bundled" id="is_display_bundled"
                                            {{$config['is_display_bundled'] == 1 ? 'checked': ''}}>
                                    <span></span>
                                </label>
                        </span>
                    </div>
                </div>
                <div class="orm-group m-form__group div_type">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại hiển thị'):
                        </label>
                        <div class="input-group">
                            <select class="form-control" id="type_bundled_product" name="type_bundled_product" onchange="view.changeType(this)">
                                <option value="tag" {{$config['type_bundled_product'] == 'tag' ? 'selected': ''}}>@lang('Theo tag')</option>
                                <option value="category" {{$config['type_bundled_product'] == 'category' ? 'selected': ''}}>@lang('Theo danh mục')</option>
                                <option value="custom_category" {{$config['type_bundled_product'] == 'custom_category' ? 'selected': ''}}>@lang('Theo danh mục chỉ định')</option>
                            </select>
                        </div>
                    </div>
                    <div class="div_detail">
                        @if ($config['type_bundled_product'] == 'custom_category')
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    @lang('Danh mục sản phẩm'):
                                </label>
                                <div class="input-group">
                                    <select class="form-control" id="product_category" name="product_category" multiple>
                                        <option></option>
                                        @foreach($optionCategory as $v)
                                            <option value="{{$v['product_category_id']}}"
                                                    {{in_array($v['product_category_id'], $arrObjectDetail) ? 'selected': ''}}>{{$v['category_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Số sản phẩm hiển thị'):
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control input_int" id="limit_item" name="limit_item" value="{{$config['limit_item']}}">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        </a>
                        <button type="button" onclick="view.save('{{$config['product_config_id']}}')"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-config/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init();
    </script>
    <script type="text/template" id="custom-tpl">
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Danh mục sản phẩm'):
            </label>
            <div class="input-group">
                <select class="form-control" id="product_category" name="product_category" multiple>
                    <option></option>
                    @foreach($optionCategory as $v)
                        <option value="{{$v['product_category_id']}}">{{$v['category_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </script>
@stop