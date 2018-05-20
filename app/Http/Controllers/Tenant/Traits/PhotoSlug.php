<?php

namespace App\Http\Controllers\Tenant\Traits;

use Storage;

/**
 * Trait PhotoSlug.
 *
 * @package App\Http\Controllers\Tenant\Traits
 */
trait PhotoSlug
{
    /**
     * Obtain photo by slug.
     *
     * @param $slug
     * @return mixed
     */
    protected function obtainPhotoBySlug($path, $slug)
    {
        $photos = get_photo_slugs_from_path($path);

        $found = $this->searchPhotoBySlug($slug, $photos);

        if ($found === false) abort('404',"No s'ha trobat cap foto amb l'slug: $slug");

        return $photos[$found]['file'];
    }

    /**
     * Search photo by slug.
     *
     * @param $slug
     * @param $photos
     * @return mixed
     */
    protected function searchPhotoBySlug($slug, $photos)
    {
        return $photos->search(function ($photo) use ($slug){
            return $photo['slug'] === $slug;
        });
    }

    /**
     * TODO
     *
     * Obtain photo by slug.
     *
     * @param $slug
     * @return mixed
     */
    protected function obtainPhotoPathBySlug($path, $slug)
    {
        $photos = get_photo_slugs_from_path($path);

        $found = $this->searchPhotoBySlug($slug, $photos);

        if ($found === false) abort('404',"No s'ha trobat cap foto amb l'slug: $slug");

        return $photos[$found]['file'];
    }
}