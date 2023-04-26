<div class="form-group table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th colspan="47" class="text-center font-weight-bold">@lang('DANH SÁCH CÔNG DÂN')</th>
        </tr>
        <tr>
            <th class="text-center" rowspan="2">@lang('Mã hồ sơ')</th>
            <th class="text-center" rowspan="2">@lang('Họ và tên')</th>
            <th class="text-center" rowspan="2">@lang('Giới tính')</th>
            <th class="text-center" rowspan="2">@lang('CMND/CCCD')</th>
            <th class="text-center" rowspan="2">@lang('Ngày cấp')</th>
            <th class="text-center" rowspan="2">@lang('Nơi cấp')</th>
            <th class="text-center" rowspan="2">@lang('Ngày')</th>
            <th class="text-center" rowspan="2">@lang('Tháng')</th>
            <th class="text-center" rowspan="2">@lang('Năm sinh')</th>
            <th class="text-center" rowspan="2">@lang('Địa chỉ thường trú')</th>
            <th class="text-center" rowspan="2">@lang('Địa chỉ tạm trú')</th>
            <th class="text-center" rowspan="2">@lang('Đăng ký khai sinh')</th>
            <th class="text-center" rowspan="2">@lang('Quê quán/Nguyên quán')</th>
            <th class="text-center" rowspan="2">@lang('Khu phố')</th>
            <th class="text-center" rowspan="2">@lang('Tổ dân phố')</th>
            <th class="text-center" rowspan="2">@lang('Dân tộc')</th>
            <th class="text-center" rowspan="2">@lang('Tôn giáo')</th>
            <th class="text-center" rowspan="2">@lang('Thành phần gia đình')</th>
            <th class="text-center" rowspan="2">@lang('Văn hóa')</th>

            <th class="text-center" rowspan="2">@lang('Năm tốt nghiệp')</th>
            <th class="text-center" rowspan="2">@lang('Chuyên ngành đào tạo')</th>
            <th class="text-center" rowspan="2">@lang('Ngoại ngữ')</th>
            <th class="text-center" rowspan="2">@lang('Ngày vào Đoàn TNCS HCM')</th>
            <th class="text-center" rowspan="2">@lang('Ngày vào Đảng CSVN')</th>

            <th class="text-center" rowspan="2">@lang('Nghề nghiệp')</th>
            <th class="text-center" colspan="5">@lang('Đơn vị công tác')</th>
            <th class="text-center" colspan="6">@lang('Thông tin cha')</th>
            <th class="text-center" colspan="6">@lang('Thông tin mẹ')</th>
            <th class="text-center" colspan="6">@lang('Thông tin anh chị em')</th>
            <th class="text-center" colspan="3">@lang('Thông tin vợ (chồng)')</th>
            <th class="text-center" colspan="2">@lang('Thông tin con')</th>
            <th class="text-center" rowspan="2">@lang('Lỗi')</th>
        </tr>
        <tr>
            <th class="text-center">@lang('Trường cấp 1')</th>
            <th class="text-center">@lang('Trường cấp 2')</th>
            <th class="text-center">@lang('Trường cấp 3')</th>
            <th class="text-center">@lang('Từ 18 - 21 tuổi')</th>
            <th class="text-center">@lang('Từ 21 tuổi đến nay')</th>
            <th class="text-center">@lang('Họ và tên')</th>
            <th class="text-center">@lang('Năm sinh')</th>
            <th class="text-center">@lang('Nghề nghiệp')</th>
            <th class="text-center">@lang('Trước 30/04/1975')</th>
            <th class="text-center">@lang('Sau 30/04/1975')</th>
            <th class="text-center">@lang('Hiện nay')</th>
            <th class="text-center">@lang('Họ và tên')</th>
            <th class="text-center">@lang('Năm sinh')</th>
            <th class="text-center">@lang('Nghề nghiệp')</th>
            <th class="text-center">@lang('Trước 30/04/1975')</th>
            <th class="text-center">@lang('Sau 30/04/1975')</th>
            <th class="text-center">@lang('Hiện nay')</th>
            <th class="text-center">@lang('Anh chị em thứ 2')</th>
            <th class="text-center">@lang('Anh chị em thứ 3')</th>
            <th class="text-center">@lang('Anh chị em thứ 4')</th>
            <th class="text-center">@lang('Anh chị em thứ 5')</th>
            <th class="text-center">@lang('Anh chị em thứ 6')</th>
            <th class="text-center">@lang('Anh chị em thứ 7')</th>
            <th class="text-center">@lang('Họ và tên')</th>
            <th class="text-center">@lang('Năm sinh')</th>
            <th class="text-center">@lang('Nghề nghiệp')</th>
            <th class="text-center">@lang('Con thứ nhất')</th>
            <th class="text-center">@lang('Con thứ hai')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>{{$item['code']??''}}</td>
                    <td>{{$item['full_name']??''}}</td>
                    <td>{{$item['gender']??''}}</td>
                    <td>{{$item['id_number']??''}}</td>
                    <td>{{$item['id_license_date']??''}}</td>
                    <td>{{$item['people_id_license_place']??''}}</td>
                    <td>{{$item['birth_day']??''}}</td>
                    <td>{{$item['birth_month']??''}}</td>
                    <td>{{$item['birth_year']??''}}</td>
                    <td>{{$item['permanent_address']??''}}</td>
                    <td>{{$item['temporary_address']??''}}</td>
                    <td>{{$item['birthplace']??''}}</td>
                    <td>{{$item['hometown']??''}}</td>
                    <td>{{$item['people_group']??''}}</td>
                    <td>{{$item['people_quarter']??''}}</td>
                    <td>{{$item['ethnic']??''}}</td>
                    <td>{{$item['religion']??''}}</td>
                    <td>{{$item['people_family']??''}}</td>
                    <td>{{$item['educational_level']??''}}</td>

                    <td>{{$item['graduation_year']??''}}</td>
                    <td>{{$item['specialized']??''}}</td>
                    <td>{{$item['foreign_language']??''}}</td>
                    <td>{{$item['union_join_date']??''}}</td>
                    <td>{{$item['group_join_date']??''}}</td>

                    <td>{{$item['people_job']??''}}</td>
                    <td>{{$item['elementary_school']??''}}</td>
                    <td>{{$item['middle_school']??''}}</td>
                    <td>{{$item['high_school']??''}}</td>
                    <td>{{$item['from_18_to_21']??''}}</td>
                    <td>{{$item['from_21_to_now']??''}}</td>
                    <td>{{$item['full_name_dad']??''}}</td>
                    <td>{{$item['birth_year_dad']??''}}</td>
                    <td>{{$item['job_dad']??''}}</td>
                    <td>{{$item['before_30_04_dad']??''}}</td>
                    <td>{{$item['after_30_04_dad']??''}}</td>
                    <td>{{$item['current_dad']??''}}</td>
                    <td>{{$item['full_name_mom']??''}}</td>
                    <td>{{$item['birth_year_mom']??''}}</td>
                    <td>{{$item['job_mom']??''}}</td>
                    <td>{{$item['before_30_04_mom']??''}}</td>
                    <td>{{$item['after_30_04_mom']??''}}</td>
                    <td>{{$item['current_mom']??''}}</td>
                    <td>{{$item['info_brother_1']??''}}</td>
                    <td>{{$item['info_brother_2']??''}}</td>
                    <td>{{$item['info_brother_3']??''}}</td>
                    <td>{{$item['info_brother_4']??''}}</td>
                    <td>{{$item['info_brother_5']??''}}</td>
                    <td>{{$item['info_brother_6']??''}}</td>
                    <td>{{$item['full_name_couple']??''}}</td>
                    <td>{{$item['birth_year_couple']??''}}</td>
                    <td>{{$item['job_couple']??''}}</td>
                    <td>{{$item['info_child_1']??''}}</td>
                    <td>{{$item['info_child_2']??''}}</td>
                    <td>{{$item['error']??''}}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
