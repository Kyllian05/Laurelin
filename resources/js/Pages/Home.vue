<template>
    <p>Voici le props passé : {{ test }}</p>
    <p>{{ compteur }}</p>
    <button @click="compteur++">Incrémenter</button>
    <p v-if="urlData">Voici la valeur passé en url : {{ urlData }}</p>
    <p v-else>Vous n'avez pas passé de valeur dans l'url</p>
    <Footer></Footer>
</template>

<script setup>
import {ref} from "vue"
import Footer from "./Components/Footer.vue"

defineProps({
    test : String,
    urlData : String
})

let compteur = ref(0)

</script>