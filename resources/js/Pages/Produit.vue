<template>
    <Header current-page="Nos bijoux"></Header>

    <div id="page">
        <div id="produitEnVente">
            <pre v-if="produit">
            {{produit}}
            </pre>
            <button class="boutton_acheter font-subtitle-16" >Acheter</button>
            <!--TODO rendre le bouton joli-->
            <button @click="favorisAction()" id="favoriteButton">{{!dynamicFavorite ? "Ajouter aux favoris" : "Supprimer des favoris"}}</button>

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
    </div>

    <Footer></Footer>
</template>

<script setup>
import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import {ref} from "vue";

const props = defineProps({
    "produit" : Object,
    "images" : String,
    "isFavorite" : Boolean,
})

const dynamicFavorite = ref(props.isFavorite)

function favorisAction(){
    let destination = "/produit/ajouterFavoris"
    if(dynamicFavorite.value){
        destination = "/produit/supprimerFavoris"
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
</script>

<style scoped>

#page {
    display: grid;
    margin-top: 8%;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(2, auto);
}

#produitEnVente {
    grid-column: 3 / 4;
    grid-row: 1 / 2;
}

#produitEnVente .boutton_acheter {
    position: absolute;
    bottom: 20%;

}

#img1 {
    grid-column: 1 / 3;
    grid-row: 1;
    width: 52vw;
    height: auto;
}

#img2 {
    grid-column: 1/2;
    grid-row: 2;
    width: 26vw;
    height: auto;
}

#img3 {
    grid-column: 2 /3;
    grid-row: 2;
    width: 26vw;
    height: auto;
}

img {
    width: 100%;
    height: auto;
    display: block;
}




</style>
