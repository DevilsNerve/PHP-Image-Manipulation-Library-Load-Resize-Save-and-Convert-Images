<?php
class ImageHandler
{
    private $image;
    private $imageType;

    function load($filename) 
    {
        if(!file_exists($filename)) {
            throw new Exception('File not found: ' . $filename);
        }

        $imageInfo = getimagesize($filename);
        if ($imageInfo === false) {
            throw new Exception('Could not determine image size');
        }

        $this->imageType = $imageInfo[2];

        if ($this->imageType == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->imageType == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->imageType == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        } else {
            throw new Exception('Unsupported image type');
        }

        if($this->image === false) {
            throw new Exception('Image creation failed');
        }
    }

    function save($filename, $imageType = IMAGETYPE_JPEG, $compression = 75, $permissions = null) 
    {
        if ($imageType == IMAGETYPE_JPEG) {
            if(!imagejpeg($this->image, $filename, $compression)) {
                throw new Exception('Saving JPEG image failed');
            }
        } elseif ($imageType == IMAGETYPE_GIF) {
            if(!imagegif($this->image, $filename)) {
                throw new Exception('Saving GIF image failed');
            }
        } elseif ($imageType == IMAGETYPE_PNG) {
            if(!imagepng($this->image, $filename)) {
                throw new Exception('Saving PNG image failed');
            }
        }

        if ($permissions != null) {
            if(!chmod($filename, $permissions)) {
                throw new Exception('Setting image permissions failed');
            }
        }
    }

    // More functions...

    function resize($width, $height) 
    {
        $newImage = imagecreatetruecolor($width, $height);
        if($newImage === false) {
            throw new Exception('Image resizing failed');
        }
        if(!imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight())) {
            throw new Exception('Image copy and resize failed');
        }
        $this->image = $newImage;
    }

    function convertToWebP($filename) 
    {
        if(!imagewebp($this->image, $filename)) {
            throw new Exception('Image conversion to WebP failed');
        }
    }

    // More functions...
}
