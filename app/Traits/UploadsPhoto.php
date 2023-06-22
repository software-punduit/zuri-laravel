<?php
namespace App\Traits;

trait UploadsPhoto
{
    function uploadPhoto($request, $field, $model, $collection) {
        if ($request->has($field)) {
            // If there is a valid file upload called
            // $field
            if ($request->file($field)->isValid()) {
                // 1. Install media-library package
                // 2. Setup the user model to use the library
                $disk = config('filesystems.default');
                $path = $request->file($field)->store('', $disk);

                $model->addMediaFromDisk($path, $disk)
                    ->toMediaCollection($collection);
            }
        }

        
    }
}
