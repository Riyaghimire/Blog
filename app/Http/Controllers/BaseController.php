<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

// use Intervention\Image\File;

class BaseController extends Controller
{
    protected $formats = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

    protected function commonData($view_path)
    {
        View::composer($view_path, function ($view) {
            $view->with('loggedInUser', auth()->user());
            $view->with('_folder', property_exists($this, 'folder') ? $this->folder : '');
        });

        return $view_path;
    }

    public function processImage($file, $setFile = null, $dimensions = null)
    {
        ini_set('memory_limit', '256M');
        $file_name = rand(0, 9999).'_'.$file->getClientOriginalName();

        //check for folder
        if (!file_exists(public_path($this->folder_path))) {
            mkdir(public_path($this->folder_path));
        }
        //checking done

        //remove old Image or File
        if (substr($file->getMimeType(), 0, 5) == 'image') {
            if (isset($setFile)) {
                $this->deleteImage($setFile, $dimensions);
            }
        } elseif (substr($file->getMimeType(), 0, 11) == 'application') {
            if (isset($setFile)) {
                $this->deleteFile($setFile);
            }
            $file->move(public_path($this->folder_path), $file_name);

            return $file_name;
        }
        //remove old Image or File Done

        $file->move(public_path($this->folder_path), $file_name);

        // convert To WebP
        $webp = public_path($this->folder_path).DIRECTORY_SEPARATOR.$file_name;

        // image created from string
        $im = imagecreatefromstring(file_get_contents($webp));

        // webp extension saved for full path
        $new_webp = preg_replace('"\.(jpg|jpeg|png|svg|webp)$"', '.webp', $webp);

        // webp extension saved for the image
        $file_name_webp = preg_replace('"\.(jpg|jpeg|png|svg|webp)$"', '.webp', $file_name);

        imagewebp($im, $new_webp, 75);
        // Convert to webp complete

        if ($dimensions) {
            $image_thumb_config = $dimensions;

            foreach ($image_thumb_config as $thumb) {
                $originalImage = Image::make(public_path($this->folder_path).DIRECTORY_SEPARATOR.$file_name);
                $originalImage->resize($thumb['width'], $thumb['height']);
                $originalImage->save(public_path($this->folder_path).DIRECTORY_SEPARATOR.$thumb['width'].'_'.$thumb['height'].'_'.$file_name);

                $webpImage = Image::make(public_path($this->folder_path).DIRECTORY_SEPARATOR.$file_name_webp);
                $webpImage->resize($thumb['width'], $thumb['height']);
                $webpImage->save(public_path($this->folder_path).DIRECTORY_SEPARATOR.$thumb['width'].'_'.$thumb['height'].'_'.$file_name_webp);
            }
        }

        return $file_name_webp;
    }

    public function deleteImage($setFile, $dimensions = null)
    {
        $setFile = chop($setFile->featured, 'webp');
        foreach ($this->formats as $key => $value) {
            $images[] = $setFile.$value;
        }

        foreach ($images as $key => $setFile) {
            if (isset($setFile)) {
                if (file_exists(public_path().DIRECTORY_SEPARATOR.$this->folder_path.DIRECTORY_SEPARATOR.$setFile)) {
                    unlink(public_path().DIRECTORY_SEPARATOR.$this->folder_path.DIRECTORY_SEPARATOR.$setFile);
                }

                if (isset($dimensions)) {
                    $image_thumb_config = $dimensions;

                    foreach ($image_thumb_config as $key => $thumb) {
                        if (file_exists(public_path().DIRECTORY_SEPARATOR.$this->folder_path.DIRECTORY_SEPARATOR.$thumb['width'].'_'.$thumb['height'].'_'.$setFile)) {
                            unlink(public_path().DIRECTORY_SEPARATOR.$this->folder_path.DIRECTORY_SEPARATOR.$thumb['width'].'_'.$thumb['height'].'_'.$setFile);
                        }
                    }
                }
            }
        }
    }

    public function deleteFile($setModel = null)
    {
        if (isset($setModel)) {
            if ($setModel->file != null and file_exists(public_path().DIRECTORY_SEPARATOR.$this->folder_path.DIRECTORY_SEPARATOR.$setModel->file)) {
                unlink(public_path().DIRECTORY_SEPARATOR.$this->folder_path.DIRECTORY_SEPARATOR.$setModel->file);
            }
        }
    }

    public function checkForDefaults($array)
    {
        foreach ($array as $key => $value) {
            if ($value == null) {
                unset($array[$key]);
            }
        }

        return $array;
    }
}


