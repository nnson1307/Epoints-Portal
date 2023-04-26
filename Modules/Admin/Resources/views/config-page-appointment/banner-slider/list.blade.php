<div class="table-responsive">
    <table class="table table-bordered m-table" id="table_banner">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list text-center" style="width: 250px">{{__('Hình ảnh')}}</th>
            <th class="tr_thead_list">{{__('Link')}}</th>
            <th class="tr_thead_list">{{__('Vị trí')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST_BANNER))
            @foreach ($LIST_BANNER as $key => $item)
                <tr>
                    <td>
                        {{$key+1}}
                    </td>
                    <td class="text-center">
                        @if($item['name']!=null)
                            <img class="m--bg-metal img-sd" id="blah"
                                 src="{{$item['name']}}"
                                 alt="{{__('Hình ảnh')}}" width="180px" height="80px">
                        @endif
                    </td>
                    <td>{{$item['link']}}</td>
                    <td>{{$item['position']}}</td>
                    <td>
                        <a href="javascript:void(0)" onclick="banner.modal_edit('{{$item['id']}}')"
                           class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                            <i class="la la-edit"></i>

                        </a>
                        <button onclick="banner.remove(this, {{$item['id']}})"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                title="Delete">
                            <i class="la la-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>

