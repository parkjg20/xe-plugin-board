{{ XeFrontend::css('plugins/board/components/board_skins/blog/assets/css/skin.css')->load() }}

{{ XeFrontend::js('plugins/board/assets/js/build/board.js')->appendTo('body')->load() }}

{{ XeFrontend::rule('board', $rules) }}

{{ XeFrontend::js('plugins/board/assets/js/build/BoardTags.js')->appendTo('body')->load() }}

<div class="board">
    <div class="board_write">
        <form method="post" class="__board_form" action="{{ $urlHandler->get('store') }}" enctype="multipart/form-data" data-rule="board" data-rule-alert-type="toast" data-instanceId="{{$instanceId}}" data-url-preview="{{ $urlHandler->get('preview') }}">
            <fieldset>
                <input type="hidden" name="_token" value="{{{ Session::token() }}}" />
                <input type="hidden" name="head" value="{{$head}}" />
                <input type="hidden" name="queryString" value="{{ http_build_query(Input::except('parentId')) }}" />

                <div class="write_header">
                    @if($config->get('category') == true)
                        <div>
                            <label>{{xe_trans('xe::category')}}</label>
                            <select name="categoryItemId">
                                <option value="">{{xe_trans('xe::select')}}</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category['value']}}" >{{xe_trans($category['text'])}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        {!! uio('titleWithSlug', [
                        'title' => Input::old('title'),
                        'slug' => Input::old('slug'),
                        'titleClassName' => 'bd_input',
                        'config' => $config
                        ]) !!}
                    </div>
                </div>

                <div class="write_body">
                    {!! editor($config->get('boardId'), [
                      'content' => Input::old('content'),
                    ]) !!}

                    @if($config->get('useTag') === true)
                        {!! uio('uiobject/board@tag') !!}
                    @endif
                </div>

                <div class="write_dynamicField">
                    @foreach ($configHandler->getDynamicFields($config) as $dynamicFieldConfig)
                        {!! XeDynamicField::getByConfig($dynamicFieldConfig)->getSkin()->create(Input::all()) !!}
                    @endforeach
                </div>

                <div class="write_footer">
                    @if (Auth::check() === false)
                        <div>
                            <input type="text" name="writer" placeholder="{{ xe_trans('xe::writer') }}" title="{{ xe_trans('xe::writer') }}" value="{{ Input::old('writer') }}">
                            <input type="password" name="certifyKey" placeholder="{{ xe_trans('xe::password') }}" title="{{ xe_trans('xe::password') }}">
                            <input type="email" name="email" placeholder="{{ xe_trans('xe::email') }}" title="{{ xe_trans('xe::email') }}" value="{{ Input::old('email') }}">
                        </div>
                    @endif

                    @if($config['useCaptcha'] === true)
                        <div class="write_form_input">
                            {!! uio('captcha') !!}
                        </div>
                    @endif

                    @if($config->get('comment') === true)
                        <div>
                            <label>
                                <input type="checkbox" name="allowComment" value="1" checked="checked">
                                <span>{{xe_trans('board::allowComment')}}</span>
                            </label>
                        </div>
                    @endif

                    @if (Auth::check() === true)
                        <div>
                            <label>
                                <input type="checkbox" name="useAlarm" value="1" @if($config->get('newCommentNotice') == true) checked="checked" @endif>
                                <span>{{xe_trans('board::useAlarm')}}</span>
                            </label>
                        </div>
                    @endif

                    <div>
                        <label>
                            <input type="checkbox" name="display" value="{{\Xpressengine\Document\Models\Document::DISPLAY_SECRET}}">
                            <span>{{xe_trans('board::secretPost')}}</span>
                        </label>
                    </div>

                    @if($isManager === true)
                        <div>
                            <label class="xe-label">
                                <input type="checkbox" name="status" value="{{\Xpressengine\Document\Models\Document::STATUS_NOTICE}}">
                                <span>{{xe_trans('xe::notice')}}</span>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="@if (Auth::check() === false) nologin @endif">
                    <button type="submit" class="__xe_btn_preview">{{ xe_trans('xe::preview') }}</button>
                    <button type="submit" class="__xe_btn_submit">{{ xe_trans('xe::submit') }}</button>
                </div>
            </fieldset>
        </form>
    </div>
</div>
