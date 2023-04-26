<div id="modal-card">
    <div class="modal fade" id="kt_modal_card" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        @lang('chathub::setting.index.CHANNEL')
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <a href="{{route('redirect', 'facebook')}}" class="btn btn-primary btn-facebook"><i class="socicon-facebook"></i></a>
{{--                    <a href="https://oauth.zaloapp.com/v3/oa/permission?app_id=1396758012695840025&redirect_uri=https://matthews.piospa.com/chat-hub/callback/zalo" class="btn btn-info">Zalo</a>--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        @lang('chathub::setting.index.CLOSE')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
