<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
</script>

<template>
<Header current-page="Magasin"></Header>
  <div id="FirstRange" >
    <span class="material-symbols-rounded">
      arrow_back_ios
    </span>
  </div>

  <div id="ProduitRange">
    <div class="container">
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
        <div class="item">
            <span class="item-text">Bague Etincelles</span>
        </div>
    </div>
  </div>
<Footer></Footer>
</template>

<style scoped>


#FirstRange {
    width: 100%;
    height: 100vh;
    position: relative;
    background: url("/public/images/imgProd/w1242_tpadding12.webp") no-repeat center center/cover;
}

#FirstRange span {
    font-size: 56px;
    position: absolute;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%) rotate(-90deg)
}

#ProduitRange .container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    margin: 20px;
    gap: 20px;
    min-height: 100vh;
}


#ProduitRange .container .item {
    position: relative;
    background-image: url("/public/images/imgProd/w1242_tpadding12.webp");
    background-size: cover;
    background-position: center;
    width: 100%;
    height: 500px;
}

#ProduitRange .container .item .item-text {
    position: absolute;
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
}

@media (max-width: 1366px) and (max-height: 768px) {
    #ProduitRange .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px;
        gap: 20px;
        min-height: 100vh;

    }
}

@media (max-width: 450px) and (max-height: 930px) {
    #FirstRange {
        height: 80vh;
    }

    #ProduitRange .container .item {
        height: 350px;
    }
}

@media (max-width: 800px) {
    #ProduitRange .container {
        display: grid;
        grid-template-columns: 1fr;
        margin: 20px;
        gap: 20px;
        min-height: 100vh;

    }
}

@media (min-width: 800px) and (max-width: 1300px) and (min-height: 800px) {
    #ProduitRange .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px;
        gap: 20px;
        min-height: 100vh;

    }
}

</style>
