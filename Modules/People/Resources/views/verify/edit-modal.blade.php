<div class="modal fade ajax people-verify-edit-modal ajax-people-verify-edit-form" method="POST" action="{{route('people.verify.ajax-edit')}}" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold text-uppercase">
                    <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                    {{__('Chỉnh sửa phúc tra')}}
                </h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="people_verify_id" value="{{$item['people_verify_id']}}">
                    <input type="hidden" name="people_id" value="{{$item['people_id']}}">
                    <input type="hidden" name="birth_year" value="{{ Carbon\Carbon::parse($item['birthday'])->format('Y') }}">

{{--                    @php $name2='people_verification_id'; $item2 = $options[$name2]; @endphp--}}
{{--                    <div class="col-6 form-group m-form__group align-items-center">--}}
{{--                        <label class="black_title">--}}
{{--                            @lang('Đợt phúc tra'):<b class="text-danger">*</b>--}}
{{--                        </label>--}}
{{--                        <div class="input-group">--}}
{{--                            @if(isset($item2['text']))--}}
{{--                                <div class="input-group-append">--}}
{{--                                    <span class="input-group-text">--}}
{{--                                        {{ $item2['text'] }}--}}
{{--                                    </span>--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                            {!! Form::select($name2, $item2['data'], $item['people_verification_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="col-6 form-group m-form__group align-items-center">
                        <label class="black_title">
                            @lang('Năm phúc tra'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <select class='form-control m-input this-is-select2' name="people_verification_year" disabled>
                                @for($i=$param['birth_year']+27;$i>=$param['birth_year']+18;$i--)
                                    <option  @if($i==( $options['people_verification_id']['data'][ $item['people_verification_id'] ] )) selected @endif  value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="col-6 form-group m-form__group">
                        <label class="black_title">
                            Tuổi:<b class="text-danger"></b>
                        </label>
                        <div class="">
                            <input type="text" class="form-control m-input" name="age" value="{{ $param['age']??'' }}" disabled>
                        </div>
                    </div>

                    @php $name2='people_object_id'; $item2 = $options[$name2]; @endphp
                    <div class="col-12 form-group m-form__group align-items-center">
                        <label class="black_title">
                            @lang('Đối tượng'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            @if(isset($item2['text']))
                                <div class="input-group-append">
                                                <span class="input-group-text">
                                                    {{ $item2['text'] }}
                                                </span>
                                </div>
                            @endif
                            {!! Form::select($name2, $item2['data'], $item['people_object_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                        </div>
                    </div>

                    <div class="col-12 form-group m-form__group">
                        <label class="black_title">
                            Lý do cụ thể:<b class="text-danger"></b>
                        </label>
                        <div class="">
                            <div class="">
                                <textarea rows="2" name="content" class="form-control m-input"  placeholder="Nhập lý do cụ thể">{{$item['content']}}</textarea>
                            </div>
                        </div>
                    </div>

                    @php $name2='people_health_type_id'; $item2 = $options[$name2]; @endphp
                    <div class="col-12 form-group m-form__group align-items-center">
                        <label class="black_title">
                            @lang('Sức khỏe loại'):<b class="text-danger"></b>
                        </label>
                        <div class="input-group">
                            @if(isset($item2['text']))
                                <div class="input-group-append">
                                                <span class="input-group-text">
                                                    {{ $item2['text'] }}
                                                </span>
                                </div>
                            @endif
                            {!! Form::select($name2, $item2['data'], $item['people_health_type_id'] ?? null, ['class' => 'form-control m-input this-is-select2','title'=>'Chọn trạng thái']) !!}
                        </div>
                    </div>

                    <div class="col-12 form-group m-form__group">
                        <label class="black_title">
                            Ghi chú:<b class="text-danger"></b>
                        </label>
                        <div class="">
                            <input type="text" class="form-control m-input"  value="{{$item['note']}}" name="note" placeholder="Nhập ghi chú">
                        </div>
                    </div>

                </div>

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
                        <button type="button" data-people_id="{{$item['people_id']??''}}"
                            class="submit ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
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
</div>