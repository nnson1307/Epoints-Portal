<div class="modal fade in" id="modal-default" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('chathub::post.index.ADD_KEY')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                        <span>@lang('chathub::post.index.BRAND')</span>
                        <select class="form-control p-key" id="brand">
                            <option value="0"></option>
                            @foreach($listBrand as $item)
                            @if($item['brand_name']==$post['brand'])
                            <option value="{{$item['entities']}}" selected>{{$item['brand_name']}}</option>
                            @else
                            <option value="{{$item['entities']}}">{{$item['brand_name']}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>  
{{--                    <div class="form-group">--}}
{{--                        <span>@lang('chathub::post.index.SUB_BRAND')</span>--}}
{{--                        <select class="form-control p-key" id="sub_brand">--}}
{{--                            <option value="0"></option>--}}
{{--                            @foreach($listSubBrand as $item)--}}
{{--                            @if($item['sub_brand_name']==$post['sub_brand'])--}}
{{--                            <option value="{{$item['entities']}}" selected>{{$item['sub_brand_name']}}</option>--}}
{{--                            @else--}}
{{--                            <option value="{{$item['entities']}}">{{$item['sub_brand_name']}}</option>--}}
{{--                            @endif--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div> --}}
                    <div class="form-group">
                        <span>@lang('chathub::post.index.SKU')</span>
                        <select class="form-control p-key" id="sku">
                            <option value="0"></option>
                            @foreach($listSku as $item)
                            @if($item['sku_name']==$post['sku'])
                            <option value="{{$item['entities']}}" selected>{{$item['sku_name']}}</option>
                            @else
                            <option value="{{$item['entities']}}">{{$item['sku_name']}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div> 
{{--                    <div class="form-group">--}}
{{--                        <span>@lang('chathub::post.index.ATTRIBUTE')</span>--}}
{{--                        <select class="form-control p-key" id="attribute">--}}
{{--                            <option value="0"></option>--}}
{{--                            @foreach($listAttribute as $item)--}}
{{--                            @if($item->getAttributes()['attribute_name']==$post['attribute'])--}}
{{--                            <option value="{{$item->getAttributes()['entities']}}" selected>{{$item->getAttributes()['attribute_name']}}</option>--}}
{{--                            @else--}}
{{--                            <option value="{{$item->getAttributes()['entities']}}">{{$item->getAttributes()['attribute_name']}}</option>--}}
{{--                            @endif--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div> --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="post.updateKey({{$post['id']}})" class="btn btn-primary">
                    Tạo
                </button>
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>