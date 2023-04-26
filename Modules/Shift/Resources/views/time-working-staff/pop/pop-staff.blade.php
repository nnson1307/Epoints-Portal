<div class="modal fade" id="modal-add-staff" role="dialog" style="z-index: 100;">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM NHÂN VIÊN VÀO CA')}}
                </h5>
            </div>

            <div class="modal-body">
                <h6>@lang('Ca làm việc'): {{$infoShift['shift_name']}}</h6>
                <h6>@lang('Thời gian'): {{\Carbon\Carbon::parse($start_time)->format('d/m/Y'). ' - ' .\Carbon\Carbon::parse($end_time)->format('d/m/Y')}}</h6>

                <div id="autotable-staff-pop">
                    <div class="padding_row bg">
                        <form class="frmFilter">
                            @if (isset($staff_have_schedule) && count($staff_have_schedule) > 0)
                                @foreach($staff_have_schedule as $v)
                                    <input type="hidden" name="staff_have_schedule[]" value="{{$v}}">
                                @endforeach
                            @endif
                            <input type="hidden" name="shift_id" value="{{$shift_id}}">

                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 3 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-3 form-group input-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach

                                <div class="col-lg-2 form-group">
                                    <button class="btn btn-primary color_button btn-search" style="display: block">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-content m--margin-top-30">
                        @include('shift::time-working-staff.pop.list-staff')
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    <button type="button"
                            onclick="index.submitAddStaff('{{$shift_id}}', '{{$start_time}}', '{{$end_time}}')"
                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỌN')}}</span>
							</span>
                    </button>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(".m_selectpicker").select2({
        width : '100%'
    });
</script>

