<template>
    <p>Voici le props passé : {{ test }}</p>
    <p>{{ compteur }}</p>
    <button @click="compteur++">Incrémenter</button>
</template>

<script setup>
import {ref} from "vue"

defineProps({
    test:{
        type:String
    }
})

let compteur = ref(0)

</script>