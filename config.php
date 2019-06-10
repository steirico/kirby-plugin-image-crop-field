<?php

Kirby::plugin('steirico/kirby-plugin-image-crop-field', [
    'fileMethods' => [
        'croppedImage' => function() {
            return CroppedImage::croppedImage($this);
        },
    ],
    'fields' => [
        'imagecrop' => [
            'props' => [
                'image' => function() {
                    return $this->model()->url();
                },

                'minSize' => function(array $minSize = []) {
                    $width = max(A::get($minSize, 'width', 1), 1);
                    $height = max(A::get($minSize, 'height', 1), 1);
                    return array(
                        'width' => $width,
                        'height' => $height
                    ); 
                },

                'targetSize' => function(array $targetSize = []) {
                    return $targetSize; 
                },

                'preserveAspectRatio' => function(bool $preserveAspectRatio = false){
                    return $preserveAspectRatio;
                },

                'value' => function($value = []){
                    $method = kirby()->request()->method();
                    if(($method == "PATCH") || ($method == "POST")) {
                        new CroppedImage($this->model());
                    }

                    if(is_array($value)){
                        return $value;
                    } else {
                        return Data::decode($value, 'yaml');
                    }
                }
            ]
        ]
    ]
]);