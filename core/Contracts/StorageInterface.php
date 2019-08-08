<?php


namespace Core\Contracts;

/**
 * Interface StorageInterface
 * Like a KV storage
 * @package Core\Contracts
 */
interface StorageInterface
{
    public function info(string $identifier) :array;

    public function fileTime(string $identifier) :int;

    public function isExist(string $identifier) :bool;

    public function delete(string $identifier) :bool;

    public function touch(string $identifier) :bool;
}