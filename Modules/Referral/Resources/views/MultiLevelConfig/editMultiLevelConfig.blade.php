@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .type_of_criteria {
            font-weight: bold;
            font-size: 20px;
        }

        .form-control-label {
            font-weight: bold;
        }
        #week {
            border: 2px solid white;
            box-shadow: 0 0 0 1px #4fc4cb;
            appearance: none;
            border-radius: 50%;
            width: 12px;
            height: 12px;
            background-color: #fff;
            transition: all ease-in 0.2s;
        }

        .stepBar.step3 .step {
            width: 25%;
        }

        #week:checked {
            background-color: #4fc4cb;
        }
    </style>
    <div class="m-portlet" id="autotable">

        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
                    </h3>
                    @if($dataProgram->type == 'cps')
                        <a href="{{route('referral.commissionCondition',['id'=>$referral_program_id])}}">
                            <div class="modal-footer" style="margin-left: 560px">
                                <div class="m-form__actions m--align-right w-100">
                                    <button data-dismiss="modal"
                                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                                    </button>
                                </div>
                            </div>
                        </a>
                    @else
                        <a href="{{route('referral.commissionConditionCPI',['id'=>$referral_program_id])}}">
                            <div class="modal-footer" style="margin-left: 560px">
                                <div class="m-form__actions m--align-right w-100">
                                    <button data-dismiss="modal"
                                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endif

                    <a href="javascript:void(0)"
                       class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit"
                       onclick="saveRate({{$referral_program_id}})">
                                    <span>
                                    <i class="la la-check"></i>
                                    <span>{{__('Lưu')}}</span>
                                    </span>
                    </a>

                </div>
            </div>
        </div>
        <div class="m-wizard m-wizard--5 m-wizard--success m-wizard--step-first" id="m_wizard">
            <div class="m-portlet__padding-x">
            </div>
            <div class="m-wizard__head m-portlet__padding-x">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="m-wizard__nav">
                            <div class="steps_3">
                                <ol class="stepBar step3">
                                    @if($dataProgram->type == 'cps')
                                        <li class="step current">
                                            {{__('Thông tin hoa hồng')}}
                                        </li>
                                        <li class="step current">
                                            {{__('Chọn sản phẩm')}}
                                        </li>
                                        <li class="step current">
                                            {{__('Điều kiện tính')}}
                                        </li>
                                        <li class="step current">
                                            {{__('Cấu hình tỷ lệ Chiết Khấu')}}
                                        </li>
                                    @else
                                        <li class="step current" style="width: 33%">
                                            {{__(' Thông tin hoa hồng')}}
                                        </li>
                                        <li class="step current" style="width: 33%">
                                            {{__('Điều kiện tính')}}
                                        </li>
                                        <li class="step current">
                                            {{__('Cấu hình tỷ lệ Chiết Khấu')}}
                                        </li>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-wizard__form-step m-wizard__form-step--current" id="m_wizard_form_step_2">
                <div class="container">
                    <form method="post" id="multi-level-config">
                        <div class="row">
                            <div class="col-sm">
                            <span style="font-size: 15px;font-weight: bold">Tầng đa cấp cho phép:<b class="text-danger">*</b>
                               </span>
                                <input type="hidden" name="referral_program_id" value="{{$referral_program_id}}">
                                <select onchange="changeLevel()" id="select-change-level" class=" form-control select2" name="level">
                                    @for($i = 1; $i<=20; $i++)
                                        <option @if($i == count($info)) selected @endif value="{{$i}}">{{$i}} cấp</option>
                                    @endfor
                                </select>
                                <div class="m-portlet__body" style="padding:0px">
                                    <div class="table-responsive">
                                        <table class="table table-striped m-table ss--header-table">
                                            <thead>
                                            <tr class="ss--nowrap">
                                                <th class="ss--font-size-th ss--text-center">{{__('Người giới thiệu')}}</th>
                                                <th class="ss--font-size-th ss--text-center">{{__('Chiết khấu nhận')}} <b class="text-danger"> *</b></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @for($i = 1; $i<=20; $i++)
                                                <tr class="config-level config-level-{{$i}} ss--font-size-13 ss--nowrap">
                                                    <td class=" ss--text-center">
                                                        <div class="out-presenter">
                                                            <p class="presenter"> Cấp {{$i}}</p>
                                                        </div>
                                                    </td>
                                                    <td class="ss--text-center">
                                                        <div class="input-group mb-3">
                                                            <input name="input_level[{{$i}}]" value="{{$info[$i]['percent'] ?? 0}}" type="text" class="form-control" placeholder="100">
                                                            <span class="input-group-text" id="basic-addon2">%</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endfor

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm" style="background-color:#FFEFD5">
                                <br>
                                <span style="font-size: 15px;font-weight: bold">Giải thích:</span>
                                <li>Người giới thiệu có thể mời người khác làm dưới cấp của mình tối đa 1 cấp</li>
                                <span style="font-weight: bold">Ví dụ:</span>
                                <div>
                                    <li>User A gửi link giới thiệu cho User B, sau khi đăng kí dưới link User A gửi, User B
                                        sẽ
                                        là cấp 1 của User A.
                                    </li>
                                    <li>User B gửi link giới thiệu cho User C, sau khi đăng kí dưới link User B gửi, User C
                                        sẽ
                                        là cấp 1 của User B và là cấp 2 của User A.
                                    </li>
                                </div>
                                <br>
                                <span style="font-weight: bold">Chế độ tính hoa hồng:</span>
                                <div>
                                    <li>Hoa hồng của người giới thiệu nhận được: Mức hoa hồng nhận được theo % hoa hồng của
                                        người giới thiệu cấp 1 nhận được.
                                    </li>
                                    <li>Ví dụ: C giới thiệu 1 đơn hàng thành công giá 100.000đ và tương ứng với hoa hồng cho
                                        đơn
                                        hàng này là 20.000đ. Lúc này, hoa hồng tính như sau:
                                    </li>
                                </div>
                                <span>
                                <li style="    margin-left: 30px" ;>
                                    <span style="font-weight: bold;text-indent: 10px">
                                        <i>Hoa hồng cho B =  % hoa hồng cấp 1 x 20,000 =</i>
                                    </span>
                                     20.000đ
                                </li>
                                <li style="    margin-left: 30px;">
                                    <span style="font-weight: bold;text-indent: 10px">
                                        <i>Hoa hồng cho A =</i>
                                    </span>
                                     0đ
                                </li>
                            <br>
                        </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('admin::product.modal.excel-image')
@endsection
@section('after_script')
            <script>
                $('#select-change-level').trigger('change');
                $('.select2').select2();
                function changeLevel(){
                    $('.config-level').hide();
                    let level = $('#select-change-level').val();
                    for(let i = 1; i <=level; i++){
                        $('.config-level-'+ i ).show();
                    }
                }

                function saveRate(){
                    $.post(laroute.route('referral.submitEditMultiLevelConfig'), $('#multi-level-config').serialize(), function (res){
                        if(res.error){
                            swal("Lỗi", res.message, "error");
                        } else {
                            swal("Lưu thành công!", "Nhấn OK để tiếp tục!", "success").then(function () {
                                window.location.href = laroute.route("referral.policyCommission")
                            });
                        }
                    }, 'json')
                }
            </script>
@stop

