<template>
    <div class="row">
        <div class="col-12">
            <p v-for="item in errors" class="text-danger">{{ item }}</p>
        </div>
        <div class="col-12">
            <draggable
                    :list="list"
                    class="list-group"
                    @change="checkMove"
                    handle=".handle">
                <div
                        class="list-group-item"
                        v-for="element in list"
                        :key="element.name">
                    <i class="fa fa-align-justify handle cursor-move mr-2"></i>
                    <a :href="element.url" v-if="element.url">
                        {{ element.name }}
                    </a>
                    <span v-else>
                        {{ element.name }}
                    </span>
                </div>
            </draggable>
        </div>
        <div class="col-12">
            <button class="btn btn-success my-3"
                    :disabled="! weightChanged"
                    @click="changeOrder"
                    :class="weightChanged ? 'animated bounceIn' : ''">
                Сохранить порядок
            </button>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        components: {
            draggable,
        },

        props: {
            elements: {
                type: Array,
                required: true
            },
            url: {
                type: String,
                required: true
            }
        },

        data() {
            return {
                list: [],
                loading: false,
                errors: [],
                weightChanged: false,
            }
        },

        created() {
            this.list = this.elements;
        },

        computed: {
            orderData() {
                let ids = [];
                for (let item in this.list) {
                    if (this.list.hasOwnProperty(item)) {
                        ids.push(this.list[item].id);
                    }
                }
                return ids;
            }
        },

        methods: {
            // Показать кнопку если порядок изменен.
            checkMove() {
                this.weightChanged = true;
            },
            // Сохранить порядок.
            changeOrder() {
                this.loading = true;
                this.errors = [];
                axios
                    .put(this.url, {
                        items: this.orderData,
                    })
                    .then(response => {
                        let result = response.data;
                        if (! result.success) {
                            this.errors.push(result.message);
                        }
                        else {
                            Swal.fire({
                                position: 'top-end',
                                type: 'success',
                                title: 'Порядок изменен',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            this.errors = [];
                            this.weightChanged = false;
                        }
                    })
                    .catch(error => {
                        let data = error.response.data;
                        if (data.errors.image.length) {
                            this.errors.push(data.errors.image[0]);
                        }
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            },
        }
    }
</script>

<style scoped>

</style>