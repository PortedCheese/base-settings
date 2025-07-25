<template>
    <div class="sortable-list">
        <div v-for="(item, idx) in elements"
             :key="item.id"
             class="sortable-item"
             :draggable="true"
             @dragstart="onDragStart(idx)"
             @dragover.prevent="onDragOver(idx)"
             @drop="onDrop(idx)"
             :class="{ 'drag-over': idx === dragOverIndex }">
            {{ item.name }}
        </div>
    </div>
    <div class="col-12">
        <button class="btn btn-success my-3"
                :disabled="! weightChanged"
                @click="changeOrder"
                :class="weightChanged ? 'animated bounceIn' : ''">
            Сохранить порядок
        </button>
    </div>
</template>

<script>
export default {
    name: "PriorityComponent",
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
            items: this.elements,
            dragStartIndex: null, // Индекс элемента, который перетаскиваем
            dragOverIndex: null,  // Индекс элемента, над которым находимся
            loading: false,
            errors: [],
            weightChanged: false,
        }
    },
    computed: {
        orderData() {
            let ids = [];
            for (let item in this.items) {
                if (this.items.hasOwnProperty(item)) {
                    ids.push(this.items[item].id);
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
                  if (data.length) {
                    this.errors.push(data[0]);
                  }
                })
                .finally(() => {
                    this.loading = false;
                })
        },
        onDragStart(idx) {
            // Запоминаем, с какого индекса начали перетаскивание
            this.dragStartIndex = idx
        },
        onDragOver(idx) {
            // При наведении устанавливаем индекс поверх которого перетаскиваем
            this.dragOverIndex = idx
        },
        onDrop(idx) {
            if (this.dragStartIndex === null) return
            // Получаем копию массива
            const movedItem = this.items[this.dragStartIndex]
            this.items.splice(this.dragStartIndex, 1) // Удаляем элемент, который двигаем
            this.items.splice(idx, 0, movedItem) // Вставляем на новое место
            // Сбрасываем индексы
            this.dragStartIndex = null
            this.dragOverIndex = null

            this.checkMove();
        }
    }
}
</script>

<style scoped>
.sortable-list {
    width: 280px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 7px;
    background: #fafafa;
}
.sortable-item {
    padding: 10px;
    margin-bottom: 8px;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: move;
    transition: background .24s;
}
.sortable-item.drag-over {
    background: #e0fcff;
}
</style>
