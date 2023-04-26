@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ NỘI DUNG HỖ TRỢ')}}</span>
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
                        {{__('CHI TIẾT NỘI DUNG HỖ TRỢ')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form action="{{ route('admin.faq.store') }}" method="POST" id="form-submit">
            {{ csrf_field() }}
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Tên nội dung hỗ trợ (Vi)')}}</label>
                    <div class="col-10">
                        <input class="form-control{{ $errors->has('faq_group_title') ? ' is-invalid' : '' }}" disabled value="{{$detail['faq_title_vi']}}"
                               type="text" name="faq_title" id="faq_title" placeholder="{{__('Nhập tên nội dung hỗ trợ (VI)')}}" >
                        @if ($errors->has('faq_title'))
                            <div class="invalid-feedback">
                                {{ $errors->first('faq_title') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Tên nội dung hỗ trợ (EN)')}}</label>
                    <div class="col-10">
                        <input class="form-control{{ $errors->has('faq_group_title') ? ' is-invalid' : '' }}" disabled value="{{$detail['faq_title_en']}}"
                               type="text" name="faq_title" id="faq_title" placeholder="{{__('Nhập tên nội dung hỗ trợ (EN)')}}" >
                        @if ($errors->has('faq_title'))
                            <div class="invalid-feedback">
                                {{ $errors->first('faq_title') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Nhóm nội dung hỗ trợ')}}</label>
                    <div class="col-10">
                        <select class="form-control select2" disabled name="faq_group"
                                id="faq_group">
                            <option value="">{{__('Nhóm nội dung hỗ trợ')}}</option>
                            @if (isset($parentList) && count($parentList) > 0)
                                @foreach ($parentList as $item)
                                    <option value="{{ $item['faq_group_id'] }}" {{$detail['faq_group'] == $item['faq_group_id'] ? "selected" :""}}>
                                        {{ __($item[getValueByLang('faq_group_title_')]) }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Nội dung (VI)')}}</label>
                    <div class="col-10">
                        <textarea type="text"
                                  class="form-control"
                                  id="faq_content_vi" name="faq_content"
                                  placeholder="" cols="5" rows="5">{!! $detail['faq_content_vi'] !!}</textarea>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Nội dung (EN)')}}</label>
                    <div class="col-10">
                        <textarea type="text"
                                  class="form-control"
                                  id="faq_content_en" name="faq_content"
                                  placeholder="" cols="5" rows="5">{!! $detail['faq_content_en'] !!}</textarea>
                    </div>
                </div>
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Thứ tự hiển thị')}}</label>
                    <div class="col-10">
                        <input type="number" min="1" value="{{ $detail['faq_position'] }}"
                               class="form-control{{ $errors->has('faq_position') ? ' is-invalid' : '' }}"
                               id="faq_position" disabled name="faq_position"
                               placeholder="{{__('Thứ tự hiển thị')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{__('Trạng thái')}}</label>
                    <div class="col-10">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" disabled class="manager-btn" {{ $detail['is_actived'] == 1 ? "checked" :"" }} name="is_actived">
                                    <span></span>
                                </label>
                         </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.faq.index')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>
                        @if(in_array('admin.faq.edit',session('routeList')))
                            <a href="{{route('admin.faq.edit',['id' => $detail['faq_id']])}}"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>{{__('CHỈNH SỬA')}}</span>
                                </span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
        $('#faq_content').summernote({
            height: 150,
            placeholder: 'Nhập nội dung...',
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]

        });
        $('#faq_content_vi').summernote('disable');
        $('#faq_content_en').summernote('disable');
    </script>
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/faq/script.js?v='.time()) }}"></script>
@stop


