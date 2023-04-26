<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN NHÓM NỘI DUNG HỖ TRỢ (VI)')}}</th>
            <th class="ss--font-size-th">{{__('TÊN NHÓM NỘI DUNG HỖ TRỢ (EN)')}}</th>
            <th class="ss--font-size-th">{{__('TÊN NHÓM CHA (VI)')}}</th>
            <th class="ss--font-size-th">{{__('TÊN NHÓM CHA (EN)')}}</th>
            <th class="ss--font-size-th">{{__('VỊ TRÍ HIỂN THỊ')}}</th>
            <th>{{__('TRẠNG THÁI HIỂN THỊ')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && $LIST->count() > 0)
            @foreach($LIST as $key=>$item)
                <tr>
                    @if(isset($page))
                        <td>{{ (($page-1)*10 + $key + 1) }}</td>
                    @else
                        <td>{{ ($key + 1) }}</td>
                    @endif
                    <td>
                        <a class="m-link" style="color:#464646;" href="{{ route('admin.faq-group.show', ['id' => $item['faq_group_id']]) }}"
                           title="{{ $item['faq_group_title_vi'] }}">
                            {{subString($item['faq_group_title_vi'])}}
                        </a>
                    </td>
                        <td>
                            <a class="m-link" style="color:#464646;" href="{{ route('admin.faq-group.show', ['id' => $item['faq_group_id']]) }}"
                               title="{{ $item['faq_group_title_en'] }}">
                                {{subString($item['faq_group_title_en'])}}
                            </a>
                        </td>
                    <td>
                        <p title="{{ $item['faq_group_parent_title_vi'] }}">
                            {{ subString($item['faq_group_parent_title_vi']) }}
                        </p>
                    </td>
                    <td>
                        <p title="{{ $item['faq_group_parent_title_en'] }}">
                            {{ subString($item['faq_group_parent_title_en']) }}
                        </p>
                    </td>
                    <td>
                        <p title="{{ $item['faq_group_position'] }}">
                            {{ subString($item['faq_group_position']) }}
                        </p>
                    </td>
                    <td>
                        @if(in_array('admin.faq-group.edit',session('routeList')))
                             <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               onchange="faqGroup.updateStatus('{{$item['faq_group_id']}}',this)"
                                               {{ ($item['is_actived'] == 1) ? 'checked' : '' }} class="manager-btn">
                                        <span></span>
                                    </label>
                             </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               disabled
                                               {{ ($item['is_actived'] == 1) ? 'checked' : '' }} class="manager-btn">
                                        <span></span>
                                    </label>
                             </span>
                        @endif
                    </td>
                    <td >
                        @if(in_array('admin.faq-group.edit',session('routeList')))
                            <a href="{{route('admin.faq-group.edit',$item['faq_group_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="Cập nhật"><i class="la la-edit"></i></a>
                        @endif
                        @if(in_array('admin.faq-group.destroy',session('routeList')))
                            <button onclick="faqGroup.remove('{{ $item['faq_group_id'] }}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Xóa"><i class="la la-trash"></i></button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-center">
                    {{__('Không có dữ liệu')}}
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
