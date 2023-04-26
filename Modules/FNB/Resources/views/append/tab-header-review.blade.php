<div class="menu-bar menu-bar-review">
    <div class="menu" style="margin-left: 0px">
        <a href="{{route('fnb.request')}}" class="btn {{\Request::route()->getName() == 'fnb.request' ? 'active' : ''}}" >
            {{ __('Yêu cầu') }}
        </a>
    </div>
    <div class="menu">
        <a href="{{route('fnb.customer-review')}}"  class="btn {{\Request::route()->getName() == 'fnb.customer-review' ? 'active' : ''}}">
            {{ __('Đánh giá') }}
        </a>
    </div>
    <div class="menu">
        <a href="{{route('fnb.request-list')}}"  class="btn {{\Request::route()->getName() == 'fnb.request-list' ? 'active' : ''}}" >
            {{ __('Cấp độ đánh giá') }}
        </a>
    </div>
    <div class="menu">
        <a href="{{route('fnb.review-list-detail')}}" class="btn {{\Request::route()->getName() == 'fnb.review-list-detail' ? 'active' : ''}}" >
            {{ __('Chi tiết cấp độ đánh giá') }}
        </a>
    </div>
</div>