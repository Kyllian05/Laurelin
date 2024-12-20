<template>
    <div v-if="page == 'info'">
        <p>Vous êtes sur la page info</p>
        <div v-for="field in Object.keys(info)">
            <p>{{ field }}</p>
            <input>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue"

let props = defineProps(
    {
        "page":String,
        "info":Object
    }
)

</script>

<style scoped>

</style>
