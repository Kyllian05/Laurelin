<template>
    <Header currentPage="Accueil"></Header>
    <div class="hero">
        <video autoplay muted loop class="background-video">
            <source src="/public/images/video1.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <Footer></Footer>
</template>

<script setup>
import { ref } from "vue"
import Footer from "./Components/Footer.vue"
import Header from "./Components/Header.vue";

defineProps({
    test: String,
    urlData: String,
    prenom: String,
})

let compteur = ref(0)
</script>

<style scoped>
html, body {
    margin: 0;
    padding: 0;
    overflow-x: hidden; /* Désactive le défilement horizontal */
    height: 100%; /* Assurez-vous que le corps prend toute la hauteur */
}

.hero {
    width: 100vw;
    height: 100vh;
    position: relative;
    overflow: hidden;
}

.background-video {
    position: absolute;
    top: 0;
    left: 0;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    object-fit: cover;
    z-index: -1;
}
</style>