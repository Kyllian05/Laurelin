<template>
    <Header currentPage="Accueil"></Header>
    <!--
    <p v-if="prenom">Bienvenue {{ prenom }}</p>
    <p>Voici le props passé : {{ test }}</p>
    <p>{{ compteur }}</p>
    <button @click="compteur++">Incrémenter</button>
    <p v-if="urlData">Voici la valeur passé en url : {{ urlData }}</p>
    <p v-else>Vous n'avez pas passé de valeur dans l'url</p>-->
    <div class="hero">
        <div class="left">
            
            <li class="font-title" id="titre">Illuminez vos journées</li>
            <li class="font-body-m">L'avenir ce dessine avec Laurelin, transformer vos rêves en réalité</li>
            <a href="/bijoux" class="btn-side">Découvrir nos bijoux</a>
        </div>
        <div class="right">
            <div class="first">
                
                <a href="/categories/Bagues">
                    <div class="text_fleche">
                        <li>Nouvelle Bague</li>
                        <img src="../../../public/images/fleche-pointant-vers-la-droite.png" id="fleche">
                    </div>
                    
                </a> 
            </div>
            <div class="second">
                <li>une autre</li>
            </div>

        </div>
    </div>
    <div id="nouveauté">
        <li class="font-title-24">Nouveauté</li>
        <div class="container">
                <div v-for="produit in produits" :key="produit.ID" class="item" :data-id="produit.ID" :style="{ backgroundImage: `url(${produit.URL})` }" @click="redirectOnClick(produit.ID)">
                    <!-- TODO : faire le backend du btn favoris -->
                    <!--<span class="material-symbols-rounded add-fav">favorite</span>-->
                    <!-- - - - - - - - - - - - - -  -->
                    <span class="item-text font-subtitle-16">{{ produit.NOM }}</span>
                    <span class="materiaux-text font-subtitle-16">{{ produit.MATERIAUX }}</span>
                    <!--<span class="prix font-subtitle-16">{{ formatPrix(produit.PRIX) }} €</span>-->
                    <!--<button class="boutton_acheter font-subtitle-16" @click="handleClick(produit)">Acheter</button>-->
                    <a class="btn-side" :href="'/produit/'+produit.ID">Découvrir</a>
                </div>
            </div>
    </div>
    <div class="history">
        <div class="image">
            <img src="/home/debian/eq_1-1_lamothe-pol_le-carluer-brieuc_destain-jauzua_arnaud-kyllian_souchet-thomas/public/images/history.png">
        </div>
        <div class="paragraphe">
            <li id="titre_histoire" class="font-title-24">Notre histoire</li>
            <li class="font-normal-12" id="histoire">Depuis ses débuts, Laurelin a repoussé les limites de l'artisanat de luxe. Chaque création est pensée comme une œuvre d'art, unique et intemporelle. La maîtrise des techniques ancestrales se marie avec une innovation constante. Nous sélectionnons uniquement les matériaux les plus précieux et durables. Notre savoir-faire incarne un héritage qui traverse les générations. </li>
            <a href="/histoire">
                    <div class="text_fleche" id="a_propos">
                        <li>A propos de nous</li>
                        <img src="../../../public/images/fleche-pointant-vers-la-droite.png" id="fleche">
                    </div>
                    
                </a>
        </div>
    </div>
    <div id="collection">
        <li class="font-title-24">Nos Collections</li>
        <div id="coll-container">
                <div v-for="collection in collections" :key="collection.ID" class="item" id="coll_item" :data-id="collection.ID" :style="{ backgroundImage: `url(/pictures/collections/${collection.ID}.avif) ` }" @click="redirectOnClick(collection.ID)">
                    <!-- TODO : faire le backend du btn favoris -->
                    <!--<span class="material-symbols-rounded add-fav">favorite</span>-->
                    <!-- - - - - - - - - - - - - -  -->
                    <span id="name" class="item-text font-subtitle-16">{{ collection.NOM }}</span>
                    <span id="description" class="materiaux-text font-subtitle-16">{{ collection.DESCRIPTION.substring(0, 90) + "..." }}</span>
                    <!--<span class="prix font-subtitle-16">{{ formatPrix(produit.PRIX) }} €</span>-->
                    <!--<button class="boutton_acheter font-subtitle-16" @click="handleClick(produit)">Acheter</button>-->
                    <a class="btn-side" :href="'/collections/'+collection.NOM">Découvrir</a>
                </div>
            </div>
    </div>
    <Footer></Footer>
</template>

<script setup>
import {ref} from "vue";
import Footer from "./Components/Footer.vue";
import Header from "./Components/Header.vue";

const props = defineProps({
    produits: {
        type: Array,
        required: true
    },
    collections: {
        type: Array,
        required: true
    }
})

let compteur = ref(0)
let last = props.produits.shift();
let last2 = props.collections.pop();
</script>

<style scoped>
    .hero {
        width: 100vw;
        height: 100vh;
        background: url("/public/images/home-1.png") no-repeat center center/cover;
        display: flex;
        justify-content: space-between;
        align-items: end;
    }
    .btn-side {
        background-color: white;
        color: #000000;
        text-decoration: none;
        cursor: pointer;
        border-radius: 50px;
        padding: 12px ;
        transition-duration: .3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        font-size: 16px;
        user-select: none;
        width: 40%;
        /*la*/
        position: relative; 
        z-index: 1;
    }
    .btn-side:hover{
        background-color: #252525;
        color: #ffffff;
    }
    .left{
        width: 40%;
        height: 20%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin-left: 3%;
        margin-bottom: 4%;
        
    }
    .left > li{
        margin-bottom: 20px;
        list-style-type: none;
        color:#ffffff;
    }
    
    li{
        list-style-type: none;
        text-decoration: none;
    }

    #titre{
        font-size: 250%;
    }

    .first{
        background-image: url("../../../public/images/imgProd/w1242_tpadding12.webp");
        background-size: cover;
        background-position: center;
        filter: opacity(90%);
        display: flex;
        align-items: end;
        justify-content: center;
        height:120%;
        border-radius: 10%;
        /*width: 20%;
        height: 20%;*/
    }
    #fleche{
        height: 25px;
        width: 25px;
        margin-left: 10px;
    }
    #fleche:hover{
        margin-left: 20px;
    }
    .text_fleche > li:hover{
        margin-right: 10px
    }

    .text_fleche{
        display: flex;
        justify-content: center;
        color: #000000;
        margin-bottom: 10px;

    }
    .first:hover{
        filter: opacity(100%);
    }
    a{
        text-decoration: none;
    }
    .second{
        filter: opacity(0%);
    }

    .right{
        width: 15%;
        height: 30%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        margin-right: 3%;
        margin-bottom: 4%;
    }

    .history{
        display: flex;
        margin-top: 10%;
    }

    .image{
        height: 200%;
        width: 200%;
    }
    .image > img{
        height: 100%;
        width: 100%;
    }
    .paragraphe{
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .paragraphe > *{
        margin-top: 10%;
        margin-left: 20%;
        margin-right: 20%;
    }

    #titre_histoire{
        font-size: 130%;
    }
    #histoire{
        font-size:15px;
    }

    #nouveauté{
        display: flex;
        flex-direction: column;
        margin-top: 60px;
        margin-left: 30px;
    }

    #nouveauté > li{
        margin-left: 30px;
    }

    .container {
        grid-row: 3;
        grid-column: 1 /4;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        margin: 20px 20px 80px;
        gap: 32px;
        margin-top: 60px;
        height: auto;
    }

    #coll-container{

        grid-row: 3;
        grid-column: 1 /4;
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 20px 20px 80px;
        gap: 32px;
        margin-top: 60px;
        height: auto;
    }
    .item {
        position: relative;
        background-size: cover;
        background-position: center;
        max-width: 100%;
        aspect-ratio: 1 / 1;
        transition: box-shadow 0.5s ease;
        display: flex;
        flex-direction: column;
        justify-content: end;
        align-items: center;
    }
    
    .item:hover {
        box-shadow: 0 0 8px 4px rgba(0, 0, 0, 0.2);
        z-index: 10;
    }

    .item-text {
    
    font-weight: bolder;
    font-size: clamp(10px, 2vw, 18px);
    }

    .item > *{
        margin-bottom: 10px;
    }

    #collection{
        margin-top: 10%;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 50px;
        margin-right: 50px;
    }

    #coll_item::before{
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.3));
        pointer-events: none; 
    }
    #name ,#description{
        color: #ffffff;
        margin-left: 20%;
        margin-bottom: 30px;
        margin-right: 20%;
        position: relative; 
        z-index: 1;
    }
    
    @media (max-width: 600px) {
        .hero{
            flex-direction: column;
            align-items: center;
        }
        .left, .right{
            margin-top: 15%;
            height:100%;
            width: 100%;
            align-items: center;
            text-align: center;
        }

        .left > li{
            margin-bottom: 20px;
            list-style-type: none;
            color:#000000;
            font-size: 200%;
        }
        .text_fleche{
            height:100%;
            width:200px;
        }

        .image{
        height: 100%;
        width: 100%;

        }
        .image > img{
            height: 100%;
            width: 900%;
        }
        .paragraphe > li{
            color:#ffffff
        }

        .item > span{
            filter: opacity(0%);
        }
        #a_propos{
            color:#ffffff;
        }

        #a_propos > img{
            filter: invert(1);
        }

        .container, #coll-container{
            grid-template-columns: 1fr;
        }
    }
</style>
