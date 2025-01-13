<template>
  <Header currentPage="carte"></Header>
  <iframe 
    class="responsive-iframe" 
    frameborder="0" 
    allowfullscreen="true" 
    allow="geolocation" 
    src="//umap.openstreetmap.fr/fr/map/laurelin_1161286?scaleControl=false&miniMap=false&scrollWheelZoom=true&zoomControl=false&editMode=disabled&moreControl=false&searchControl=null&tilelayersControl=false&embedControl=null&datalayersControl=false&onLoadPanel=none&captionBar=false&captionMenus=true">
  </iframe>
  <Footer></Footer> 
</template>

<script setup>
import Footer from "./Components/Footer.vue";
import Header from "./Components/Header.vue";
</script>    

<style scoped>
.responsive-iframe {
  width: 100%;
  height: 1080px;
}

@media (max-width: 768px) {
  .responsive-iframe {
    height: 768px; 
  }
}

@media (max-width: 480px) {
  .responsive-iframe {
    height: 480px; 
  }
}
</style>
