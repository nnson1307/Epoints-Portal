
    <div class="m-portlet m-portlet--head-sm tab_work_detail pb-5">
        <nav class="nav">
            <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('comment')">{{ __('managerwork::managerwork.comment') }}</a>
            <a class="hover-cursor nav-link active" onclick="ChangeTab.tabComment('document')">{{ __('managerwork::managerwork.document') }}</a>
            <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('remind')">{{ __('managerwork::managerwork.remind') }}</a>

        @if($detail['parent_id'] == null)
                <a class="hover-cursor nav-link " onclick="ChangeTab.tabComment('sub_task')">{{ __('managerwork::managerwork.child_task') }}</a>
            @endif
            <a class="hover-cursor nav-link" onclick="ChangeTab.tabComment('history')">{{ __('managerwork::managerwork.history') }}</a>
        </nav>
{{--        @if(count($listDocument) != 0)--}}
            <div class="col-12 mt-3 ml-2 text-right">
                <button type="button" style="border-radius:20px" onclick="Document.showPopup()" class=" ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">
                    <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.add_document') }}
                </button>
            </div>
            <div class="col-12 mt-3 ml-2 append-list-document">

            </div>
{{--        @else--}}
{{--            <div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">--}}
{{--                <div class="h-50">--}}
{{--                    <div class="d-flex align-items-center text-center justify-content-center" style="height: 300px" >--}}
{{--                        <div>--}}
{{--                            <h5 class="d-block">{{ __('managerwork::managerwork.no_document') }}</h5>--}}
{{--                            <button type="button" style="border-radius:20px" onclick="Document.showPopup()" class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10  m--margin-bottom-5">--}}
{{--                                <i class="fas fa-plus-circle"></i> {{ __('managerwork::managerwork.add_document') }}--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
    </div>
    <form id="form-file" autocomplete="off">
        <div id="block_append"></div>
        <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">
    </form>

    <script type="text/template" id="imageShow">
        <div class="image-show col-3">
            <img class="img-fluid" src="{link}">
            <p class="name_file">{file_name}</p>
            <input type="hidden" class="path" value="{link}">
            <input type="hidden" class="file_name" value="{file_name}">
            <input type="hidden" class="file_type" value="image">
            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage(this)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="imageShowFile">
        <div class="image-show col-3">
            <img src="{{asset('static/backend/images/document.png')}}">
            <p class="name_file">{file_name}</p>
            <input type="hidden" class="file_name" value="{file_name}">
            <input type="hidden" class="file_type" value="file">
            <input type="hidden" class="path" value="{link}">
            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage(this)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>
    <script src="{{asset('static/backend/js/manager-project/managerWork/detail-work-document.js?v='.time())}}"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/detail-work.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            Document.search(1);
        })
    </script>
