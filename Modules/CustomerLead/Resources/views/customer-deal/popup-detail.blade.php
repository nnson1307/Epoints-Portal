<div class="modal fade show" id="modal-detail" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> @lang('CHI TIẾT DEAL')
                </h5>
            </div>
            <div class="modal-body">
                
                <div class="row mb-3" style="border-bottom: 1px solid #E6E6E6;">
                    <div class="col-1">
                        <img src="{{$item['avatar']}}"
                             onerror="this.onerror=null;this.src='{{asset('static/backend/images/default-placeholder.png')}}';"
                             class="m--img-rounded m--marginless" alt="photo" width="50px" height="50px">
                    </div>
                    <div class="col-5">
                        <h4>{{$item['deal_name']}}</h4>
                        <h5>{{$item['amount']?$item['amount']:0}} @lang('đ')</h5>
                        <p><i class="la la-tags"></i> {{$item['tag_name']}}</p>
                    </div>
                    <div class="col-6">
                        <h5>@lang('Liên hệ')</h5>
                        <p>{{$item['full_address']}}</p>
                    </div>
                </div>

                <div class="row mb-3" style="border-bottom: 1px solid #E6E6E6;">
                    <h6 class="mb-3">@lang('Trạng thái deal')</h6>
                    <?php $i = 0; ?>
                    @foreach($listJourney as $v)
                        @if($v['journey_code'] == $item['journey_code'])
                            @break;
                        @else
                            <?php $i++; ?>
                        @endif
                    @endforeach
                    <ol class="stepBar step{{count($listJourney)}}">
                        @foreach($listJourney as $key => $value)
                            <li class="step {{$key <= $i ? 'current': ''}}" >
                                {{$value['journey_name']}}
                            </li>
                        @endforeach
                    </ol>
                    <!-- -->
                </div>
                <div class="row mb-3" style="border-bottom: 1px solid #E6E6E6;">

                    <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--right m-tabs-line-danger fix-tab-inventory"
                        role="tablist">
                        <li class="nav-item m-tabs__item">
                            <a onclick="view.openTab(1);" class="nav-link active show" data-toggle="tab" href="#infor-detail" role="tab">
                                <span>{{__('THÔNG TIN CHI TIẾT')}}</span>
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a onclick="view.openTab(2);" class="nav-link"
                               data-toggle="tab" href="#recent-activity" role="tab">
                                {{__('HOẠT ĐỘNG GẦN ĐÂY')}}
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a onclick="view.openTab(3);"class="nav-link"
                               data-toggle="tab" href="#info-customer" role="tab">
                                {{__('THÔNG TIN KHÁCH HÀNG')}}
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a onclick="view.openTab(4);"class="nav-link"
                               data-toggle="tab" href="#support-customer" role="tab">
                                {{__('CHĂM SÓC KHÁCH HÀNG')}}
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a onclick="view.openTab(5);"class="nav-link"
                               data-toggle="tab" href="#history-customer" role="tab">
                                {{__('LỊCH SỬ CHĂM SÓC')}}
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a onclick="view.openTab(6);"class="nav-link"
                               data-toggle="tab" href="#comment-customer" role="tab">
                                {{__('BÌNH LUẬN')}}
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-pane " id="infor-detail" role="tabpanel">
                    <div class="row" style="border-bottom: 1px solid #E6E6E6;">
                        <div class="col-md-6 col-xs-12">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <th scope="row">@lang('Người sở hữu')</th>
                                    <td>{{$item['owner_name']}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Sản phẩm')</th>
                                    <td>
                                        @if(isset($listObject) && count($listObject) > 0)
                                            @foreach($listObject as $key => $value)
                                                {{$value['object_name']}}
                                                @if($key != count($listObject) - 1)
                                                    ,
                                                @endif
                                            @endforeach
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Pipeline')</th>
                                    <td>{{$item['pipeline_name']}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Ngày kết thúc dự kiến')</th>
                                    <td>{{\Carbon\Carbon::parse($item['closing_date'])->format('d/m/Y')}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Ngày kết thúc thực tế')</th>
                                    <td>
                                        @if(isset($item['closing_due_date']) && $item['closing_due_date'] != null)
                                            {{\Carbon\Carbon::parse($item['closing_due_date'])->format('d/m/Y')}}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Lý do thất bại')</th>
                                    <td>{{$item['reason_lose_code']}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <th scope="row">@lang('Chi nhánh')</th>
                                    <td>{{$item['branch_name']}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Mã deal')</th>
                                    <td>{{$item['deal_code']}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Nguồn đơn hàng')</th>
                                    <td>{{$item['order_source_name']}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Xác suất')</th>
                                    <td>{{$item['probability']}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Chi tiết deal')</th>
                                    <td>{{$item['deal_description']}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Ngày tạo')</th>
                                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Ngày sửa gần nhất')</th>
                                    <td>{{\Carbon\Carbon::parse($item['updated_at'])->format('d/m/Y H:i')}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" hidden id="recent-activity" role="tabpanel">
                    <div class="row mb-3" style="border-bottom: 1px solid #E6E6E6;">
                        <div class="col-6">

                        </div>
                        <div class="col-6">

                        </div>
                    </div>
                </div>
                <div class="tab-pane" hidden id="info-customer" role="tabpanel">
                    <div class="row">
                        <div class="col-md-8 row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                    <tr>
                                        <th scope="row">@lang('Hình ảnh')</th>
                                        <td>
                                            <div id="div-image">
                                                <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                                     src="{{$infor['avatar'] != '' ? $infor['avatar'] :'https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947'}}"
                                                     alt="{{__('Hình ảnh')}}"/>
                                            </div>
                                            <span class="kt-avatar__cancel" data-toggle="kt-tooltip" title=""
                                                  data-original-title="Cancel avatar">
                                                    </span>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th scope="row">@lang('Loại khách hàng')</th>
                                        @if($item['type_customer'] == 'lead')
                                            @if($infor['customer_type'] == 'business')
                                                <td>@lang("Doanh nghiệp")</td>
                                            @else
                                                <td>@lang("Cá nhân")</td>
                                            @endif
                                        @else
                                            <td>{{$infor['group_name']}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        @if($item['type_customer'] == 'lead')
                                            <th scope="row">@lang('Tên lead')</th>
                                        @else
                                            <th scope="row">@lang('Tên khách hàng')</th>
                                        @endif
                                        <td>{{isset($infor['full_name']) != '' ? $infor['full_name'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">@lang('Mã số thuế')</th>
                                        <td>{{isset($infor['tax_code']) != '' ? $infor['tax_code'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">@lang('Nguồn khách hàng')</th>
                                        <td>{{isset($infor['customer_source_name']) != '' ? $infor['customer_source_name'] : ''}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                    @if($item['type_customer'] == 'lead' && $infor['customer_type'] == 'business')
                                        <tr>
                                            <th scope="row">@lang('Người đại diện')</th>
                                            <td>{{isset($infor['representative']) != '' ? $infor['representative'] : ''}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th scope="row">@lang('Giới tính')</th>
                                        @if($infor['gender'] == 'male')
                                            <td>@lang("Nam")</td>
                                        @elseif($infor['gender'] == 'female')
                                            <td>@lang("Nữ")</td>
                                        @endif
                                    </tr>
                                    @if($item['type_customer'] == 'lead')
                                        <tr>
                                            <th scope="row">@lang('Tag')</th>
                                            <td>{{isset($infor['tag_name']) != '' ? $infor['tag_name'] : ''}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">@lang('Pipeline')</th>
                                            <td>{{isset($infor['pipeline_name']) != '' ? $infor['pipeline_name'] : ''}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">@lang('Hành trình')</th>
                                            <td>{{isset($infor['journey_name']) != '' ? $infor['journey_name'] : ''}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">@lang('Đầu mối doanh nghiệp')</th>
                                            <td>{{isset($infor['business_clue']) != '' ? $infor['business_clue'] : ''}}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th scope="row">@lang('Số điện thoại')</th>
                                        <td>{{isset($infor['phone']) != '' ? $infor['phone'] : ''}}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">@lang('Email')</th>
                                        <td>{{isset($infor['email']) != '' ? $infor['email'] : ''}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-borderless">
                                <tbody>
                                <tr>
                                    <th scope="row">@lang('Địa chỉ')</th>
                                    <td>{{isset($infor['address']) != '' ? $infor['address'] : ''}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Fanpage')</th>
                                    <td>{{isset($infor['fanpage']) != '' ? $infor['fanpage'] : ''}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">@lang('Zalo')</th>
                                    <td>{{isset($infor['zalo']) != '' ? $infor['zalo'] : ''}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" hidden id="support-customer" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <form id="form-search-support" autocomplete="off">
                                <div class="padding_row row">
                                    <div class="col-lg-3">
                                        <input type="text" name="manage_work_title" class="form-control" placeholder="Nhập tiêu đề công việc">
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control selectForm" name="manage_type_work_id" >
                                            <option value="">Loại công việc</option>
                                            @foreach($listTypeWork as $itemSelect)
                                                <option value="{{$itemSelect['manage_type_work_id']}}">{{$itemSelect['manage_type_work_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control selectForm" name="manage_status_id" >
                                            <option value="">Trạng thái</option>
                                            @foreach($listStatusWork as $itemSelect)
                                                @if(!in_array($itemSelect['manage_status_id'],[6,7]))
                                                    <option value="{{$itemSelect['manage_status_id']}}">{{$itemSelect['manage_status_name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control selectForm" name="processor_id" >
                                            <option value="">Người thực hiện</option>
                                            @foreach($liststaff as $itemSelect)
                                                <option value="{{$itemSelect['staff_id']}}">{{$itemSelect['full_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   
                                </div>
                                <div class="padding_row row">
                                    <div class="col-lg-3">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" class="form-control searchDateForm" name="date_end" placeholder="Ngày hết hạn">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <button type="button" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md" onclick="Work.removeSearchWork()">
                                                        <span class="ss--text-btn-mobi">
                                                            <span>Xoá</span>
                                                        </span>
                                        </button>
                                        <button type="button" onclick="Work.search()" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                            TÌM KIẾM
                                            <i class="fa fa-search ss--icon-search" style="vertical-align:initial"></i>
                                        </button>
                                    </div>   
                                </div>
                                <input type="hidden" name="type_search" value="support">
                                <input type="hidden" name="customer_id" value="{{$item['deal_id']}}">
                                <input type="hidden" name="manage_work_customer_type" value="deal">
                                <input type="hidden" name="page" id="page_support" value="1">
                                <input type="hidden" name="deal_id" id="deal_id" value="{{$item['deal_id']}}">
                            </form>
                        </div>
                        <div class="col-12 list-table-work">
                            @include('customer-lead::append.append-list-work-child')
                        </div>
                    </div>
                </div>
                <div class="tab-pane" hidden id="history-customer" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <form id="form-search-history" autocomplete="off">
                                <div class="padding_row row">
                                    <div class="col-lg-3">
                                        <input type="text" name="manage_work_title" class="form-control" placeholder="Nhập tiêu đề công việc">
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control selectForm" name="manage_type_work_id" >
                                            <option value="">Loại công việc</option>
                                            @foreach($listTypeWork as $itemSelect)
                                                <option value="{{$itemSelect['manage_type_work_id']}}">{{$itemSelect['manage_type_work_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control selectForm" name="manage_status_id" >
                                            <option value="">Trạng thái</option>
                                            @foreach($listStatusWork as $itemSelect)
                                                @if(in_array($itemSelect['manage_status_id'],[6,7]))
                                                    <option value="{{$itemSelect['manage_status_id']}}">{{$itemSelect['manage_status_name']}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <select class="form-control selectForm" name="processor_id" >
                                            <option value="">Người thực hiện</option>
                                            @foreach($liststaff as $itemSelect)
                                                <option value="{{$itemSelect['staff_id']}}">{{$itemSelect['full_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                   
                                </div>
                                <div class="padding_row row">
                                    <div class="col-lg-3">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input type="text" class="form-control searchDateForm" name="date_end" placeholder="Ngày hết hạn">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md" onclick="Work.removeSearchWorkHistory()">
                                                        <span class="ss--text-btn-mobi">
                                                            <span>Xoá</span>
                                                        </span>
                                        </button>
                                        <button type="button" onclick="Work.searchHistory()" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                            TÌM KIẾM
                                            <i class="fa fa-search ss--icon-search" style="vertical-align:initial"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="type_search" value="history">
                                <input type="hidden" name="customer_id" value="{{$item['deal_id']}}">
                                <input type="hidden" name="manage_work_customer_type" value="deal">
                                <input type="hidden" name="page" id="page_history" value="1">
                            </form>
                        </div>
                        <div class="col-12 list-table-work-history">
                            @include('customer-lead::append.append-list-history-work-child')
                        </div>
                    </div>
                </div>
                <div class="tab-pane" hidden id="comment-customer" role="tabpanel">
                    <div class="row" id='tab-comment'>

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
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function registerSummernote(element, placeholder, max, callbackMax) {
        $('.description').summernote({
            placeholder: '',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname', 'fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
            ],
            callbacks: {
                onImageUpload: function (files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadImgCk(files[i]);
                    }
                },
                onKeydown: function (e) {
                    var t = e.currentTarget.innerText;
                    if (t.length >= max) {
                        //delete key
                        if (e.keyCode != 8)
                            e.preventDefault();
                        // add other keys ...
                    }
                },
                onKeyup: function (e) {
                    var t = e.currentTarget.innerText;
                    if (typeof callbackMax == 'function') {
                        callbackMax(max - t.length);
                    }
                },
                onPaste: function (e) {
                    var t = e.currentTarget.innerText;
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    // var all = t + bufferText;
                    var all = bufferText;
                    document.execCommand('insertText', false, all.trim().substring(0, max - t.length));
                    // document.execCommand('insertText', false, bufferText);
                    if (typeof callbackMax == 'function') {
                        callbackMax(max - t.length);
                    }
                }
            },
        });
    }
</script>