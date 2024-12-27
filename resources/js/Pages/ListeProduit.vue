<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import { router } from '@inertiajs/vue3';


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
    router.visit('/Produit');
    /* $inertia.visit(`/produits/${produit.ID}`);*/

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
          <div v-for="(produit, index) in produits" :key="produit.ID" class="item" :style="{ backgroundImage: `url('/images/imgProd/img_bague_tressage.jpg')` }">
              <span class="item-text font-subtitle-16">{{ produit.NOM }}</span>
              <span class="materiaux-text font-subtitle-16">{{ produit.MATERIAUX }}</span>
              <span class="prix font-subtitle-16">{{ formatPrix(produit.PRIX) }} €</span>
              <button class="boutton_acheter font-subtitle-16" @click="handleClick(produit)">Acheter</button>

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

#ProduitRange .container .boutton_acheter {
    position: absolute;
    width: 70%;
    height: clamp(50px, 2vw, 65px);
    left: 50%;
    border-width: 0;
    font-size: clamp(10px, 2vw, 22px);
    bottom: 1%;
    transform: translateX(-50%);
    background-color: black;
    color: white;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.5s ease, transform 0.3s ease, background-color 0.5s ease, color 0.5s ease;
}


#ProduitRange .container .item {
    position: relative;
    background-size: cover;
    background-position: center;
    max-width: 100%;
    aspect-ratio: 1 / 1;
    cursor: pointer;
    transition: box-shadow 0.5s ease;
}

#ProduitRange .container .item:hover {
    box-shadow: 1px 1px 6px 6px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

#ProduitRange .container .item:hover .boutton_acheter{
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-10px);
}

#ProduitRange .container .item:hover .materiaux-text {
    display: none;
}

#ProduitRange .container .item:hover .prix {
    display: none;
}


#ProduitRange .container .item .boutton_acheter:hover {
    background-color: transparent;
    border: 2px solid black;
    color: black;
}

#ProduitRange .container .item .boutton_acheter:active {
    transform: translateX(-50%) translateY(-10px) scale(0.98);
}

#ProduitRange .container .item .item-text {
    position: absolute;
    bottom: 15%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: 90%;
    font-weight: bolder;
    font-size: clamp(10px, 2vw, 18px);
}

#ProduitRange .container .item .prix {
    position: absolute;
    bottom: 5%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    font-size: clamp(14px, 2vw, 18px);
}

#ProduitRange .container .item .materiaux-text {
    position: absolute;
    bottom: 10%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: 90%;
    font-size: clamp(12px, 2vw, 14px);
}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (min-width: 1050px) and (max-width: 1500px) {
    #ProduitRange .container .item .item-text {
        font-size: clamp(10px, 2vw, 14px);
        line-height: 1;
        bottom: 17%;
    }

    #ProduitRange .container .item .prix {
        bottom: 3%;
        font-size: clamp(10px, 2vw, 16px);
    }

    #ProduitRange .container .item .materiaux-text {
        font-size: clamp(10px, 2vw, 12px);
        line-height: 1;
        bottom: 10%;
    }

    #ProduitRange .container .boutton_acheter {
        font-size: clamp(16px, 2vw, 18px);
        height: clamp(40px, 2vw, 60px);
    }

}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (max-width: 450px) and (max-height: 930px) {
    #FirstRange {
        height: 80vh;
    }

}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (max-width: 800px) {
    #ProduitRange .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px;
        gap: 20px;
        min-height: 100vh;
    }

    #ProduitRange .container .item {
        aspect-ratio: 4/5;
    }


    #ProduitRange .container .item .item-text {
        bottom: 18%;
        width: 90%;
        font-size: clamp(8px, 2vw, 20px);
        line-height: 1;
    }

    #ProduitRange .container .item .prix {
        bottom: 1%;
        font-size: clamp(8px, 2vw, 20px);
    }

    #ProduitRange .container .item .materiaux-text {
        position: absolute;
        bottom: 10%;
        font-size: clamp(5px, 2vw, 12px);
        line-height: 1;
    }
}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (max-width: 650px) {
    #ProduitRange .container .item .item-text {
        bottom: 12%;
    }

    #ProduitRange .container .item .materiaux-text {
        display: none;
    }

    #ProduitRange .container .boutton_acheter {
        font-size: clamp(12px, 2vw, 16px);
        height: clamp(25px, 2vw, 45px);
    }

}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (min-width: 800px) and (max-width: 1050px) {
    #ProduitRange .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px;
        gap: 20px;
        min-height: 100vh;

    }

    #ProduitRange .container .item {
        aspect-ratio: 1 / 1;
    }

    #ProduitRange .container .item .item-text {
        bottom: 18%;
        width: 90%;
        font-size: clamp(8px, 2vw, 20px);
        line-height: 1;
    }

    #ProduitRange .container .item .prix {
        bottom: 1%;
        font-size: clamp(8px, 2vw, 20px);
    }

    #ProduitRange .container .item .materiaux-text {
        position: absolute;
        bottom: 10%;
        font-size: clamp(5px, 2vw, 12px);
        line-height: 1;
    }

    #ProduitRange .container .boutton_acheter {
        font-size: clamp(18px, 2vw, 20px);
        height: clamp(40px, 5vw, 50px);
    }
}

</style>
