<div class="modal fade ajax people-edit-modal" method="POST" action="{{route('people.people.ajax-edit')}}" role="dialog">
    <div class="modal-dialog modal-dialog-centered hu-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Sửa công dân')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="m-form__group form-group col-2">
                        <div class="form-group m-form__group m-widget19">
                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd avatar" height="150px" src="{{$item['avatar']??'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}" alt="Hình ảnh">
                            </div>
                            <input type="hidden" id="avatar" name="avatar" class="avatar">
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" data-msg-accept="Hình ảnh không đúng định dạng" type="file" onchange="uploadImage(this,'.people-edit-modal .avatar');" class="form-control getFile" style="display:none">
                            <div class="m-widget19__action" style="max-width: 170px">
                                <a href="javascript:void(0)" onclick="$('.people-edit-modal .getFile').click()" class="btn  btn-sm m-btn--icon color w-100">
                                        <span class="">
                                            <i class="fa fa-camera"></i>
                                            <span>
                                                Tải ảnh lên</span>
                                        </span>
                                </a>

                                <a href="javascript:void(0)" onclick="index.showPopCamera()" class="btn btn-sm m-btn--icon color w-100" style="margin-top: 5px;">
                                    <span class="">
                                        <i class="fa fa-camera"></i>
                                        <span>
                                            Chụp ảnh camera                                                </span>
                                    </span>
                            </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="m-portlet m-portlet--head-sm m-0">
                            <div class="m-portlet__head" onclick="$(this).parent().find('.m-portlet__body').toggleClass('d-none')">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                        <h2 class="m-portlet__head-text">@lang('Thông tin cơ bản')</h2>
                                    </div>
                                </div>
                                <div class="m-portlet__head-tools">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>

                            <div class="m-portlet__body">
                                <div class="row">
                                    <div class="col-4 form-group m-form__group">
                                        <label class="black_title">
                                            Tên công dân:<b class="text-danger">*</b>
                                        </label>
                                        <div class="">
                                            <input type="text" class="form-control m-input" name="full_name" placeholder="Nhập tên công dân" value="{{$item['full_name']}}">
                                        </div>
                                    </div>
                                    <div class="col-4 form-group m-form__group">
                                        <label class="black_title">
                                            Mã hồ sơ:<b class="text-danger">*</b>
                                        </label>
                                        <div class="">
                                            <input type="text" class="form-control m-input" name="code" placeholder="Nhập mã hồ sơ" value="{{$item['code']}}">
                                        </div>
                                    </div>
                                    <div class="col-4 form-group m-form__group">
                                        <label class="black_title">
                                            Ngày tháng năm sinh:<b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group date">
                                            <input type="text" class="form-control m-input datepicker" placeholder="Ngày tháng năm sinh" name="birthday" value="{{Carbon\Carbon::parse($item['birthday'])->format('d/m/Y')}}">
                                        </div>
                                    </div>
                                    <div class="col-4 m-form__group form-group">
                                        <label for="">
                                            Giới tính:<b class="text-danger">*</b>
                                        </label>
                                        <div class="m-radio-inline">
                                            <label class="m-radio">
                                                <input type="radio" name="gender" value="male" @if($item['gender']=='male') checked @endif> Nam
                                                <span></span>
                                            </label>
                                            <label class="m-radio">
                                                <input type="radio" name="gender" value="female" @if($item['gender']=='female') checked @endif> Nữ
                                                <span></span>
                                            </label>
                                            <label class="m-radio">
                                                <input type="radio" name="gender" value="others" @if($item['gender']=='others') checked @endif> Khác
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-4 form-group m-form__group">
                                        <label class="black_title">
                                            Địa chỉ tạm trú:<b class="text-danger">*</b>
                                        </label>
                                        <div class="">
                                            <textarea type="text" class="form-control m-input" name="temporary_address" placeholder="Nhập địa chỉ tạm trú">{{$item['temporary_address']}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-4 form-group m-form__group">
                                        <label class="black_title">
                                            Địa chỉ thường trú:<b class="text-danger">*</b>
                                        </label>
                                        <div class="">
                                            <textarea type="text" class="form-control m-input" name="permanent_address" placeholder="Nhập địa chỉ thường trú">{{$item['permanent_address']}}</textarea>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet m-portlet--head-sm m-0">
                    <div class="m-portlet__head" onclick="$(this).parent().find('.m-portlet__body').toggleClass('d-none')">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                <h2 class="m-portlet__head-text">@lang('Thông tin xác nhận')</h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <div class="row">
                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    CMND/CCCD:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" name="id_number" placeholder="Nhập CMND/CCCD" value="{{$item['id_number']}}">
                                </div>
                            </div>
                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Ngày cấp:<b class="text-danger"></b>
                                </label>
                                <div class="input-group date">
                                    <input type="text" class="form-control m-input datepicker" placeholder="Ngày cấp CMND/CCCD" name="id_license_date" value="@if($item['id_license_date']){{Carbon\Carbon::parse($item['id_license_date'])->format('d/m/Y')}}@endif">
                                </div>
                            </div>
                            @php $name='people_id_license_place_id'; $item2 = $filters[$name]; @endphp
                            <div class="col-4 form-group m-form__group align-items-center">
                                <label class="black_title">
                                    @lang('Nơi cấp'):<b class="text-danger"></b>
                                </label>
                                <div class="input-group">
                                    @if(isset($item2['text']))
                                        <div class="input-group-append">
                                <span class="input-group-text">
                                    {{ $item2['text'] }}
                                </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item2['data'], $item['people_id_license_place_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Quê quán:<b class="text-danger">*</b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" name="hometown" value="{{$item['hometown']??''}}" placeholder="Nhập quê quán">
                                </div>
                            </div>

                            @php $name='ethnic_id'; $item2 = $filters[$name]; @endphp
                            <div class="col-2 form-group m-form__group align-items-center">
                                <label class="black_title">
                                    @lang('Dân tộc'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    @if(isset($item2['text']))
                                        <div class="input-group-append">
                                <span class="input-group-text">
                                    {{ $item2['text'] }}
                                </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item2['data'], $item['ethnic_id'] ?? null, ['class' => 'form-control m-input','title'=>'Chọn trạng thái']) !!}
                                </div>
                            </div>

                            @php $name='religion_id'; $item2 = $filters[$name]; @endphp
                            <div class="col-2 form-group m-form__group align-items-center">
                                <label class="black_title">
                                    @lang('Tôn giáo'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    @if(isset($item2['text']))
                                        <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        {{ $item2['text'] }}
                                                    </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item2['data'], $item['religion_id'] ?? null, ['class' => 'form-control m-input','title'=>'Chọn trạng thái']) !!}
                                </div>
                            </div>

                            @php $name='people_job_id'; $item2 = $filters[$name]; @endphp
                            <div class="col-4 form-group m-form__group align-items-center">
                                <label class="black_title">
                                    @lang('Nghề nghiệp'):<b class="text-danger"></b>
                                </label>
                                <div class="input-group">
                                    @if(isset($item2['text']))
                                        <div class="input-group-append">
                                <span class="input-group-text">
                                    {{ $item2['text'] }}
                                </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item2['data'], $item['people_job_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Đăng ký khai sinh:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" name="birthplace" value="{{$item['birthplace']??''}}" placeholder="Nhập nơi đăng ký khai sinh">
                                </div>
                            </div>

                            <div class="col-2 form-group m-form__group">
                                <label class="black_title">
                                    Khu phố:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" name="group" value="{{$item['group']}}" placeholder="Nhập khu phố">
                                </div>
                            </div>

                            <div class="col-2 form-group m-form__group">
                                <label class="black_title">
                                    Tổ dân phố:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" name="quarter" value="{{$item['quarter']}}" placeholder="Nhập tổ dân phố">
                                </div>
                            </div>

                            @php $name='people_family_type_id'; $item2 = $filters[$name]; @endphp
                            <div class="col-4 form-group m-form__group align-items-center">
                                <label class="black_title">
                                    @lang('Thành phần gia đình'):<b class="text-danger"></b>
                                </label>
                                <div class="input-group">
                                    @if(isset($item2['text']))
                                        <div class="input-group-append">
                                <span class="input-group-text">
                                    {{ $item2['text'] }}
                                </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item2['data'], $item['people_family_type_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="m-portlet m-portlet--head-sm m-0">
                    <div class="m-portlet__head" onclick="$(this).parent().find('.m-portlet__body').toggleClass('d-none')">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                <h2 class="m-portlet__head-text">@lang('Đơn vị công tác')</h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <div class="row">
                            @php $name='educational_level_id'; $item2 = $filters[$name]; @endphp
                            <div class="col-4 form-group m-form__group align-items-center">
                                <label class="black_title">
                                    Trình độ văn hóa:<b class="text-danger"></b>
                                </label>
                                <div class="input-group">
                                    @if(isset($item2['text']))
                                        <div class="input-group-append">
                                <span class="input-group-text">
                                    {{ $item2['text'] }}
                                </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item2['data'], $item['educational_level_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Năm tốt nghiệp:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" onLoad="$(this).datepicker({format: 'yyyy',viewMode: 'years',minViewMode: 'years'})" name="graduation_year" value="{{$item['graduation_year']??''}}" placeholder="Nhập năm tốt nghiệp">
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Chuyên ngành đào tạo:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" name="specialized" value="{{$item['specialized']??''}}" placeholder="Nhập tên chuyên ngành đào tạo">
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Ngoại ngữ:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" name="foreign_language" value="{{$item['foreign_language']??''}}" placeholder="Nhập ngoại ngữ">
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Ngày vào đoàn TNCS Hồ Chí Minh:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" onLoad="$(this).datepicker({format: 'dd/mm/yyyy',viewMode: 'years'})"
                                           name="union_join_date" value="@if($item['union_join_date']){{Carbon\Carbon::parse($item['union_join_date'])->format('d/m/Y')}}@endif" placeholder="Chọn Ngày vào đoàn TNCS Hồ Chí Minh">
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    Ngày vào Đảng CSVN:<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <input type="text" class="form-control m-input" onLoad="$(this).datepicker({format: 'dd/mm/yyyy',viewMode: 'years'})"
                                           name="group_join_date" value="@if($item['group_join_date']){{Carbon\Carbon::parse($item['group_join_date'])->format('d/m/Y')}}@endif" placeholder="Chọn Ngày vào Đảng CSVN">
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    @lang('Nơi làm việc'):<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <textarea type="text" class="form-control m-input" name="workplace" placeholder="Nhập nơi làm việc" rows=4>{{$item['workplace']??''}}</textarea>
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    @lang('Trường cấp 1'):<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <textarea type="text" class="form-control m-input" name="elementary_school" placeholder="Nhập tên trường cấp 1" rows=4>{{$item['elementary_school']}}</textarea>
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    @lang('Trường cấp 2'):<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <textarea type="text" class="form-control m-input" name="middle_school" placeholder="Nhập tên trường cấp 2" rows=4>{{$item['middle_school']}}</textarea>
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    @lang('Trường cấp 3'):<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <textarea type="text" class="form-control m-input" name="high_school" placeholder="Nhập tên trường cấp 3" rows=4>{{$item['high_school']}}</textarea>
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    @lang('Từ 18-21 tuổi'):<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <textarea type="text" class="form-control m-input" name="from_18_to_21" placeholder="Từ 18-21 tuổi làm gì, ở đâu" rows=4>{{$item['from_18_to_21']}}</textarea>
                                </div>
                            </div>

                            <div class="col-4 form-group m-form__group">
                                <label class="black_title">
                                    @lang('Từ 21 tuổi đến nay'):<b class="text-danger"></b>
                                </label>
                                <div class="">
                                    <textarea type="text" class="form-control m-input" name="from_21_to_now" placeholder="Từ 21 tuổi đến nay làm gì, ở đâu" rows=4>{{$item['from_21_to_now']}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="m-portlet m-portlet--head-sm m-0">
                    <div class="m-portlet__head" onclick="$(this).parent().find('.m-portlet__body').toggleClass('d-none')">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                <h2 class="m-portlet__head-text">@lang('Thông tin gia đình')</h2>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>

                    <div class="m-portlet__body">
                        <div class="table-responsive">
                            <table class="hu-table-input table table-striped m-table ss--header-table ss--nowrap">
                                <thead>
                                <tr>
                                    <th class="ss--font-size-th">Mối quan hệ</th>
                                    <th>Họ & Tên</th>
                                    <th>Năm sinh</th>
                                    <th>Nghề nghiệp</th>
                                    <th>Địa chỉ cư trú</th>
                                    <th style="width:20%">Trước 30/04/1975</th>
                                    <th style="width:20%">Sau 30/04/1975</th>
                                    <th style="width:20%">Địa chỉ hiện tại</th>
                                    <th>Sống/Chết</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="hidden" name="family_member[people_family_id][]" value="">
                                        @php $name='people_family_relationship_type_id'; $item2 = $filters[$name]; @endphp
                                        <div class="form-group m-form__group align-items-center">
                                            <div class="input-group">
                                                @if(isset($item2['text']))
                                                    <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                {{ $item2['text'] }}
                                                            </span>
                                                    </div>
                                                @endif
                                                {!! Form::select("family_member[people_family_relationship_type_id][]", $item2['data'], $item2['default'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group m-form__group">
                                            <div class="">
                                                <input type="text" class="form-control m-input" name="family_member[full_name][]" placeholder="Nhập Họ & Tên">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group m-form__group">
                                            <div class="">
                                                <input type="text" class="form-control m-input" name="family_member[birth_year][]" placeholder="Nhập năm sinh">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php $name='people_job_id'; $item2 = $filters[$name]; @endphp
                                        <div class="form-group m-form__group align-items-center">
                                            <div class="input-group">
                                                @if(isset($item2['text']))
                                                    <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                {{ $item2['text'] }}
                                                            </span>
                                                    </div>
                                                @endif
                                                {!! Form::select("family_member[people_job_id][]", $item2['data'], $item2['default'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group m-form__group">
                                            <div class="">
                                                <input type="text" class="form-control m-input" name="family_member[address][]" placeholder="Nhập địa chỉ cư trú">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group m-form__group">
                                            <div class="">
                                                <textarea type="text" class="form-control m-input" name="family_member[before_30041975][]" placeholder="Trước 30/04/1975" rows=4></textarea>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group m-form__group">
                                            <div class="">
                                                <textarea type="text" class="form-control m-input" name="family_member[after_30041975][]" placeholder="Sau 30/04/1975" rows=4></textarea>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group m-form__group">
                                            <div class="">
                                                <textarea type="text" class="form-control m-input" name="family_member[current][]" placeholder="Hiện tại" rows=4></textarea>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select name="family_member[is_dead][]" onShow="$(this).select2({width:'100%'})">
                                            <option value="0">Sống</option>
                                            <option value="1">Chết</option>
                                        </select>
                                    </td>
                                </tr>

                                @foreach( ($item['family_member']??[]) as $member)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="family_member[people_family_id][]" value="{{$member['people_family_id']}}">
                                            @php $name='people_family_relationship_type_id'; $item2 = $filters[$name]; @endphp
                                            <div class="form-group m-form__group align-items-center">
                                                <div class="input-group">
                                                    @if(isset($item2['text']))
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                {{ $item2['text'] }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    {!! Form::select("family_member[people_family_relationship_type_id][]", $item2['data'], $member['people_family_relationship_type_id'] ?? null, ['class' => 'form-control m-input','title'=>'Chọn trạng thái','onLoad'=>'$(this).select2({width:"100%"})']) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-form__group">
                                                <div class="">
                                                    <input type="text" class="form-control m-input" name="family_member[full_name][]" placeholder="Nhập Họ & Tên" value="{{$member['full_name']}}">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-form__group">
                                                <div class="">
                                                    <input type="text" class="form-control m-input" name="family_member[birth_year][]" placeholder="Nhập năm sinh" value="{{$member['birth_year']}}">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php $name='people_job_id'; $item2 = $filters[$name]; @endphp
                                            <div class="form-group m-form__group align-items-center">
                                                <div class="input-group">
                                                    @if(isset($item2['text']))
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">
                                                                {{ $item2['text'] }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    {!! Form::select("family_member[people_job_id][]", $item2['data'], $member['people_job_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-form__group">
                                                <div class="">
                                                    <input type="text" class="form-control m-input" name="family_member[address][]" placeholder="Nhập địa chỉ cư trú" value="{{$member['address']}}">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-form__group">
                                                <div class="">
                                                    <textarea type="text" class="form-control m-input" name="family_member[before_30041975][]" placeholder="Trước 30/04/1975" rows=4>{{$member['before_30041975']}}</textarea>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-form__group">
                                                <div class="">
                                                    <textarea type="text" class="form-control m-input" name="family_member[after_30041975][]" placeholder="Sau 30/04/1975" rows=4>{{$member['after_30041975']}}</textarea>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-form__group">
                                                <div class="">
                                                    <textarea type="text" class="form-control m-input" name="family_member[current][]" placeholder="Hiện nay" rows=4>{{$member['current']}}</textarea>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="min-width:100px;">
                                            <div class="form-group m-form__group">
                                                <select class="form-control m-input" name="family_member[is_dead][]" onLoad="$(this).select2({width:'100%'})">
                                                    <option value="0">Sống</option>
                                                    <option @if( ($member['is_dead']??0) ) selected @endif value="1">Chết</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <button type="button"
                                    class="table-input-add-item ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>{{__('Thêm mối quan hệ')}}</span>
                                    </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                            class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{__('HỦY')}}</span>
                            </span>
                        </button>
                        <button type="button" data-people_id="{{$item['people_id']??''}}"
                            class="submit ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('Chỉnh sửa')}}</span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $( '.datepicker' ).datepicker({
            format: "dd/mm/yyyy",
            viewMode: "years"
        });
    </script>
</div>