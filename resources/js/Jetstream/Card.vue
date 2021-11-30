<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <slot name="header" />
                </div>
                <div class="mt-6">
                    <slot name="content" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import JetButton from '@/Jetstream/Button.vue'

export default defineComponent({
    components: {
        JetButton,
    }
})
</script>

