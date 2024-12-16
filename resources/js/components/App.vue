<template>
    <div>
      <h1>{{ count }}</h1>
      <button @click="count++">Augmenter le compteur</button>
    </div>
</template>

<script setup>
import {ref} from "vue"

let count = ref(0)
</script>

<style>
</style>