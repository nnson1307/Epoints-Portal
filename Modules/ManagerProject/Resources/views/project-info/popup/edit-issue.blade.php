<div class="modal fade" id="edit-issue" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="    max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <i class=" fa 	fa-plus-circle" style="padding: 5px;color: #0067AC;"></i>
                <h2 class="modal-title" id="title" style="    color: #0067AC">{{__('CHỈNH SỬA VẤN ĐỀ')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-issue-form" method="post">
                <div class="modal-body" style="padding: 10px 30px;">
                    <span class="note-font" style="font-weight: bold">{{__('Nhập nội dung :')}}<b
                                class="text-danger">*</b></span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row" style="    margin-bottom: 0rem;">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <textarea id="content" name="content"
                                                  rows="4" cols="100"
                                                  placeholder="{{__('Nhập nội dung vấn đề')}}">{!! $dataIssue['content'] !!}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <span class="note-font" style="font-weight: bold">{{__('Trạng thái :')}}</span>
                    <div class="note-plus">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control" name="issue_status">
                                    <option value="">{{__('Trạng thái')}}</option>
                                    <option value="new" {{isset($dataIssue['status']) && $dataIssue['status'] == 'new' ? 'selected' : ''}}>{{__('Mới')}}</option>
                                    <option value="processing" {{isset($dataIssue['status']) && $dataIssue['status'] == 'processing' ? 'selected' : ''}}>{{__('Đang xử lí')}}</option>
                                    <option value="success" {{isset($dataIssue['status']) && $dataIssue['status'] == 'success' ? 'selected' : ''}}>{{__('Đã xử lí')}}</option>
                                    <option value="reject" {{isset($dataIssue['status']) && $dataIssue['status'] == 'reject' ? 'selected' : ''}}>{{__('Hủy')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="project_issue_id" value="{{$dataIssue['project_issue_id']}}">

            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="la 	la-arrow-left"></span>
                    {{__('HỦY')}}
                </button>
                <button type="button" class="btn btn-primary" onclick="projectInfo.saveEditIssue()">
                    <span class="la 		la-check"></span>
                    {{__('LƯU THÔNG TIN')}}
                </button>
            </div>
        </div>
    </div>
</div>
