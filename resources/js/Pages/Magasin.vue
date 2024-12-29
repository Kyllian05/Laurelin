<template>
    <Header currentPage="Nos bijoux"></Header>
    <div class="ImgDefillante">
        <div class="text-overlay">La collection
            <li>Bijoux Laurelin</li>
        </div>
        <img src="/public/images/img_Nos_Bijoux_finale.jpg">
    </div>
    <div class = "BasPage">
        <div class="text-overlay" id="Catégorie">
            <li>Catégorie</li>
        </div>
        <div class="text-overlay" id="Collection">
            <li>Collection</li>
        </div>
    </div>
    <div class="ImgDefillante" v-for="categorie in allCategories">
        <img :src="'/images/'+categorie.img+'_img.webp'">
        <div class="text-overlay">{{ categorie.text }}</div>
    </div>

    <Footer></Footer>
</template>

<script setup>
import Footer from "./Components/Footer.vue"
import Header from "./Components/Header.vue";

let allCategories = [
    {text : "Bague",img : "second"},
    {text : "Colliers", img : "third"},
    {text : "Bracelets",img :"fourth"},
    {text : "Boucles d'oreilles", img : "fifth"}
]
</script>

<style scoped>
    /*.ImgDefillante {
        width: 100%;
        height: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: row;
    }


    .ImgDefillante img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }*/

    /*

    .BasPage {
        width: 100%;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #Collection {
        background-color: whitesmoke;
        position: relative;
        width: 50%;
        height: 100%;
        text-align: center;

    }

    #Catégorie {
        background-color: black;
        position: relative;
        width: 50%;
        height: 100%;
        text-align: center;
    }*/
    .ImgDefillante {
    width: 100%;
    height: auto;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    }

    .ImgDefillante img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .text-overlay {
        position: absolute;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 3em;
        font-weight: bold;
        /*text-shadow: 2px 5px 5px rgba(0, 0, 0, 0.7); */
        text-align: center;
        z-index: 1;
        list-style: none;

    }

    .BasPage {
        width: 100%;
        height: 250px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }


    #Catégorie {
        display: flex;
        background-color: black;
        position: relative;
        width: 50%;
        height: 100%;
        color: white;
        justify-content: center;
        text-align: center;
        transform: translate(-100%, -0%);
    }

    #Collection {
        display: flex;
        background-color: whitesmoke;
        color : black;
        position: relative;
        width: 50%;
        height: 100%;
        justify-content: center;
        text-align: center;
        transform: translate(-100%, 0%);
    }

    li {
    display: flex;
    justify-content: center;  /* Centrer le texte horizontalement */
    align-items: center;  /* Centrer le texte verticalement */
    height: 100%;  /* Assurer que l'élément li occupe toute la hauteur */
    margin: 0;  /* Enlever la marge par défaut sur les éléments li */
}
    #Collection :hover{
        background-color: rgb(199, 207, 214);
        color : whitesmoke;
        width: 103%;
        height: 103%;
        border-radius: 0% 0% 3% 3%;
    }

    #Catégorie :hover{
        background-color: rgb(41, 45, 48);
        color : whitesmoke;
        width: 103%;
        height: 103%;
        border-radius:0% 0% 3% 3%;
    }

</style>
