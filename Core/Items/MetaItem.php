<?php

namespace Core\Items;

use Core\Contracts\CacheAbstract;

class MetaItem extends CacheAbstract
{
    public $type = 'meta';
    public $rawUrl = '';
    public $para = [];
    public $dataKey;

    public function __construct($dataKey, $rawUrl = '', $para = [], $expireAt = -1) {
        $this->rawUrl = $rawUrl;
        $this->para = $para;
        $this->expireAt = $expireAt;
        $this->dataKey = $dataKey;
    }

    /**
     * @return string
     */
    public function getDataKey() {
        return $this->dataKey;
    }
}