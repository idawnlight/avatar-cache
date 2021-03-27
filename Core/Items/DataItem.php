<?php

namespace Core\Items;

use Core\Components\Helper;
use Core\Contracts\CacheAbstract;
use Core\Contracts\Responsible;

class DataItem extends CacheAbstract implements Responsible
{
    public $type = 'data';
    public $content;
    public $mime;
    public $size;
    public $last_modify;

    public function __construct($content = '', $expireAt = -1, $mime = 'image/jpeg', $last_modify = null) {
        $this->content = $content;
        $this->expireAt = $expireAt;
        $this->mime = $mime;
        $this->size = strlen($content);
        $this->last_modify = ($last_modify === null) ? time() : $last_modify;
    }
}