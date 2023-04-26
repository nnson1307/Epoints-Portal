<div class="modal fade show" id="modal-product">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="fa fa-plus-circle"></i> {{__('THÊM SẢN PHẨM')}}
                </h5>
            </div>
            <div class="modal-body" id="autotable">
                <div class="padding_row bg">
                    <form class="frmFilter">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search_keyword" placeholder="{{__('Nhập tên hoặc mã sản phẩm')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="m-form m-form--label-align-right">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="row">
                                                @php $i = 0; @endphp
                                                @foreach ($FILTER as $name => $item)
                                                    @if ($i > 0 && ($i % 4 == 0))
                                            </div>
                                            <div class="form-group m-form__group row align-items-center">
                                                @endif
                                                @php $i++; @endphp
                                                <div class="col-lg-6 form-group input-group">
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
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group m-form__group">
                                                <button class="btn btn-primary color_button btn-search-product">
                                                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-content m--margin-top-30">
                    @include('promotion::promotion.popup.list-product')
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
                    <button type="submit" onclick="view.submitChoose('product')"
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