<template>
<div id="header">
    <div id="imgWrapper">
        <img src="/public/images/logo-simple.png" alt="Logo">
        <a href="/">Laurelin</a>
    </div>
    <div id="centerWrapper">
        <div v-for="page in Object.keys(allPages)" :class="{selected : currentPage === page}" @click="redirect(page)">
        {{ page }}
        </div>
    </div>
    <div id="btn-wrapper">
        <a class="font-body-m btn-side">
            <span class="material-symbols-rounded">location_on</span>
        </a>
        <a href="/auth/login" class="font-body-m btn-side">
            <span class="material-symbols-rounded">person</span>
        </a>
        <a class="font-body-m p-side btn-side">
            <span class="material-symbols-rounded">shopping_bag</span>
            Pannier
        </a>
    </div>
</div>
</template>

<script setup>
    defineProps(["currentPage"])

    let allPages = {
        "Accueil":"/",
        "Nos bijoux":"/categories",
        "Notre histoire" : "/histoire",
        "Contact":"/contact"
    }

    function redirect(page){
        window.location = allPages[page]
    }
</script>

<style scoped>
    #header {
        position: fixed;
        display: flex;
        justify-content: space-between;
        align-items: center;
        top: 0;
        width: 100%;
        padding: 32px 48px;
        z-index: 1000;
    }

    /* Logo left */
    #imgWrapper a{
        font-family: "Parisienne", serif;
        font-size: 40px;
        text-decoration: none;
        color: #000000;
    }
    #imgWrapper{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
    }
    img{
        height: 48px;
        width: auto;
        filter: brightness(0%) grayscale(100%);
    }

    /* Button right */
    #btn-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
    }
    .btn-side {
        background-color: white;
        color: #000000;
        text-decoration: none;
        cursor: pointer;
        border-radius: 8px;
        padding: 8px ;
        transition-duration: .3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }
    .btn-side:hover{
        background-color: #252525;
        color: #ffffff;
    }
    .p-side {
        padding: 8px 18px;
    }

    /* Nav center */
    #centerWrapper{
        display: flex;
        gap: 16px;
        padding: 4px 6px;
        border-radius: 50px;
        background-color: #ffffff;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }
    #centerWrapper div{
        font-family: "Poppins", sans-serif;
        padding: 8px 16px;
        border-radius: 50px;
        cursor: pointer;
        transition-duration: .3s;
    }
    #centerWrapper div:hover{
        background-color: #efefef;
    }
    .selected{
        background-color: #000000;
        color: #ffffff;
    }
    #centerWrapper .selected:hover {
        background-color: #000000;
        color: #ffffff;
    }
</style>
