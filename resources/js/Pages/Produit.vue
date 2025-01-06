<template>
    <Header current-page="Nos bijoux"></Header>

    <div id="page">
        <div id="produitEnVente">
            <div id="nom" class="font-subtitle-16"> {{produit.NOM}} </div>
            <div id="materiaux" class="font-body-l">{{produit.MATERIAUX}} </div>
            <div id="annee" class="font-body-s"> Année création: {{produit.ANNEE_CREATION}} </div>
            <select id="taille">
                <option name="selcetionner" class="font-body-m">Séléctionner une taille</option>
            </select>
            <div id="prixDiv">
                <div id="prix" class="font-body-m"> {{formatPrix(produit.PRIX)}}€</div>
                <div id="tva" class="font-body-m">incl. TVA</div>
            </div>
            <button class="boutton_acheter font-body-m" > <span class="material-symbols-rounded">shopping_bag</span> Ajouter au panier</button>
            <div id="description" class="font-body-m"> {{produit.DESCRIPTION}} </div>
            <span @click="favorisAction()" id="favoriteButton" class="material-symbols-rounded add-fav">{{!dynamicFavorite ? "favorite" : "heart_check"}}</span>
        </div>

        <div id="img1" v-if="images[0]">
            <img :src="images[0].URL">
        </div>

        <div id="img2" v-if="images[1]">
            <img :src="images[1].URL">
        </div>

        <div id="img3" v-if="images[2]">
            <img :src="images[2].URL">
        </div>

        <div id="creaAssocier" class="font-subtitle-16">
            Créations Associées
        </div>

        <div id="avis" class="font-subtitle-16">
            Les Avis de Nos Clients
        </div>
    </div>

    <Footer></Footer>
</template>

<script setup>
import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import {ref} from "vue";

const props = defineProps({
    "produit" : Object,
    "images" : Array,
    "isFavorite" : Boolean,
})

const dynamicFavorite = ref(props.isFavorite)

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
            dynamicFavorite.value = !dynamicFavorite.value
        }
    })
}

/* Gère l'espace du prix */
const formatPrix = (prix) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "decimal",
        maximumFractionDigits: 0, // Pas de décimales
    }).format(prix);
};

</script>

<style scoped>

#page {
    display: grid;
    margin-top: 119px;
    grid-template-columns: 30% 30% 40%;
    grid-template-rows: repeat(4, auto);
}

#produitEnVente {
    grid-column: 3 / 4;
    grid-row: 1 / 3;
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



#img1 {
    grid-column: 1 / 3;
    grid-row: 1;
    width: 100%;
    height: auto;
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
}

#creaAssocier {
    grid-column: 1 / 4;
    grid-row: 3;
    text-align: center;
    margin-top: 50px;
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

</style>
