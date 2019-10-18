<?php

namespace PortedCheese\BaseSettings\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $modelName = "";
        foreach (config('gallery.models') as $name => $class) {
            if ($this->imageable_type == $class) {
                $modelName = $name;
                break;
            }
        }
        return [
            'src' => route('imagecache', [
                'template' => 'small',
                'filename' => $this->file_name,
            ]),
            'id' => $this->id,
            'weight' => $this->weight,

            'name' => $this->name,
            'nameChanged' => $this->name,
            'nameInput' => false,
            "nameUrl" => route("admin.vue.gallery.name", [
                "model" => $modelName,
                'id' => $this->imageable_id,
                'image' => $this->id,
            ]),

            'delete' => route('admin.vue.gallery.delete', [
                'model' => $modelName,
                'id' => $this->imageable_id,
                'image' => $this->id,
            ]),
        ];
    }
}
