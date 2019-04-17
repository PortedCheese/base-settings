<template>
    <div v-if="showConfirm">
        <slot name="delete"></slot>
        <div class="btn-group">
            <button class="btn btn-danger"
                    @click="submitForm()">
                <i class="far fa-check-circle"></i>
            </button>
            <button type="button"
                    @click="toggleConfirm()"
                    class="btn btn-success">
                <i class="fas fa-ban"></i>
            </button>
        </div>
    </div>
    <div class="btn-group"
         role="group"
         v-else>
        <slot name="edit"></slot>
        <slot name="show"></slot>
        <slot name="other"></slot>
        <button type="button"
                v-if="hasSlot()"
                @click="toggleConfirm()"
                class="btn btn-danger">
            <i class="fas fa-trash-alt"></i>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['modelId'],
        data() {
            return {
                showConfirm: false
            }
        },
        methods: {
            submitForm() {
                $("#delete-" + this.modelId).submit();
            },

            toggleConfirm() {
                this.showConfirm = !this.showConfirm;
            },

            hasSlot (name = 'delete') {
                return !!this.$slots[ name ] || !!this.$scopedSlots[ name ];
            }
        }
    }
</script>
