<div class="modal fade" id="modal-{{ isset($customerLeadFile) ? 'edit' : 'add' }}-file">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Thêm tập tin mới')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form class="frm-add-file" data-action="{{ route('admin.upload-image') }}">
                    <div class="form-group text-center">
                        <label for="files" class="btn btn-success p-2">@lang('Tải lên')</label>
                        <input id="files" style="display:none;" type="file"><br>
                        <span class="upload-file-name">{{ isset($customerLeadFile) ? $customerLeadFile->file_name : '' }}</span>
                        <span class="error-file-name color_red"></span>
                    </div>
                    <input type="hidden" class="customer_lead_file_id" value="{{ isset($customerLeadFile) ? $customerLeadFile->customer_lead_file_id : ''}}">
                    <input type="hidden" name="customer_lead_id" value="{{ isset($customerLeadId) ? $customerLeadId : ''}}">
                    <input type="hidden" class="full-path" name="full_path" value="">
                    <input type="hidden" class="submit_type" name="submit_type" value="{{ isset($customerLeadFile) ? 'update' : 'add'}}">
                    <div class="form-group">
                        <p>@lang('Nội dung'):</p>
                        <textarea id="file-note" class="form-control" name="content" rows="7">{{ isset($customerLeadFile) ? $customerLeadFile->content : '' }}</textarea>
                    </div>
                    <div class="text-center">
                        <a href="#" id="btn-add-file" data-id="{{ $customerLeadId }}" data-action="{{ route('customer-lead.add-file') }}" class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m--margin-left-10">
                            <span>
                                <span>@lang('Lưu')</span>
                            </span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>