<template>
    <Header current-page="Nos bijoux"></Header>
    <div id="page">
        <div id="produitEnVente">
            <div id="nom" class="font-subtitle-16"> {{produit.NOM}} </div>
            <div id="materiaux" class="font-body-l">{{produit.MATERIAUX}} </div>
            <div id="annee" class="font-body-s"> Année création: {{produit.ANNEE_CREATION}} </div>
            <select id="taille">
                <option name="selcetionner" class="font-body-m">Séléctionner une taille</option>
                <!-- TODO : mettre les tailles -->
            </select>
            <div id="prixDiv">
                <div id="prix" class="font-body-m"> {{formatPrix(produit.PRIX)}}€</div>
                <div id="tva" class="font-body-m">incl. TVA</div>
            </div>
            <button class="boutton_acheter font-body-m" @click="ajoutAuPanier()">
                <span class="material-symbols-rounded">shopping_bag</span>
                Ajouter au panier
            </button>
            <div id="description" class="font-body-m"> {{produit.DESCRIPTION}} </div>
            <span @click="favorisAction()" id="favoriteButton" class="material-symbols-rounded add-fav">{{!dynamicFavorite ? "favorite" : "heart_check"}}</span>
        </div>


        <div v-for="(image, index) in images" :key="index" :id="'img' + (index + 1)" class="produitImage">
            <img :src="image.URL">
        </div>

        <Splide :options="{ rewind: true }" aria-label="My Favorite Images" class="slideImage">
            <SplideSlide v-for="(image, index) in images">
                <img :src="image.URL">
            </SplideSlide>
        </Splide>


        <div id="creaAssocier" class="font-subtitle-16">
            Créations Associées
        </div>

        <div id="prodCreaAssocier">
            <div v-for="produit in prodAssocier" :key="produit.ID" class="item" :style="{ backgroundImage: `url(${produit.URL})` }">
                <!-- TODO : faire le backend du btn favoris -->
                <span class="material-symbols-rounded add-fav">favorite</span>
                <!-- - - - - - - - - - - - - -  -->
                <span class="item-text font-subtitle-16">{{ produit.NOM }}</span>
                <span class="materiaux-text font-subtitle-16">{{ produit.MATERIAUX }}</span>
                <span class="prix font-subtitle-16">{{ formatPrix(produit.PRIX) }} €</span>
                <button class="boutton_acheter font-subtitle-16" @click="handleClick(produit)">Acheter</button>
            </div>
        </div>

        <div id="avis" class="font-subtitle-16">
            Les Avis de Nos Clients
            <button class="avisButton font-body-l" > Donnez votre avis </button>
        </div>
    </div>

    <Footer></Footer>
</template>

<script setup>
import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import {onMounted, ref} from "vue";
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';
import {router} from "@inertiajs/vue3";


const props = defineProps({
    "produit" : Object,
    "images" : Array,
    "isFavorite" : Boolean,
    "autreProduits" : Array
})

const dynamicFavorite = ref(props.isFavorite)

/* Gère l'espace du prix */
const formatPrix = (prix) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "decimal",
        maximumFractionDigits: 0, // Pas de décimales
    }).format(prix);
};


const produits = ref([]);
const prodAssocier = ref([]);

function favorisAction(){
    let destination = "/ajouterFavoris"
    if(dynamicFavorite.value){
        destination = "/supprimerFavoris"
    }
    fetch(destination,{
        method : "POST",
        body : JSON.stringify({
            produit : props.produit["ID"]
        }),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    }).then(async response => {
        if(response.status == 200){
            alert("ajouté au panier")
        }
    })
}

function ajoutAuPanier(){
    fetch("/panier/ajout",{
        method : "POST",
        body : JSON.stringify({
            produit : props.produit["ID"]
        }),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    }).then(async response => {
        if(response.status == 200){
            dynamicFavorite.value = !dynamicFavorite.value
        }
    })
}

const choisirProduitsAleatoires = () => {
    if (props.autreProduits.length > 3) {
        const produitsMelanges = [...props.autreProduits].sort(() => Math.random() - 0.5);
        prodAssocier.value = produitsMelanges.slice(0, 3);
    } else {
        prodAssocier.value = produits.value;
    }
};

// Initialisation des produits associés au montage
onMounted(() => {
    choisirProduitsAleatoires();
});

const handleClick = (produit) => {
    router.visit(`/produit/${produit.ID}`);
};


</script>

<style scoped>

#page {
    display: grid;
    margin-top: 119px;
    grid-template-columns: 30% 30% 40%;
    grid-template-rows: repeat(4, auto);
}

.slideImage {
    display: none;
}

#produitEnVente {
    grid-column: 3 / 4;
    grid-row: 1 / 4;
    padding: 60px;
    border-spacing: 60px;
}

#produitEnVente .boutton_acheter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: clamp(45px, 3vw, 65px);
    margin-bottom: 45px;
    border-radius: 10px;
    background-color: black;
    color: white;
    border: 1px solid;
    cursor: pointer;
    transition: color 0.5s ease, background-color 0.5s ease, transform 0.1s ease;
}

#produitEnVente .boutton_acheter:hover {
    background-color: white;
    color: black;
}

#produitEnVente .boutton_acheter:active {
    transform: scale(0.99);
}


#nom {
    font-size: clamp(14px, 2vw, 22px);
    margin-bottom: 30px;
    width: 90%;
}

#annee {
    font-size: clamp(10px, 1vw, 14px);
    margin-bottom: 30px;
}

#materiaux {
    margin-bottom: 5px;
    font-size: clamp(10px, 1vw, 14px);
}


#favoriteButton {
    position: absolute;
    right: 60px;
    top: 179px;
    cursor: pointer;
}

#taille {
    width: 100%;
    text-align: center;
    height: clamp(30px, 2vw, 50px);
    font-size: 14px;
    border: 1px solid #000;
    border-radius: 10px;
    background-color: transparent;
    cursor: pointer;
    margin-bottom: 30px;
    transition: background-color 0.5s ease, color 0.5s ease;
}

#taille:hover {
    background-color: black;
    color: white;
}

#prixDiv {
    display: flex;
    align-items: baseline;
    margin-bottom: 30px;
}

#prix {
    font-size: clamp(14px, 2vw, 18px);
}

#tva {
    font-size: 10px;
}

#description {
    text-align: justify;
}

#img1 {
    grid-column: 1 / 3;
    grid-row: 1;
    width: 100%;
    aspect-ratio: 1;
}

#img1 img {
    aspect-ratio: 1 / 1;
    object-fit: cover;
    object-position: top;
}

#img2 {
    grid-column: 1/2;
    grid-row: 2;
    width: 100%;
    height: auto;
}

#img3 {
    grid-column: 2 /3;
    grid-row: 2;
    width: 100%;
    height: auto;
}

img {
    width: 100%;
    height: auto;
    display: block;
    padding: 10px;
    margin-left: 10px;
}

#creaAssocier {
    grid-column: 1 / 4;
    grid-row: 3;
    text-align: center;
    display: block;
    margin-top: 250px;
}

#prodCreaAssocier {
    grid-row: 3;
    grid-column: 1 /4;
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    margin: 20px 20px 80px;
    gap: 32px;
    margin-top: 300px;
    height: auto;
}


#prodCreaAssocier .boutton_acheter {
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


#prodCreaAssocier .item {
    position: relative;
    background-size: cover;
    background-position: center;
    max-width: 100%;
    aspect-ratio: 1 / 1;
    transition: box-shadow 0.5s ease;
}

#prodCreaAssocier .item:hover {
    box-shadow: 0 0 8px 4px rgba(0, 0, 0, 0.2);
    z-index: 10;
}

#prodCreaAssocier .item:hover .boutton_acheter{
    opacity: 1;
    visibility: visible;
    transform: translateX(-50%) translateY(-10px);
    cursor: pointer;
}

#prodCreaAssocier .item:hover .add-fav {
    opacity: 1;
}

#prodCreaAssocier .item:hover .materiaux-text {
    display: none;
}

#prodCreaAssocier .item:hover .prix {
    display: none;
}


#prodCreaAssocier .item .boutton_acheter:hover {
    background-color: transparent;
    border: 2px solid black;
    color: black;
}

#prodCreaAssocier .item .boutton_acheter:active {
    transform: translateX(-50%) translateY(-10px) scale(0.98);
}

#prodCreaAssocier .item .item-text {
    position: absolute;
    bottom: 15%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: 90%;
    font-weight: bolder;
    font-size: clamp(10px, 2vw, 18px);
}

#prodCreaAssocier .item .prix {
    position: absolute;
    bottom: 5%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    font-size: clamp(14px, 2vw, 18px);
}

#prodCreaAssocier .item .materiaux-text {
    position: absolute;
    bottom: 10%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: 90%;
    font-size: clamp(12px, 2vw, 14px);
}

#avis {
    display: flex;
    flex-direction: column;
    align-items: center;
}

#avis .avisButton {
    width: 200px;
    height: clamp(45px, 3vw, 65px);
    margin-bottom: 45px;
    border-radius: 10px;
    background-color: black;
    color: white;
    border: 1px solid;
    cursor: pointer;
    transition: color 0.5s ease, background-color 0.5s ease, transform 0.1s ease;
}

#avis .avisButton:hover {
    background-color: white;
    color: black;
}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (min-width: 1050px) and (max-width: 1500px) {
    #prodCreaAssocier .item .item-text {
        font-size: clamp(10px, 2vw, 14px);
        line-height: 1;
        bottom: 17%;
    }

    #prodCreaAssocier .item .prix {
        bottom: 3%;
        font-size: clamp(10px, 2vw, 16px);
    }

    #prodCreaAssocier .item .materiaux-text {
        font-size: clamp(10px, 2vw, 12px);
        line-height: 1;
        bottom: 10%;
    }

    #prodCreaAssocier .boutton_acheter {
        font-size: clamp(16px, 2vw, 18px);
        height: clamp(40px, 2vw, 60px);
    }

}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (max-width: 800px) {
    #produitEnVente {
        padding: 70px;
    }

    #prodCreaAssocier {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px;
        margin-top: 300px;
        gap: 20px;
        height: auto;
    }

    #prodCreaAssocier .item {
        aspect-ratio: 4/5;
    }


    #prodCreaAssocier .item .item-text {
        bottom: 18%;
        width: 90%;
        font-size: clamp(8px, 2vw, 20px);
        line-height: 1;
    }

    #prodCreaAssocier .item .prix {
        bottom: 1%;
        font-size: clamp(8px, 2vw, 20px);
    }

    #prodCreaAssocier .item .materiaux-text {
        position: absolute;
        bottom: 10%;
        font-size: clamp(5px, 2vw, 12px);
        line-height: 1;
    }
}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (max-width: 650px) {
    #produitEnVente {
        padding: 25px;
    }

    #prodCreaAssocier .item .item-text {
        bottom: 12%;
    }

    #prodCreaAssocier .item .materiaux-text {
        display: none;
    }

    #prodCreaAssocier .boutton_acheter {
        font-size: clamp(12px, 2vw, 16px);
        height: clamp(25px, 2vw, 45px);
    }

}


/* @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@ */


@media (min-width: 800px) and (max-width: 1050px) {
    #prodCreaAssocier {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px;
        margin-top: 300px;
        gap: 20px;
        height: auto;
    }

    #prodCreaAssocier .item {
        aspect-ratio: 1 / 1;
    }

    #prodCreaAssocier .item .item-text {
        bottom: 18%;
        width: 90%;
        font-size: clamp(8px, 2vw, 20px);
        line-height: 1;
    }

    #prodCreaAssocier .item .prix {
        bottom: 1%;
        font-size: clamp(8px, 2vw, 20px);
    }

    #prodCreaAssocier .item .materiaux-text {
        position: absolute;
        bottom: 10%;
        font-size: clamp(5px, 2vw, 12px);
        line-height: 1;
    }

    #prodCreaAssocier .boutton_acheter {
        font-size: clamp(18px, 2vw, 20px);
        height: clamp(40px, 5vw, 50px);
    }
}

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

#avis {
    grid-column: 1 / 4;
    grid-row: 4;
    text-align: center;
    margin-top: 50px;
}


@media (max-width: 1200px) {
    #page{
        margin-top: 84px;
    }

    #favoriteButton {
        top: 144px;
    }
}

@media (max-width: 1050px) {

    #page {
        display: flex;
        flex-direction: column;
    }

    .produitImage {
        display: none;
    }

    .slideImage{
        display: block;
        order: 1;
    }

    .slideImage img {
        aspect-ratio: 1 / 1;
        object-fit: cover;
        object-position: top;
    }

    img {
        width: 100vw;
        margin-left: 0px;
        padding: 0px;
    }

    #produitEnVente {
        order: 2;
    }


    #description {
        text-align: justify;
    }

    #creaAssocier {
        order: 3;
        margin-top: 80px;
    }

    #prodCreaAssocier {
        order: 4;
        margin-top: 40px;
    }

    #avis {
        order: 5;
    }
}

</style>
