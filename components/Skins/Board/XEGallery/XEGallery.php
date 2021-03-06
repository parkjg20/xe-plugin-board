<?php
/**
 * XEGallery
 *
 * PHP version 7
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Board
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */

namespace Xpressengine\Plugins\Board\Components\Skins\Board\XEGallery;

use Xpressengine\Config\ConfigEntity;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\Board\Components\Modules\BoardModule;
use Xpressengine\Plugins\Board\Components\Skins\Board\XEDefault\XEDefault;
use Xpressengine\Plugins\Board\Models\Board;
use Xpressengine\Plugins\Board\Models\BoardGalleryThumb;
use Xpressengine\Plugins\Board\Handler as BoardHandler;
use App;
use XeStorage;
use XeSkin;
use View;
use Event;
use Input;
use Xpressengine\Presenter\Presenter;
use Xpressengine\Routing\InstanceConfig;
use Xpressengine\Media\Repositories\ImageRepository;

/**
 * XEGallery
 *
 * @category    Board
 * @package     Xpressengine\Plugins\Board
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2019 Copyright XEHub Corp. <https://www.xehub.io>
 * @license     http://www.gnu.org/licenses/lgpl-3.0-standalone.html LGPL
 * @link        https://xpressengine.io
 */
class XEGallery extends XEDefault
{
    protected static $path = 'board/components/Skins/Board/XEGallery';

    /**
     * @var array
     */
    protected static $thumbSkins = [];

    public static function boot()
    {
        if (static::class == self::class) {
            static::interceptSetSkinTargetId();
        }
    }

    /**
     * render
     *
     * @return \Illuminate\Contracts\Support\Renderable|string
     */
    public function render()
    {
        $this->registerGetOrdersIntercept();

        if (in_array($this->view, ['index', 'show'])) {
            if (isset($this->data['paginate'])) {
                static::attachThumbnail($this->data['paginate']);
            }

            if (isset($this->data['notices'])) {
                static::attachThumbnail($this->data['notices']);
            }
        }

        return parent::render();
    }

    public function resolveSetting(array $inputs = [])
    {
        if (isset($inputs['visibleIndexWebzineProfileImage']) === false) {
            $inputs['visibleIndexWebzineProfileImage'] = '';
        }

        if (isset($inputs['visibleIndexWebzineDescription']) === false) {
            $inputs['visibleIndexWebzineDescription'] = '';
        }
        
        return parent::resolveSetting($inputs);
    }

    /**
     * register board handler intercept
     * intercept BoardHandler getOrder(), getsNotice()
     *
     * @return void
     */
    protected static function registerGetOrdersIntercept()
    {
        intercept(
            sprintf('%s@getOrders', BoardHandler::class),
            static::class.'-board-getOrders',
            function ($func) {
                $orders = $func();
                $orders[] = ['value' => 'exceptNotice', 'text' => 'board::exceptNotice'];
                return $orders;
            }
        );

        intercept(
            sprintf('%s@getsNotice', BoardHandler::class),
            static::class.'-board-getsNotice',
            function ($func, ConfigEntity $config, $userId) {
                $notice = $func($config, $userId);

                // ?????? ???????????? ?????? ?????? ??????
                if (Request::get('orderType') == 'exceptNotice') {
                    return [];
                }

                foreach ($notice as $item) {
                    $thumbItem = BoardGalleryThumb::find($item->id);
                    if ($thumbItem !== null) {
                        $item->board_thumbnail_file_id = $thumbItem->board_thumbnail_file_id;
                        $item->board_thumbnail_external_path = $thumbItem->board_thumbnail_external_path;
                        $item->board_thumbnail_path = $thumbItem->board_thumbnail_path;
                    }
                }

                static::attachThumbnail($notice);
                return $notice;
            }
        );
    }

    /**
     * set using thumbnail skin id
     *
     * @param string $skinId skin id
     * @return void
     */
    public static function addThumbSkin($skinId)
    {
        static::$thumbSkins[] = $skinId;
    }

    /**
     * get thumbnail skin ids
     *
     * @return array
     */
    public static function getThumbSkins()
    {
        return static::$thumbSkins;
    }

    /**
     * skin ????????? ??? thumbnail table ??? join ??? ??? ????????? intercept ??????
     *
     * @return void
     */
    protected static function interceptSetSkinTargetId()
    {
        intercept(
            sprintf('%s@setSkinTargetId', Presenter::class),
            'board_new_gallery_skin::set_skin_target_id',
            function ($func, $skinTargetId) {
                $func($skinTargetId);
                if (!$skinTargetId) {
                    return;
                }
                if ($skinTargetId != BoardModule::getId()) {
                    return;
                }

                $request = app('request');
                $instanceConfig = InstanceConfig::instance();

                if ($request instanceof Request) {
                    $isMobile = $request->isMobile();
                } else {
                    $isMobile = false;
                }
                $assignedSkin = XeSkin::getAssigned(
                    [$skinTargetId, $instanceConfig->getInstanceId()],
                    $isMobile ? 'mobile' : 'desktop'
                );

                // target ??? ????????? ?????? skin ??? ???????????? ??????????????? ??????
                if (in_array($assignedSkin->getId(), static::getThumbSkins())) {
                    // ????????? ????????? ??? gallery thumbnail ????????? ?????? table join ????????? ??????
                    Event::listen('xe.plugin.board.articles', function ($query) {
                        $query->leftJoin(
                            'board_gallery_thumbs',
                            sprintf('%s.%s', $query->getQuery()->from, 'id'),
                            '=',
                            sprintf('%s.%s', 'board_gallery_thumbs', 'target_id')
                        );
                    });
                }
            }
        );
    }

    /**
     * attach thumbnail for list
     *
     * @param array $list list of board model
     * @return void
     */
    public static function attachThumbnail($list)
    {
        foreach ($list as $item) {
            static::bindGalleryThumb($item);
        }
    }

    /**
     * bind gallery thumbnail
     *
     * @param Board $item board model
     * @return  void
     */
    protected static function bindGalleryThumb(Board $item)
    {
        /** @var \Xpressengine\Media\MediaManager $mediaManager */
        $mediaManager = App::make('xe.media');

        // board gallery thumbnails ??? ????????? ?????? ??????
        if ($item->thumb == null) {
            // find file by document id
            $files = XeStorage::fetchByFileable($item->id);
            $fileId = '';
            $externalPath = '';
            $thumbnailPath = '';

            if (count($files) == 0) {
                // find file by contents link or path
                $externalPath = static::getImagePathFromContent($item->content);

                // make thumbnail
                $thumbnailPath = $externalPath;
            } else {
                foreach ($files as $file) {
                    if ($mediaManager->is($file) !== true) {
                        continue;
                    }

                    /**
                     * set thumbnail size
                     */
                    $dimension = 'L';

                    $imageRepository = new ImageRepository();
                    $media = $imageRepository->getThumbnail(
                        $mediaManager->make($file),
                        BoardModule::THUMBNAIL_TYPE,
                        $dimension
                    );

                    if ($media === null) {
                        continue;
                    }

                    $fileId = $file->id;
                    $thumbnailPath = $media->url();
                    break;
                }
            }

            $item->board_thumbnail_file_id = $fileId;
            $item->board_thumbnail_external_path = $externalPath;
            $item->board_thumbnail_path = $thumbnailPath;
        } else {
            $item->board_thumbnail_file_id = $item->thumb->board_thumbnail_file_id;
            $item->board_thumbnail_external_path = $item->thumb->board_thumbnail_external_path;
            $item->board_thumbnail_path = $item->thumb->board_thumbnail_path;
        }

        // ?????? ?????? ????????? ????????? ????????? (????????? ???????????? ??? ??? ????????? ??????)
        if ($item->board_thumbnail_path == '') {
            $item->board_thumbnail_path = asset('assets/core/common/img/default_image_1200x800.jpg');
        }
    }

    /**
     * get path from content image tag source
     *
     * @param string $content document content
     * @return string
     */
    protected static function getImagePathFromContent($content)
    {
        $path = '';

        $pattern = '/<img[^>]*src="([^"]+)"[^>][^>]*>/';
        $matches = [];

        preg_match_all($pattern, $content, $matches);
        if (isset($matches[1][0])) {
            $path= $matches[1][0];
        }

        $fullUrl = $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $path = str_replace($fullUrl, '', $path);
        return $path;
    }
}
