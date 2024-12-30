<template>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <Header></Header>
    <div id="mainWrapper">
        <div id="navWrapper">
            <div v-for="nav in Object.keys(navConv)" :class="dynamicPage == nav ? 'currentNav' : ''" @click="dynamicPage = nav">
                <span class="material-symbols-rounded">{{ navConv[nav].icon }}</span>
                <p class="font-body-s">{{ navConv[nav].text }}</p>
            </div>
        </div>
        <div id="contentWrapper">
            <p id="pageInfo" class="font-body-s">{{ navConv[dynamicPage].text  }}</p>
            <hr style="margin-bottom: 2vh">
            <div v-if="dynamicPage == 'info'">
                <div id="formWrapper1">
                    <Form :fields="infoFields[0]" :check-boxs="[]" buttonText="Valider les modifications" :displayColumn="true" dest="/updateInfo" succeed-message="Les modifications ont bien étés effectuées"></Form>
                </div>
                <div id="formWrapper2">
                    <Form :fields="infoFields[1]" :check-boxs="[]" buttonText="Valider les modifications"  dest="/updateInfo" style="margin-top: 5vh"></Form>
                </div>
            </div>
            <div v-else-if="dynamicPage == 'commandes'" id="commandsWrapper">
                <div v-for="(commande,index) in commandes" class="commandWrapper" :class="index%2==1 ? 'grayBackground' :''">
                    <p class="font-body-s" style="margin-bottom: 2vh">Commande du {{commande["Date"]}}</p>
                    <p class="font-body-s" style="margin-bottom: 2vh">Produits :</p>
                    <ul>
                        <li v-for="produit in commande['Produits']">
                            <p class="font-body-s">{{ produit["Nom"] }} {{ produit["Quantité"] > 1 ? 'x'+produit["Quantité"] : '' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>

<script setup>
import {defineProps, ref} from "vue"
import Form from "./Components/Form.vue"
import Header from "./Components/Header.vue"
import Footer from "./Components/Footer.vue"

let props = defineProps(
    {
        "page":String,
        "info":Object,
        "commandes":Object
    })

    console.log(props.commandes)

    let dynamicPage = ref(props.page)

    let navConv = {
        "info" : {text:"Informations personnelles",icon:"person"},
        "commandes" : {text: "Mes commandes",icon:"shopping_cart"},
        "produits favoris" : {text:"Mes produits favoris",icon:"favorite"},
        "adresses" : {text:"Mes adresses",icon:"home"}
    }

    let infoFields = [[],[]]

    for(let i = 0;i<3;i++){
        infoFields[0].push({"name":Object.keys(props.info)[i],"value":props.info[Object.keys(props.info)[i]]})
    }

    infoFields[1].push({"name":Object.keys(props.info)[3],"value":props.info[Object.keys(props.info)[3]],"required":false})

</script>

<style scoped>
    .commandWrapper{
        padding-bottom: 2vh;
        padding-top: 2vh;
        padding-left: 1vw;
    }
    .grayBackground{
        background-color: #eee;
    }
    .commandWrapper ul{
        margin-left: 2vw;
        display: flex;
        flex-direction: column;
        gap: 1vh;
    }
    #commandsWrapper p{
        letter-spacing: 1px;
        font-size: 15px;
        font-weight: lighter;
    }
    #pageInfo{
        font-size: 18px;
        margin-bottom: 1.5vh;
        letter-spacing: 2px;
    }
    #navWrapper div:hover{
        background-color: black;
        color: white;
    }
    #navWrapper div p{
        width: max-content;
        text-align: center;
    }
    #mainWrapper{
        display: flex;
        flex-direction: row;
        gap: 10vw;
        width: fit-content;
        margin-left: 50%;
        transform: translateX(-50%);
        margin-top: 11vh;
    }
    .currentNav{
        background-color: black;
        color: white;
    }
    #navWrapper{
        display: flex;
        flex-direction: column;
        cursor: pointer;
        border: solid 1px gray;
        width: fit-content;
        height: fit-content;
    }
    #navWrapper div{
        height: 4vh;
        padding-left: 2vw;
        padding-right: 2vw;
        text-align: center;
        align-content: center;
        cursor: pointer;
        transition-duration: .2s;
        display: flex;
        justify-content: left;
        gap: 1vw;
        align-items: center;
        text-align: center;
    }
    #contentWrapper{
        width: 50vw;
        margin-bottom: 5vh;
    }
    #formWrapper2{
        margin-top: 5vh;
    }
</style>
