<?php

Kirby::plugin('steirico/kirby-plugin-image-crop-field', [
    'fileMethods' => [
        'croppedMedia' => function() {
            return CroppedImage::croppedImage($this);
        },
    ],
    'fields' => [
        'imagecrop' => [
            'props' => [
                'image' => function($value = null) {
                    return $this->model()->url();
                },
                'value' => function($value = null){
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