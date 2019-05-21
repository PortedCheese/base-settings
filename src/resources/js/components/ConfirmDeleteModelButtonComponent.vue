<template>
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group mr-1">
            <template v-if="showConfirm">
                <button class="btn btn-danger"
                        @click="submitForm()">
                    <i class="far fa-check-circle"></i>
                </button>
                <slot name="delete"></slot>
                <button type="button"
                        @click="toggleConfirm()"
                        class="btn btn-success">
                    <i class="fas fa-ban"></i>
                </button>
            </template>
            <template v-else>
                <slot name="edit"></slot>
                <slot name="show"></slot>
                <button type="button"
                        v-if="hasSlot()"
                        @click="toggleConfirm()"
                        class="btn btn-danger">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </template>
        </div>
        <div class="btn-group" v-if="hasSlot('other')">
            <slot name="other"></slot>
        </div>
        <div class="d-none" v-if="hasSlot('forms')">
            <slot name="forms"></slot>
        </div>
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
