<template>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=shopping_bag" />
    <div id="imgWrapper">
        <img src="/public/images/logoSansTexte.png">
        <p>Laurelin</p>
    </div>
    <div id="centerWrapper">
        <div v-for="page in Object.keys(allPages)" :class="{selected : currentPage === page}" @click="redirect(page)">
        {{ page }}
        </div>
    </div>
    <button>
        <span class="material-symbols-outlined">
            shopping_bag
        </span>
        Pannier
    </button>
</template>

<script setup>
    import { defineProps } from 'vue';

    defineProps(["currentPage"])

    let allPages = {
        "Accueil":"/",
        "Magasin":"/shop",
        "Notre histoire" : "/story",
        "Contact":"/contact"
    }

    function redirect(page){
        window.location = allPages[page]
    }
</script>

<style scoped>
    button{
        right: 5vw;
        font-family: "Roboto";
        background-color: white;
        padding: 0.5vw;
        border: none;
        top: 2.5vh;
        cursor: pointer;
        border-radius: 10px;
        padding-left: 1vw;
        padding-right: 1vw;
        transition-duration: .5s;
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 0.5vw;
        position: absolute;
    }
    button:hover{
        background-color: black;
        color: white;
        transform: scale(1.1);
    }
    #imgWrapper p{
        font-family: "Parisienne", serif !important;
        font-size: 30px;
        margin: 0px;
    }
    #imgWrapper{
        display: flex;
        flex-direction: row;
        gap: 2vw;
        align-items: center;
        position: absolute;
        top: 2.5vh;
    }
    img{
        width: 5vw;
        height: 5vw;
    }
    #centerWrapper{
        gap: 2.5vw;
        background-color: white;
        padding: .5vw;
        border-radius: 50px;
        width: fit-content;
        display: flex;
        flex-direction: row;
        margin-left: 50%;
        transform: translateX(-50%);
        top: 2.5vh;
        position: sticky;
    }
    .selected{
        background-color: black;
        color: white;
    }
    #centerWrapper div:hover{
        background-color: black;
        color: white;
    }
    #centerWrapper div{
        font-family: "Roboto", serif;
        padding: .75vw;
        border-radius: 50px;
        cursor: pointer;
        transition-duration: .5s;
    }
</style>
