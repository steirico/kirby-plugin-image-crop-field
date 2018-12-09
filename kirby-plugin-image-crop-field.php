<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/fields/CropMedia.php';
require_once __DIR__ . '/fields/filemethod.php';

$kirby->set('field', 'imagecrop', __DIR__ . '/fields');
