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
            <input type="text" name="image" class="form-control" placeholder="Dán link tại đây" value="{{isset($item->image)?$item->image:''}}">
            <a href="javascript:void(0)"
               onclick="document.getElementById('getFile').click()" class="btn  btn-sm m-btn--icon color">
                <div class="avatar float-left">
                    <img class="m--bg-metal img-sd" id="blah"
                         src="{{isset($item->image)?$item->image:asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                         alt="{{ __('Hình ảnh') }}" width="300px" height="300px">
                </div>
            </a>

            <input accept=".png, .jpg"
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
              class="form-control m-input preview-class" maxlength="2000">{{isset($item->image_title)?$item->image_title:''}}</textarea>
        <i class="pull-right">{{ __('Số ký tự') }}: <i
                    class="count-character">0</i>{{ __('/2000 ký tự') }}</i>
    </div>
</div>
</div>