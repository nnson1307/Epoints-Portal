<div class="modal fade in" id="modal-template" style="display: none; padding-right: 16px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Mẫu')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="frmAddTemplate">
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
                                    <label>@lang('Tiêu đề')</label>
                                    <input type="text" name="title" id="title"  class="form-control">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>@lang('Tiêu đề con')</label>
                                    <input type="text" name="subtitle" id="subtitle"  class="form-control">
                                </div>
                                <div class="form-group col-sm-12">
                                    <label>@lang('Hình ảnh')</label>
                                    <div id="add_image">
                                        <div id="image">
                                            <div style="background-position: center;">
                                            </div>
                                        </div>

                                        <label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Hình ảnh">
                                            <i class="fa fa-pen"></i>
                                            <input type="hidden" id="image_url">
                                            <input type="file" id="getFileLogo" name="getFileLogo" accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" onchange="response.uploadImage(this);">
                                        </label>
        
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            {{-- <div class="box-footer">
                                <button type="submit" class="btn btn-success ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-save"></i> Save</span></button>
                            </div><!-- /.box-footer--> --}}
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="response.addTemplate()">
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
