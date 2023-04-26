<div class="modal fade in" id="modal-template" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Button</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="frmAddButton">
                        <div class="box">
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
                                <div class="form-group col-sm-12">
                                    <label>Title</label>
                                    <input type="text" name="btn_title" class="form-control">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Type</label>
                                    <select name="btn_type" onchange="MyDisabled()" class="form-control">
                                        <option value="postback" selected>@lang('Post back')</option>
                                        <option value="web_url" >@lang('Web Url')</option>
                                    </select>
                                </div>
        
                                <div class="form-group col-sm-12">
                                    <label>Url</label>
                                    <input type="text" name="btn_url" id="url" class="form-control" disabled>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>Payload</label>
                                    <input type="text" name="btn_payload" id="payload"class="form-control">
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="response.addButton({{$response_element_id}})">
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
<script>
    function MyDisabled(){
        if($('#url').attr('disabled')){
            $('#payload').val('');
            $("#url").attr("disabled", false);
            $("#payload").attr("disabled", true);
        }else{
            $('#url').val('');
            $("#url").attr("disabled", true);
            $("#payload").attr("disabled", false);
        }
        
    }
</script>