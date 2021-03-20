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

    public function indexLabel($indexLabel) {
        return $this->withMeta(['indexLabel' => $indexLabel]);
    }
}
