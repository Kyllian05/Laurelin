<template>
<div id="header">
    <div id="burgerMenu" class="btn-side p-side" @click="showMenu = true">
        <span class="material-symbols-rounded">menu</span>
    </div>
    <div id="imgWrapper">
        <img src="/public/images/logo-simple.png" alt="Logo">
        <a href="/">Laurelin</a>
    </div>
    <div id="centerWrapper">
        <nav>
            <a v-for="page in Object.keys(allPages)" :class="{selected : currentPage === page}" :href="allPages[page]">
                {{ page }}
            </a>
        </nav>
        <div id="search">
            <span class="material-symbols-rounded">search</span>
        </div>
    </div>
    <div id="btn-wrapper">
        <a class="btn-side">
            <span class="material-symbols-rounded">location_on</span>
        </a>
        <a href="/auth/login" class="btn-side">
            <span class="material-symbols-rounded">person</span>
        </a>
        <a class="p-side btn-side">
            <span class="material-symbols-rounded">shopping_bag</span>
            <p>Pannier</p>
        </a>
    </div>
</div>
<div id="sideMenu" :class="{show : showMenu === true}">
    <span class="material-symbols-rounded" @click="showMenu = false" id="closeBtn">close</span>
    <div class="nav">
        <a v-for="page in Object.keys(allPages)" :class="{selected : currentPage === page}" :href="allPages[page]">
            {{ page }}
        </a>
    </div>
    <div class="itemWrapper">
        <a class="item">
            <span class="material-symbols-rounded">search</span>
            Rechercher
        </a>
        <a class="item">
            <span class="material-symbols-rounded">location_on</span>
            Nos boutiques
        </a>
        <a href="/auth/login" class="item">
            <span class="material-symbols-rounded">person</span>
            Espace personnel
        </a>
    </div>
</div>
</template>

<script setup>
    import { ref } from 'vue'

    defineProps(["currentPage"])

    let allPages = {
        "Accueil":"/",
        "Nos bijoux":"/categories",
        "Notre histoire" : "/histoire",
        "Contact":"/contact"
    }

    const showMenu = ref(false)
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
        z-index: 500;
    }

    /* Menu burger */
    #burgerMenu, #sideMenu {
        display: none;
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
        gap: 8px;
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
    }
    .btn-side:hover{
        background-color: #252525;
        color: #ffffff;
    }
    .p-side {
        padding: 12px 22px;
    }

    /* Nav center */
    #centerWrapper {
        display: flex;
        gap: 8px;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }
    #centerWrapper nav{
        display: flex;
        gap: 16px;
        padding: 4px 6px;
        border-radius: 50px;
        background-color: #ffffff;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }
    #centerWrapper nav a{
        font-family: "Poppins", sans-serif;
        font-size: 16px;
        padding: 8px 16px;
        border-radius: 50px;
        cursor: pointer;
        transition-duration: .3s;
        color: #000000;
        text-decoration: none;
    }
    #centerWrapper nav a:hover{
        background-color: #efefef;
    }
    #centerWrapper nav .selected{
        background-color: #000000;
        color: #ffffff;
    }
    #centerWrapper nav .selected:hover {
        background-color: #000000;
        color: #ffffff;
    }
    #search {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 8px 12px;
        background: #ffffff;
        border-radius: 50px;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
        transition-duration: .3s;
        cursor: pointer;
    }
    #search:hover {
        background-color: #252525;
        color: #ffffff;
    }

    /* Media queries */
    @media screen and (max-width: 1200px) {
        #header {
            padding: 20px;
        }

        /* Burger menu */
        #burgerMenu {
            display: flex;
        }
        #sideMenu {
            position: absolute;
            width: 70%;
            max-width: 500px;
            height: 100vh;
            background: #fff;
            z-index: 501;
            display: flex;
            left: -100%;
            transition: left .7s ease-in-out;
            flex-direction: column;
            justify-content: start;
            gap: 32px;
            padding: 32px;
            box-shadow: 8px 0 16px rgba(0, 0, 0, 0.2);
        }
        #sideMenu.show {
            left: 0;
        }
        #sideMenu div {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        #sideMenu a {
            color: #000000;
            text-decoration: none;
            cursor: pointer;
        }
        #sideMenu .nav {
            border-top: solid 1px #000000;
            border-bottom: solid 1px #000000;
            padding: 32px 0;
        }
        #sideMenu .nav a {
            padding: 8px 18px;
            border-radius: 50px;
            width: fit-content;
        }
        #sideMenu .nav a:hover, #sideMenu .itemWrapper a:hover {
            background-color: #efefef;
        }
        #sideMenu .nav .selected, #sideMenu .nav .selected:hover {
            background: #000;
            color: #ffffff;
        }
        #sideMenu #closeBtn {
            cursor: pointer;
            padding: 8px;
        }
        #sideMenu #closeBtn:hover {
            background-color: #252525;
            color: #ffffff;
            border-radius: 50px;
            width: fit-content;
        }
        #sideMenu .itemWrapper a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 50px;
            width: fit-content;
        }

        /* Logo left */
        #imgWrapper{
            flex-direction: column;
            gap: 0;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        #imgWrapper a{
            font-size: 30px;
        }
        img{
            height: 35px;
            transform: translateY(10px);
        }

        /* Center */
        #centerWrapper {
            display: none;
        }

        /* Btn side */
        .btn-side {
            display: none;
        }
        .btn-side.p-side {
            display: flex;
            padding: 10px;
        }
        .btn-side.p-side p {
            display: none;
        }
    }
</style>
