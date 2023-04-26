@if(count($listDocument) != 0)
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <th>#</th>
        <th>{{ __('managerwork::managerwork.action') }}</th>
        <th>{{ __('managerwork::managerwork.name') }}</th>
        <th>File</th>
{{--        <th>{{ __('managerwork::managerwork.note') }}</th>--}}
        <th>{{ __('managerwork::managerwork.staff_created') }}</th>
        <th>{{ __('managerwork::managerwork.updated_at') }}</th>
        </thead>
        <tbody class="">
        @foreach($listDocument as $key => $item)
            <tr>
                <td>{{($listDocument->currentPage() - 1)*$listDocument->perPage() + $key+1 }}</td>
                <td>
                    @if(\Illuminate\Support\Facades\Session::has('is_staff_work_project') == false || \Illuminate\Support\Facades\Session::get('is_staff_work_project') == 1)
                        <a href="{{$item['path']}}" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"> <i class="fa fa-download" aria-hidden="true"></i></a>
{{--                    @if(strpos($item['path'],'file-systems') == true)--}}
{{--                        <a href="javascript:void(0)" onclick="Image.changeFolder({{$item['manage_document_file_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="{{ __('managerwork::managerwork.update') }}"><i class="fa fa-file-export"></i></a>--}}
{{--                    @endif--}}
{{--                        <a href="javascript:void(0)" onclick="Document.showPopup({{$item['manage_document_file_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="{{ __('managerwork::managerwork.update') }}"><i class="la la-edit"></i></a>--}}
                        <button onclick="Document.removeDocument({{$item['manage_document_file_id']}})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="{{ __('managerwork::managerwork.delete_th') }}"><i class="la la-trash"></i></button>
                    @endif
                </td>
                <td>{{$item['file_name']}}</td>
                <td><a href="{{$item['path']}}" target="_blank"> <img width="50px" src="{{$item['file_type'] == 'image' ? $item['path'] : asset('static/backend/images/document.png')}}"></a></td>
{{--                <td>{{$item['note']}}</td>--}}
                <td>{{$item['staff_name']}}</td>
                <td>{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y')}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $listDocument->links('manager-work::managerWork.helpers.paging') }}
@else
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <th>#</th>
        <th>{{ __('managerwork::managerwork.action') }}</th>
        <th>{{ __('managerwork::managerwork.name') }}</th>
        <th>File</th>
        <th>{{ __('managerwork::managerwork.note') }}</th>
        <th>{{ __('managerwork::managerwork.staff_created') }}</th>
        <th>{{ __('managerwork::managerwork.updated_at') }}</th>
        </thead>
        <tbody class="append-list-document">
            <tr>
                <td colspan="7" class="text-center">{{ __('managerwork::managerwork.no_data') }}</td>
            </tr>
        </tbody>
    </table>
@endif
