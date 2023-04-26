<div class="form-group m-form__group mt-3">
    <div class="row">
        <div class="col-lg-3  col-sm-12">
            {{__('Hình ảnh')}}<b class="text-danger">*</b> <br>
            <span>
                {{__('Hỗ trợ ảnh tĩnh, ảnh động.')}}<br>
                {{__('Các định dạng ảnh hỗ trợ: jpg và png')}}<br>
                {{__('Dung lượng tối đa: 1MB')}}
            </span>
        </div>
        <div class="col-lg-9  col-sm-12 div_avatar">
            <input type="text" class="form-control" name="image" placeholder="Dán link tại đây"
                   value="{{isset($item->image)?$item->image:''}}">
            <a href="javascript:void(0)"
               onclick="document.getElementById('getFile').click()" class="btn  btn-sm m-btn--icon color">
                <div class="avatar float-left">
                    <img class="m--bg-metal img-sd" id="blah"
                         src="{{isset($item->image)?$item->image:asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                         alt="{{ __('Hình ảnh') }}" width="300px" height="300px">
                </div>
            </a>

            <input accept="image/png,jpg|png"
                   data-msg-accept="{{ __('Hình ảnh không đúng định dạng') }}"
                   id="getFile" type="file" onchange="uploadImage(this);"
                   class="form-control" style="display:none">
        </div>
    </div>
</div>
<div class="form-group m-form__group">
    <div class="row">
        <div class="col-lg-3  w-col-mb-100">
            {{__('Tiêu đề ảnh')}}<b class="text-danger">*</b> <br>
        </div>
        <div class="col-md-9">
    <textarea name="image_title" rows="1" cols="40"
              class="form-control m-input preview-class" maxlength="100">{{isset($item->image_title)?$item->image_title:''}}</textarea>
            <i class="pull-right">{{ __('Số ký tự') }}: <i
                        class="count-character">0</i>{{ __('/100 ký tự') }}</i>
        </div>
    </div>
</div>
<div class="form-group m-form__group">
    <div class="row">
        <div class="col-lg-3  w-col-mb-100">
            {{__('Nội dung')}}<b class="text-danger">*</b> <br>
        </div>
        <div class="col-md-9">
    <textarea name="preview" rows="1" cols="40"
              class="form-control m-input preview-class" maxlength="500">{{isset($item->preview)?$item->preview:''}}</textarea>
            <i class="pull-right">{{ __('Số ký tự') }}: <i
                        class="count-character">0</i>{{ __('/500 ký tự') }}</i>
        </div>
    </div>
</div>
<div class="form-group m-form__group">
    <div class="row">
        <div class="col-lg-3  w-col-mb-100">
            {{__('Url')}}<b class="text-danger">*</b> <br>
        </div>
        <div class="col-md-9">
            <input type="text" class="form-control" name="link_image" value="{{isset($item->link_image)?$item->link_image:''}}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3">
        <label>
            {{ __('Danh sách tham số') }}:
        </label>
    </div>
    <div class="col-lg-9">
        <div class="d-flex">
            @foreach ($param_list as $key => $value)
                <button type="button" class="mr-3 p-3 text-black-50 bg-secondary text-params-coppy"
                        data-value="<{{$key}}>"><i
                            class="fa fa-clone mr-2"></i>{{ $value }}</button>
            @endforeach
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <label>
            {{ __('Nút thao tác') }}:<b class="text-danger">*</b>
        </label>
    </div>
    <div class="col-lg-12">
        <div id="list_button">
            @if(isset($item) && $item->template_button())
                @foreach($item->template_button() as $key => $button_item)
                    @php
                        $arr = [
                        'stt' => $key + 1,
                        'list_type_button' => $list_type_button,
                        'type_button' => $button_item->type_button,
                        'button_item' => $button_item
                    ];
                    @endphp
                    @include('zns::template.follower.include.button_item',$arr )
                @endforeach
            @endif
        </div>
    </div>
    <div class="col-lg-12">
        <div class="dropdown mr-3">
            <button class="btn ss--button-cms-piospa dropdown-toggle" type="button" id="dropdownMenu2"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-plus-circle"></i>
                <span> {{ __('THÊM NÚT') }}</span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <span class="dropdown-item add_type_button" data-value="1">{{__('Đến trang web khác')}}</span>
                <span class="dropdown-item add_type_button" data-value="2">{{__('Gọi điện')}}</span>
                <span class="dropdown-item add_type_button" data-value="3">{{__('Gửi tin nhắn')}}</span>
            </div>
        </div>
    </div>
</div>
