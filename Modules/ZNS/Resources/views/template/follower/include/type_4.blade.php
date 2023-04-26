<div class="row">
    <div class="col-lg-3">
        <label>
            {{ __('Chọn file') }}: <b class="text-danger">*</b>
        </label>
        <div class="form-group m-form__group">
            <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
               onclick="follower.modalFile()">
                <i class="fa fa-plus-circle"></i> @lang('Chọn file')
            </a>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="form-group m-form__group">
            <div class="div_file_ticket">
                @if(isset($item->file))
                    <div class="form-group m-form__group div_file d-flex">
                        <input type="hidden" name="file" value="{{$item->file}}">
                        <a target="_blank" href="{{$item->file}}" class="file_ticket">
                            {{$item->file}}
                        </a>
                        <a style="color:black;" href="javascript:void(0)" onclick="follower.removeFile(this)">
                            <i class="la la-trash"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{--<div class="row">--}}
{{--    <div class="col-lg-3">--}}
{{--        <label>--}}
{{--            {{ __('Tiêu đề file') }}: <b class="text-danger">*</b>--}}
{{--        </label>--}}
{{--    </div>--}}
{{--    <div class="col-lg-9">--}}
{{--        <div class="form-group m-form__group">--}}
{{--                        <textarea name="file_title" rows="5" cols="40"--}}
{{--                                  class="form-control m-input" maxlength="480">{{isset($item->file_title)?$item->file_title:''}}</textarea>--}}
{{--            <i class="pull-right">{{ __('Số ký tự') }}: <i--}}
{{--                        class="count-character">0</i>{{ __('/480 ký tự') }}</i>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="row">--}}
{{--    <div class="col-lg-3">--}}
{{--        <label>--}}
{{--            {{ __('Danh sách tham số') }}:--}}
{{--        </label>--}}
{{--    </div>--}}
{{--    <div class="col-lg-9">--}}
{{--        <div class="d-flex">--}}
{{--            @foreach ($param_list as $key => $value)--}}
{{--                <button type="button" class="mr-3 p-3 text-black-50 bg-secondary text-params-coppy" data-value="<{{$key}}>"><i--}}
{{--                            class="fa fa-clone mr-2"></i>{{ $value }}</button>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}