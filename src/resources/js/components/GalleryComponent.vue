<template>
    <div class="row">
        <button class="btn btn-success position-fixed fixed-bottom mx-auto mb-3"
                v-if="weightChanged"
                @click="changeOrder"
                :class="weightChanged ? 'animated bounceIn' : ''">
            Сохранить порядок
        </button>

        <div class="col-12">
            <div class="row h-150">
                <div class="col-12">
                    <form>
                        <div class="form-group">
                            <div class="custom-file" id="galleryInputFile">
                                <input type="file"
                                       :disabled="loading"
                                       @change.prevent="getImage"
                                       class="custom-file-input"
                                       multiple
                                       id="custom-file-input">
                                <label class="drop-zone-label text-center border border-primary bg-light"
                                       for="custom-file-input">
                                    Нажмите или перетащите сюда файлы
                                </label>
                            </div>
                        </div>
                        <button class="btn btn-success"
                                type="button"
                                @click.prevent="send"
                                :disabled="loading || ! fileContents.length">
                            <span v-if="loading">Идет обработка запроса</span>
                            <span v-else>Загрузить</span>
                        </button>
                    </form>
                    <p :class="{ 'text-success': !error, 'text-danger': error}">{{ message }}</p>
                    <p v-for="item in errors" class="text-danger">{{ item }}</p>
                </div>

                <div class="col-6 col-md-2 mb-2" v-for="(item, name) in fileContents">
                    <button type="button"
                            v-if="! loading"
                            @click="fileContents.splice(name, 1)"
                            class="close"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="form-group">
                        <label :for="name" class="sr-only">Имя</label>
                        <input :id="name" class="form-control" type="text" v-model="item.name">
                    </div>

                    <img :src="item.content"
                         class="rounded preview-image m-auto d-block"
                         alt="Предпросмотр">
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Изображение</th>
                        <th>Имя</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <draggable :list="images" group="images" tag="tbody" handle=".handle" @change="checkMove">
                        <tr v-for="image in images" :key="image.id">
                            <th>
                                <i class="fa fa-align-justify handle cursor-move"></i>
                            </th>
                            <td>
                                <img class="rounded float-left" :src="image.src" :alt="image.id">
                            </td>
                            <td width="40%">
                                <div v-if="image.nameInput">
                                    <div class="input-group input-group">
                                        <input type="text"
                                               class="form-control"
                                               v-model="image.nameChanged">
                                        <div class="input-group-append">
                                            <button class="btn btn-danger"
                                                    @click="image.nameInput = false">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            <button class="btn btn-success"
                                                    @click="changeName(image)">
                                                <i class="far fa-check-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-outline-secondary" v-else @click="image.nameInput = true">
                                    {{ image.name }}
                                </button>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-danger"
                                            :disabled="loading"
                                            @click="deleteImage(image)">
                                        Удалить
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </draggable>
                </table>
            </div>
        </div>
    </div>
</template>

<style>
    .preview-image {
        width: 150px;
    }
    #galleryInputFile .drop-zone-label,
    #galleryInputFile .custom-file-input,
    #galleryInputFile {
        height: 10em;
        cursor: pointer;
    }
    #galleryInputFile .drop-zone-label {
        border-style: dashed !important;
        padding: 4em;
        position: absolute;
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
    }
</style>

<script>
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable,
        },
        props: ['getUrl', 'uploadUrl'],
        data: function() {
            return {
                fileContents: [],
                loading: false,
                message: '',
                errors: [],
                error: false,
                images: [],
                iteration: 0,
                weightChanged: false,
            }
        },

        computed: {
            orderData() {
                let ids = [];
                for (let item in this.images) {
                    if (this.images.hasOwnProperty(item)) {
                        ids.push(this.images[item].id);
                    }
                }
                return ids;
            }
        },

        methods: {
            // Восстановить переменные.
            reset() {
                this.loading = false;
                this.weightChanged = false;
                this.error = false;
                this.errors = [];
                this.iteration = 0;
            },
            // Показать кнопку если порядок изменен.
            checkMove() {
                this.weightChanged = true;
            },
            // Сохранить порядок.
            changeOrder() {
                this.loading = true;
                axios
                    .put(this.uploadUrl, {
                        images: this.orderData,
                    })
                    .then(response => {
                        let result = response.data;
                        if (result.success) {
                            this.images = result.images;
                        }
                        else {
                            this.errors.push(result.message);
                        }
                    })
                    .catch(error => {
                        let data = error.response.data;
                        if (data.errors.image.length) {
                            this.errors.push(data.errors.image[0]);
                        }
                    })
                    .finally(() => {
                        this.reset();
                    })
            },
            // Меняем имя.
            changeName (image) {
                image.nameInput = false;
                this.loading = true;
                this.message = "";
                let formData = new FormData();
                formData.append('changed', image.nameChanged);
                axios
                    .post(image.nameUrl, formData)
                    .then(response => {
                        let result = response.data;
                        if (result.success) {
                            this.images = result.images;
                            this.message = 'Имя изменено';
                        }
                        else {
                            this.message = result.message;
                        }
                    })
                    .finally(() => {
                        this.reset();
                    });
            },
            // Удаление изображения.
            deleteImage (image) {
                Swal.fire({
                    title: 'Вы уверены?',
                    text: "Изображение будет невозможно восстановить!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Да, удалить!',
                    cancelButtonText: "Отмена"
                }).then((result) => {
                    if (result.value) {
                        this.loading = true;
                        this.message = "";
                        axios
                            .delete(image.delete)
                            .then(response => {
                                this.error = false;
                                let result = response.data;
                                if (result.success) {
                                    this.images = result.images;
                                    this.message = 'Удалено';
                                }
                                else {
                                    this.message = result.message;
                                }
                            })
                            .finally(() => {
                                this.reset();
                            });
                    }
                });
            },
            // Отправляем на сервер.
            send () {
                this.message = "";
                this.errors = [];
                this.sendSingleFile();
            },
            // Отправка по одному файлу.
            sendSingleFile() {
                this.loading = true;
                let formData = new FormData();
                let file = this.fileContents[0].file;
                let name = this.fileContents[0].name;
                formData.append('image', file);
                formData.append("name", name);
                axios
                    .post(this.uploadUrl, formData, {
                        responseType: 'json'
                    })
                    .then(response => {
                        let result = response.data;
                        this.reset();
                        if (result.success) {
                            this.images = result.images;
                            this.fileContents.shift();
                            if (this.fileContents.length) {
                                this.loading = true;
                                this.sendSingleFile();
                            }
                        }
                        else {
                            this.errors.push(result.message);
                        }
                    })
                    .catch(error => {
                        let data = error.response.data;
                        this.reset();
                        if (data.errors.image.length) {
                            this.errors.push(data.errors.image[0]);
                        }
                    })
            },
            // Получаем выбранное изображение.
            getImage (event) {
                this.message = '';
                this.fileContents = [];
                for (let item in event.target.files) {
                    if (event.target.files.hasOwnProperty(item)) {
                        this.selectImage(event.target.files[item]);
                    }
                }
            },
            // Данные по изображению.
            selectImage (file) {
                let reader = new FileReader();
                reader.onload = (function (inputFile, contents) {
                    return function(event) {
                        let content = event.target.result;
                        let originalName = inputFile.name;
                        let exploded = originalName.split(".");
                        let name = originalName;
                        if (exploded.length > 1) {
                            name = exploded[0];
                        }
                        contents.push({
                            file: inputFile,
                            name: name,
                            content: content
                        })
                    };
                })(file, this.fileContents);
                reader.readAsDataURL(file);
            },
        },

        created() {
            axios.get(this.getUrl)
                .then(response => {
                    this.error = false;
                    let result = response.data;
                    if (result.success) {
                        this.images = result.images;
                    }
                    else {
                        this.message = result.message;
                    }
                })
        }
    }
</script>
