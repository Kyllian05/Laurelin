<template>
    <div id="content">
        <span class="material-symbols-rounded">close</span>
        <p>{{ message }}</p>
    </div>
</template>

<script setup>

const props = defineProps(["message"])
</script>

<style scoped>
    #content{
        position: fixed;
        right: 1vw;
        bottom: 1vw;
        background-color: rgba(255,100,100);
        cursor: pointer;
        border-radius: 20px;
        padding: 1vw;
        z-index: 1001;
    }
</style>
