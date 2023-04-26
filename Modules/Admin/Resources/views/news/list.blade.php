<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">Tiêu đề VI</th>
            <th class="tr_thead_list">Tiêu đề EN</th>
            <th class="tr_thead_list">Nội dung VI</th>
            <th class="tr_thead_list">Nội dung EN</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>{{$item['title_vi']}}</td>
                    <td>{{$item['title_en']}}</td>
                    <td>{{$item['description_vi']}}</td>
                    <td>{{$item['description_en']}}</td>
                    <td>
{{--                        @if(in_array('admin.branch.change-status',session('routeList')))--}}
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onchange="index.changeStatus('{{$item['new_id']}}', this)"
                                           {{$item['is_actived'] == 1 ? 'checked' : ''}} class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
{{--                        @else--}}
{{--                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">--}}
{{--                                <label style="margin: 0 0 0 10px; padding-top: 4px">--}}
{{--                                    <input type="checkbox"--}}
{{--                                           {{$item['is_actived'] == 1 ? 'checked' : ''}} class="manager-btn" name="">--}}
{{--                                    <span></span>--}}
{{--                                </label>--}}
{{--                            </span>--}}
{{--                        @endif--}}
                    </td>
                    <td>
                        {{--                        @if(in_array('admin.branch.edit',session('routeList')))--}}
                        <a href="{{route('admin.new.edit', $item['new_id'])}}"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>
                        </a>
                        {{--                        @endif--}}
                        {{--                        @if(in_array('admin.branch.delete',session('routeList')))--}}
                        <button onclick="index.remove({{$item['new_id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="Delete">
                            <i class="la la-trash"></i>
                        </button>
                        {{--                        @endif--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
