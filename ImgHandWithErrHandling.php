<?php
class ImageHandler {
    private $image;
    private $imageType;

    function load($filename) {
        if (!file_exists($filename)) {
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

        if ($this->image === false) {
            throw new Exception('Image creation failed');
        }
    }

    function save($filename, $imageType = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {
        if ($imageType == IMAGETYPE_JPEG) {
            if (!imagejpeg($this->image, $filename, $compression)) {
                throw new Exception('Saving JPEG image failed');
            }
        } elseif ($imageType == IMAGETYPE_GIF) {
            if (!imagegif($this->image, $filename)) {
                throw new Exception('Saving GIF image failed');
            }
        } elseif ($imageType == IMAGETYPE_PNG) {
            if (!imagepng($this->image, $filename)) {
                throw new Exception('Saving PNG image failed');
            }
        }

        if ($permissions != null) {
            if (!chmod($filename, $permissions)) {
                throw new Exception('Setting image permissions failed');
            }
        }
    }

    function resizeToHeight($height) {
        if (!is_numeric($height) || $height <= 0) {
            throw new Exception('Invalid height value. It must be a positive numeric value.');
        }

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        if (!is_numeric($width) || $width <= 0) {
            throw new Exception('Invalid width value. It must be a positive numeric value.');
        }

        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        if (!is_numeric($scale) || $scale <= 0 || $scale > 100) {
            throw new Exception('Invalid scale value. It must be a positive numeric value between 1 and 100.');
        }

        $width = $this->getWidth() * $scale / 100;
        $height = $this->getHeight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) {
        if (!is_numeric($width) || $width <= 0 || !is_numeric($height) || $height <= 0) {
            throw new Exception('Invalid width or height value. They must be positive numeric values.');
        }

        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $newImage;
    }

    function getWidth() {
        $width = imagesx($this->image);
        if ($width === false) {
            throw new Exception('Failed to retrieve image width.');
        }
        return $width;
    }

    function getHeight() {
        $height = imagesy($this->image);
        if ($height === false) {
            throw new Exception('Failed to retrieve image height.');
        }
        return $height;
    }

    function convertToWebP($filename) {
        try {
            if (!imagewebp($this->image, $filename)) {
                throw new Exception('Failed to convert image to WebP');
            }
        } catch (Exception $e) {
            // Handle the exception as desired, for example:
            error_log('Error converting image to WebP: ' . $e->getMessage());
            // You can also re-throw the exception to let the calling code handle it:
            // throw $e;
        }
    }

}
