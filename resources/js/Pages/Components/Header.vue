<template>
<div id="header">
    <link rel="icon" href="/images/logo-simple.png" type="image/png">
    <div id="burgerMenu" class="btn-side p-side" @click="showMenu = true">
        <span class="material-symbols-rounded">menu</span>
    </div>
    <a href="/" id="imgWrapper">
        <img src="/public/images/logo-simple.png" alt="Logo">
        <span>Laurelin</span>
    </a>
    <div id="centerWrapper">
        <nav>
            <a v-for="page in Object.keys(allPages)" :class="{selected : currentPage === page}" :href="allPages[page]">
                {{ page }}
            </a>
        </nav>
        <div id="search" @click="showSearch = true">
            <span class="material-symbols-rounded">search</span>
        </div>
    </div>
    <div id="btn-wrapper">
        <a href="/carte" class="btn-side" :class="{selected : currentPage === 'carte'}">
            <span class="material-symbols-rounded">location_on</span>
        </a>
        <a href="/account" class="btn-side" :class="{selected : currentPage === 'account'}">
            <span class="material-symbols-rounded">person</span>
        </a>
        <a href="/panier" class="p-side btn-side" :class="{selected : currentPage === 'panier'}">
            <span class="material-symbols-rounded">shopping_bag</span>
            <p>Panier</p>
            <p id="panierNumber" v-if="numberInPanier > 0">{{ numberInPanier }}</p>
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
        <a class="item" @click="showSearch = true">
            <span class="material-symbols-rounded">search</span>
            Rechercher
        </a>
        <a href="/carte"  class="item" :class="{selected : currentPage === 'carte'}">
            <span class="material-symbols-rounded">location_on</span>
            Nos boutiques
        </a>
        <a href="/account" class="item" :class="{selected : currentPage === 'account'}">
            <span class="material-symbols-rounded">person</span>
            Espace personnel
        </a>
    </div>
</div>
<div id="searchMenu" v-show="showSearch" @click.self="showSearch = false">
    <div id="searchForm">
        <label for="query" id="searchBar">
            <span class="material-symbols-rounded">search</span>
            <input type="search" placeholder="Rechercher un produit" name="query" v-model="searchQuery" ref="searchInput">
            <span class="material-symbols-rounded" @click.self="searchQuery = ''">backspace</span>
            <span class="material-symbols-rounded" @click.self="showSearch = false">close</span>
        </label>
        <ul id="searchResult">
            <li v-if="!results.length">Aucun résultat</li>
            <li v-for="result in results" :key="result.ID" @click="handleClick(result.ID)">{{ result.NOM }}</li>
        </ul>
    </div>
</div>
</template>

<script setup>
    import { ref, watch, nextTick } from 'vue'

    const props = defineProps({
        "currentPage":String,
        "updatePanier":Boolean
    })

    const emits = defineEmits(["panierUpdated"])

    let allPages = {
        "Accueil":"/",
        "Nos bijoux":"/bijoux",
        "Notre histoire" : "/histoire",
        "Contact":"/contact"
    }

    const showMenu = ref(false)
    const showSearch = ref(false)
    const searchQuery = ref("")
    const debouncedQuery = ref("Laurelin")
    const results = ref([])
    const searchInput = ref(null)
    const numberInPanier = ref(0)

    getNumberInPanier()

    function handleClick(id) {
        window.location.href = `/produit/${id}`
    }

    // Request API
    const fetchResults = async (query) => {
        if (!query) return [];
        const response = await fetch('/search/'+query);
        return await response.json();
    };

    function getNumberInPanier(){
        fetch("/getNumberInPanier",{
            method : "GET",
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type":"application/json"
            },
        }).then(async response=> {
            if(response.status == 200){
                numberInPanier.value = await response.json()
            }
        })
    }

    watch(
        () => props.updatePanier, // Utiliser une fonction pour observer la prop
        () => {
            if(props.updatePanier == true){
                getNumberInPanier()
                emits("panierUpdated")
            }
        }
    )

    // Watcher pour appliquer un debounce à `searchQuery`
    let debounceTimeout = null;
    watch(
        searchQuery,
        (newQuery) => {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                debouncedQuery.value = newQuery;
            }, 500); // Délai de debounce
        }
    )

    // Watcher pour lancer la recherche lorsque `debouncedQuery` change
    watch(
        debouncedQuery,
        async (query) => {
        results.value = await fetchResults(query);
        },
        {immediate: true}
    );

    // Watcher pour empêcher le scroll lorsque la recherche est affichée
    watch(showSearch, async (newValue) => {
        if (newValue) {
            document.documentElement.style.overflow = "hidden"
            await nextTick()
            if (searchInput.value) {
                searchInput.value.focus()
            }
        } else {
            document.documentElement.style.overflow = "auto"
        }
    })
</script>

<style scoped>
  #panierNumber p{
      margin: 0px;
  }
    #panierNumber{
        position: absolute;
        top: -1vh;
        right: -1vw;
        background-color: black;
        color: white;
        border-radius: 100%;
        aspect-ratio: 1/1;
        width: 30px;
        text-align: center;
        align-items: center;
        justify-content: center;
        display: flex;
    }
    #header {
        position: fixed;
        display: flex;
        justify-content: space-between;
        align-items: center;
        top: 0;
        width: 100%;
        padding: 32px 48px;
        z-index: 500;
        background: linear-gradient(rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0));
    }

    /* Search */
    #searchMenu {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 502;
        backdrop-filter: blur(5px);
    }
    #searchForm {
        position: fixed;
        background: #ffffff;
        width: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 503;
        border-radius: 12px;
        box-shadow: 0 0 16px rgba(0, 0, 0, 0.3);
        padding: 32px;
        display: flex;
        flex-direction: column;
        gap: 32px;
    }
    #searchBar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: solid 1px #000000;
        padding-bottom: 8px;
    }
    #searchBar input {
        flex-grow: 2;
        margin: 0 16px;
        padding: 8px 16px;
        border: none;
        font-size: 16px;
    }
    #searchBar input:focus {
        outline: none;
    }
    #searchBar span:nth-child(3) {
        cursor: pointer;
        margin-right: 8px;
    }
    #searchBar span:nth-child(4) {
        cursor: pointer;
    }
    #searchResult {
        list-style: none;
        display: flex;
        flex-direction: column;
        max-height: 300px;
        overflow-y: scroll;
    }
    #searchResult li {
        padding: 16px 16px;
        cursor: pointer;
        border: solid 1px transparent;
        transition: all .1s;
    }
    #searchResult li:nth-child(even) {
        background: rgba(0, 0, 0, 0.05);
    }
    #searchResult li:hover {
        border: solid 1px #000000;
    }

    /* Menu burger */
    #burgerMenu, #sideMenu {
        display: none;
    }

    /* Logo left */
    #imgWrapper span{
        font-family: "Parisienne", serif;
        font-size: 40px;
        color: #000000;
    }

    #imgWrapper{
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        text-decoration: none;
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
    .btn-side.selected{
        background-color: #000000;
        color: #ffffff;
    }
    .btn-side.selected:hover{
        background-color: #000000;
        color: #ffffff;
    }
    .p-side {
        padding: 12px 22px;
        position: relative;
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
    @media screen and (max-width: 1000px) {
        #searchForm {
            width: 80%;
        }
    }
    @media screen and (max-width: 500px) {
        #searchForm {
            width: 100%;
        }
    }

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
        #sideMenu .itemWrapper .selected, #sideMenu .itemWrapper .selected:hover {
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
