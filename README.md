# PHP ImageHandler

PHP ImageHandler is a class that provides a simple way to handle images in PHP. It includes functionality for loading images, saving images, resizing images based on height, width, or scale, and converting images to different formats.

## Features

- Load JPEG, GIF, and PNG images
- Save images with optional compression level and permissions
- Resize images to a specific height or width, maintaining aspect ratio
- Scale images by a percentage
- Convert images to the WebP format

## Usage

```php
$image = new ImageHandler();
$image->load('input.jpg');      // Load an image file
$image->resizeToWidth(500);     // Resize the image to a width of 500px
$image->save('output.jpg', IMAGETYPE_JPEG, 60);  // Save the image as a JPEG with a quality of 60
$image->convertToWebP('output.webp');   // Convert the image to WebP format
```

### Load an image

```php
$image->load('input.jpg');
```

### Save an image

```php
$image->save('output.jpg', IMAGETYPE_JPEG, 60);
```

### Resize an image

To resize by height:

```php
$image->resizeToHeight(500);
```

To resize by width:

```php
$image->resizeToWidth(500);
```

To scale an image:

```php
$image->scale(50);  // scales the image to 50%
```

### Convert an image to WebP

```php
$image->convertToWebP('output.webp');
```

### Handling Errors

The `ImageHandler` class includes error handling to throw exceptions if an invalid parameter is passed or if an operation fails. You can catch these exceptions using a `try`/`catch` block:

```php
try {
    // Perform an image operation
} catch (Exception $e) {
    // Handle the exception
}
```

## Installation

No installation is necessary, just include the `ImageHandler.php` file in your PHP script.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

[MIT](https://choosealicense.com/licenses/mit/)
