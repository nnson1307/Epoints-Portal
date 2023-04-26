@if (count($data) > 0)
    @foreach ($data as $key => $block)
        <div class="accordion div-block kt-margin-b-20 {{ $key > 0 ? 'kt-margin-t-20' : '' }}"
            id="accordionExample_{{ $block['position'] }}" data-position="{{ $block['position'] }}">
            <div class="card">
                <div class="card-header headerblock">
                    <div class="card-title block-title collapsed" data-toggle="collapse"
                        data-target="#contentblock_{{ $block['position'] }}"
                        aria-expanded="{{ isset($block['is_show']) && $block['is_show'] == 0 ? 'false' : 'true' }}">
                        <i class="fa {{ isset($block['is_show']) && $block['is_show'] == 0 ? 'fa-caret-right' : 'fa-caret-down' }} icon_drop_{{ $block['position'] }} font-size-20-important"
                            onclick="question.showBlockCollapse(this, '{{ $block['position'] }}', '{{ $key }}')"></i>
                    </div>
                    <input type="text" class="form-control width-70pt" value="{{ $block['block_name'] }}"
                        onchange="question.onChangeBlock(this, '{{ $key }}', 'block_name')"
                        placeholder="@lang('Nhập tên block')" {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>
                    <span
                        class="text-nowrap span_count_question_{{ $block['position'] }}
                    {{ isset($block['is_show']) && $block['is_show'] == 0 ? '' : 'text-white' }}">
                        ({{ count($block['question']) }} @lang('câu hỏi'))
                    </span>
                    <div class="dropdown kt-margin-l-100">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"
                            {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>
                            @lang('Chọn mẫu template')
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            @if ($templateQuestion->count() > 0)
                                @foreach ($templateQuestion as $k => $item)
                                    @if (!empty($block['template']) && in_array($item->key_template, $block['template']))
                                        <a class="dropdown-item"
                                            onclick="question.loadTeample('{{ $item->key_template }}' , {{ $key }})">
                                            <i class='far fa-check-circle' style="color:black">
                                            </i>
                                            {{ __($item->name) }}
                                        </a>
                                    @else
                                        <a class="dropdown-item" style="margin-left : "
                                            onclick="question.loadTeample('{{ $item->key_template }}' , {{ $key }})">
                                            {{ __($item->name) }}
                                        </a>
                                    @endif
                                @endForeach
                            @endif
                        </div>
                    </div>

                    <div class="dropdown kt-margin-l-100">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"
                            {{ $params['action_page'] == 'show' ? 'disabled' : '' }}>
                            @lang('Tùy chọn Block')
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item"
                                onclick="question.onChangeBlock('up', '{{ $key }}', null, 'change_position')">
                                @lang('Di chuyển block lên phía trên')
                            </a>
                            <a class="dropdown-item"
                                onclick="question.onChangeBlock('down', '{{ $key }}', null, 'change_position')">
                                @lang('Di chuyển block xuống phía dưới')
                            </a>
                            <a class="dropdown-item" style="color:red"
                                onclick="question.showModalRemove({{ $key }})">@lang('Xóa block')</a>
                        </div>
                    </div>
                </div>
                <div id="contentblock_{{ $block['position'] }}"
                    class="collapse bodyblock {{ isset($block['is_show']) && $block['is_show'] == 0 ? '' : 'show' }}"
                    data-parent="#accordionExample_{{ $block['position'] }}" style="">
                    <div class="card-body kt-padding-l-0 kt-padding-r-0 div_sortable"
                        id="sortable_{{ $key }}">
                    </div>
                    <div class="div-btn-add-question text-center kt-margin-b-20">
                        @if (count($block['question']) <= 20 && $params['action_page'] != 'show')
                            <button type="button"
                                class="btn btn-primary color_button btn-search button-add-list-{{ $key }}"
                                onclick="question.addQuestion('{{ $key }}', 0)">
                                <i class="fa fa-plus-circle"></i> @lang('Thêm câu hỏi')
                            </button>
                        @else
                            <button type="button"
                                class="btn btn-primary color_button btn-search button-add-list-{{ $key }}"
                                disabled>
                                <i class="fa fa-plus-circle"></i> @lang('Thêm câu hỏi')
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            @if (count($data) >= 10 || $params['action_page'] == 'show')
                <a href="javascript:void(0)" style="font-weight:500; color:#0F2BE6;" class="text-dark">
                    @lang('Thêm Block')
                </a>
            @else
                <a href="javascript:void(0)" style="font-weight:500; color:#0F2BE6;"
                    onclick="question.addBlock('{{ $block['position'] }}')">
                    @lang('Thêm Block')
                </a>
            @endif
        </div>
    @endforeach
@else
    <div class="text-center mt-3">
        @if ($params['action_page'] == 'show')
            <a href="javascript:void(0)" class="text-dark" style="font-weight:500; color:#0F2BE6;">
                @lang('Thêm Block')
            </a>
        @else
            <a href="javascript:void(0)" style="font-weight:500; color:#0F2BE6;" onclick="question.addBlock(0)">
                @lang('Thêm Block')
            </a>
        @endif
    </div>
@endif
