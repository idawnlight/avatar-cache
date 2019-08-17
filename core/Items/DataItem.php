<?php

namespace Core\Items;

use Core\Components\Helper;
use Core\Contracts\CacheAbstract;
use Core\Contracts\Responsible;

class DataItem extends CacheAbstract implements Responsible
{
    public $type = 'data';
    public $content;
    public $content_gz;
    public $mime;
    public $size;
    public $size_gz;
    public $last_modify;

    public function __construct($content = '', $expireAt = -1, $mime = 'image/jpeg', $last_modify = null) {
        $this->content = $content;
        $this->content_gz = Helper::gzencode($content);
        $this->expireAt = $expireAt;
        $this->mime = $mime;
        $this->size = strlen($content);
        $this->size_gz = strlen($this->content_gz);
        $this->last_modify = ($last_modify === null) ? time() : $last_modify;
    }
}