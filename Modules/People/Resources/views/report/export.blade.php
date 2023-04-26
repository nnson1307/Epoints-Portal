<table border="1" style="width:100%">
    <thead>
    <tr>
        <th colspan="23"><center>
            DANH SÁCH PHÚC TRA @if($people_object_group_name??false)NHÓM ĐỐI TƯỢNG {{mb_strtoupper($people_object_group_name)}}@endif NĂM {{$people_verification_year??''}}
            </center>
        </th>
        <th>Mẫu số: 1/DS</th>
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
            - Nơi làm việc.
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

        <th rowspan="1">Lý do cụ thể</th>
        <th rowspan="1">Tổ dân phố</th>
        <th rowspan="1">Khu phố</th>

        <th colspan="1">Loại danh sách<br>(đối tượng)</th>
    </tr>

    </thead>
    <tbody>
    @foreach($list??[] as $key => $item)
        <tr>
            <td>{{$item['code']}}</td>
            <td>{{$key+1}}</td>
            <td>
                {{$item['full_name']??''}}<br>
                {{ Carbon\Carbon::parse($item['birthday']??'')->format('d/m/Y') }}
            </td>
            <td>
                {!! $item['people_job_name'] != null ? $item['people_job_name'].'<br>' : ''!!}
                {{$item['workplace']}}
            </td>
            <td>
                {{$item['permanent_address']}}<br>
                {{$item['temporary_address']??''}}
            </td>
            <td>
                {!! $item['people_family_type_name'] != null ? $item['people_family_type_name']."<br>" : ''!!}
                {{$item['ethnic_name']}} - {{$item['religion_name'] ?? __('Không')}}
            </td>
            <td>
                {{$item['educational_level_name'] != null ? $item['educational_level_name'] : ''}}
                {{$item['specialized'] != null ? ','.$item['specialized'] : ''}}
                {{$item['foreign_language'] != null ? ','.$item['foreign_language'] : ''}}

                {!! $item['group_join_date'] != null ? '<br>'.Carbon\Carbon::parse($item['group_join_date']??'')->format('d/m/Y') : '' !!}
            </td>
            <td>
                {{$item['info_father']['full_name']??null != null ? $item['info_father']['full_name'] : ''}}
                {{$item['info_father']['birth_year']??null != null ? ', '.$item['info_father']['birth_year'] : ''}}
                {{$item['info_father']['job_name']??null != null ? ', '.$item['info_father']['job_name'] : ''}}

                {!! $item['info_mother']['full_name']??null != null ? '<br>'.$item['info_mother']['full_name'] : '' !!}
                {{$item['info_mother']['birth_year']??null != null ? ', '.$item['info_mother']['birth_year'] : ''}}
                {{$item['info_mother']['job_name']??null != null ? ', '.$item['info_mother']['job_name'] : ''}}

                {!! $item['info_partner']['full_name']??null != null ? '<br>'.$item['info_partner']['full_name'] : ''!!}
                {{$item['info_partner']['birth_year']??null != null ? ', '.$item['info_partner']['birth_year'] : ''}}
                {{$item['info_partner']['job_name']??null != null ? ', '.$item['info_partner']['job_name'] : ''}}
            </td>
            <td>{{$item['content']??''}}</td>
            <td>{{$item['quarter']??''}}</td>
            <td>{{$item['group']??''}}</td>
            <td>
                @if( ($item['people_object_name']??'')==($item['people_object_group_name']??'') )
                    {{$item['people_object_name']??''}}
                    @else
                    {{$item['people_object_group_name']??''}} - {{$item['people_object_name']??''}}
                    @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>