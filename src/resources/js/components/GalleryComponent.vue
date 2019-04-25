<template>
    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                <th>Изображение</th>
                <th>Вес</th>
                <th>Действия</th>
                </thead>
                <tbody>
                <tr v-for="image in images">
                    <td>
                        <img class="rounded float-left" :src="image.src" :alt="image.id">
                    </td>
                    <td width="20%">
                        <div v-if="image.input">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend" id="button-addon">
                                    <button class="btn btn-danger"
                                            @click="image.input = false">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    <button class="btn btn-success"
                                            @click="changeWeight(image)">
                                        <i class="far fa-check-circle"></i>
                                    </button>
                                </div>
                                <input type="text"
                                       class="form-control"
                                       v-model="image.changed" aria-describedby="button-addon">
                            </div>
                        </div>
                        <p v-else @click="image.input = true">
                            {{ image.weight }}
                        </p>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button class="btn btn-primary"
                                    v-if="image.weight > 1"
                                    :disabled="loading"
                                    @click="upWeight(image)">
                                <i class="fas fa-level-up-alt"></i>
                            </button>
                            <button class="btn btn-danger"
                                    :disabled="loading"
                                    @click="deleteImage(image)">
                                Удалить
                            </button>
                            <button class="btn btn-primary"
                                    :disabled="loading"
                                    @click="downWeight(image)">
                                <i class="fas fa-level-down-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <div class="row h-150">
                <div class="col">
                    <form v-if="!loading">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                        <span class="input-group-text"
                              id="inputGroupAvatar">
                            Файл
                        </span>
                                </div>
                                <div class="custom-file">
                                    <input type="file"
                                           @change.prevent="getImage"
                                           class="custom-file-input"
                                           id="custom-file-input"
                                           lang="ru"
                                           name="avatar"
                                           aria-describedby="inputGroupAvatar">
                                    <label class="custom-file-label"
                                           for="custom-file-input">
                                        Выберите файл
                                    </label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-success"
                                type="button"
                                @click.prevent="send"
                                :disabled="loading"
                                v-if="content">
                            Загрузить
                        </button>
                    </form>
                    <p class="text-info" v-else>Идет обработка запроса</p>
                    <p :class="{ 'text-success': !error, 'text-danger': error}">{{ message }}</p>
                </div>
                <div class="col-3">
                    <img v-if="content"
                         :src="content"
                         class="rounded float-left preview-image"
                         alt="Предпросмотр">
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .h-150 {
        min-height: 150px;
    }
    .preview-image {
        width: 150px;
    }
</style>

<script>
    export default {
        props: ['getUrl', 'uploadUrl', 'csrfToken'],
        data: function() {
            return {
                uploadFile: false,
                content: false,
                loading: false,
                message: '',
                error: false,
                images: []
            }
        },

        methods: {
            downWeight (image) {
                image.changed++;
                this.changeWeight(image);
            },
            upWeight (image) {
                image.changed--;
                this.changeWeight(image);
            },
            // Меняем на введенный вес.
            changeWeight (image) {
                image.input = false;
                this.loading = true;
                this.message = "";
                let formData = new FormData();
                formData.append('changed', image.changed);
                formData.append('weight', image.weight);
                axios
                    .post(image.weightUrl, formData, {
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(response => {
                        this.error = false;
                        let result = response.data;
                        if (result.success) {
                            this.images = result.images;
                            this.message = 'Вес изменен';
                        }
                        else {
                            this.message = result.message;
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    });
            },

            // Удаление изображения.
            deleteImage (image) {
                this.loading = true;
                this.message = "";
                axios
                    .delete(image.delete, {
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        }
                    })
                    .then(response => {
                        this.error = false;
                        this.loading = false;
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
                        this.loading = false;
                    });
            },

            // Отправляем на сервер.
            send () {
                this.loading = true;
                this.message = "";
                let formData = new FormData();
                formData.append('image', this.uploadFile);
                axios
                    .post(this.uploadUrl, formData, {
                        headers: {
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        responseType: 'json'
                    })
                    .then(response => {
                        this.error = false;
                        let result = response.data;
                        if (result.success) {
                            this.images = result.images;
                            this.message = 'Загружено';
                        }
                        else {
                            this.message = result.message;
                        }
                    })
                    .catch(error => {
                        this.error = true;
                        let data = error.response.data;
                        if (data.errors.image.length) {
                            this.message = data.errors.image[0];
                        }
                    })
                    .finally(() => {
                        this.content = false;
                        this.uploadFile = false;
                        this.loading = false;
                    });
            },

            // Получаем выбранное изображение.
            getImage (event) {
                this.message = '';
                this.selectImage(event.target.files[0]);
            },

            selectImage (file) {
                this.uploadFile = file;
                let reader = new FileReader();
                reader.onload = this.onImageLoad;
                reader.readAsDataURL(file);
            },

            onImageLoad (event) {
                this.content = event.target.result;
                let filename = this.uploadFile instanceof File ? this.uploadFile.name : '';
                // Dispatch new input event with filename
                this.$emit('input', filename);
                // Dispatch new event with image content
                this.$emit('image-changed', this.content);
            }
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
