<?php

namespace App\Services\MediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

class CustomPathGenerator extends DefaultPathGenerator
{
    /*
     * Get the path for the given media, relative to the root storage path.
     */
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media).'/';
    }

    /*
    * Get the path for conversions of the given media, relative to the root storage path.
    */
    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media).'/conversions/';
    }

    /*
     * Get the path for responsive images of the given media, relative to the root storage path.
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media).'/responsive-images/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $prefix = config('media-library.prefix', '');

        if($media->collection_name === 'scan') {
            $prefix = 'mails';
        }

        if($media->collection_name === 'envelopes') {
            $prefix = 'envelopes';
        }

        if ($prefix !== '') {
            return $prefix . '/' . $media->getKey();
        }

        return $media->getKey();
    }
}
