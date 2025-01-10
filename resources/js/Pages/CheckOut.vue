
<template>
    <Header current-page="CheckOut"></Header>
        <div id="Page">
            <p id="Title" class="font-subtitle-16">Commande</p>
            <div id="DonneeCommande">
                <div id="infoWrapper" class="wrapper" :class="currentStep > 1 ? 'cursor' : '    '">
                    <p id="info" class="font-subtitle-16">1 - Informations Personnelles</p>
                    <div id="contenuInfo" v-if="currentStep == 1">
                        <p id="texteInfo" class="font-body-m"> Pour poursuivre votre commande veuillez vous identifiez</p>
                        <button class="font-body-l">Se connecter</button>
                        <button class="font-body-l">S'inscrire</button>
                    </div>
                    <div id="contenuInfo" v-else>
                        <p>Vous commandez avec cette adresse mail : {{ user["EMAIL"] }} </p>
                    </div>
                </div>

                <div id="adresseWrapper" class="wrapper" :class="currentStep > 2 ? 'cursor' : ''">
                    <p id="adresse" class="font-subtitle-16">2 - Adresse de livraison</p>
                    <div id="contenuAdresse" v-if="currentStep == 2">
                        <div id="adresseChoiceWrapper">
                            <button :class="{adresseChoiceActive :  adresseMethod === 'domicile'}" @click="changeadresseMethod()" class="font-subtitle-16">a domicile</button>
                            <button :class="{adresseChoiceActive :  adresseMethod === 'retirer'}" @click="changeadresseMethod()" class="font-subtitle-16">retirer en magasin</button>
                        </div>
                        <div v-if="adresseMethod === 'domicile'">
                            <div class="adresseUser" v-for="(adresse,index) in adresses">
                                <input class="radButton" type="radio" :checked="index == currentAdresse" @click="changeLivraison(index)" style="cursor: pointer">
                                <div class="adresseUserr">
                                    <p class="font-subtitle-16 adresse">{{ adresse["NUM_RUE"] }} {{ adresse["NOM_RUE"] }}</p>
                                    <p class="font-subtitle-16 codePostale">{{ adresse["CODE_POSTAL"] }}</p>
                                    <p class="font-subtitle-16 ville">{{ adresse["VILLE"] }}</p>
                                </div>
                            </div>
                            <button id="adresseValidateButton" @click="validateLivraison()">
                                <span class="material-symbols-rounded">
                                    location_on
                                </span>
                                <p>Valider</p>
                            </button>
                        </div>
                        <div v-else>
                            <select id="selectVille" class="font-subtitle-16">
                                <option value="">Choisir une ville</option>
                                <!---TODO: mettre les villes de la base de dounées-->
                            </select>
                        </div>
                    </div>
                    <div v-else-if="currentStep > 2" @click="annuleLivraison()">
                        <p class="font-subtitle-16 adresse">{{ data["adresse"]["NUM_RUE"] }} {{ data["adresse"]["NOM_RUE"] }}</p>
                        <p class="font-subtitle-16 codePostale">{{ data["adresse"]["CODE_POSTAL"] }}</p>
                        <p class="font-subtitle-16 ville">{{ data["adresse"]["VILLE"] }}</p>
                    </div>
                </div>

                <div id="livraisonWrapper" class="wrapper" :class="currentStep > 3 ? 'cursor' : ''">
                    <p id="livraison" class="font-subtitle-16">3 - Options de Livraison</p>
                </div>

                <div id="paiementWrapper" class="wrapper" :style="currentStep == 4 ? 'padding-bottom: 5vh;' : ''">
                    <p id="paiement" class="font-subtitle-16">4 - Options de paiement</p>
                    <div v-if="currentStep == 4" id="paiementContent">
                        <div class="paimentFieldWrapper" style="flex-direction: column">
                            <input placeholder="PRENOM ET NOM" class="font-body-l paiementinput1">
                            <input placeholder="NUMERO DE LA CARTE" class="font-body-l paiementinput1">
                        </div>
                        <div class="paimentFieldWrapper" style="flex-direction: row;gap: 5vw">
                            <input placeholder="MOIS" class="font-body-l">
                            <input placeholder="ANNEE" class="font-body-l">
                        </div>
                        <div class="paimentFieldWrapper" style="width: 20%">
                            <input placeholder="CRYPTOGRAME" class="font-body-l">
                        </div>
                        <ButtonSubmit button-text="Payer" id="payButton" @click="paye()"></ButtonSubmit>
                    </div>
                </div>
            </div>

            <div id="Recap">
                <p id="RecapTitle" class="font-subtitle-16">Récapitulatif de commande</p>
                <div id="produitComander" class="font-subtitle-16" v-for="produit in produits">
                    <p>{{ produit["NOM"] }} x{{ produit["QUANTITE"] }} <b>{{ produit["PRIX"] * produit["QUANTITE"] }} €</b></p>
                </div>
                <div id="sousTot">
                    <p id="soustotal" class="font-subtitle-16">sous-total <b>{{ Math.floor(sum/1.2) }} €</b></p>
                    <p id="tva" class="font-subtitle-16">tva <b>{{ sum-Math.floor(sum/1.2) }} €</b></p>
                </div>
                <div id="Tot">
                    <p id="total" class="font-subtitle-16">total <b>{{ sum}} €</b></p>
                </div>

            </div>
        </div>
    <Footer></Footer>
</template>

<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import Form from "./Components/Form.vue";
import {ref, toRaw} from "vue";
import ButtonSubmit from "./Components/ButtonSubmit.vue";

let props = defineProps({
    "user" : Object,
    "adresses" : Array,
    "produits":Array
})

let currentAdresse = ref(0)

let sum = 0

for(let i = 0;i<props.produits.length;i++){
    sum += props.produits[i]["PRIX"] * props.produits[i]["QUANTITE"]
}

let data = {}

let adresseMethod = ref("domicile")

let currentStep = ref(2)

function paye(){
    fetch("/checkout/valider",{
        method : "POST",
        body : JSON.stringify({
            "adresse" : props.adresses[currentAdresse.value]["ID"],
            "livraison" : adresseMethod.value
        }),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    })
}

function changeLivraison(index){
    currentAdresse.value = index
}

function annuleLivraison() {
    if(currentStep.value > 2){
        currentStep.value = 2
    }
}

function validateLivraison(){
    data["adresse"] = toRaw(props["adresses"])[currentAdresse.value]
    currentStep.value += 2
}

function changeadresseMethod(){
    if(adresseMethod.value === "domicile")adresseMethod.value = "retirer"
    else adresseMethod.value = "domicile"
}
</script>

<style scoped>
.cursor{
    cursor: pointer;
}
#payButton{
    width: 50%;
    margin-left: 50%;
    transform: translateX(-50%);
    padding: 0.8vw 0px;
}
.paimentFieldWrapper{
    display: flex;
    width: 50%;
    margin-left: 2vw;
}
.wrapper{
    background-color: rgb(225,225,225);
    margin-top: 2.5vh;
}
#paiementWrapper input{
    border: none;
    border-bottom: solid 1px black;
    background-color: transparent;
    width: 100%;
    margin-top: 3.5vh;
}
#adresseValidateButton:hover {
    background-color: white;
    color: black;
}
#adresseValidateButton{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 50%;
    height: clamp(45px, 3vw, 65px);
    margin-left: 50%;
    transform: translateX(-50%);
    margin-bottom: 45px;
    border-radius: 10px;
    background-color: black;
    color: white;
    border: 1px solid;
    cursor: pointer;
    gap: .25vw;
    transition: color 0.5s ease, background-color 0.5s ease, transform 0.1s ease;
}
#Page{
    display: flex;
    flex-wrap: wrap;
    margin-top: 119px;
    justify-content: center;
    margin-bottom: 100px;
}
#Title{
    width: 94%;
    padding: 40px;
    text-align: center;
    border-top: 1px solid black;
    border-bottom: 1px solid black;

}
#DonneeCommande {
    flex: 1 1 40%;
    margin-left: 3%;
    padding-right: 3%;
}
#infoWrapper {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}
#contenuInfo {
    text-align: center;
    padding-top: 20px;
}
#contenuInfo button {
    margin: 10px;
    width: 200px;
    height: 50px;
    border-radius: 10px;
    border: 1px solid black;
    background-color: black;
    color: white;
    transition: 0.5s ease;
}
#contenuInfo button:hover {
    background-color: white;
    color: black;
}
#adresseWrapper {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}
#adresseChoiceWrapper button{
    background: none;
    border: none;
    padding-bottom: 8px;
    cursor: pointer;
}
#adresseChoiceWrapper{
    display: flex;
    flex-direction: row;
    width: 80%;
    margin-left: 50%;
    transform: translateX(-50%);
    border-bottom: solid 1px #E9E9E9;
    justify-content: space-around;
    margin-top: 20px;
    margin-bottom: 40px;
}
.adresseChoiceActive{
    border-bottom: solid 2px black !important;
}
#selectVille {
    margin-left: 50%;
    transform: translateX(-50%);
    border-bottom: solid 2px black !important;
    padding: 2px;
    justify-content: space-around;
    background: none;
    border: none;
    cursor: pointer;
    width: 230px;
}
.adresseUser {
    display: grid;
    grid-template-columns: 1fr 1fr;
    padding: 0px 0 20px 50px;
    width: 200px;

}
.radButton {
    grid-column: 1;
    width: 20px;
    margin: 25px;
}
.adresseUserr {
    grid-column: 2;
    width: 300px;
}
#livraison {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}
#paiement {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}
#Recap{
    flex: 1 1 20%;
    padding: 25px;
    margin-right: 3%;
}
#produitComander{
    border-top: 1px solid black;
    margin-top: 30px;
}
#Tot {
    border-top: 1px solid black;
    width: 80%;
    margin: 30px auto 0;
}
#sousTot{
    padding: 10px 0 0 15px;
}
#total {
    padding: 10px 0 0 15px;
}
</style>
