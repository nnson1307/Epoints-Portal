<div class="table-responsive">
    <div class="vertical-tree">
        <ul>
            <li>
                <a href="javascript:void(0);">{{$detail['full_name']}} | SL : {{$detail['total_node_nearest']}}</a>
                <div class="vertical-tree-start">
{{--                    <ul>--}}
{{--                        @foreach($list as $item)--}}
{{--                            <li class="child-li-{{$item['referral_member_id']}}">--}}
{{--                                <a onclick="loadChild(this)" data-id="{{$item['referral_member_id']}}" href="javascript:void(0);">{{$item['full_name']}} | SL : {{$item['total_node_nearest']}}</a>--}}
{{--                                <div class="vertical-tree-container"></div>--}}
{{--                            </li>--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
                    @include('referral::ReferralMember.detail-referral-child.ul-list', ['lv' => 1])
                </div>

            </li>
        </ul>
    </div>
</div>
<style>
    @import url("https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap");
    body{
        font-family: "Rubik", sans-serif;
    }

    /*-------------vertical-tree-view------------*/
    .vertical-tree{
        padding-top: 40px;
        padding-bottom: 40px;
    }
    .vertical-tree ul{
        padding-left: 30px;
    }
    .vertical-tree li {
        margin: 0px 0;
        list-style-type: none;
        position: relative;
        padding: 20px 5px 0px 5px;
    }
    .vertical-tree li::before{
        content: '';
        position: absolute;
        top: 0;
        width: 1px;
        height: 100%;
        right: auto;
        left: -20px;
        border-left: 2px solid #ccc;
        bottom: 50px;
    }
    .vertical-tree li::after{
        content: '';
        position: absolute;
        top: 34px;
        width: 25px;
        height: 20px;
        right: auto;
        left: -20px;
        border-top: 2px solid #ccc;
    }
    .vertical-tree li a{
        display: inline-block;
        padding: 8px 30px;
        text-decoration: none;
        background-color: #e1eafc;
        color: #5a8dee;
        border: 1px solid #e1eafc;
        font-size: 13px;
        border-radius: 4px;
    }
    .vertical-tree > ul > li::before,
    .vertical-tree > ul > li::after{
        border: 0;
    }
    .vertical-tree li:last-child::before{
        height: 34px;
    }
    .vertical-tree li a:hover,
    .vertical-tree li a:hover+ul li a {
        background-color: #5a8dee;
        color: #fff;
        border: 1px solid #5a8dee;
    }
    .vertical-tree li a:hover+ul li::after,
    .vertical-tree li a:hover+ul li::before,
    .vertical-tree li a:hover+ul::before,
    .vertical-tree li a:hover+ul ul::before{
        border-color:  #fbba00;
    }
</style>


