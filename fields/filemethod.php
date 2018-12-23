<?php

file::$methods['imageCrop'] = function($requestedFile) {
    $path = kirby()->roots()->thumbs() . DS . $requestedFile->page();
    $baseUrl = kirby()->urls()->thumbs() . '/' . $requestedFile->page();
    $file =  str::replace($requestedFile->safeName(), '.' . $requestedFile->extension(), '') . '-cropped.' . $requestedFile->extension();
    $filePath = $path . DS . $file;
    $fileUrl = $baseUrl . '/' . $file;
    $media = new CroppedMedia($filePath, $fileUrl, $requestedFile);
    if($media->exists()){
        return $media;
    } else {
        return $requestedFile;
    }
};

file::$methods['imageCropUrl'] = function($requestedFile) {
    return $requestedFile->imageCrop()->url();
};