<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";

defineProps({
    produits: Array, // Liste des produits passés depuis Laravel
});

/* Gère l'espace du prix */
const formatPrix = (prix) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "decimal",
        maximumFractionDigits: 0, // Pas de décimales
    }).format(prix);
};

const handleClick = (produit) => {
    console.log(`Produit cliqué : ${produit.NOM}`);
    // Par exemple : navigation vers une page de détail produit
    $inertia.visit(`/produits/${produit.ID}`);
};

</script>

/* -----------------------HTML----------------------- */

<template>

<Header current-page="Magasin"></Header>
  <div id="FirstRange" >
    <span class="material-symbols-rounded">
      arrow_back_ios
    </span>
  </div>

  <div id="ProduitRange">
      <div class="container">
          <div
              v-for="(produit, index) in produits"
              :key="produit.ID"
              class="item"
              @click="handleClick(produit)"
              :style="{ backgroundImage: `url('/images/imgProd/w1242_tpadding12.webp')` }"
          >
              <span class="item-text font-subtitle-16">{{ produit.NOM }}</span>
              <span class="prix font-subtitle-16">{{ formatPrix(produit.PRIX) }} €</span>
          </div>
      </div>
  </div>

<Footer></Footer>

</template>

/* -----------------------CSS----------------------- */

<style scoped>

#FirstRange {
    width: 100%;
    height: 100vh;
    position: relative;
    background: url("/public/images/imgProd/img_caté_bagues_finale.jpg") no-repeat center 30%/cover;
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
    background-size: cover;
    background-position: center;
    width: 100%;
    height: 700px;
    cursor: pointer;
}

#ProduitRange .container .item .item-text {
    position: absolute;
    bottom: 15%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: 90%;
    font-size: 24px;
}

#ProduitRange .container .item .prix {
    position: absolute;
    bottom: 5%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    font-size: 24px;
}


@media (max-width: 1366px) and (max-height: 768px) {
    #ProduitRange .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px;
        gap: 20px;
        min-height: 100vh;

    }

    #ProduitRange .container .item {
        height: 500px;
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

    #ProduitRange .container .item {
        height: 500px;
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

    #ProduitRange .container .item {
        height: 500px;
    }
}

</style>
