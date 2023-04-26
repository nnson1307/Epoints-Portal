<div class="modal fade ajax people-detail-modal ajax-people-edit-form" method="POST"
     action="{{route('people.people.ajax-edit')}}" role="dialog">
    <style>
        .people-detail-modal .info .form-group {
            margin: 0;
            padding: 10px 0px;
            border-bottom: dashed 1px lightgray;
        }
    </style>
    <div class="modal-dialog modal-dialog-centered hu-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Chi tiết công dân')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="m-form__group form-group col-1">

                        <div class="row form-group m-form__group m-widget19">
                            <div class="m-widget19__pic">
                                <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                     src="{{$item['avatar']??'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                                     alt="Hình ảnh">
                            </div>
                        </div>
                    </div>
                    <div class="col-11 info">
                        <div class="row">

                            <div class="col-6 m-portlet m-portlet--head-sm m-0">
                                <div class="m-portlet__head"
                                     xonclick="$(this).parent().find('.m-portlet__body').toggleClass('d-none')">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                            <h2 class="m-portlet__head-text">@lang('Thông tin cơ bản')</h2>
                                        </div>
                                    </div>
                                    <div class="m-portlet__head-tools">
                                        <i class="xfas xfa-chevron-down"></i>
                                    </div>
                                </div>

                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Tên công dân: </strong>
                                            <span>{{$item['full_name']??''}}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Mã hồ sơ: </strong>
                                            <span>{{$item['code']??''}}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Ngày tháng năm sinh: </strong>
                                            <span>{{Carbon\Carbon::parse($item['birthday']??'')->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Giới tính: </strong>
                                            <span>
                                            @switch($item['gender']??'')
                                                    @case('male')
                                                        Nam
                                                        @break
                                                    @case('female')
                                                        Nữ
                                                        @break
                                                    @default
                                                        Khác
                                                @endswitch
                                        </span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Trình độ văn hóa: </strong>
                                            <span>
                                                @if($item['educational_level_id'])
                                                    {{ $filters['educational_level_id']['data'][$item['educational_level_id']]??'' }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Năm tốt nghiệp: </strong>
                                            <span>{{ $item['graduation_year'] }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Chuyên ngành đào tạo: </strong>
                                            <span>{{ $item['specialized'] }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Ngoại ngữ: </strong>
                                            <span>{{ $item['foreign_language'] }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Nơi làm việc: </strong>
                                            <span>{{ $item['workplace']??'' }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Giấy chứng nhận đăng ký NVQS số:</strong>
                                            <input class="form-control" id="register_nvqs" name="register_nvqs" value="{{$item['register_nvqs']}}">
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Ngày cấp giấy chứng nhận đăng ký:</strong>
                                            <input class="form-control" id="date_register_nvqs" name="date_register_nvqs" 
                                                value="{{$item['date_register_nvqs'] != null ? \Carbon\Carbon::parse($item['date_register_nvqs'])->format('d/m/Y') : null}}">
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Nơi cấp giấy chứng nhận đăng ký: </strong>
                                            <textarea class="form-control" cols="4" id="issuer_register_nvqs" name="issuer_register_nvqs">{{$item['issuer_register_nvqs']}}</textarea>
                                        </div>

                                        <div class="col-12 form-group m-form__group">
                                            <a href="javascript:void(0)" onclick="index.quickUpdateDetail('{{$item['people_id']}}')"
                                               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn-sm">
                                                    <span class="text-uppercase">
                                                        <i class="la la-edit"></i>
                                                        <span class="">Cập nhật nhanh</span>
                                                    </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 m-portlet m-portlet--head-sm m-0">
                                <div class="m-portlet__head"
                                     xonclick="$(this).parent().find('.m-portlet__body').toggleClass('d-none')">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                            <h2 class="m-portlet__head-text">@lang('Đơn vị công tác')</h2>
                                        </div>
                                    </div>
                                    <div class="m-portlet__head-tools">
                                        <i class="fas xfa-chevron-down"></i>
                                    </div>
                                </div>

                                <div class="m-portlet__body">
                                    <div class="row">


                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Địa chỉ tạm trú: </strong>
                                            <span>{{$item['temporary_address']??''}}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Địa chỉ thường trú: </strong>
                                            <span>{{$item['permanent_address']??''}}</span>
                                        </div>

                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Ngày vào đoàn TNCS Hồ Chí Minh: </strong>
                                            <span>
                                                @if($item['union_join_date']??false)
                                                    {{ Carbon\Carbon::parse($item['union_join_date'])->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Ngày vào Đảng CSVN: </strong>
                                            <span>
                                                @if($item['group_join_date']??false)
                                                    {{ Carbon\Carbon::parse($item['group_join_date'])->format('d/m/Y') }}
                                                @endif
                                            </span>
                                        </div>

                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Trường cấp 1: </strong>
                                            <span>{{ $item['elementary_school'] }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Trường cấp 2: </strong>
                                            <span>{{ $item['middle_school'] }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Trường cấp 3: </strong>
                                            <span>{{ $item['high_school'] }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Từ 18-21 tuổi: </strong>
                                            <span>{{ $item['from_18_to_21'] }}</span>
                                        </div>
                                        <div class="col-12 form-group m-form__group">
                                            <strong class="black_title">Từ 21 tuổi đến nay: </strong>
                                            <span>{{ $item['from_21_to_now'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 m-portlet m-portlet--head-sm m-0">
                                <div class="m-portlet__head"
                                     xonclick="$(this).parent().find('.m-portlet__body').toggleClass('d-none')">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="fas fa-home"></i>
                                </span>
                                            <h2 class="m-portlet__head-text">@lang('Thông tin xác nhận')</h2>
                                        </div>
                                    </div>
                                    <div class="m-portlet__head-tools">
                                        <i class="fas xfa-chevron-down"></i>
                                    </div>
                                </div>

                                <div class="m-portlet__body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-12 form-group m-form__group">
                                                    <strong class="black_title">CMND/CCCD: </strong>
                                                    <span>{{ $item['id_number'] }}</span>
                                                </div>
                                                <div class="col-12 form-group m-form__group">
                                                    <strong class="black_title">Ngày cấp: </strong>
                                                    <span>
                                                    @if($item['id_license_date']??false)
                                                            {{ Carbon\Carbon::parse($item['id_license_date'])->format('d/m/Y') }}
                                                        @endif
                                                </span>
                                                </div>
                                                <div class="col-12 form-group m-form__group">
                                                    <strong class="black_title">Nơi cấp: </strong>
                                                    <span>
                                                @if($item['people_id_license_place_id'])
                                                            {{ $filters['people_id_license_place_id']['data'][$item['people_id_license_place_id']] }}
                                                        @endif
                                                </span>
                                                </div>
                                                <div class="col-12 form-group m-form__group">
                                                    <strong class="black_title">Quê quán: </strong>
                                                    <span>
                                                    @if($item['hometown'])
                                                            {{ $item['hometown'] }}
                                                        @endif
                                                </span>
                                                </div>
                                                <div class="col-6 form-group m-form__group">
                                                    <strong class="black_title">Dân tộc: </strong>
                                                    <span>
                                                    @if($item['ethnic_id'])
                                                            {{ $filters['ethnic_id']['data'][$item['ethnic_id']] }}
                                                        @endif
                                                </span>
                                                </div>
                                                <div class="col-6 form-group m-form__group">
                                                    <strong class="black_title">Tôn giáo: </strong>
                                                    <span>
                                                    @if($item['religion_id'])
                                                            {{ $filters['religion_id']['data'][$item['religion_id']] }}
                                                        @else
                                                            Không
                                                        @endif
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="row">
                                                <div class="col-12 form-group m-form__group">
                                                    <strong class="black_title">Nghề nghiệp: </strong>
                                                    <span>
                                                    @if($item['people_job_id'])
                                                            {{ $filters['people_job_id']['data'][$item['people_job_id']] }}
                                                        @endif
                                                </span>
                                                </div>
                                                <div class="col-12 form-group m-form__group">
                                                    <strong class="black_title">Nơi đăng ký khai sinh: </strong>
                                                    <span>
                                                    @if($item['birthplace'])
                                                            {{ $item['birthplace'] }}
                                                        @endif
                                                </span>
                                                </div>
                                                <div class="col-6 form-group m-form__group">
                                                    <strong class="black_title">Khu phố: </strong>
                                                    <span>{{$item['group']??''}}</span>
                                                </div>
                                                <div class="col-6 form-group m-form__group">
                                                    <strong class="black_title">Tổ dân phố: </strong>
                                                    <span>{{$item['quarter']??''}}</span>
                                                </div>
                                                <div class="col-12 form-group m-form__group">
                                                    <strong class="black_title">Thành phần gia đình: </strong>
                                                    <span>
                                                    @if($item['people_family_type_id'])
                                                            {{ $filters['people_family_type_id']['data'][$item['people_family_type_id']] }}
                                                        @endif
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>


                    <div class="m-portlet w-100">

                        <div class="">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active show" data-toggle="tab" href="#"
                                       data-target="#m_tabs_1_1">Danh sách phúc tra</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#m_tabs_1_3">Thông tin gia đình</a>
                                </li>
                            </ul>
                            <div class="tab-content row">
                                <div class="col-12 tab-pane active show" id="m_tabs_1_1" role="tabpanel">

                                    <div class="m-portlet__body p-0">
                                        @if(in_array('people.verify.ajax-add-modal',session('routeList')))
                                            <div class="text-right" style="position: absolute;right: 45px;top: -55px;">
                                                <a href="javascript:void(0)"
                                                   class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                                                    <span class="ajax-people-verify-add-modal ajax submit text-uppercase"
                                                          method="POST"
                                                          action="{{route('people.verify.ajax-add-modal')}}"
                                                          data-people_id="{{$item['people_id']}}" ;
                                                    >
                                                        <i class="fa fa-plus-circle"></i>
                                                        <span class=""> {{__('Thêm phúc tra')}}</span>
                                                    </span>
                                                </a>
                                            </div>
                                        @endif
                                        <div class="people-verify-filters">
                                            @include('People::verify.filters')
                                        </div>
                                        <div class="table-content people-verify-table">
                                            @include('People::verify.table')
                                        </div><!-- end table-content -->
                                    </div>
                                </div>

                                <div class="col-12 tab-pane" id="m_tabs_1_3" role="tabpanel">
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-table ss--header-table ss--nowrap">
                                                <thead>
                                                <tr>
                                                    <th class="ss--font-size-th">Mối quan hệ</th>
                                                    <th>Họ & Tên</th>
                                                    <th>Năm sinh</th>
                                                    <th>Nghề nghiệp</th>
                                                    <th>Địa chỉ cư trú</th>
                                                    <th>Trước 30/04/1975</th>
                                                    <th>Sau 30/04/1975</th>
                                                    <th>Địa chỉ hiện nay</th>
                                                    <th>Sống/Chết</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach( ($item['family_member']??[]) as $member)
                                                    <tr>
                                                        <td>
                                                            {{$filters['people_family_relationship_type_id']['data'][ $member['people_family_relationship_type_id'] ]}}
                                                        </td>
                                                        <td>
                                                            {{$member['full_name']}}
                                                        </td>
                                                        <td>
                                                            {{$member['birth_year']}}
                                                        </td>
                                                        <td>
                                                            {{$member['people_job_id'] != null ? $filters['people_job_id']['data'][ $member['people_job_id'] ] : ''}}
                                                        </td>
                                                        <td>
                                                            {{$member['address']}}
                                                        </td>
                                                        <td>
                                                            {{$member['before_30041975']}}
                                                        </td>
                                                        <td>
                                                            {{$member['after_30041975']}}
                                                        </td>
                                                        <td>
                                                            {{$member['current']}}
                                                        </td>
                                                        <td>
                                                            @if(($member['is_dead']??0)==1)
                                                                Chết
                                                            @else
                                                                Sống
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                        @if(in_array('people.people.print-preview',session('routeList')))
                            <a target="_blank"
                               href="{{route('people.people.print-preview', ['people_id' => $item['people_id'], 'type' => 'citizen'])}}">
                                <button type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">

                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('In Lý Lịch')}}</span>
                                    </span>

                                </button>
                            </a>

                            <a target="_blank"
                               href="{{route('people.people.print-preview', ['people_id' => $item['people_id'], 'type' => 'military'])}}">
                                <button type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">

                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('In Đk NVQS')}}</span>
                                    </span>

                                </button>
                            </a>

                            <a target="_blank"
                               href="{{route('people.people.print-preview', ['people_id' => $item['people_id'], 'type' => 'absent'])}}">
                                <button type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">

                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('In Đk vắng mặt')}}</span>
                                    </span>

                                </button>
                            </a>

                            <a target="_blank"
                               href="{{route('people.people.print-preview', ['people_id' => $item['people_id'], 'type' => 'move'])}}">
                                <button type="button"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">

                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('In di chuyển NVQS')}}</span>
                                    </span>

                                </button>
                            </a>
                        @endif
                        @if(in_array('people.people.ajax-edit-modal',session('routeList')))
                            <button type="button" data-people_id="{{$item['people_id']??''}}"
                                    method="POST"
                                    action="{{route('people.people.ajax-edit-modal')}}"
                                    data-people_id="{{$item['people_id']}}"
                                    class="ajax submit ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('Chỉnh sửa')}}</span>
                                </span>
                            </button>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('[name="people_object_group_id"]').select2();

        $('#date_register_nvqs').datepicker();
    </script>
</div>