<div id="autotable">
    <div class="form-group m-form__group">
        <div style="text-align: right">
            <a class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc"
               href="{{route('admin.config-page-appointment.add-info')}}">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span>THÊM ĐƠN VỊ</span>
                        </span>
            </a>
            <a href="{{route('admin.config-page-appointment.add-info')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
               style="display: none">
                <i class="fa fa-plus-circle" style="color: #fff"></i>
            </a>
        </div>
    </div>
    <form class="frmFilter bg">
        <div class="row padding_row">
            <div class="col-lg-4">
                <div class="form-group m-form__group">
                    <div class="input-group">

                        <input type="text" class="form-control" name="search_info"
                               placeholder="{{__('Nhập thông tin tìm kiếm...')}}">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 form-group">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-12 input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                        <span class="input-group-text">
                            {{ $item['text'] }}
                        </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input']) !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 form-group">
                <button class="btn btn-primary color_button btn-search">
                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                </button>
            </div>
        </div>
    </form>
    <div class="table-content m--padding-top-30">
        @include('admin::config-page-appointment.spa-info.list')
    </div><!-- end table-content -->
</div>