<div id="autotable">
    <form class="frmFilter bg">
        <div class="padding_row">
            <div class="row">
                <div class="form-group col-lg-3">
                    <select class="form-control m_selectpicker" id="date_type" name="date_type" onchange="list.changeDateType(this)">
                        <option value="by_week">@lang('Theo tuần')</option>
                        <option value="by_month" selected>@lang('Theo tháng')</option>
                    </select>
                </div>
                <div class="form-group col-lg-3">
                    <select class="form-control m_selectpicker" id="date_object" name="date_object">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->format('m') ? 'selected': ''}}>
                                {{ __('Tháng ' . $i) }}
                            </option>
                        @endfor
                    </select>
                </div>
                @php $i = 0; @endphp
                @foreach ($FILTER as $name => $item)
                    @if ($i > 0 && ($i % 6 == 0))
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
                    <button class="btn btn-primary color_button btn-search">
                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                    </button>
                </div>
            </div>

        </div>
    </form>
    <div class="table-content m--padding-top-30">
        {{-- @include('staff-salary::report-budget-branch.list') --}}
    </div>
</div>