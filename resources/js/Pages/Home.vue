<template>
    <Header currentPage="Accueil"></Header>
    <div class="hero">
        <video autoplay muted loop class="background-video">
            <source src="/public/images/video1.mp4" type="video/mp4">
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
    overflow-x: hidden;
    height: 100%; /* Désactive le défilement horizontal */
}

.hero {
    width: 100vw;
    height: 100vh;
    position: relative;
    overflow: hidden;
}

.background-video {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: translate(-50%, -50%);
    z-index: -1;
}
</style>