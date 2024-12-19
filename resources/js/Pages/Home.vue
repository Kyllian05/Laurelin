<template>
    <Header currentPage="Accueil"></Header>
    <p v-if="prenom">Bienvenue {{ prenom }}</p>
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
import Header from "./Components/Header.vue";

defineProps({
    test : String,
    urlData : String,
    prenom: String,
})

let compteur = ref(0)

</script>

<style scoped>
#form{
    width: 30vw;
    margin-left: 50%;
    transform: translateX(-50%);
    margin-bottom: 10vh;
    margin-top: 10vh;
}
</style>
