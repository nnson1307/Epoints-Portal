<?php
use Modules\Admin\Models\CustomerCustomDefineTable;use Modules\Admin\Models\CustomerGroupTable;use Modules\Admin\Models\CustomerTable;use Modules\CustomerLead\Models\CustomerLeadTable;use Modules\CustomerLead\Models\CustomerSourceTable;use Modules\CustomerLead\Models\DistrictTable;use Modules\CustomerLead\Models\JourneyTable;use Modules\CustomerLead\Models\PipelineTable;use Modules\CustomerLead\Models\ProvinceTable;use Modules\CustomerLead\Models\TagTable;
?>


<div class="modal fade show" id="log-update" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-edit"></i> @lang('CHI TIẾT CHỈNH SỬA KHÁCH HÀNG')
                </h5>
            </div>
            <div class="modal-body">
                <div class="table-content m--padding-top-15 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default">
                            <thead class="bg">
                            <tr>
                                <th style="width: 40%" class="tr_thead_list">@lang('Thông tin thay đổi')</th>
                                <th style="width: 30%" class="tr_thead_list">{{__('TRƯỚC CẬP NHẬT')}}</th>
                                <th style="width: 30%" class="tr_thead_list">{{__('SAU CẬP NHẬT')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($item) && count($item) > 0)
                                @foreach($item as $key => $value)
                                    @switch($value['key'])
                                        @case('customer_group_id')
                                        <?php
                                        $mCustomerGroup = new CustomerGroupTable();
                                        $customerGroupOld = $mCustomerGroup->getItem($value['value_old']);
                                        $customerGroupNew = $mCustomerGroup->getItem($value['value_new']);
                                        ?>
                                        <tr>
                                            <td>@lang('Nhóm khách hàng')</td>
                                            <td>{{isset($customerGroupOld['group_name']) ? $customerGroupOld['group_name'] : ''}}</td>
                                            <td>{{isset($customerGroupNew['group_name']) ? $customerGroupNew['group_name'] : ''}}</td>
                                        </tr>
                                        @break
                                        @case('full_name')
                                        <tr>
                                            <td>@lang('Họ & tên')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('gender')
                                        <tr>
                                            <td>@lang('Giới tính')</td>
                                            <td>
                                                @if($value['value_old'] == 'female')
                                                    {{ __('Nam') }}
                                                @elseif($value['value_old'] == 'male')
                                                    {{__('Nữ')}}
                                                @else
                                                    {{__('Khác')}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($value['value_new'] == 'female')
                                                    {{ __('Nam') }}
                                                @elseif($value['value_new'] == 'male')
                                                    {{__('Nữ')}}
                                                @else
                                                    {{__('Khác')}}
                                                @endif
                                            </td>
                                        </tr>
                                        @break
                                        @case('phone1')
                                        <tr>
                                            <td>@lang('Số điện thoại 1')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('phone2')
                                        <tr>
                                            <td>@lang('Số điện thoại 2')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('email')
                                        <tr>
                                            <td>@lang('Email')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('avatar')
                                        <tr>
                                            <td>@lang('Hình ảnh')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('facebook')
                                        <tr>
                                            <td>@lang('Facebook')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('note')
                                        <tr>
                                            <td>@lang('Ghi chú')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('postcode')
                                        <tr>
                                            <td>@lang('Postcode')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('birthday')
                                        <tr>
                                            <td>@lang('Ngày sinh')</td>
                                            <td>{{$value['value_old'] != '' ? \Carbon\Carbon::createFromFormat('Y-m-d', $value['value_old'])->format('d/m/Y') : ''}}</td>
                                            <td>{{$value['value_new'] != '' ? \Carbon\Carbon::createFromFormat('Y-m-d', $value['value_new'])->format('d/m/Y') : ''}}</td>
                                        </tr>
                                        @break
                                        @case('is_actived')
                                        <tr>
                                            <td>@lang('Trạng thái')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('customer_refer_id')
                                        <?php
                                        $mCustomerGroup = new CustomerTable();
                                        $customerGroupOld = $mCustomerGroup->getItem($value['value_old']);
                                        $customerGroupNew = $mCustomerGroup->getItem($value['value_new']);
                                        ?>
                                        <tr>
                                            <td>@lang('Người giới thiệu')</td>
                                            <td>{{isset($customerGroupOld['full_name']) ? $customerGroupOld['full_name'] : ''}}</td>
                                            <td>{{isset($customerGroupNew['full_name']) ? $customerGroupNew['full_name'] : ''}}</td>
                                        </tr>
                                        @break
                                        @case('customer_type')
                                        <tr>
                                            <td>@lang('Loại khách hàng')</td>
                                            <td>{{$value['value_old'] == 'personal' ? __('Cá nhân') : ($value['value_old'] == 'business' ? __('Doanh nghiệp') : '')}}</td>
                                            <td>{{$value['value_new'] == 'personal' ? __('Cá nhân') : ($value['value_new'] == 'business' ? __('Doanh nghiệp') : '')}}</td>
                                        </tr>
                                        @break
                                        @case('hotline')
                                        <tr>
                                            <td>@lang('Hot line')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('fanpage')
                                        <tr>
                                            <td>@lang('Fanpage')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('zalo')
                                        <tr>
                                            <td>@lang('Zalo')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('tax_code')
                                        <tr>
                                            <td>@lang('Mã số thuế')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('representative')
                                        <tr>
                                            <td>@lang('Người đại diện')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('customer_source_id')
                                        <?php
                                        $mCustomerSource = new CustomerSourceTable();
                                        $customerSourceOld = $mCustomerSource->getInfo($value['value_old']);
                                        $customerSourceNew = $mCustomerSource->getInfo($value['value_new']);
                                        ?>
                                        <tr>
                                            <td>@lang('Nguồn khách hàng')</td>
                                            <td>{{isset($customerSourceOld['customer_source_name']) ? $customerSourceOld['customer_source_name'] : ''}}</td>
                                            <td>{{isset($customerSourceNew['customer_source_name']) ? $customerSourceNew['customer_source_name'] : ''}}</td>
                                        </tr>
                                        @break
                                        @case('province_id')
                                        <?php
                                        $mProvince = new ProvinceTable();
                                        $provinceOld = $mProvince->getProvinceById($value['value_old']);
                                        $provinceNew = $mProvince->getProvinceById($value['value_new']);
                                        ?>
                                        <tr>
                                            <td>@lang('Tỉnh thành')</td>
                                            <td>{{isset($provinceOld['name']) ? $provinceOld['name'] : ''}}</td>
                                            <td>{{isset($provinceNew['name']) ? $provinceNew['name'] : ''}}</td>
                                        </tr>
                                        @break
                                        @case('district_id')
                                        <?php
                                        $mDistrict = new DistrictTable();
                                        $districtOld = $mDistrict->getItem(sprintf("%03d", $value['value_old']));
                                        $districtNew = $mDistrict->getItem(sprintf("%03d", $value['value_new']));
                                        ?>
                                        <tr>
                                            <td>@lang('Quận huyện')</td>
                                            <td>{{isset($districtOld['name']) ? $districtOld['name'] : ''}}</td>
                                            <td>{{isset($districtNew['name']) ? $districtNew['name'] : ''}}</td>
                                        </tr>
                                        @break
                                        @case('address')
                                        <tr>
                                            <td>@lang('Địa chỉ')</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_1')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_1');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_2')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_2');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_3')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_3');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_4')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_4');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_5')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_5');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_6')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_6');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_7')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_7');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_8')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_8');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_9')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_9');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('custom_10')
                                        <?php
                                        $mCustomDefine = new CustomerCustomDefineTable();
                                        $customDefine = $mCustomDefine->getItemByKey('custom_10');
                                        ?>
                                        <tr>
                                            <td>{{ $customDefine['title'] }}</td>
                                            <td>{{$value['value_old']}}</td>
                                            <td>{{$value['value_new']}}</td>
                                        </tr>
                                        @break
                                        @case('image_customer')
                                        <?php
                                        $tagOld = json_decode($value['value_old']);
                                        $tagNew = json_decode($value['value_new']);
                                        $lstTagNameOld = $lstTagNameNew = '';
                                        foreach ($tagOld as $kt => $vt) {
                                            $vt = (array)$vt;
                                            $lstTagNameOld .= $vt[array_key_first($vt)] . '<br/>';
                                        }
                                        foreach ($tagNew as $kt => $vt) {
                                            $vt = (array)$vt;
                                            $lstTagNameNew .= $vt[array_key_first($vt)] . '<br/>';

                                        }
                                        ?>
                                        <tr>
                                            <td>{{ __('Ảnh kèo theo') }}</td>
                                            <td>{!! $lstTagNameOld !!}</td>
                                            <td>{!! $lstTagNameNew !!}</td>
                                        </tr>
                                        @break
                                        @case('file_customer')
                                        <?php
                                        $tagOld = json_decode($value['value_old']);
                                        $tagNew = json_decode($value['value_new']);
                                        $lstTagNameOld = $lstTagNameNew = '';
                                        foreach ($tagOld as $kt => $vt) {
                                            $vt = (array)$vt;
                                            $lstTagNameOld .= $vt[array_key_first($vt)] . '<br/>';
                                        }
                                        foreach ($tagNew as $kt => $vt) {
                                            $vt = (array)$vt;
                                            $lstTagNameNew .= $vt[array_key_first($vt)] . '<br/>';

                                        }
                                        ?>
                                        <tr>
                                            <td>{{ __('File kèm theo') }}</td>
                                            <td>{!! $lstTagNameOld !!}</td>
                                            <td>{!! $lstTagNameNew !!}</td>
                                        </tr>
                                        @break
                                    @endswitch
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
