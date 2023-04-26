<div class="modal fade in" id="modal-template" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Type template')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                            @if (count($errors) > 0)
                                <div class="callout callout-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
        
                                </div>
                            @endif
        
                            <div class="box-body row">
                                <label>@lang('Type template')</label>
                                <select
                                    name="template_type"
                                    id="type_template"
                                    {{-- @include('crud::inc.field_attributes') --}}
                                    class = 'form-control col-sm-6'
                                    >
                                    
                                    <option value=""></option>
                                    <option value="generic" @if(isset($response_content['template_type']) && $response_content['template_type']=='generic') selected @endif>@lang('Generic')</option>
                                    <option value="list" @if(isset($response_content['template_type']) && $response_content['template_type']=='list') selected @endif>@lang('List')</option>
                                </select>
                            </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="response.editTypeTemplate({{$response_content['response_content_id']}})">
                    Lưu
                </button>
            </div>
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script type="text/template" id="icon-tpl">
    <div style="height:100px;width:100px;background-position: center;background-image: url({link});background-size:100%; background-repeat:no-repeat"></div>
</script>
