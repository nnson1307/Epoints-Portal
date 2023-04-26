<div class="px-5">
    <div class="row">
        <div class="col">
            <div class="dropdown show">
                <button class="btn btn-primary color_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-cog"></i> @lang('cấu hình')
                </button>
            </div>
        </div>
        <div class="col">
            <div class="dropdown show">
                <button class="btn btn-primary color_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-user-clock"></i> @lang('ca làm')
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -126px, 0px);">
                    <a class="dropdown-item" href="{{route('shift')}}">@lang('Danh sách ca làm')</a>
                    <a class="dropdown-item" href="#">@lang('Tạo ca làm')</a>
                    <a class="dropdown-item" href="#">@lang('Phân ca làm')</a>
                    <a class="dropdown-item" href="#">@lang('Sao chép ca')</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="dropdown show">
                <button class="btn btn-primary color_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-sticky-note"></i> @lang('chấm công')
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -126px, 0px);">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="dropdown show">
                <button class="btn btn-primary color_button dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="fas fa-user-lock"></i> @lang('chốt công')
                </button>
            </div>
        </div>
    </div>
</div>