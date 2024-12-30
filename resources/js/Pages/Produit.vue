<script setup>
import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";

defineProps({
    produit: Object,
    images: Array,
})
</script>


<template>
<Header current-page="Nos bijoux"></Header>
<div id="produitEnVente">
    <button class="boutton_acheter font-subtitle-16" >Acheter</button>
</div>
    <pre v-if="produit">
        {{produit}}
    </pre>
    <h3>Images :</h3>
    <img v-for="img in images" :key="img.URL" :src="img.URL">
<Footer></Footer>
</template>



<style scoped>

#produitEnVente {
    height: 100vh;
    background: url("/public/images/imgProd/img_bague_tressage.jpg") no-repeat center 45%/cover;
}

#produitEnVente .boutton_acheter {
    position: absolute;
    bottom: 20%;

}

</style>
