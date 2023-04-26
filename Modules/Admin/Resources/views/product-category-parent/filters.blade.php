<div class="frmFilter ss--background m--margin-bottom-30 ajax ajax-product-category-parent-list-form hu-first-uppercase ajax"
     method="POST" action="{{route('admin.product-category-parent.ajax-list')}}
        ">
    <div class="ss--bao-filter">
        <div class="row">
            <div class="col-lg-4 form-group">
                <div class="m-form__group">
                    <div class="input-group">

                        <button class="btn btn-primary btn-search submit" style="display: none">
                            <i class="fa fa-search"></i>
                        </button>
                        <input type="text" class="form-control" name="search"
                               placeholder="{{__('Nhập thông tin tìm kiếm')}}">
                    </div>
                </div>
            </div>
            @isset($filters)
                @foreach ($filters as $name => $item)
                    <div class="col-lg-4 form-group">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-lg-12 input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input select2','title'=>'Chọn trạng thái']) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset
            <div class="col-lg-2 form-group">
                <button class="btn ss--button-cms-piospa m-btn--icon submit submit-list-product-category-parent"
                    data-current_page=1
                >
                    {{__('TÌM KIẾM')}}
                    <i class="fa fa-search ss--icon-search"></i>
                </button>
            </div>
        </div>

    </div>
</div>
