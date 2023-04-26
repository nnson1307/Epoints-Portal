<div id="detail" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <p id="now"></p>
                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>
            <form  id="form-detail">
                <div class="modal-body">

                    {{--{!! csrf_field() !!}--}}
                        <table style="text-align: center" class="table table-striped m-table m-table--head-bg-primary table-list" id="table-detail">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Khách hàng')}}</th>
                                <th>{{__('Số điện thoại')}}</th>
                                <th>{{__('Giờ hẹn')}}</th>
                                <th>{{__('Trạng thái')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                </div>

            </form>
        </div>

    </div>
</div>
