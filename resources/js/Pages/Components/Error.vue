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
        background-color: #ffffff;
        color: darkred;
        box-shadow: 0 0 12px rgba(139, 0, 0, 0.3);
        cursor: pointer;
        border-radius: 8px;
        padding: 16px 32px;
        z-index: 1001;
        border: solid 1px darkred;
        display: flex;
        gap: 16px;
        align-items: center;
    }
</style>
