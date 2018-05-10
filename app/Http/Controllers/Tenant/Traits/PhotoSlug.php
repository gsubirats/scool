<?php

namespace App\Http\Controllers\Tenant\Traits;

use File;
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
    protected function obtainPhotoBySlug($slug)
    {
        $photos = collect(File::allFiles(Storage::disk('local')->path('teacher_photos')))->map(function ($photo) {
            return [
                'file' => $photo,
                'filename' => $filename = $photo->getFilename(),
                'slug' => str_slug($filename,'-')
            ];
        });

        $found = $photos->search(function ($photo) use ($slug){
            return $photo['slug'] ===  $slug;
        });

        if ($found === false) abort('404',"No s'ha trobat cap foto amb l'slug: $slug");

        return $photos[$found]['file'];
    }
}