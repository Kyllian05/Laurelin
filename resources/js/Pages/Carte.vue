<template>
  <Header currentPage="Carte"></Header>
  <iframe class="responsive-iframe" frameborder="0" allowfullscreen allow="geolocation" src="//umap.openstreetmap.fr/fr/map/laurelin_1161286?scaleControl=false&miniMap=false&scrollWheelZoom=false&zoomControl=true&editMode=disabled&moreControl=true&searchControl=null&tilelayersControl=null&embedControl=null&datalayersControl=true&onLoadPanel=none&captionBar=false&captionMenus=true"></iframe>
  <Footer></Footer> 
</template>

<script setup>
import Footer from "./Components/Footer.vue";
import Header from "./Components/Header.vue";
</script>    

<style scoped>
.responsive-iframe {
width: 100%;
height: 60vh;
}

@media (max-width: 768px) {
.responsive-iframe {
  height: 50vh; 
}
}

@media (max-width: 480px) {
.responsive-iframe {
  height: 40vh; 
}
}
</style>