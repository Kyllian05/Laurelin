<template>
    <Header currentPage="Accueil"></Header>
    <!--
    <p v-if="prenom">Bienvenue {{ prenom }}</p>
    <p>Voici le props passé : {{ test }}</p>
    <p>{{ compteur }}</p>
    <button @click="compteur++">Incrémenter</button>
    <p v-if="urlData">Voici la valeur passé en url : {{ urlData }}</p>
    <p v-else>Vous n'avez pas passé de valeur dans l'url</p>-->
    <div class="hero">
    </div>
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
    .hero {
        width: 100vw;
        height: 100vh;
        background: url("/public/images/home-1.png") no-repeat center center/cover;
    }
</style>
