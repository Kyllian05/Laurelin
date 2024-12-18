<template>
    <div id="globalWrapper">
        <div v-for="page in Object.keys(allPages)" :class="{selected : currentPage == page}" @click="redirect(page)">
            {{ page }}
        </div>
    </div>
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
    .selected{
        background-color: black;
        color: white;
    }
    #globalWrapper div:hover{
        background-color: black;
        color: white;
    }
    #globalWrapper div{
        font-family: "Roboto", serif;
        padding: .75vw;
        border-radius: 50px;
        cursor: pointer;
        transition-duration: .5s;
    }
    #globalWrapper{
        width: fit-content;
        display: flex;
        flex-direction: row;
        position: sticky;
        margin-left: 50%;
        transform: translateX(-50%);
        top: 5vh;
        gap: 2.5vw;   
        background-color: white;
        padding: .5vw;
        border-radius: 50px;
    }
</style>