<template>
<Header current-page="Nos bijoux"></Header>
<div id="produitEnVente">
    <button class="boutton_acheter font-subtitle-16" >Acheter</button>
    <!--TODO rendre le bouton joli-->
    <button @click="favorisAction()" id="favoriteButton">{{!dynamicFavorite ? "Ajouter aux favoris" : "Supprimer des favoris"}}</button>
</div>
    <pre v-if="produit">
        {{produit}}
    </pre>
    <h3>Images :</h3>
    <img v-for="img in produit.images" :src="img">
<Footer></Footer>
</template>

<script setup>
import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import {ref} from "vue";

const props = defineProps({
    "produit" : Object,
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
            produit : props.produit.id
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

#favoriteButton{
    margin-top: 10vh;
}

#produitEnVente {
    height: 100vh;
    background: url("/public/images/imgProd/img_bague_tressage.jpg") no-repeat center 45%/cover;
}

#produitEnVente .boutton_acheter {
    position: absolute;
    bottom: 20%;

}

</style>
