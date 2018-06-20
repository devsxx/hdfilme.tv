<?php namespace Anhi\Movie\Models;

use Model, DB;

/**
 * Model
 */
class File extends \System\Models\File
{

    protected $appends = ['path', 'extension', 'thumb'];

    function getPartitionDirectory ()
    {
        return '';
    }

    public function getPath()
    {
        return $this->getPublicPath(). $this->disk_name;
    }

    function getPublicPath ()
    {
    	return config('upload.cdn_img');
    }

    protected function getThumbFilename($width, $height, $options)
    {
        $names = explode('.', $this->disk_name);

        return "{$names[0]}_thumb.{$names[1]}";
    }

    function getThumbAttribute()
    {
        $names = explode('.', $this->disk_name);

        if (count($names) == 2)
           return $this->getPublicPath() . "{$names[0]}_thumb.{$names[1]}";

       return '';
    }

    public function getStorageDirectory()
    {
        $path = config('upload.path');

        return empty($path) ? parent::getStorageDirectory() : ($path . '/');
    }

    /**
     * Generate the thumbnail based on a remote storage engine.
     */
    protected function makeThumbStorage($thumbFile, $thumbPath, $width, $height, $options)
    {
        $height = '185';
        $width = '135';

        parent::makeThumbStorage($thumbFile, $thumbPath, $width, $height, $options);

    }
}