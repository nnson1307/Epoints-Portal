{{-- <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
    <style>
        body {
            /* height: 1122px; */
            width: 750px;
            font-family: Roboto;
            /* to centre page on screen*/
            margin-left: auto;
            margin-right: auto;
        }

        * {
            box-sizing: border-box;
            font-family: times;
            /*font-family: DejaVu Sans, serif;*/
        }

        .column_20 {
            float: left;
            width: 20%;
            padding: 10px;
            padding-top: 0px;
        }

        .column_80 {
            float: left;
            width: 80%;
            padding: 10px;    padding-top: 0px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .font_bold {
            font-weight: bold;
        }

        .color_red {
            color: #ff0000;
        }

        .text_center {
            text-align: center;
        }

        .text_right {
            text-align: right;
        }

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .font_12 {
            font-size: 12px;
        }

        .font_14 {
            font-size: 14px;
            padding-top: 10px;
            
        }

        h1,h2,h3{
            text-transform: uppercase;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
    
</head>
<body>



<div class="row page-break" style="margin-top: -30px;width: 100%;">
    <div class="col-lg-12 row">
        <h1 class="color_red text_center">@lang('Phiếu lương nhân viên')</h1>
        <div class="column_80">
            <h3 style="margin: 0px;">{{$staffInfo['full_name']}}</h3>
            <div class="font_14">
                <span class="font_bold">@lang('Phòng ban'):</span> {{$staffInfo['department_name']}} - <span class="font_bold">@lang('Chức vụ')
                    :</span>
                {{$staffInfo['staff_title']}} <br>
                <span class="font_bold">@lang('Bảng lương'):</span>
                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffInfoSalary['start_date'])->format('d/m/Y') }}
                 -
                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffInfoSalary['end_date'])->format('d/m/Y') }}
                <br>
                <span class="font_bold">@lang('Loại lương'):</span>
                @if($staffInfoSalary['staff_salary_type_code'] == 'shift')
                    @lang('Lương ca')
                @elseif($staffInfoSalary['staff_salary_type_code'] == 'monthly')
                    @lang('Lương tháng')
                @else
                    @lang('Lương giờ')
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-12 row">
        @if($staffInfoSalary['staff_salary_type_code'] == 'hourly')
        @include('staff-salary::staff-salary.pdf.detail-staff-hourly')
        @elseif($staffInfoSalary['staff_salary_type_code'] == 'shift')
        @include('staff-salary::staff-salary.pdf.detail-staff-shift')
        @else
        @include('staff-salary::staff-salary.pdf.detail-staff-monthly')
        @endif

        <div class="text_right" style="margin-top: 10px">
        <span class="font_14">@lang('Tổng tiền thực nhận'):</span>
        <span class="color_red font_bold" style="font-size: 20px">
            {{number_format($staffInfoSalary['staff_salary_received'] , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
        </span>
        </div>
    </div>
</div>

</body>

</html> --}}
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <title>Receipt example</title>
     
             <style>
             
             * {
                font-size: 14px;
                font-family: 'Dejavu Sans';
            }
                .column_20 {
                    float: left;
                    width: 20%;
                    padding: 10px;
                    padding-top: 0px;
                }
        
                .column_80 {
                    float: left;
                    width: 80%;
                    padding: 10px;    padding-top: 0px;
                }
        
                .row:after {
                    content: "";
                    display: table;
                    clear: both;
                }
        
                .font_bold {
                    font-weight: bold;
                }
        
                .color_red {
                    color: #ff0000;
                }
        
                .text_center {
                    text-align: center;
                }
        
                .text_right {
                    text-align: right;
                }
        
                table {
                    font-family: arial, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                }
        
                td, th {
                    border: 1px solid #dddddd;
                    text-align: left;
                    padding: 8px;
                }
        
                .font_12 {
                    font-size: 12px;
                }
        
                .font_14 {
                    font-size: 14px;
                    padding-top: 10px;
                    
                }
        
                h1,h2,h3{
                    text-transform: uppercase;
                }
                .page-break {
                    page-break-after: always;
                }
            </style>
    </head>
    <body style="font-family: 'Dejavu Sans'">
        <div class="col-lg-12 row">
            <h1 class="color_red text_center">@lang('Phiếu lương nhân viên')</h1>
            <div class="column_80">
                <h3 style="margin: 0px;">{{$staffInfo['full_name']}}</h3>
                <div class="font_14">
                    <span class="font_bold">@lang('Phòng ban'):</span> {{$staffInfo['department_name']}} - <span class="font_bold">@lang('Chức vụ')
                        :</span>
                    {{$staffInfo['staff_title']}} <br>
                    <span class="font_bold">@lang('Bảng lương'):</span>
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffInfoSalary['start_date'])->format('d/m/Y') }}
                     -
                    {{ \Carbon\Carbon::createFromFormat('Y-m-d', $staffInfoSalary['end_date'])->format('d/m/Y') }}
                    <br>
                    <span class="font_bold">@lang('Loại lương'):</span>
                    @if($staffInfoSalary['staff_salary_type_code'] == 'shift')
                        @lang('Lương ca')
                    @elseif($staffInfoSalary['staff_salary_type_code'] == 'monthly')
                        @lang('Lương tháng')
                    @else
                        @lang('Lương giờ')
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-12 row">
            @if($staffInfoSalary['staff_salary_type_code'] == 'hourly')
            @include('staff-salary::staff-salary.pdf.detail-staff-hourly')
            @elseif($staffInfoSalary['staff_salary_type_code'] == 'shift')
            @include('staff-salary::staff-salary.pdf.detail-staff-shift')
            @else
            @include('staff-salary::staff-salary.pdf.detail-staff-monthly')
            @endif
    
            <div class="text_right" style="margin-top: 10px">
            <span class="font_14">@lang('Tổng tiền thực nhận'):</span>
            <span class="color_red font_bold" style="font-size: 20px">
                {{number_format($staffInfoSalary['staff_salary_received'] , isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{$staffInfoSalary['staff_salary_unit_name'] ?? __('VNĐ')}}
            </span>
            </div>
        </div>
    </body>

</html>