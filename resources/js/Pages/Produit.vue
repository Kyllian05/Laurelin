<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
</script>


<template>
<Header></Header>
<div id="produitEnVente">
    <button class="boutton_acheter font-subtitle-16" >Acheter</button>
</div>
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
