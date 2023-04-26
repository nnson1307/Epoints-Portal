<div class="modal fade" id="add-exchange" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="    max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <i class=" fa 	fa-plus-circle" style="padding: 5px;color: #0067AC;"></i>
                <h2 class="modal-title" id="title" style="    color: #0067AC">{{__('THÊM TRAO ĐỔI')}}</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="note-font" style="font-weight: bold">{{__('Vấn đề cần trao đổi:')}}</span>
                <div class="issue" style="    margin-bottom: 10px;    position: relative;    border-radius: 2px;">
                    <div style="display: flex">
                        <p class="font-weight-bold">
                            <img src="{{isset($data['staff_avatar']) ? $data['staff_avatar'] : ''}}"
                                 alt="" style="    width: 35px;height: 35px;border-radius: 50%;">
                            {{isset($data['staff_name']) ? $data['staff_name'] : ''}}
                        </p>
                        <p style="    margin-top: 5px;margin-left: 10px;">
                            <i class=" la 	la-clock-o"></i>
                            {{isset($data['created_at']) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s' , $data['created_at'])->format('d-m-Y H:i') : ''}}
                        </p>
                    </div>
                    <p style="margin: 10px">{{isset($data['content']) ? $data['content'] : ''}}</p>
                    <div>
                        @if(isset($data['status']) && $data['status'] == 'success')
                            <button class="processed" style="background-color: #339933;">
                                <i class="fa fa-solid fa-check"></i>

                                {{__('Đã xử lí')}}
                            </button>
                        @elseif(isset($data['status']) && $data['status'] == 'new')
                            <button class="processed" style="background-color: #00CCCC;">
                                <i class="fa fa-light fa-sparkles"></i>

                                {{__('Mới')}}
                            </button>
                        @elseif(isset($data['status']) && $data['status'] == 'processing')
                            <button class="processed" style="background-color: #9966FF;">
                                <i class="fa fa-duotone fa-typewriter"></i>
                                {{__('Đang xử lí')}}
                            </button>
                        @else
                            <button class="processed" style="background-color: #FF6633;">
                                <i class="fa fa-solid fa-xmark"></i>
                                {{__('Hủy')}}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            <form id="add-issue-form" method="post">
                <div class="modal-body">
                    <span class="note-font" style="font-weight: bold;    padding-left: 510px;">{{__('Nhập nội dung trao đổi:')}}</span>
                    <div class="note-plus">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <div class="form-group m-form__group" style="margin-bottom: 5px">
                                    <div class="input-group">
                                        <textarea id="content" name="content" rows="4" cols="100"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="manage_project_id" value="{{$data['project_id']}}">
                <input type="hidden" name="parent_id" value="{{$data['project_issue_id']}}">
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <span class="la 	la-arrow-left"></span>
                    {{__('HỦY')}}
                </button>
                <button type="button" class="btn btn-primary" onclick="projectInfo.saveNewIssue()">
                    <span class="la 		la-check"></span>
                    {{__('LƯU THÔNG TIN')}}
                </button>
            </div>
        </div>
    </div>
</div>
