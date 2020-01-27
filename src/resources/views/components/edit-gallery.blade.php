@if (! empty($noCover))
    <gallery upload-url="{{ route('admin.vue.gallery.post', ['id' => $id, 'model' => $model]) }}"
             get-url="{{ route('admin.vue.gallery.get', ['id' => $id, 'model' => $model]) }}">
    </gallery>
@else
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <gallery upload-url="{{ route('admin.vue.gallery.post', ['id' => $id, 'model' => $model]) }}"
                         get-url="{{ route('admin.vue.gallery.get', ['id' => $id, 'model' => $model]) }}">
                </gallery>
            </div>
        </div>
    </div>
@endif