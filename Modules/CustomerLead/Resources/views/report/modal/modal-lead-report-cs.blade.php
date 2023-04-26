<div class="modal fade" id="lead-report-cs" role="dialog" style="display: none">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    {{__('DANH SÁCH LEAD')}}
                </h4>
            </div>
            <div class="m-widget4  m-section__content" id="load">
                <div class="modal-body">
                    <div id="autotable-report-cs">
                        <form action="{{route('customer-lead.report.export-excel-popup-lead-report-cs')}}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="time" value="{{isset($FILTER['time']) != '' ? $FILTER['time'] : ''}}">
                            <input type="hidden" name="source_id" value="{{isset($FILTER['source_id']) != '' ? $FILTER['source_id'] : ''}}">
                            <input type="hidden" name="pipeline_code" value="{{isset($FILTER['pipeline_code']) != '' ? $FILTER['pipeline_code'] : ''}}">
                            <input type="hidden" name="journey_code" value="{{isset($FILTER['journey_code']) != '' ? $FILTER['journey_code'] : ''}}">
                            <input type="hidden" name="staff_id" value="{{isset($FILTER['staff_id']) != '' ? $FILTER['staff_id'] : ''}}">
                            <input type="hidden" name="is_convert" value="{{isset($FILTER['is_convert']) != '' ? $FILTER['is_convert'] : ''}}">

                            <button type="submit"
                                    class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10 float-right mb-4">
                                    <span>
                                        <i class="la la-files-o"></i>
                                        <span>{{__('Export excel')}}</span>
                                    </span>
                            </button>
                        </form>
                        <form class="frmFilter bg">
                            <div class="row padding_row form-group" hidden>
                                <input type="hidden" name="time" value="{{isset($FILTER['time']) != '' ? $FILTER['time'] : ''}}">
                                <input type="hidden" name="source_id" value="{{isset($FILTER['source_id']) != '' ? $FILTER['source_id'] : ''}}">
                                <input type="hidden" name="pipeline_code" value="{{isset($FILTER['pipeline_code']) != '' ? $FILTER['pipeline_code'] : ''}}">
                                <input type="hidden" name="journey_code" value="{{isset($FILTER['journey_code']) != '' ? $FILTER['journey_code'] : ''}}">
                                <input type="hidden" name="staff_id" value="{{isset($FILTER['staff_id']) != '' ? $FILTER['staff_id'] : ''}}">
                                <div class="col-lg-2 form-group" hidden>
                                    <button class="btn btn-primary color_button btn-search">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="table-content m--padding-top-15">
                            @include('customer-lead::report.modal.list-customer-lead-report-cs')
                        </div><!-- end table-content -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                               class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>@lang('HỦY')</span>
                                </span>
                                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
