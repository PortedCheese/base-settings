<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Site;

use App\Image;
use Carbon\Carbon;
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
        $modelObject = $this->getModel($model, $id);
        if ($modelObject) {
            return [
                'success' => TRUE,
                'images' => $this->parseImages($modelObject, $model),
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
        if ($modelClass = $this->getModel($model, $id)) {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store("gallery/$model");
                $image = Image::create([
                    'path' => $path,
                    'name' => "$model-$id" . Carbon::now()->timestamp,
                ]);
                $modelClass->images()->save($image);
                event(new ImageUpdate($image));
                return [
                    'success' => TRUE,
                    'images' => $this->parseImages($modelClass, $model),
                ];
            }
            else {
                return [
                    'success' => FALSE,
                    'message' => 'File not found',
                ];
            }
        }
        else {
            return [
                'success' => FALSE,
                'message' => 'Model not found',
            ];
        }
    }

    /**
     * Пробуем удалить картинку.
     *
     * @param Request $request
     * @param $model
     * @param $id
     * @param $image
     * @return array
     */
    public function delete(Request $request, $model, $id, $image) {
        if ($modelClass = $this->getModel($model, $id)) {
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
                'images' => $this->parseImages($modelClass, $model),
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
        if (!$modelClass = $this->getModel($model, $id)) {
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
            'images' => $this->parseImages($modelClass, $model),
        ];
    }

    /**
     * Пробуем создать экземпляр класса модели.
     * @param $modelName
     * @param $id
     * @return bool
     */
    private function getModel($modelName, $id) {
        $model = FALSE;
        foreach (config('gallery.models') as $name => $class) {
            if (
                $name == $modelName &&
                class_exists($class)
            ) {
                try {
                    $model = $class::findOrFail($id);
                } catch (\Exception $e) {
                    return FALSE;
                }
                break;
            }
        }
        return $model;
    }

    /**
     * Подготавливаем картинки для vue.
     *
     * @param $model
     * @return array
     */
    private function parseImages($modelObject, $modelName) {
        $images = [];
        $collection = $modelObject->images->sortBy('id')->sortBy('weight');
        foreach ($collection as $image) {
            $images[] = [
                'src' => route('imagecache', [
                    'template' => 'small',
                    'filename' => $image->file_name,
                ]),
                'id' => $image->id,
                'weight' => $image->weight,
                'changed' => $image->weight,
                'delete' => route('admin.vue.gallery.delete', [
                    'model' => $modelName,
                    'id' => $modelObject->id,
                    'image' => $image->id,
                ]),
                'input' => FALSE,
                'weightUrl' => route('admin.vue.gallery.weight', [
                    'model' => $modelName,
                    'id' => $modelObject->id,
                    'image' => $image->id,
                ]),
            ];

        }
        return $images;
    }
}
