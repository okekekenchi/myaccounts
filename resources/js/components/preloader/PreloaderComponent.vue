<template>
    <transition name="fade">
        <div class="text-center preloader" :class="background || '#f8f8f8'">
            <v-progress-circular :size="50" :width="4" :color="color" indeterminate/>
        </div>
    </transition>
</template>

<script>
    export default {
        props: ['background', 'color' ], 
    }
</script>

<style scoped>
    .v-progress-circular {
        margin-top: 22%;
    }

    .preloader {
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        z-index: 2000;
        position: fixed;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .8s;
    }

    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
</style>
