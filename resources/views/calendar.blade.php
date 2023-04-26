@extends('layout')

@section('content')
<style>
    .ddt .ddt-sp{
        max-width: 100%;
    }
    .ddt-th{
        font-weight:bold;
        display:block;
    }
    .ddt-ng{
        display:block;
    }
    .ddt th, .ddt td {
        text-align: center;
        width: 6.66%
    }
    .ddt .table-bordered th, .ddt .table-bordered td {
        border-color: #6f5499;
        vertical-align: middle;
    }

    .ddt table {
        border-collapse:separate;
        border-spacing:0 10px;
    }

    .ddt-ite{
        position: relative;
    }
    .ddt-ite span{
        position: relative;
        z-index:3;
    }
    .ddt-prg{
        position:absolute;
        top:0;
        left:0;
        height:100%;
        background-color:#6f5499;
    }
    .ddt-cn {
        color: #f00;
    }
</style>
@php($now = Carbon\Carbon::now())
@php($now = $now->startOfWeek())
    <div class="row ddt">
        <div class="table-responsive-lg">
            <table width="100%" class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        @for($i=1; $i<=14; $i++)
                        <th class="{{ $i % 7 == 0 ? 'ddt-cn' : '' }}">
                            <span class="ddt-th">{{ getThu($now) }}</span>
                            <span class="ddt-ng">{{ $now->format('d/m') }}</span>
                            @php($now->addDay())
                        </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>

                <tr>
                    <td>
                        <img class="ddt-sp" src="https://www.driving.co.uk/s3/st-driving-prod/uploads/2020/02/2020-Vauxhall-Corsa-SRi-UK-01.jpg">
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg" data-busy="100"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg" data-busy="100"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg" data-busy="100"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>1200k</span>
                        <div class="ddt-prg" data-busy="30"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                    <td class="ddt-ite">
                        <span>120k</span>
                        <div class="ddt-prg"></div>
                    </td>
                </tr>

                @for($r = 1; $r < 7; $r++)
                    <tr>
                        <td>
                            <img class="ddt-sp" src="https://www.driving.co.uk/s3/st-driving-prod/uploads/2020/02/2020-Vauxhall-Corsa-SRi-UK-01.jpg">
                        </td>
                        @for($i=1; $i<=14; $i++)
                        <td class="ddt-ite">
                            <span>120k</span>
                            <div class="ddt-prg"></div>
                        </td>
                        @endfor
                    </tr>
                @endfor
                </tbody>
            </table>
        </div>
    </div>

@stop

@section('after_script')
    <script>
        $('.ddt-prg').each(function() {
            var progress = $(this).data('busy');
            if (typeof progress != 'undefined') {
                $(this).css('width', progress + '%')
            }
        });
    </script>

@stop