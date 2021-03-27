<?php

namespace Norgeit\VideoPlay;

use Laravel\Nova\Fields\Field;

class VideoPlay extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'video-play';

    /**
     * 视频显示名称
     *
     * @param string $indexLabel
     * @return array
     */
    public function indexLabel($indexLabel) {
        return $this->withMeta(['indexLabel' => $indexLabel]);
    }


    /**
     * 直播保活
     *
     * @param string $keepAlive - 保活链接
     * @return array
     */
    public function keepAlive($keepAlive) {
        return $this->withMeta(['keepAlive' => $keepAlive]);
    }

    public function deviceVideoLink($deviceVideoLink) {
        return $this->withMeta(['deviceVideoLink' => $deviceVideoLink]);
    }

}
