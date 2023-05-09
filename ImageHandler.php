<?php

class ImageHandler
{
    private $image;
    private $imageType;

    function load($filename) 
    {
        $imageInfo = getimagesize($filename);
        $this->imageType = $imageInfo[2];
        
        if ($this->imageType == IMAGETYPE_JPEG) 
        {
            $this->image = imagecreatefromjpeg($filename);
        } 
        elseif ($this->imageType == IMAGETYPE_GIF) 
        {
            $this->image = imagecreatefromgif($filename);
        } 
        elseif ($this->imageType == IMAGETYPE_PNG) 
        {
            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $imageType=IMAGETYPE_JPEG, $compression=75, $permissions=null) 
    {
        if ($imageType == IMAGETYPE_JPEG) 
        {
            imagejpeg($this->image, $filename, $compression);
        } 
        elseif ($imageType == IMAGETYPE_GIF) 
        {
            imagegif($this->image, $filename);
        } 
        elseif ($imageType == IMAGETYPE_PNG) 
        {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) 
        {
            chmod($filename, $permissions);
        }
    }

    function resizeToHeight($height) 
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) 
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) 
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getHeight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) 
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($newImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $newImage;
    }

    function convertToWebP($filename) 
    {
        imagewebp($this->image, $filename);
    }

    function getWidth() 
    {
        return imagesx($this->image);
    }

    function getHeight() 
    {
        return imagesy($this->image);
    }
}

$image = new ImageHandler();
$image->load('input.jpg');
$image->resizeToWidth(500);
$image->save('output.jpg', IMAGETYPE_JPEG, 60);
$image->convertToWebP('output.webp');
?>
