<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Site;

use App\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PortedCheese\BaseSettings\Events\ImageUpdate;
use PortedCheese\BaseSettings\Http\Requests\ImagePostRequest;

class ImageController extends Controller
{

    /**
     * Получаем все изображения модели.
     * @param $model
     * @param $id
     * @return array
     */
    public function get($model, $id) {
        $modelObject = Image::getGalleryModel($model, $id);
        if ($modelObject) {
            return [
                'success' => TRUE,
                'images' => Image::prepareImage($modelObject),
            ];
        }
        else {
            return [
                'success' => FALSE,
                'message' => 'Model not found',
            ];
        }
    }

    /**
     * Пробуем сохранить изображение.
     *
     * @param Request $request
     * @param $model
     * @param $id
     * @return array
     */
    public function post(ImagePostRequest $request, $model, $id) {
        $modelClass = Image::getGalleryModel($model, $id);
        if (! $modelClass) {
            return [
                'success' => FALSE,
                'message' => 'Model not found',
            ];
        }

        if (! $request->hasFile('image')) {
            return [
                'success' => FALSE,
                'message' => 'File not found',
            ];
        }

        $path = $request->file('image')->store("gallery/$model");
        $image = Image::create([
            'path' => $path,
            'name' => $request->get("name", "$model-$id"),
        ]);
        $modelClass->images()->save($image);
        $image->setMax();
        event(new ImageUpdate($image));
        return [
            'success' => TRUE,
            'images' => Image::prepareImage($modelClass),
        ];
    }

    /**
     * Пробуем удалить картинку.
     *
     * @param $model
     * @param $id
     * @param $image
     * @return array
     */
    public function delete($model, $id, $image) {
        if ($modelClass = Image::getGalleryModel($model, $id)) {
            try {
                $imageObject = Image::findOrFail($image);
            } catch (\Exception $e) {
                return [
                    'success' => FALSE,
                    'message' => 'Image not found',
                ];
            }
            $imageObject->delete();
            return [
                'success' => TRUE,
                'images' => Image::prepareImage($modelClass),
            ];
        }
        else {
            return [
                'success' => FALSE,
                'message' => 'Model not found',
            ];
        }
    }

    /**
     * Пробуем изменить вес.
     *
     * @param Request $request
     * @param $model
     * @param $id
     * @param $image
     * @return array
     */
    public function weight(Request $request, $model, $id, $image) {
        if (!($request->has('changed') && is_numeric($request->get('changed')))) {
            return [
                'success' => FALSE,
                'message' => "Вес не найден",
            ];
        }
        if (!$modelClass = Image::getGalleryModel($model, $id)) {
            return [
                'success' => FALSE,
                'message' => 'Model not found',
            ];
        }
        try {
            $imageObject = Image::findOrFail($image);
        } catch (\Exception $e) {
            return [
                'success' => FALSE,
                'message' => 'Image not found',
            ];
        }
        $imageObject->weight = $request->get('changed');
        $imageObject->save();
        return [
            'success' => TRUE,
            'images' => Image::prepareImage($modelClass),
        ];
    }
}
