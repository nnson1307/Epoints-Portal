<table class="table table-bordered">
    <thead>
    <tr>
        <th scope="col">{{__('Tên liên hệ')}}</th>
        <th scope="col">{{__('SĐT liên hệ')}}</th>
        <th scope="col">{{__('Postcode')}}</th>
        <th scope="col">{{__('Địa chỉ')}}</th>
        <th scope="col">{{__('Mặc định')}}</th>
        <th scope="col">{{__('Hành động')}}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($listContact as $key => $value)
        <tr id="contact-{{$value['customer_contact_id']}}">
            <td class="text_middle">{{$value['contact_name']}}</td>
            <td class="text_middle">{{$value['contact_phone']}}</td>
            <td class="text_middle">{{$value['postcode']}}</td>
            <td class="text_middle">{{$value['full_address']}} , {{$value['district_name']}} , {{$value['province_name']}}</td>
            <td class="text_middle">
                <input type="radio" name="address_default" {{$value['address_default'] == 1 ?'checked' :''}}
                onclick="create.default({{$customer_id}},{{$value['customer_contact_id']}})"
                value="{{$value['customer_contact_id']}}">
            </td>
            <td class="text_middle">
                <a onclick="create.edit_contact({{$value['customer_contact_id']}})"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                   title="{{__('Cập nhật')}}">
                    <i class="la la-edit"></i>
                </a>
                <button onclick="create.remove_contact({{$value['customer_contact_id']}}, {{$value['address_default']}})"
                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                        title="{{__('Xoá')}}">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
        <input type="hidden" id="customer_contact_id" value="{{$value['customer_contact_id']}}">
    @endforeach
    </tbody>
</table>
{{ $listContact->links('helpers.paging') }}