<template>
    <Link :href="'/'">
        <img :src="'/onthego-Logo.png'" alt="Image"/>
    </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
    })
</script>
