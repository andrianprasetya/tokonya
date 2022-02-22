<?php

/**
 * Copyright 2021 Odenktools Technology Open Source Project
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge
 * publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */

namespace App\Libraries;

use App\Models\FileModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as ImgMap;
use Ramsey\Uuid\Uuid;

/**
 * File library, we can easy switch local or aws filesystem.
 *
 * Class FilesLibrary.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2021, Odenktools Technology.
 *
 * @package App\Libraries
 */
class FilesLibrary
{
    private $rootDir = 'public/';

    /**
     * Untuk simpan images.
     *
     * @param $image
     * @param $dir
     * @param $moduleType
     * @param $standardWidth
     * @param $standardHeight
     * @param $autoRotate
     * @return mixed|string
     * @throws \Exception
     */
    public function saveImage(
        $image,
        $dir,
        $autoRotate = false,
        $standardWidth = 750,
        $standardHeight = 410,
        $moduleType = 'user',
        $resizePhoto = false
    ) {
        $id = Uuid::uuid4()->toString();

        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        if ($resizePhoto) {
            $resize = $this->resize($image, $standardWidth, $standardHeight, $autoRotate);
            Storage::disk()->put($full, $resize->encode());
        } else {
            Storage::disk()->put($full, file_get_contents($image));
        }
        $modelImage = new FileModel();
        $modelImage->id = $id;
        $modelImage->module_type = $moduleType;
        $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        $modelImage->path = $this->rootDir . $dir;
        $modelImage->file_url = $full;
        $modelImage->created_at = Carbon::now();
        $modelImage->save();
        $imageId = $modelImage->id;

        return $imageId;
    }

    public function saveImageAspectRatio($image, $dir, $moduleType = 'user', $sizeWidthHeight = 700)
    {
        $id = Uuid::uuid4()->toString();
        $resize = $this->resizeAspectRatio($image, $sizeWidthHeight);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        $modelImage = new FileModel();
        $modelImage->id = $id;
        $modelImage->module_type = $moduleType;
        $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        $modelImage->path = $this->rootDir . $dir;
        $modelImage->file_url = $full;
        $modelImage->created_at = Carbon::now();
        $modelImage->save();
        $imageId = $modelImage->id;

        return $imageId;
    }

    /**
     * Untuk simpan photo profile.
     *
     * @param $image
     * @param $dir
     * @param $moduleType
     * @param $resize
     * @return mixed|string
     * @throws \Exception
     */
    public function saveUserImage($image, $dir, $moduleType = 'user')
    {
        $modelId = auth()->user()->id;
        $id = Uuid::uuid4()->toString();
        $resize = $this->resize($image, 128, 128, false);
        $ext = $image->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, $resize->encode());
        $modelImage = new FileModel();
        $modelImage->id = $id;
        $modelImage->module_type = $moduleType;
        $modelImage->extension = Str::slug($image->getClientOriginalExtension());
        $modelImage->path = $this->rootDir . $dir;
        $modelImage->file_url = $full;
        $modelImage->created_at = Carbon::now();
        $modelImage->save();
        $imageId = $modelImage->id;

        return $imageId;
    }

    /**
     * Simpan data selain file images.
     *
     * @param $file
     * @param $dir
     * @return array
     * @throws \Exception
     */
    public function saveFileAttachment($file, $dir)
    {
        $id = Uuid::uuid4()->toString();
        $ext = $file->getClientOriginalExtension();
        $full = $this->rootDir . $dir . '/' . $id . '.' . $ext;
        Storage::disk()->put($full, file_get_contents($file));
        $modelImage = new FileModel();
        $modelImage->id = $id;
        $modelImage->module_type = 'attachment';
        $modelImage->extension = Str::slug($ext);
        $modelImage->file_url = $full;
        $modelImage->path = $this->rootDir . $dir;
        $modelImage->created_at = Carbon::now();
        $modelImage->save();

        //Return save id;
        $imageId = $modelImage->id;

        return [
            'id' => $imageId,
            'type' => Str::slug($file->getClientOriginalExtension()),
        ];
    }

    /**
     * @param $dbPath
     * @return mixed
     */
    public static function getAttachment($dbPath)
    {
        return Storage::disk()->url($dbPath);
    }

    public static function delete(FileModel $model)
    {
        Storage::disk()->delete($model->file_url);
    }

    private function resizeAspectRatio($rawImage, $sizeWidthHeight = 700)
    {
        $image = ImgMap::make($rawImage);
        $width = $image->getWidth();
        $height = $image->getHeight();
        if ($width > $height) {
            // landscape
            if ($width > $sizeWidthHeight) {
                $image = $image->resize($sizeWidthHeight, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        } else {
            // portrait
            if ($height > $sizeWidthHeight) {
                $image = $image->resize(null, $sizeWidthHeight, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        return $image;
    }

    public function resize($raw, $standardWidth = 750, $standardHeight = 410, $autoRotate = true)
    {
        $standard = 750;
        $image = ImgMap::make($raw);
        if (!$autoRotate) {
            $image = $image->resize($standardWidth, $standardHeight);
            return $image;
        }
        $width = $image->getWidth();
        $height = $image->getHeight();
        if ($width > $height) {
            // landscape
            if ($width > $standard) {
                $image = $image->resize($standard, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        } else {
            // portrait
            if ($height > $standard) {
                $image = $image->resize(null, $standard, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        return $image;
    }

    /**
     * Converts bytes into human readable file size.
     *
     * @param string $bytes
     * @return string human readable file size (2,87 Мб)
     * @author Mogilev Arseny
     */
    public static function fileSizeConvert($bytes)
    {
        $bytes = floatval($bytes);
        $arBytes = [
            0 => [
                'UNIT' => 'TB',
                'VALUE' => pow(1024, 4),
            ],
            1 => [
                'UNIT' => 'GB',
                'VALUE' => pow(1024, 3),
            ],
            2 => [
                'UNIT' => 'MB',
                'VALUE' => pow(1024, 2),
            ],
            3 => [
                'UNIT' => 'KB',
                'VALUE' => 1024,
            ],
            4 => [
                'UNIT' => 'B',
                'VALUE' => 1,
            ],
        ];

        foreach ($arBytes as $arItem) {
            if ($bytes >= $arItem['VALUE']) {
                $result = $bytes / $arItem['VALUE'];
                $result = round($result, 2) . $arItem['UNIT'];
                break;
            }
        }

        return $result;
    }
}
