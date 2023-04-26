@if (isset($list))
    <div class="table-responsive">
        <table class="table table-striped m-table ss--header-table ss--nowrap">
            <thead>
            <tr>
                <th colspan="3" class="ss--font-size-th">{{ __('Danh sách nhãn') }}</th>
            </tr>
            <tr id="form-add">
                <td class="ss--font-size-13 float-right">
                    <div class="form-color-input">
                        <span></span>
                        <input type='color' name="color_code" value='#3dd61f' class='bar choose-color'>
                    </div>
                </td>
                <td class="ss--font-size-13" colspan="2">
                    <div class="input-group">
                        <div class="input-group-append w-75">
                            <input type="text" class="form-control" name="tag_name" placeholder="{{__('Nhập tên nhãn mới')}}">
                            <button class="btn btn-primary" type="button" onclick="CustomerTag.add()">
                                <i class="fa fa-plus-circle"></i> @lang('Tạo')
                            </button>
                        </div>
                    </div>
                </td>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <td class="ss--font-size-13 float-right">
                        <div class="form-color-input" data-id="{{ $item->zalo_customer_tag_id }}">
                            <span style="background-color: {{ $item->color_code }}"></span>
                            <input type='color' value='{{ $item->color_code }}' class='bar choose-color'>
                        </div>
                    </td>
                    <td class="ss--font-size-13">{{ $item->tag_name }}</td>
                    <td class="float-right">
                        <a href="javascript:void(0)" onclick="CustomerTag.removeAction({{ $item->zalo_customer_tag_id }})" class="text-black-50" title="{{__("Xóa")}}">
                            <i class="la la-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $list->links('helpers.paging') }}
@endif
