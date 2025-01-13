<template>
    <button @click="emits('submit-clicked')" class="font-body-l">{{ buttonText }}</button>
</template>

<script setup>
let props = defineProps({
    "buttonText": String,
})

let emits = defineEmits(["submit-clicked"])
</script>

<style scoped>
button{
    background-color: black;
    width: 100%;
    color: white;
    border: none;
    border-radius: 10px;
    margin-top: 32px;
    padding: 18px 0;
    cursor: pointer;
    transition: background-color .2s;
}

button:hover {
    background-color: #3a3a3a;
}
</style>
