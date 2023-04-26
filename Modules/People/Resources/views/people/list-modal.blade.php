<div class="modal fade ajax people-list-modal" role="dialog">
    <style>
        .people-detail-modal .info .form-group{
            margin: 0;
            padding: 10px 0px;
            border-bottom: dashed 1px lightgray;
        }
        .people-list-modal table>thead>tr>th:nth-child(7),
        .people-list-modal table>tbody>tr>td:nth-child(7){
            display:none;
        }
    </style>
    <div class="modal-dialog modal-dialog-centered hu-modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Danh sách công dân')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="d-none ajax-people-list-form" method="POST" action="{{route('people.people.ajax-list')}}">
                    <input type="hidden" name="people_verification_year" value="{{ $param['people_verification_year']??'' }}">
                    <input type="hidden" name="people_object_group_id" value="{{ $param['people_object_group_id']??'' }}">
                    <input type="hidden" name="people_object_id" value="{{ $param['people_object_id']??'' }}">
                </div>
                <div class="table-content people-table">
                    @include('People::people.table')
                </div><!-- end table-content -->
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{__('HỦY')}}</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>