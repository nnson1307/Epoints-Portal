<div class="modal fade in" id="modal-template" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Button')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="frmEditButton">
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
                                <!-- load the view from the application if it exists, otherwise load the one in the package -->
        
                                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                                <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>@lang('Title')</label>
                                    <input type="text" name="btn_title" value="{{$button['title']}}" class="form-control">
                                </div>
                                <!-- load the view from the application if it exists, otherwise load the one in the package -->
                                <!-- text input -->
                                <div class="form-group col-sm-12">
                                    <label>@lang('Type')</label>
                                    <select name="btn_type" onchange="MyDisabled()" class="form-control">
                                        <option value="postback" @if($button['title']=='postback')selected @endif>@lang('Post back')</option>
                                        <option value="web_url" @if($button['title']=='web_url')selected @endif>@lang('Web Url')</option>
                                    </select>
                                </div>
        
                                <div class="form-group col-sm-12">
                                    <label>@lang('Url')</label>
                                    <input type="text" name="btn_url" value="{{$button['url']}}" id="url" class="form-control" @if(!$button['url']) disabled @endif>
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>@lang('Payload')</label>
                                    <input type="text" name="btn_payload" value="{{$button['payload']}}" id="payload" class="form-control" @if(!$button['payload']) disabled @endif>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="response.updateButton({{$button['response_button_id']}})">
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
