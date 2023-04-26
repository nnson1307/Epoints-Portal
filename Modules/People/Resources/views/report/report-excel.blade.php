<table border="1" style="width:100%">
    <thead>
    <tr>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1">Mẫu số: 1/DS</th>
        <th colspan="1"></th>
        <th colspan="1"></th>
        <th colspan="1"></th>
    </tr>
    <tr>
        <th rowspan="1">Mã hồ sơ</th>
        <th rowspan="1">Số thứ tự</th>
        <th rowspan="1">
            - Họ, đệm, tên khai sinh;<br>
            - Ngày, tháng, năm sinh.
        </th>
        <th rowspan="1">
            - Nghề nghiệp;<br>
            - nơi làm việc.
        </th>
        <th rowspan="1">
            - Địa chỉ thường trú;<br>
            - Địa chỉ tạm trú.
        </th>
        <th rowspan="1">
            - Thành phần gia đình;<br>
            - Thành phần bản thân;<br>
            - Dân tộc, tôn giáo.
        </th>
        <th rowspan="1">
            - Học vấn;<br>
            - CMKT;<br>
            - Ngoại ngữ<br>
            - Đảng.
        </th>
        <th rowspan="1">
            - Họ tên cha, năm sinh, nghề nghiệp;<br>
            - Họ tên mẹ, năm sinh, nghề nghiệp;<br>
            - Họ tên vợ(chồng), năm sinh, nghề nghiệp.
        </th>

        <th rowspan="2">Lý do cụ thể</th>
        <th rowspan="2">Tổ dân phố</th>
        <th rowspan="2">Khu phố</th>

        <th colspan="3">Loại danh sách<br>(đối tượng)</th>
    </tr>
    <tr>
        <th>Họ và tên</th>
        <th>Năm sinh</th>
        <th>Nghề nghiệp</th>

        <th>Họ và tên</th>
        <th>Năm sinh</th>
        <th>Nghề nghiệp</th>
    </tr>
    </thead>
    <tbody>
    @foreach($list??[] as $key => $item)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$item['full_name']??''}}</td>
            <td>
                @if( ($item['people_object_name']??'')==($item['people_object_group_name']??'') )
                    {{$item['people_object_name']??''}}
                    @else
                    {{$item['people_object_group_name']??''}} - {{$item['people_object_name']??''}}
                    @endif

            </td>
            <td>{{ Carbon\Carbon::parse($item['birthday']??'')->format('d/m/Y') }}</td>

            <td>{{$item['code']}}</td>
            <td>{{$item['workplace']}}</td>
            <td>{{$item['educational_level_name']}}</td>

            <td>{{$item['temporary_address']??''}}</td>
            <td>{{$item['permanent_address']}}</td>
            <td>{{$item['ethnic_name']}}</td>
            <td>{{$item['religion_name'] ?? __('Không')}}</td>
            <td>{{$item['info_father'] != null ? $item['info_father']['full_name'] : ''}}</td>
            <td>{{$item['info_father'] != null ? $item['info_father']['birth_year'] : ''}}</td>
            <td>{{$item['info_father'] != null ? $item['info_father']['job_name'] : ''}}</td>

            <td>{{$item['info_mother'] != null ? $item['info_mother']['full_name'] : ''}}</td>
            <td>{{$item['info_mother'] != null ? $item['info_mother']['birth_year'] : ''}}</td>
            <td>{{$item['info_mother'] != null ? $item['info_mother']['job_name'] : ''}}</td>
        </tr>
    @endforeach
    </tbody>
</table>