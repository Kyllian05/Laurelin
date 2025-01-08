<template>
    <Header current-page="Nos bijoux"></Header>

    <div id="page">
        <div id="FirstRange" :style="{ backgroundImage: `url('/pictures/categories/${categories}.1.webp')` }">
            <span class="material-symbols-rounded">
              arrow_back_ios
            </span>

        </div>

        <div id="SecondRange">
            <select name="trieur" id="Tri-produit" class="font-subtitle-16" @change="trierProduits($event.target.value)">
                <option value="">Trier par</option>
                <option value="croiss">Prix croissant</option>
                <option value="decroiss">Prix décroissant</option>
                <option value="recent">Les plus récents</option>
            </select>
        </div>



        <div id="ProduitRange">
            <div class="container">
                <div v-for="(produit, index) in produitsAffiches" :key="produit.ID" class="item" :data-id="produit.ID" :style="{ backgroundImage: `url(${produit.URL})` }" @click="redirectOnClick(produit.ID)">
                    <!-- TODO : faire le backend du btn favoris -->
                    <span class="material-symbols-rounded add-fav">favorite</span>
                    <!-- - - - - - - - - - - - - -  -->
                    <span class="item-text font-subtitle-16">{{ produit.NOM }}</span>
                    <span class="materiaux-text font-subtitle-16">{{ produit.MATERIAUX }}</span>
                    <span class="prix font-subtitle-16">{{ formatPrix(produit.PRIX) }} €</span>
                    <button class="boutton_acheter font-subtitle-16" @click="handleClick(produit)">Acheter</button>
                </div>
            </div>
        </div>
        <img id="logo" src="/public/images/logo-simple.png" alt="logo laurelin">
        <button id="plusProd" v-if="hasMoreProducts" @click="loadMoreProducts" class="font-subtitle-16">Charger plus</button>
    </div>

<Footer></Footer>
</template>

<script setup>
import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import { router } from '@inertiajs/vue3';
import {reactive, computed, onMounted} from "vue";

const props = defineProps({
    produits: {
        type: Array,
        required: true
    },
    categories: {
        type: Number,
        required: true
    }
});

const produitsAffiches = reactive([...props.produits]);
const itemsPerPage = 6;
let currentPage = 1;


/* Gère l'espace du prix */
const formatPrix = (prix) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "decimal",
        maximumFractionDigits: 0, // Pas de décimales
    }).format(prix);
};


// Fonction pour trier les produits
const trierProduits = (critere) => {
    if (critere === "croiss") {
        produitsAffiches.sort((a, b) => a.PRIX - b.PRIX);
    } else if (critere === "decroiss") {
        produitsAffiches.sort((a, b) => b.PRIX - a.PRIX);
    } else if (critere === "recent") {
        produitsAffiches.sort((a, b) => b.ANNEE_CREATION - a.ANNEE_CREATION);
    }
};


// Charger les produits initialement avec limitation
const fetchProducts = () => {
    produitsAffiches.splice(0, produitsAffiches.length, ...props.produits.slice(0, itemsPerPage));
};


const loadMoreProducts = () => {
    const start = currentPage * itemsPerPage;
    const nextProducts = props.produits.slice(start, start + itemsPerPage);

    if (nextProducts.length > 0) {
        produitsAffiches.push(...nextProducts);
        currentPage++;
    }
};


// Vérifie s'il reste des produits à charger
const hasMoreProducts = computed(() => {
    return produitsAffiches.length < props.produits.length;
});

onMounted(fetchProducts);

const containers = document.querySelectorAll('.container .item');

containers.forEach(container => {
    if (isTouchDevice) {
        container.style.pointerEvents = 'auto';
        container.addEventListener('touchstart', () => handleTouchClick(container.dataset.id));
    } else {
        container.style.pointerEvents = 'none';
    }
});


const handleClick = (produit) => {
    router.visit(`/produit/${produit.ID}`);
};


const handleTouchClick = (id) => {
    router.visit(`/produit/${id}`);
};

function redirectOnClick(id){
    const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
    if(isTouchDevice){
        window.location = "/produit/"+id
    }
}
</script>

<style scoped>

.add-fav {
    position: absolute;
    right: 12px;
    top: 12px;
    cursor: pointer;
    background: #ffffff;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    padding: 8px 8px 7px 8px;
    border-radius: 50px;
    opacity: 0;
    transition: all .3s;
}
.add-fav:hover {
    background: #000;
    color: #ffffff;
}


#logo {
    display: block;
    text-align: center;
    position: relative;
    width: 50px;
    filter: brightness(0%) grayscale(100%);
    margin: 20px auto;
}

#plusProd {
    display: block;
    margin: 20px auto;
    padding: 10px 20px;
    background-color: black;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    border-radius: 5px;
    text-align: center;
}

#plusProd:hover {
    background-color: #333;
}

#FirstRange {
    width: 100%;
    height: 100vh;
    position: relative;
    background: no-repeat center 30%/cover;
}

#FirstRange span {
    font-size: 56px;
    position: absolute;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%) rotate(-90deg)
}

#SecondRange {
    width: 100%;
    display: flex;
    justify-content: end;
    align-items: center;
    padding: 32px 64px;
}

#SecondRange #Tri-produit {
    padding: 10px 24px;
    font-size: 14px;
    width: 250px;
    background-color: black;
    border-radius: 8px;
    border: none;
    color: white;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

#SecondRange #Tri-produit:hover {
    background-color: #333333;
}

#ProduitRange .container {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    margin: 20px 20px 80px;
    gap: 32px;
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
    transition: box-shadow 0.5s ease;
}

#ProduitRange .container .item:hover {
    box-shadow: 0 0 8px 4px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

#ProduitRange .container .item:hover .boutton_acheter{
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-10px);
    cursor: pointer;
}

#ProduitRange .container .item:hover .add-fav {
    opacity: 1;
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
        margin-top: 0;
        gap: 20px;
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

    #SecondRange {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 32px 64px;
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
        margin-top: 0;
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
