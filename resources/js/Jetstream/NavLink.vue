<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Link,
        },
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 text-sm font-bold leading-5 text-gray-300 focus:outline-none focus:border-indigo-700 transition'
                    : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-bold leading-5 text-white hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-white focus:border-gray-300 transition'
            }
        }
    }
</script>
