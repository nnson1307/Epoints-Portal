<div class="frmFilter ss--background m--margin-bottom-30 ajax ajax-people-verify-list-form hu-first-uppercase ajax"
     method="POST" action="{{route('people.verify.ajax-list')}}
        ">
    <input type="hidden" name="people_id" value="{{$item['people_id']??$param['people_id']}}">
    <div class="ss--bao-filter">
        <div class="row">
            @isset($filters2)
                @foreach ($filters2 as $name2 => $item2)
                    <div class="col-lg-4 form-group">
                        <div class="form-group m-form__group row align-items-center">
                            <div class="col-lg-12 input-group">
                                @if(isset($item2['text']))
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item2['text'] }}
                                        </span>
                                    </div>
                                @endif
                                {!! Form::select($name2, $item2['data'], $param[$name2] ?? null, ['class' => 'form-control m-input','title'=>'Chọn trạng thái','onLoad'=>'$(this).select2({width:"100%"})']) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset
            <div class="col-lg-2 form-group">
                <button class="btn ss--button-cms-piospa m-btn--icon submit my-1">
                    {{__('TÌM KIẾM')}}
                    <i class="fa fa-search ss--icon-search"></i>
                </button>
            </div>
        </div>

    </div>
</div>
