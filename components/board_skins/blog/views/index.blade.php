{{ XeFrontend::css('plugins/board/components/board_skins/blog/assets/css/skin.css')->load() }}

{{ XeFrontend::js('plugins/board/assets/js/build/board.js')->appendTo('body')->load() }}

{{ XeFrontend::js('assets/core/xe-ui-component/js/xe-page.js')->appendTo('body')->load() }}

<div class="board">
    <div class="board_header">
        <div>
            <label>{{xe_trans('xe::order')}}</label>
            <select name="orderType">
                <option value="">{{xe_trans('xe::select')}}</option>
                @foreach ($orders as $order)
                    <option value="{{$order['value']}}" @if(Input::get('orderType') == $order['value']) selected="selected" @endif >{{xe_trans($order['text'])}}</option>
                @endforeach
            </select>
        </div>

        @if($config->get('category') == true)
            <div>
                <label>{{xe_trans('xe::category')}}</label>
                <select name="categoryItemId">
                    <option value="">{{xe_trans('xe::select')}}</option>
                    @foreach ($categories as $category)
                        <option value="{{$category['value']}}" @if(Input::get('categoryItemId') == $category['value']) selected="selected" @endif >{{xe_trans($category['text'])}}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <!-- search area -->
        <div class="search_area">
            <form method="get" action="{{ $urlHandler->get('index') }}">
            <fieldset>
                <div>
                    <label>{{ xe_trans('board::titleAndContent') }}</label>
                    <input type="text" name="title_pureContent" title="{{ xe_trans('board::boardSearch') }}" placeholder="{{ xe_trans('xe::enterKeyword') }}" value="{{ Input::get('title_pureContent') }}">
                </div>
                <div>
                    <label>{{ xe_trans('xe::writer') }}</label>
                    <input type="text" name="writer" title="{{ xe_trans('xe::writer') }}" value="{{ Input::get('writer') }}">
                </div>
                <div>
                    <label>{{xe_trans('board::period')}}</label>
                    <input type="text" name="startCreatedAt" title="{{xe_trans('board::startDate')}}" value="{{Input::get('startCreatedAt')}}">
                    ~
                    <input type="text" name="endCreatedAt" title="{{xe_trans('board::endDate')}}" value="{{Input::get('endCreatedAt')}}">
                </div>

                <!-- dynamic fields -->
                @foreach($fieldTypes as $typeConfig)
                    @if($typeConfig->get('searchable') === true)
                    <div >
                        <label>{{ xe_trans($typeConfig->get('label')) }}</label>
                        {!! XeDynamicField::get($config->get('documentGroup'), $typeConfig->get('id'))->getSkin()->search(Input::all()) !!}
                    </div>
                    @endif
                @endforeach
                <!-- dynamic fields -->
                <div>
                    <button type="submit">{{ xe_trans('xe::search') }}</button>
                    <button type="button">{{ xe_trans('xe::cancel') }}</button>
                </div>
            </fieldset>
            </form>
        </div>
        <!-- search area -->

        <a href="{{ $urlHandler->get('create') }}"><i class="xi-pen-o"></i>{{ xe_trans('board::newPost') }}</a>
    </div>

    <div class="board_list">
        <table>
            <thead>
            <tr>
                @if(Input::has('favorite'))
                    <th scope="col"><a href="{{$urlHandler->get('index', Input::except(['favorite', 'page']))}}"><i class="xi-star-o on"></i><span class="xe-sr-only">{{ xe_trans('board::favorite') }}</span></a></th>
                @else
                    <th scope="col"><a href="{{$urlHandler->get('index', array_merge(Input::except('page'), ['favorite' => 1]))}}"><i class="xi-star-o"></i><span class="xe-sr-only">{{ xe_trans('board::favorite') }}</span></a></th>
                @endif
                @if ($config->get('category') == true)
                    <th scope="col">{{ xe_trans('board::category') }}</th>
                @endif
                <th scope="col">{{ xe_trans('board::title') }}</th>
                <th scope="col">{{ xe_trans('board::writer') }}</th>
                <th scope="col">{{ xe_trans('board::assentCount') }}</th>
                <th scope="col">{{ xe_trans('board::dissentCount') }}</th>
                <th scope="col">{{ xe_trans('board::readCount') }}</th>
                <th scope="col">{{ xe_trans('board::createdAt') }}</th>
                <th scope="col">{{ xe_trans('board::updatedAt') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($notices as $item)
                <tr>
                    <td><a href="#" data-url="{{$urlHandler->get('favorite', ['id' => $item->id])}}" class="@if($item->favorite !== null) on @endif __xe-bd-favorite"  title="{{xe_trans('board::favorite')}}"><i class="xi-star"></i><span class="xe-sr-only">{{xe_trans('board::favorite')}}</span></a></td>
                    @if ($config->get('category') == true)
                        <td>{!! $item->boardCategory !== null ? xe_trans($item->boardCategory->categoryItem->word) : '' !!}</td>
                    @endif
                    <td>
                        <a href="{{$urlHandler->getShow($item, Input::all())}}">{!! $item->title !!}</a>
                        @if($item->commentCount > 0)
                            <span><i class="xi-comment"></i>{{ $item->commentCount }}</span>
                        @endif
                        @if ($item->data->fileCount > 0)
                            <span ><i class="xi-clip"></i>file</span>
                        @endif
                        @if($item->isNew($config->get('newTime')))
                            <span ><i class="xi-new"></i>new</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->hasAuthor())
                            <a href="#" data-toggle="xeUserMenu" data-user-id="{{$item->getUserId()}}">{!! $item->writer !!}</a>
                        @else
                            <a >{!! $item->writer !!}</a>
                        @endif
                    </td>
                    <td>{{$item->assentCount}}</td>
                    <td>{{$item->dissentCount}}</td>
                    <td>{{$item->readCount}}</td>
                    <td data-xe-timeago="{{ $item->createdAt }}" title="{{$item->createdAt}}">{{$item->createdAt}}</td>
                    <td data-xe-timeago="{{ $item->updatedAt }}" title="{{$item->updatedAt}}">{{$item->updatedAt}}</td>
                </tr>
            @endforeach

            @if (count($paginate) == 0)
                <!-- NO ARTICLE -->
                <tr class="no_article">
                    <!-- [D] 컬럼수에 따라 colspan 적용 -->
                    <td @if ($config->get('category') == true) colspan="9" @else colspan="8" @endif >
                        <img src="/plugins/board/assets/img/img_pen.jpg" alt="">
                        <p>{{ xe_trans('xe::noPost') }}</p>
                    </td>
                </tr>
                <!-- / NO ARTICLE -->
            @endif

            @foreach($paginate as $item)
                <tr>
                    <td><a href="#" data-url="{{$urlHandler->get('favorite', ['id' => $item->id])}}" class="@if($item->favorite !== null) on @endif __xe-bd-favorite"  title="{{xe_trans('board::favorite')}}"><i class="xi-star"></i><span class="xe-sr-only">{{xe_trans('board::favorite')}}</span></a></td>
                    @if ($config->get('category') == true)
                        <td>{!! $item->boardCategory !== null ? xe_trans($item->boardCategory->categoryItem->word) : '' !!}</td>
                    @endif
                    <td>
                        <a href="{{$urlHandler->getShow($item, Input::all())}}">{!! $item->title !!}</a>
                        @if($item->commentCount > 0)
                            <span><i class="xi-comment"></i>{{ $item->commentCount }}</span>
                        @endif
                        @if ($item->data->fileCount > 0)
                            <span ><i class="xi-clip"></i>file</span>
                        @endif
                        @if($item->isNew($config->get('newTime')))
                            <span ><i class="xi-new"></i>new</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->hasAuthor())
                            <a href="#" data-toggle="xeUserMenu" data-user-id="{{$item->getUserId()}}">{!! $item->writer !!}</a>
                        @else
                            <a >{!! $item->writer !!}</a>
                        @endif
                    </td>
                    <td>{{$item->assentCount}}</td>
                    <td>{{$item->dissentCount}}</td>
                    <td>{{$item->readCount}}</td>
                    <td data-xe-timeago="{{ $item->createdAt }}" title="{{$item->createdAt}}">{{$item->createdAt}}</td>
                    <td data-xe-timeago="{{ $item->updatedAt }}" title="{{$item->updatedAt}}">{{$item->updatedAt}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="board_footer">
    {!! $paginate->render() !!}
</div>
