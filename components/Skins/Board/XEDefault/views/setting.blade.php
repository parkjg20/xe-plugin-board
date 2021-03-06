<div class="row">
    <div class="col-sm-12">
        <div class="panel">
            <div class="form-group">
                <div class="panel-heading">
                    <h4 class="panel-title">{{xe_trans('xe::list')}}</h4>
                </div>
    
                <div class="panel-body">
                    <div class="table-responsive item-setting">
                        <table class="table table-sortable">
                            <colgroup>
                                <col style="width: 200px">
                                <col>
                            </colgroup>
                            <tbody>
                                @foreach($config['listColumns'] as $columnName)
                                    <tr>
                                        <input type="hidden" name="listColumns[]" value="{{ $columnName }}">
                                        @if (isset($config['dynamicFields'][$columnName]) === false)
                                            <td>
                                                <button class="btn handler"><i class="xi-drag-vertical"></i></button>
                                                <em class="item-title">{{ xe_trans('board::' . $columnName) }}</em>
                                            </td>
                                            <td>
                                                <span class="item-subtext">{{ xe_trans('board::' . $columnName . 'Description') }}</span>
                                            </td>
                                        @else
                                            <td>
                                                <button class="btn handler"><i class="xi-drag-vertical"></i></button>
                                                <em class="item-title">{{ xe_trans($config['dynamicFields'][$columnName]->get('label')) }}</em>
                                            </td>
                                            <td>
                                                <span class="item-subtext"></span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">?????? ??????</h4>
            </div>
        
            <div class="panel-body">
                <div class="form-group">
                    <label>????????? ?????? <small> ????????? ?????? ???????????? ???????????????.</small></label>
                    <select class="form-control" name="titleStyle">
                        <option value="titleWithCount" @if (array_get($config, 'titleStyle', 'titleWithCount') === 'titleWithCount') selected @endif>????????? ?????? + ????????? ???</option>
                        <option value="title" @if (array_get($config, 'titleStyle', 'titleWithCount') === 'title') selected @endif>????????? ??????</option>
                        <option value="none" @if (array_get($config, 'titleStyle', 'titleWithCount') === 'none') selected @endif>????????????</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>?????? ??? ???<small> ?????? ??? ?????? ????????? ??? ??? ????????????.</small></label>
                    <select class="form-control" name="visibleIndexMyBoard">
                        <option value="show" @if (array_get($config, 'visibleIndexMyBoard', 'show') === 'show') selected @endif>??????</option>
                        <option value="hidden" @if (array_get($config, 'visibleIndexMyBoard', 'show') === 'hidden') selected @endif>????????????</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>????????? ??????<small> ???????????? ????????? ??? ?????? ???????????????.</small></label>
                    <select class="form-control" name="visibleIndexWriteButton">
                        <option value="always" @if (array_get($config, 'visibleIndexWriteButton', 'always') === 'always') selected @endif>????????????</option>
                        <option value="permission" @if (array_get($config, 'visibleIndexWriteButton', 'always') === 'permission') selected @endif>???????????? ??????</option>
                        <option value="hidden" @if (array_get($config, 'visibleIndexWriteButton', 'always') === 'hidden') selected @endif>????????????</option>
                    </select>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="visibleIndexMobileWriteButton" value="on" 
                                @if (array_get($config, 'visibleIndexMobileWriteButton', 'on') === 'on') checked @endif
                                @if (array_get($config, 'visibleIndexWriteButton', 'always') === 'hidden') disabled @endif>????????????????????? ???????????? ??????
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>?????? ?????????<small> ??? ??? ????????? ?????? ???????????? ????????? ?????? ???????????????.</small></label>
                    <select class="form-control" name="visibleIndexNewIcon">
                        <option value="show" @if (array_get($config, 'visibleIndexNewIcon', 'show') === 'show') selected @endif>??????</option>
                        <option value="hidden" @if (array_get($config, 'visibleIndexNewIcon', 'show') === 'hidden') selected @endif>????????????</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>?????? ?????? ??????<small> ????????? ?????? ????????? ????????? ??? ????????????.</small></label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="visibleIndexDefaultProfileImage" value="on" @if (array_get($config, 'visibleIndexDefaultProfileImage', 'on') === 'on') checked @endif>????????? ??????
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">???????????? ??????</h4>
            </div>
            
            <div class="panel-body">
                <div class="form-group">
                    <label>????????????</label>
                    <select class="form-control" name="visibleShowCategory">
                        <option value="show" @if (array_get($config, 'visibleShowCategory', 'show') === 'show') selected @endif>??????</option>
                        <option value="hidden" @if (array_get($config, 'visibleShowCategory', 'show') === 'hidden') selected @endif>????????????</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>?????? ?????? ??????<small> ????????? ?????? ????????? ????????? ??? ????????????.</small></label>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="visibleShowProfileImage" value="on" @if (array_get($config, 'visibleShowProfileImage', 'on') === 'on') checked @endif>????????? ??????
                        </label>
                        <label>
                            <input type="checkbox" name="visibleShowDisplayName" value="on" @if (array_get($config, 'visibleShowDisplayName', 'on') === 'on') checked @endif>?????????
                        </label>
                        <label>
                            <input type="checkbox" name="visibleShowReadCount" value="on" @if (array_get($config, 'visibleShowReadCount', 'on') === 'on') checked @endif>?????????
                        </label>
                        <label>
                            <input type="checkbox" name="visibleShowCreatedAt" value="on" @if (array_get($config, 'visibleShowCreatedAt', 'on') === 'on') checked @endif>?????????
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>????????????<small> ??????????????? ??? ????????? ????????? ??? ????????????.</small></label>
                    <select class="form-control" name="visibleShowShare">
                        <option value="show" @if (array_get($config, 'visibleShowShare', 'show') === 'show') selected @endif>??????</option>
                        <option value="hidden" @if (array_get($config, 'visibleShowShare', 'show') === 'hidden') selected @endif>????????????</option>
                    </select>
                </div>
        
                <div class="form-group">
                    <label>?????????<small> ?????? ??????????????? ??????????????? ??? ??? ????????????.</small></label>
                    <select class="form-control" name="visibleShowFavorite">
                        <option value="show" @if (array_get($config, 'visibleShowFavorite', 'show') === 'show') selected @endif>??????</option>
                        <option value="hidden" @if (array_get($config, 'visibleShowFavorite', 'show') === 'hidden') selected @endif>????????????</option>
                    </select>
                </div>
        
                <div class="form-group">
                    <label>????????? ?????????<small> ????????? ????????? ????????? ????????? ??? ????????????.</small></label>
                    <select class="form-control" name="visibleShowMoreBoardItems">
                        <option value="show" @if (array_get($config, 'visibleShowMoreBoardItems', 'show') === 'show') selected @endif>??????</option>
                        <option value="hidden" @if (array_get($config, 'visibleShowMoreBoardItems', 'show') === 'hidden') selected @endif>????????????</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('[name=visibleIndexWriteButton]').change(function () {
            if ($(this).val() === 'hidden') {
                $('[name=visibleIndexMobileWriteButton]').prop('checked', false)
                $('[name=visibleIndexMobileWriteButton]').prop('disabled', true)
            } else {
                $('[name=visibleIndexMobileWriteButton]').prop('disabled', false)
            }
        })
        
        $(".table-sortable tbody").sortable({
            handle: '.handler',
            cancel: '',
            update: function( event, ui ) {
            },
            start: function(e, ui) {
                ui.placeholder.height(ui.helper.outerHeight());
                ui.placeholder.css("display", "table-row");
                ui.helper.css("display", "table");
            },
            stop: function(e, ui) {
                $(ui.item.context).css("display", "table-row");
            }
        }).disableSelection();
    });
</script>

<style>
    .panel { box-shadow: none; }
    .panel .panel-heading { padding: 0; }
    .row:first-child .panel .panel-body { padding: 0; }
    .checkbox { margin-bottom: 0; }
    .panel .panel-heading .panel-title { font-size: 18px; }

    @media (min-width: 768px) {
        .xe-modal-dialog {
            width: 760px;
        }
    }
</style>
