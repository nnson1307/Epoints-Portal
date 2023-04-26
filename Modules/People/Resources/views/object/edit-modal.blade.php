<div class="modal fade people-object-edit-modal ajax-people-object-edit-form" method="POST" action="{{route('people.object.ajax-edit')}}" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Chình sửa đối tượng')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">


                @isset($filters)
                    @foreach ($filters as $name2 => $item2)
                        <div class="form-group m-form__group align-items-center">
                            <label class="black_title">
                                @lang('Nhóm đối tượng'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                @if(isset($item2['text']))
                                    <div class="input-group-append">
                                    <span class="input-group-text">
                                        {{ $item2['text'] }}
                                    </span>
                                    </div>
                                @endif
                                {!! Form::select($name2, $item2['data'], $item['people_object_group_id'] ?? null, ['class' => 'form-control m-input','title'=>'Chọn trạng thái']) !!}
                            </div>
                        </div>
                    @endforeach
                @endisset

                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Tên đối tượng'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" class="form-control m-input" name="name" value="{{$item['name']}}"
                           placeholder="@lang('Nhập tên đối tượng')">
                </div>

                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Mã đối tượng'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" class="form-control m-input" name="code" value="{{$item['code']}}"
                           placeholder="@lang('Nhập mã đối tượng')">
                </div>

                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label class="black_title">
                        @lang('Trạng thái'):
                    </label>
                    <label class="ss--switch form-control" style="border:none">
                        <input type="checkbox"
                               @if($item['is_active']) checked @endif class="manager-btn" name="is_active">
                        <span></span>
                    </label>
                </span>

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
                        <button type="button" data-people_object_id="{{$item['people_object_id']}}"
                            class="ajax-people-object-edit-submit ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('[name="people_object_group_id"]').select2({width:'100%'});
    </script>
</div>