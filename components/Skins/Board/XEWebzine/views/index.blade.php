{{ XeFrontend::css('plugins/board/assets/css/new-board-webzine.css')->load() }}

<div class="xe-list-board-header__contents">
    <form method="get" action="{{ $urlHandler->get('index') }}" class="__xe_search">
        <div class="xe-list-board-header--left-box">
            <div class="xe-list-board--header__search">
                <input type="text" name="title_content" class="xe-list-board--header__search__control" value="{{ Request::get('title_content') }}">
                <span class="xe-list-board--header__search__icon">
                    <button type="submit"><i class="xi-search"></i></button>
                </span>
            </div>
        </div>
        <div class="xe-list-board-header--right-box __xe-forms">
            @if ($config->get('category') === true)
                <div class="xe-list-board-header--category xe-list-board-header--dropdown-box">
                    <div class="xe-list-board-header--dropdown __xe-dropdown-form">
                        <div class="xe-list-board-header-category__button xe-list-board-header--dropdown__button">
                            {!! uio('uiobject/board@new_select', [
                                'name' => 'category_item_id',
                                'label' => xe_trans('xe::category'),
                                'value' => Request::get('category_item_id'),
                                'items' => $categories
                            ]) !!}
                        </div>
                    </div>
                </div>
            @endif
            <div class="xe-list-board-header--sort xe-list-board-header--dropdown-box">
                <div class="xe-list-board-header--dropdown __xe-dropdown-form">
                    <div class="xe-list-board-header-order__button xe-list-board-header--dropdown__button">
                        {!! uio('uiobject/board@new_select', [
                            'name' => 'order_type',
                            'label' => xe_trans('xe::order'),
                            'value' => Request::get('order_type', $config->get('orderType')),
                            'items' => $handler->getOrders()
                        ]) !!}
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="xe-list-webzine-board-body">
    <ul class="xe-list-webzine-board-list">
        @foreach ($notices as $item)
            <li class="xe-list-webzine-board-list-item">
                <div class="xe-list-webzine-board-list-item__img-box">
                    <a href="{{$urlHandler->getShow($item, Request::all())}}">
                        <div class="xe-list-board-list-item__notice-banner">??????</div>
                        <div class="xe-list-webzine-board-list-item__img" @if($item->board_thumbnail_path) style="background-image: url('{{ $item->board_thumbnail_path }}')" @endif></div>
                    </a>
                </div>
                <div class="xe-list-webzine-board-list-item__body">
                    @if (in_array('title', $skinConfig['listColumns']) === true)
                        <div class="xe-list-webzine-board-list-item__text">
                            <a href="{{$urlHandler->getShow($item, Request::all())}}" class="xe-list-webzine-board-list-item__text-link" id="title_{{$item->id}}">
                                <div class="xe-list-webzine-board-list-item__title-box">
                                    @if ($item->display === $item::DISPLAY_SECRET)
                                        <span class="xe-list-board-list__subjec-secret"><i class="xi-lock"></i></span>
                                    @endif
                                    <h2 class="xe-list-webzine-board-list-item__title">{!! $item->title !!}</h2>
                                    @if ($item->isNew($config->get('newTime')) && array_get($skinConfig, 'visibleIndexNewIcon', 'show') === 'show')
                                        <div class="xe-list-board-list__title-new-icon">
                                            <span class="xe-list-board-list__title-new"><span class="blind">??????</span></span>
                                        </div>
                                    @endif
                                </div>
                                @if ($item->display !== $item::DISPLAY_SECRET && array_get($skinConfig, 'visibleIndexBlogDescription', 'on') === 'on')
                                    <p class="xe-list-webzine-board-list-item__description">{{ $item->pure_content }}</p>
                                @endif
                            </a>
                        </div>
                    @endif

                    <div class="xe-list-webzine-board-list-item--wrapper">
                        @if (in_array('writer', $skinConfig['listColumns']) === true && array_get($skinConfig, 'visibleIndexBlogProfileImage', 'on') === 'on')
                            <div class="xe-list-board-list--left-box">
                                <div class="xe-list-webzine-board-list-item__user-info">
                                    @if ($item->hasAuthor() && $config->get('anonymity') === false)
                                        <a href="#" class="xe-list-webzine-board-list-item__text-link"
                                        data-toggle="xe-page-toggle-menu"
                                        data-url="{{ route('toggleMenuPage') }}"
                                        data-data='{!! json_encode(['id'=>$item->getUserId(), 'type'=>'user']) !!}'>
                                            <span class="xe-list-board-list__user-image" style="background: url({{ $item->user->getProfileImage() }}); background-size: 28px;"><span class="blind">?????? ?????????</span></span>
                                        </a>
                                    @else
                                        <a href="#">
                                            <span class="xe-list-board-list__user-image"><span class="blind">?????? ?????????</span></span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="xe-list-board-list--middle-box">
                            <div class="xe-list-board-list--middle-wrapper">
                                @if (in_array('writer', $skinConfig['listColumns']) === true)
                                    <div class="xe-list-board-list--title">
                                        @if ($item->hasAuthor() && $config->get('anonymity') === false)
                                            <a href="#" class="xe-list-webzine-board-list-item__text-link"
                                                data-toggle="xe-page-toggle-menu"
                                                data-url="{{ route('toggleMenuPage') }}"
                                                data-data='{!! json_encode(['id'=>$item->getUserId(), 'type'=>'user']) !!}'>
                                                <span class="xe-list-board-list__display_name">{{ $item->writer }}</span>
                                            </a>
                                        @else
                                            <span class="xe-list-board-list__display_name">{{ $item->writer }}</span>
                                        @endif
                                        
                                        @if ($config->get('category') === true)
                                            @if ($item->boardCategory !== null)
                                                <span class="xe-list-webzine-board-list-item__category">
                                                    {!! xe_trans($item->boardCategory->categoryItem->word) !!}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                                <div class="xe-list-webzine-board-list-item___detail-info">
                                    <p class="xe-list-webzine-board-list-item___detail  xe-list-webzine-board-list-item___detail-comment_count">
                                        <span class="xe-list-webzine-board-list-item___detail-label">{{ xe_trans('board::comment_count') }}</span> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->comment_count) }}</span>
                                    </p>

                                    @if (in_array('read_count', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-read_count">
                                            <span class="xe-list-webzine-board-list-item___detail-label">{{ xe_trans('board::read_count') }}</span> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->read_count) }}</span>
                                        </p>
                                    @endif

                                    @if (in_array('created_at', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-create_at">
                                            <span class="xe-list-webzine-board-list-item___detail-label blind">{{ xe_trans('board::created_at') }}</span> {{ $item->created_at->format('Y. m. d.') }}
                                        </p>
                                    @endif

                                    @if (in_array('updated_at', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-updated_at">
                                            <span class="xe-list-webzine-board-list-item___detail-label">{{ xe_trans('board::updated_at') }}</span> {{ $item->updated_at->format('Y. m. d.') }}
                                        </p>
                                    @endif

                                    @if (in_array('dissent_count', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-vote_count">
                                            <span class="xe-list-webzine-board-leist-item___detail-label">{{ xe_trans('board::dissent_count') }}</span> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->dissent_count) }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="xe-list-board-list--right-box">
                            @if (in_array('assent_count', $skinConfig['listColumns']) === true)
                            <span class="blind xe-list-gallery-board-list-item___detail-label">{{ xe_trans('board::assent_count') }}</span><p class="xe-list-board-vote-count__icon"></p> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->assent_count) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
        @endforeach

        @foreach ($paginate as $item)
            <li class="xe-list-webzine-board-list-item">
                <div class="xe-list-webzine-board-list-item__img-box">
                    <a href="{{$urlHandler->getShow($item, Request::all())}}">
                        <div class="xe-list-webzine-board-list-item__img" @if($item->board_thumbnail_path) style="background-image: url('{{ $item->board_thumbnail_path }}')" @endif></div>
                    </a>
                </div>
                <div class="xe-list-webzine-board-list-item__body">
                    @if (in_array('title', $skinConfig['listColumns']) === true)
                        <div class="xe-list-webzine-board-list-item__text">
                            <a href="{{$urlHandler->getShow($item, Request::all())}}" class="xe-list-webzine-board-list-item__text-link" id="title_{{$item->id}}">
                                <div class="xe-list-webzine-board-list-item__title-box">
                                    @if ($item->display === $item::DISPLAY_SECRET)
                                        <span class="xe-list-board-list__subjec-secret"><i class="xi-lock"></i></span>
                                    @endif
                                    <h2 class="xe-list-webzine-board-list-item__title">{!! $item->title !!}</h2>
                                    @if ($item->isNew($config->get('newTime')) && array_get($skinConfig, 'visibleIndexNewIcon', 'show') === 'show')
                                        <div class="xe-list-board-list__title-new-icon">
                                            <span class="xe-list-board-list__title-new"><span class="blind">??????</span></span>
                                        </div>
                                    @endif
                                </div>
                                @if ($item->display !== $item::DISPLAY_SECRET && array_get($skinConfig, 'visibleIndexGalleryDescription', 'on') === 'on')
                                    <p class="xe-list-webzine-board-list-item__description">{{ $item->pure_content }}</p>
                                @endif
                            </a>
                        </div>
                    @endif
                    <div class="xe-list-webzine-board-list-item--wrapper">
                        @if (in_array('writer', $skinConfig['listColumns']) === true && array_get($skinConfig, 'visibleIndexBlogProfileImage', 'on') === 'on')
                            <div class="xe-list-board-list--left-box">
                                <div class="xe-list-webzine-board-list-item__user-info">
                                    @if ($item->hasAuthor() && $config->get('anonymity') === false)
                                        <a href="#" class="xe-list-webzine-board-list-item__text-link"
                                        data-toggle="xe-page-toggle-menu"
                                        data-url="{{ route('toggleMenuPage') }}"
                                        data-data='{!! json_encode(['id'=>$item->getUserId(), 'type'=>'user']) !!}'>
                                            <span class="xe-list-board-list__user-image" style="background: url({{ $item->user->getProfileImage() }}); background-size: 28px;"><span class="blind">?????? ?????????</span></span>
                                        </a>
                                    @else
                                        <span class="xe-list-board-list__user-image"><span class="blind">?????? ?????????</span></span>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="xe-list-board-list--middle-box">
                            <div class="xe-list-board-list--middle-wraper">
                                @if (in_array('writer', $skinConfig['listColumns']) === true)
                                    <div class="xe-list-board-list--writer">
                                        @if ($item->hasAuthor() && $config->get('anonymity') === false)
                                            <a href="#" class="xe-list-webzine-board-list-item__text-link"
                                                data-toggle="xe-page-toggle-menu"
                                                data-url="{{ route('toggleMenuPage') }}"
                                                data-data='{!! json_encode(['id'=>$item->getUserId(), 'type'=>'user']) !!}'>
                                                <span class="xe-list-board-list__display_name">{{ $item->writer }}</span>
                                            </a>
                                        @else
                                            <a href="#">
                                                <span class="xe-list-board-list__display_name">{{ $item->writer }}</span>
                                            </a>
                                        @endif
                                        @if ($config->get('category') === true)
                                            @if ($item->boardCategory !== null)
                                                <span class="xe-list-webzine-board-list-item__category">
                                                    {!! xe_trans($item->boardCategory->categoryItem->word) !!}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                                <div class="xe-list-webzine-board-list-item___detail-info">
                                    <p class="xe-list-webzine-board-list-item___detail  xe-list-webzine-board-list-item___detail-comment_count">
                                        <span class="xe-list-webzine-board-list-item___detail-label">{{ xe_trans('board::comment_count') }}</span> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->comment_count) }}</span>
                                    </p>

                                    @if (in_array('read_count', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-read_count">
                                            <span class="xe-list-webzine-board-list-item___detail-label">{{ xe_trans('board::read_count') }}</span> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->read_count) }}</span>
                                        </p>
                                    @endif

                                    @if (in_array('created_at', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-create_at">
                                            <span class="xe-list-webzine-board-list-item___detail-label blind">{{ xe_trans('board::created_at') }}</span> {{ $item->created_at->format('Y. m. d.') }}
                                        </p>
                                    @endif

                                    @if (in_array('updated_at', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-updated_at">
                                            <span class="xe-list-webzine-board-list-item___detail-label">{{ xe_trans('board::updated_at') }}</span> {{ $item->updated_at->format('Y. m. d.') }}
                                        </p>
                                    @endif

                                    @if (in_array('dissent_count', $skinConfig['listColumns']) === true)
                                        <p class="xe-list-webzine-board-list-item___detail xe-list-webzine-board-list-item___detail-vote_count">
                                            <span class="xe-list-webzine-board-list-item___detail-label">{{ xe_trans('board::dissent_count') }}</span> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->dissent_count) }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="xe-list-board-list--right-box">
                            @if (in_array('assent_count', $skinConfig['listColumns']) === true)
                            <span class="blind xe-list-gallery-board-list-item___detail-label">{{ xe_trans('board::assent_count') }}</span><p class="xe-list-board-vote-count__icon"></p> <span class="xe-list-board-list-item___detail-number">{{ number_format($item->assent_count) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
        
        @if ($paginate->total() === 0)
        <div class="xe-list-webzine-board__no-result">
            <span class="xe-list-webzine-board__text">????????? ???????????? ????????????.</span>
        </div>
        @endif
    </ul>
</div>

<div class="xe-list-board-footer">
    <div class="xe-list-board--button-box">
        @if ($isManager === true)
            <div class="xe-list-board--btn-left-box">
                <a href="{{ $urlHandler->managerUrl('config', ['boardId' => $instanceId]) }}" class="xe-list-board__btn xe-list-board__btn-primary" target="_blank">{{ xe_trans('xe::manage') }}</a>
            </div>
        @endif
        <div class="xe-list-board--btn-right-box">
            @if (Auth::check() === true && array_get($skinConfig, 'visibleIndexMyBoard', 'show') === 'show')
                @if (Request::get('user_id') === Auth::user()->getId())
                    <a href="{{ $urlHandler->get('index') }}" class="xe-list-board__btn active">?????? ??? ???</a>
                @else
                    <a href="{{ $urlHandler->get('index', ['user_id' => Auth::user()->getId()]) }}" class="xe-list-board__btn">?????? ??? ???</a>
                @endif
            @endif
            @if (array_get($skinConfig, 'visibleIndexWriteButton', 'always') !== 'hidden')
                @if (array_get($skinConfig, 'visibleIndexWriteButton', 'always') === 'always')
                    <a href="{{ $urlHandler->get('create') }}" class="xe-list-board__btn">{{ xe_trans('board::writeItem') }}</a>
                @elseif (array_get($skinConfig, 'visibleIndexWriteButton', 'always') === 'permission' && $isWritable === true)
                    <a href="{{ $urlHandler->get('create') }}" class="xe-list-board__btn">{{ xe_trans('board::writeItem') }}</a>
                @endif
            @endif
        </div>
    </div>
</div>

{!! $paginate->render($_skin::view('default-pagination')) !!}
