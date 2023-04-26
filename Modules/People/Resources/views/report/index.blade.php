@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ LÝ LỊCH CÔNG DÂN')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css?v='.time())}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/huniel.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('content')

    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head hu-first-uppercase">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('BÁO CÁO THEO ĐỘ TUỔI/TRÌNH ĐỘ HỌC VẤN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools text-right">
                <form action="{{route('people.report.export')}}">
                    <input type="hidden" name="year" value="{{$year}}">
                    <input type="hidden" name="people_verification_year" value="{{$year}}">
                    <input type="hidden" name="people_object_group_id" value="{{$people_object_group_id}}">


                <button type="submit"
                   class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                    <span>
                        <span>
                            {{__('EXPORT')}}
                        </span>
                    </span>
                </button>
                </form>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">


                <div class="col-4 offset-5">
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-4 col-form-label">Chọn năm phúc tra</label>
                        <div class="col-sm-8">
                            <div class="m-input-icon m-input-icon--right">
                                <input type="text" class="form-control  m-input" name="people_verification_year" id="date_search" value="{{$year}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3 form-group m-form__group align-items-center">
                    <div class="input-group">
                        {!! Form::select("group_search", $people_object_group_option, $people_object_group_id ?? null, ['class' => 'form-control m-input this-is-select2','id'=>'group_search']) !!}
                    </div>
                </div>

                <div class="col-12">
                    <h3 class="m-portlet__head-text title_header_report">
                        {{__('THỐNG KÊ SỐ LIỆU')}}
                    </h3>
                </div>

                <div class="col-12 table-responsive ">
                    <div class="row p-3">
                        <div class="col-12 box-shadow-report p-4">
                            <table class="table update_border">
                                <tr class="background-table-blue">
                                    <td rowspan="2" class="text-center">{{__('Nhóm danh sách')}}</td>
                                    <td rowspan="2" class="text-center">{{__('Tên danh sách')}}</td>
                                    <td rowspan="2" class="text-center">{{__('Mã số')}}</td>
                                    <td rowspan="2" class="text-center border-right">{{__('Số lượng công dân')}}</td>
                                    <td colspan="{{count($list_year)}}" class="text-center position-relative">
                                        {{__('Năm sinh')}}
                                    </td>
                                    <td rowspan="2" class="text-center border-left-right">{{__('Tổng số')}}</td>
                                    <td colspan="16"  class="text-center">{{__('Trình độ học vấn')}}</td>

                                </tr>
                                <tr class="background-table-blue">
                                    {{--                            Nhóm năm sinh--}}
                                    @if($list_year)
                                    @foreach($list_year as $itemYear)
                                        <td>{{$itemYear}}</td>
                                    @endforeach
                                    @else
                                        <td></td>
                                    @endif
                                    {{--                            Kết thúc nhóm năm sinh--}}
                                    {{--                            Nhóm Trình độ học vấn--}}
                                    @foreach($edu as $itemEdu)
                                        <td>{{$itemEdu['name']}}</td>
                                    @endforeach

                                    {{--                            Kết thúc nhóm trình độ học vấn--}}
                                </tr>
                                @if($report)
                                @foreach($report as $objectGroupId => $itemObjectGroup)
                                    @if(count($itemObjectGroup['child']) > 1)
                                        <tr>
                                            <td rowspan="{{count($itemObjectGroup['child']) + 1}}">
                                                <strong>{{$itemObjectGroup['data']['people_object_group_name']}}</strong>
                                            </td>
                                        </tr>
                                        <?php
                                            $phutd = 1;
                                        ?>
                                        @foreach($itemObjectGroup['child'] as $objectId => $itemObject)
                                            <tr>
                                                <td>{{$itemObject['data']['people_object_name']}}</td>
                                                <td class="text-center">{{$itemObject['data']['people_object_code']}}</td>
                                                <td class="text-center">{{$itemObject['data']['total']}}</td>
                                                {{--                            Nhóm năm sinh--}}

                                                @foreach($list_year as $itemYear)
                                                    <td>
                                                        @if(isset($itemObject['year'][$itemYear]))
                                                            {{$itemObject['year'][$itemYear]}}
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                @endforeach
                                                {{--                            Kết thúc nhóm năm sinh--}}
                                                @if($phutd == 1)
                                                <td rowspan="{{count($itemObjectGroup['child'])}}" class="text-center justify-content-center">
                                                    <a href="javascript:void(0)"
                                                       class="ajax submit"
                                                       method="POST"
                                                       action="{{route('people.people.ajax-list-modal')}}"
                                                       data-people_verification_year="{{$year}}"
                                                       data-people_object_group_id="{{$itemObjectGroup['data']['people_object_group_id']}}"
                                                    >{{$itemObjectGroup['data']['total']}}</a>
                                                </td>
                                                @endif
                                                {{--                            Nhóm trình độ học vấn--}}
                                                @foreach($edu as $itemEdu)
                                                    <td>
                                                        @if(isset($itemObject['edu'][$itemEdu['educational_level_id']]))
                                                            {{$itemObject['edu'][$itemEdu['educational_level_id']]}}
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                @endforeach

                                            </tr>
                                            <?php $phutd++; ?>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td rowspan="{{count($itemObjectGroup['child'])}}">
                                                <strong>{{$itemObjectGroup['data']['people_object_group_name']}}</strong>
                                            </td>
                                            @foreach($itemObjectGroup['child'] as $objectId => $itemObject)
                                            <td>{{$itemObject['data']['people_object_name']}}</td>
                                            <td class="text-center">{{$itemObject['data']['people_object_code']}}</td>
                                            <td class="text-center">{{$itemObject['data']['total']}}</td>
                                            {{--                            Nhóm năm sinh--}}
                                                @foreach($list_year as $itemYear)
                                                    <td>
                                                        @if(isset($itemObject['year'][$itemYear]))
                                                            {{$itemObject['year'][$itemYear]}}
                                                        @else
                                                            0
                                                        @endif
                                                    </td>
                                                @endforeach
                                            {{--                            Kết thúc nhóm năm sinh--}}
                                            <td rowspan="{{count($itemObjectGroup['child'])}}" class="text-center justify-content-center">
                                                <a href="javascript:void(0)"
                                                   class="ajax submit"
                                                   method="POST"
                                                   action="{{route('people.people.ajax-list-modal')}}"
                                                   data-people_verification_year="{{$year}}"
                                                   data-people_object_id="{{$itemObject['data']['people_object_id']}}"
                                                >{{$itemObjectGroup['data']['total']}}</a>
                                            </td>
                                            {{--                            Nhóm trình độ học vấn--}}
                                            @foreach($edu as $itemEdu)
                                                <td>
                                                    @if(isset($itemObject['edu'][$itemEdu['educational_level_id']]))
                                                        {{$itemObject['edu'][$itemEdu['educational_level_id']]}}
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                            @endforeach

                                        </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                @else
                                    <tr >
                                        <td align="center" colspan="25">Không có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="m-portlet__head-tools text-right">
                        <form action="{{route('people.report.export')}}">
                            <input type="hidden" name="year" value="{{$year}}">
                            <input type="hidden" name="people_verification_year" value="{{$year}}">
                            <input type="hidden" name="people_object_group_id" value="{{$people_object_group_id}}">
                            <input type="hidden" name="type" value="people">

                            <button type="submit"
                                    class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                <span>
                                    <span>
                                        {{__('EXPORT DANH SÁCH CÔNG DÂN')}}
                                    </span>
                                </span>
                            </button>
                        </form>
                    </div>
                    <div id="accordion">
                        <div class="card card-fix">
                            <div class="card-header background-white box-shadow-report position-relative" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <h5 class="mb-0 title_header_report">
                                    {{__('BÁO CÁO CHI TIẾT')}}
                                </h5>
{{--                                <p class="mb-0"><i class="fa fa-angle-down"></i></p>--}}
                                <p class="mb-0 position-absolute-arrow">
                                    <img class="img-fluid w-75" src="{{asset('static/backend/images/Vector.png')}}">
                                </p>
                            </div>

                            <div id="collapseOne" class="collapse show box-shadow-report" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped m-table ss--header-table ss--nowrap">
                                            <thead>
                                            <tr class="ss--first-uppercase">
                                                <th class="ss--font-size-th">#</th>
                                                <th class="ss--font-size-th">{{__('Tên công dân')}}</th>
                                                <th class="ss--font-size-th">{{__('Ngày tháng năm sinh')}}</th>
                                                <th class="ss--font-size-th">{{__('Mã hồ sơ')}}</th>
                                                <th class="ss--font-size-th">{{__('Nơi làm việc')}}</th>
                                                <th class="ss--font-size-th">{{__('Văn hóa')}}</th>
                                                <th class="ss--font-size-th">{{__('Địa chỉ tạm trú')}}</th>
                                                <th class="ss--font-size-th">{{__('Kết quả phúc tra gần nhất')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($list))
                                            <?php
                                                $i = 1;
                                            ?>
                                            @foreach($list as $itemList)
                                                <tr>
                                                    <td>{{ $i + ($page - 1) * 10}}</td>
                                                    <td>{{$itemList['full_name']}}</td>
                                                    <td>{{\Carbon\Carbon::createFromFormat('Y-m-d',$itemList['birthday'])->format('d/m/Y')}}</td>
                                                    <td>{{$itemList['code']}}</td>
                                                    <td>{{$itemList['workplace']}}</td>
                                                    <td>{{$itemList['educational_level_name']}}</td>
                                                    <td>{{$itemList['temporary_address']}}</td>
                                                    <td>
                                                        @if( ($itemList['people_object_name']??'')==($itemList['people_object_group_name']??'') )
                                                            {{$itemList['people_object_name']??''}}
                                                        @else
                                                            {{$itemList['people_object_group_name']??''}} - {{$itemList['people_object_name']??''}}
                                                        @endif
                                                    </td>
                                                </tr>
                                                <?php
                                                $i++;
                                                ?>
                                            @endforeach
                                            @else
                                                <tr >
                                                    <td align="center" colspan="6">Không có dữ liệu</td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                        {{ $list->links('helpers.paging-reload', ['year' => $year,'people_object_group_id' => $people_object_group_id]) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@stop
@section('after_script')
    <script src="{{asset('static/backend/js/people/report/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/huniel.js?v='.time())}}" type="text/javascript"></script>
    <script>
        AjaxHandle.startListen({
            form:'.ajax',
            button:'.submit'
        })
        AjaxLaravelPagination.startListen(".laravel-paginator");
        $('.this-is-select2').select2({width:'100%'})
    </script>
@stop
