@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;">@lang('Chỉnh sửa phản hồi')</span>
@endsection
@section("after_style")
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 ">
            <!-- Default box -->
            <form method="POST" action="{{route('chathub.response.update',[ 'response_id' => $arrDetail['response_id']])}}" accept-charset="UTF-8">
                {{ csrf_field() }}
                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">@lang('Chỉnh sửa phản hồi')</h3>
                    </div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>
                    @endif

                    <div class="box-body row">
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->

                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <!-- text input -->
                        <div class="form-group col-sm-12">
                            <label>@lang('Tiêu đề')</label>
                            <input type="text" name="response_name" value="{{$arrDetail['response_name']}}" class="form-control">
                        </div>
                        <!-- load the view from the application if it exists, otherwise load the one in the package -->
                        <!-- text input -->
                        <div class="form-group col-sm-12">
                            <label>@lang('Nhãn hiệu')</label>
                            <select name="entities[brand][]" class="form-control select2" multiple>
                                @foreach($arrBrand as $brand)
                                    <option @if(isset($arrDetail['brand'][$brand['brand_id']])) selected @endif value="{{$brand['brand_id']}}">{{$brand['brand_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>@lang('Nhãn hiệu con')</label>
                            <select name="entities[sub_brand][]" class="form-control select2" multiple>
                                @foreach($arrSubBrand as $subBrand)
                                    <option @if(isset($arrDetail['sub_brand'][$subBrand['sub_brand_id']])) selected @endif value="{{$subBrand['sub_brand_id']}}">{{$subBrand['sub_brand_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>@lang('Sản phẩm')</label>
                            <select name="entities[sku][]" class="form-control select2" multiple>
                                @foreach($arrSku as $sku)
                                    <option @if(isset($arrDetail['sku'][$sku['sku_id']])) selected @endif value="{{$sku['sku_id']}}">{{$sku['sku_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>@lang('Thuộc tính')</label>
                            <select name="entities[attribute][]" class="form-control select2" multiple>
                                @foreach($arrAttribute as $attr)
                                    <option @if(isset($arrDetail['attribute'][$attr['attribute_id']])) selected @endif value="{{$attr['attribute_id']}}">{{$attr['attribute_name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-12">
                            <label>@lang('Phản hồi')</label>
                            <select name="response_content" class="form-control select2">
                                @foreach($arrResponseContent as $responseContent)
                                    <option value="{{$responseContent['response_content_id']}}" @if($responseContent['response_content_id'] == $arrDetail['response_content'])) selected @endif >{{$responseContent['title']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>@lang('Loại')</label>
                            <select name="type" class="form-control select2">
                                <option value="" {{$arrResponseDetail['type'] == '' ? "selected" : "" }}>------------</option>
                                @foreach($type as $key => $value)
                                    <option value="{{$key}}" {{$arrResponseDetail['type'] == $key ? "selected" : ""}} >{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="preurl" value="{{@$preurl}}">
                    </div><!-- /.box-body -->
                    <div class="modal-footer">
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m--align-right">
                                <a href="{{route("chathub.response")}}" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md" data-style="zoom-in">
                                <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                                </span>
                                </a>
                                <button type="submit" class="btn btn-primary color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10" data-style="zoom-in">
                                    <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('CẬP NHẬT')</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div></form><!-- /.box -->
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/chathub/response/add.js?v='.time())}}" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function($) {
            // trigger select2 for each untriggered select2_multiple box
            $('.select2').each(function (i, obj) {
                if (!$(obj).data("select2"))
                {
                    $(obj).select2();
                }
            });
        });
        $(document).ready(function(){
            $('.date-picker').datepicker({format: 'dd/mm/yyyy',});
        });
    </script>
@stop