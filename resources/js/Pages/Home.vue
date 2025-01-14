<template>
    <Header currentPage="Accueil"></Header>
    <div class="hero">
        <video autoplay muted loop class="background-video">
            <source src="/public/images/video1.mp4" type="video/mp4">
        </video>
        <div class="left">
            <p class="font-title" id="titre">Illuminez vos journées</p>
            <p class="font-body-m">L'avenir ce dessine avec Laurelin, transformer vos rêves en réalité</p>
            <a href="/bijoux" class="btn-side">Découvrir nos bijoux</a>
        </div>
        <div class="right">
            <div class="first">
                <a href="/categories/Bagues">
                    <div class="text_fleche">
                        <p>Nouvelle Bague</p>
                        <span class="material-symbols-rounded">east</span>
                    </div>
                </a>
            </div>
            <div class="second">
                <p>une autre</p>
            </div>
        </div>
    </div>
    <div id="nouveauté">
        <p class="font-title-24">Nouveauté</p>
        <div class="container">
                <div v-for="produit in produits" :key="produit.ID" class="item" :data-id="produit.ID" :style="{ backgroundImage: `url(${produit.IMAGES[0]})` }" @click="redirectOnClick(produit.ID)">
                    <span class="item-text font-subtitle-16">{{ produit.NOM }}</span>
                    <span class="materiaux-text font-subtitle-16">{{ produit.MATERIAUX }}</span>
                    <a class="btn-side" :href="'/produit/'+produit.ID">Découvrir</a>
                </div>
            </div>
    </div>
    <div class="history">
        <div class="image">
            <img src="/public/images/history.png">
        </div>
        <div class="paragraphe">
            <p id="titre_histoire" class="font-title-24">Notre histoire</p>
            <p class="font-normal-12" id="histoire">Depuis ses débuts, Laurelin a repoussé les limites de l'artisanat de luxe. Chaque création est pensée comme une œuvre d'art, unique et intemporelle. La maîtrise des techniques ancestrales se marie avec une innovation constante. Nous sélectionnons uniquement les matériaux les plus précieux et durables. Notre savoir-faire incarne un héritage qui traverse les générations. </p>
            <a href="/histoire">
                    <div class="text_fleche" id="a_propos">
                        <p>A propos de nous</p>
                        <span class="material-symbols-rounded">east</span>
                    </div>

                </a>
        </div>
    </div>
    <div id="collection">
        <p class="font-title-24">Nos Collections</p>
        <div id="coll-container">
            <div v-for="collection in collections" :key="collection.ID" class="item" id="coll_item" :data-id="collection.ID" :style="{ backgroundImage: `url(/pictures/collections/${collection.ID}.avif) ` }" @click="redirectOnClick(collection.ID)">
                <span id="name" class="item-text font-subtitle-16">{{ collection.NOM }}</span>
                <span id="description" class="materiaux-text font-subtitle-16">{{ collection.DESCRIPTION.substring(0, 90) + "..." }}</span>
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
    html, body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        height: 100%; /* Désactive le défilement horizontal */
    }

    .background-video {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: translate(-50%, -50%);
        z-index: -1;
    }

    .hero {
        width: 100vw;
        height: 100vh;
        position: relative;
        overflow: hidden;
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
        background-image: url("/public/images/imgProd/w1242_tpadding12.webp");
        background-size: cover;
        background-position: center;
        filter: opacity(90%);
        display: flex;
        align-items: end;
        justify-content: center;
        height:120%;
        border-radius: 10%;
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
        width: 90%;
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
        margin-top: 115px;
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

    @media screen and (max-width: 810px) and  (max-height: 1080px){
        .hero{
            flex-direction: column;
            align-items: center;
        }
        .left, .right{
            margin-top: 30%;
            height:100%;
            width: 100%;
            align-items: center;
            justify-content: end;
            text-align: center;
        }

        .left > li{
            margin-bottom: 20px;
            list-style-type: none;
            color:#000000;
            font-size: 150%;
        }

        #titre{
            font-size: 150%;
        }

        .text_fleche{
            height:100%;
            width:200px;
        }

        .history{
            overflow: hidden;
        }
        .paragraphe{
            overflow: hidden;
            z-index: 1;
            margin-bottom: 50px;
        }
        .image{
        height: 100%;
        width: 100%;
        position: absolute;


        }
        .image > img{
            height: 600px;
            min-width: 1100px;

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
        .right{
            display: none;
        }
    }
</style>
