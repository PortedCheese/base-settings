<template>
    <div class="d-none">
        <slot></slot>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                type: String,
                required: true,
            },
            title: {
                type: String,
                default: "Вы уверены?",
            },
            text: {
                type: String,
                default: "Это действие будет невозможно отменить!",
            },
            confirmText: {
                type: String,
                default: "Да, удалить!",
            },
            cancelText: {
                type: String,
                default: "Отмена",
            }
        },

        methods: {
            clickMe(event) {
                event.preventDefault();
                Swal.fire({
                    title: this.title,
                    text: this.text,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: this.confirmText,
                    cancelButtonText: this.cancelText,
                }).then((result) => {
                    if (result.value) {
                        $("#" + this.id).submit();
                    }
                })
            }
        },

        mounted() {
            let $element = $("[data-confirm=" + this.id + "]");
            if ($element.length) {
                $element.on("click", this.clickMe);
            }
        }
    }
</script>

<style scoped>

</style>