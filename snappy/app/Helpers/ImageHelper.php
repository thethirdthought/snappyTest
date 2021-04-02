<?php


namespace App\Helpers;


class ImageHelper
{
    const IMAGE_HANDLERS = [
        IMAGETYPE_JPEG => [
            'load' => 'imagecreatefromjpeg',
            'save' => 'imagejpeg',
            'quality' => 100
        ],
        IMAGETYPE_PNG => [
            'load' => 'imagecreatefrompng',
            'save' => 'imagepng',
            'quality' => 0
        ],

    ];

    /**
     * @param $src - a valid file location
     * @param $dest - a valid file target
     * @param $targetWidth - desired output width
     * @param $targetHeight - desired output height or null
     * @return mixed|null
     */
    public static function createThumbnail($src, $dest, $targetWidth, $targetHeight = null)
    {

        // 1. Load the image from the given $src
        // - see if the file actually exists
        // - check if it's of a valid image type
        // - load the image resource

        // get the type of the image
        // we need the type to determine the correct loader
        $type = exif_imagetype($src);

        // if no valid type or no handler found -> exit
        if (!$type || !self::IMAGE_HANDLERS[$type]) {
            return null;
        }

        // load the image with the correct loader
        $image = call_user_func(self::IMAGE_HANDLERS[$type]['load'], $src);

        // no image found at supplied location -> exit
        if (!$image) {
            return null;
        }


        // 2. Create a thumbnail and resize the loaded $image
        // - get the image dimensions
        // - define the output size appropriately
        // - create a thumbnail based on that size
        // - set alpha transparency for GIFs and PNGs
        // - draw the final thumbnail

        // get original image width and height
        $width = imagesx($image);
        $height = imagesy($image);

        // maintain aspect ratio when no height set
        if ($targetHeight == null) {

            // get width to height ratio
            $ratio = $width / $height;

            // if is portrait
            // use ratio to scale height to fit in square
            if ($width > $height) {
                $targetHeight = floor($targetWidth / $ratio);
            }
            // if is landscape
            // use ratio to scale width to fit in square
            else {
                $targetHeight = $targetWidth;
                $targetWidth = floor($targetWidth * $ratio);
            }
        }

        // create duplicate image based on calculated target size
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

        // set transparency options for GIFs and PNGs
        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

            // make image transparent
            imagecolortransparent(
                $thumbnail,
                imagecolorallocate($thumbnail, 0, 0, 0)
            );

            // additional settings for PNGs
            if ($type == IMAGETYPE_PNG) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
        }

        // copy entire source image to duplicate image and resize
        imagecopyresampled(
            $thumbnail,
            $image,
            0, 0, 0, 0,
            $targetWidth, $targetHeight,
            $width, $height
        );


        // 3. Save the $thumbnail to disk
        // - call the correct save method
        // - set the correct quality level

        // save the duplicate version of the image to disk
        return call_user_func(
            self::IMAGE_HANDLERS[$type]['save'],
            $thumbnail,
            $dest,
            self::IMAGE_HANDLERS[$type]['quality']
        );
    }

    /**
     * @param $file
     * @return array
     */
    public static function saveFullImage($file) : array
    {
        $response = [];
        try {
            if(!isset($file['image_full']['name'])) {
                throw new \Exception("No file uploaded");
            }
            $filename = $file['image_full']['name'];
            $timeStamp = microtime();
            $imageFileType = pathinfo($filename,PATHINFO_EXTENSION);
            $imageFileType = strtolower($imageFileType);
            $filename = str_replace($imageFileType,"",$filename) . $timeStamp;
            $filename = Self::clean($filename);
            $filename = $filename.".".$imageFileType;
            $location = WRITEPATH."/uploads/fullImage/".$filename;
            $valid_extensions = array("jpg","jpeg","png");
            if(!in_array(strtolower($imageFileType), $valid_extensions)) {
                throw new \Exception("Invalid file type");
            }
            if(in_array(strtolower($imageFileType), $valid_extensions)) {
                if(move_uploaded_file($file['image_full']['tmp_name'],$location)){
                    $response['location'] = $location;
                    $response['success'] = true;
                } else {
                    throw new \Exception("Unable to upload file");
                }
            }

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['error'] = $e->getMessage();

        }
        return $response;
    }

    public static function clean(string $string) : string
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}