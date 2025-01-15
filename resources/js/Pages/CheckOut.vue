<template>
    <Header current-page="CheckOut"></Header>
    <Error :message="errorMesage" v-if="errorMesage != ''" @click="errorMesage = ''"></Error>
    <div v-if="paiementState && errorMesage == ''" id="paimentBackground">
        <div id="paiementPopupWrapper">
            <div id="paimentPopupContent">
                <img src="/public/images/loading.gif">
                <p>Nous procédons au paiment</p>
            </div>
        </div>
    </div>
    <div id="Page">
        <p id="Title" class="font-subtitle-16">Commande</p>
        <div id="DonneeCommande">
            <div id="infoWrapper" class="wrapper" :class="['wrapper', currentStep === 1 ? 'bgGris' : '', currentStep > 1 ? 'cursor' : '']">
                <p id="info" class="font-subtitle-16">1 - Informations Personnelles</p>
                <div id="contenuInfo" v-if="currentStep === 1">
                    <p id="texteInfo" class="font-body-m"> Pour poursuivre votre commande veuillez vous identifiez</p>
                    <button class="font-body-l">Se connecter</button>
                    <button class="font-body-l">S'inscrire</button>
                </div>
                <div id="contenuInfo" v-else>
                    <p>Vous commandez avec cette adresse mail : {{ user["EMAIL"] }} </p>
                </div>
            </div>

            <div id="adresseWrapper" class="wrapper" :class="['wrapper', currentStep === 2 ? 'bgGris' : '', currentStep > 2 ? 'cursor' : '']">
                <p id="adresse" class="font-subtitle-16">2 - Adresse de livraison</p>
                <div id="contenuAdresse" v-if="currentStep === 2">
                    <div id="adresseChoiceWrapper">
                        <button :class="{adresseChoiceActive :  adresseMethod === 'domicile'}" @click="changeadresseMethod()" class="font-subtitle-16">a domicile</button>
                        <button :class="{adresseChoiceActive :  adresseMethod === 'magasin'}" @click="changeadresseMethod()" class="font-subtitle-16">retirer en magasin</button>
                    </div>
                    <div v-if="adresseMethod === 'domicile'">
                        <div class="adresseUser" v-for="(adresse,index) in adresses">
                            <input class="radButton" type="radio" :checked="index === currentAdresse" @click="changeLivraison(index)" style="cursor: pointer">
                            <div class="adresseUserr">
                                <p class="font-subtitle-16 adresse">{{ adresse.NUM_RUE }} {{ adresse.NOM_RUE }}</p>
                                <p class="font-subtitle-16 codePostale">{{ adresse.VILLE.CODE_POSTAL }}</p>
                                <p class="font-subtitle-16 ville">{{ adresse.VILLE.NOM }}</p>
                            </div>
                        </div>
                    </div>
                    <div v-if="adresseMethod === 'magasin'">
                        <Field name="Code Postal" @input="codePostale => searchMagasins(codePostale)" style="width: 50%;margin-left: 50%;transform: translateX(-50%);margin-bottom: 2vh"></Field>
                        <select id="selectVille" class="font-subtitle-16" v-model="currentMagasin">
                            <option :value="magasin" v-for="magasin in magasinsRecomm">{{ magasin.ADRESSE }} {{ magasin.VILLE.NOM }} {{ magasin.VILLE.CODE_POSTAL }}</option>
                        </select>
                    </div>
                    <button id="adresseValidateButton" @click="validateLivraison()">
                            <span class="material-symbols-rounded">
                                location_on
                            </span>
                        <p>Valider</p>
                    </button>
                </div>
                <div v-else-if="currentStep > 2" @click="annuleLivraison()">
                    <p class="font-subtitle-16 adresse" v-if="adresseMethod === 'domicile'">{{ data["adresse"].NUM_RUE }} {{ data["adresse"].NOM_RUE }}</p>
                    <p class="font-subtitle-16 adresse" v-else>{{ data["adresse"].ADRESSE }}</p>
                    <p class="font-subtitle-16 codePostale">{{ data["adresse"].VILLE.CODE_POSTAL }}</p>
                    <p class="font-subtitle-16 ville">{{ data["adresse"].VILLE.NOM }}</p>
                </div>
            </div>

            <div id="livraisonWrapper" class="wrapper" :class="['wrapper', currentStep === 3 ? 'bgGris' : '', currentStep > 3 ? 'cursor' : '']">
                <p id="livraison" class="font-subtitle-16">3 - Options de Livraison</p>
            </div>

            <div id="paiementWrapper" :class="currentStep === 4 ? 'bgGris' : ''" class="wrapper" :style="currentStep == 4 ? 'padding-bottom: 5vh;' : ''">
                <p id="paiement" class="font-subtitle-16">4 - Options de paiement</p>
                <div v-if="currentStep == 4" id="paiementContent">
                    <div class="paimentFieldWrapper" style="flex-direction: column">
                        <input placeholder="PRENOM ET NOM" class="font-body-l paiementinput1" v-model="paimentData['nom']">
                        <input placeholder="NUMERO DE LA CARTE" class="font-body-l paiementinput1" v-model="paimentData['numéro']">
                    </div>
                    <div class="paimentFieldWrapper" style="flex-direction: row;gap: 5vw">
                        <input placeholder="MOIS" class="font-body-l" v-model="paimentData['mois']">
                        <input placeholder="ANNEE" class="font-body-l" v-model="paimentData['année']">
                    </div>
                    <div class="paimentFieldWrapper" style="width: 20%">
                        <input placeholder="CRYPTOGRAME" class="font-body-l" v-model="paimentData['cryptograme']">
                    </div>
                    <ButtonSubmit button-text="Payer" id="payButton" @click="paye()"></ButtonSubmit>
                </div>
            </div>
        </div>

        <div id="Recap" >
            <div id="recapFirst">
                <p id="RecapTitle" class="font-subtitle-16">Récapitulatif de commande</p>
                <p id="articles">{{totArticle}} articles</p>
            </div>
            <div id="prodRecap" v-for="produitCmd in produits.PRODUITS">
                <div id="produitComander" class="font-subtitle-16">
                    <p>{{ produitCmd.PRODUIT.NOM }}</p>
                    <b>{{ formatPrix(produitCmd.PRODUIT.PRIX * produitCmd.QUANTITE) }} €</b>
                </div>
                <div id="quantite" >
                    <p>{{ produitCmd.PRODUIT.MATERIAUX }}</p>
                    <p>x{{ produitCmd.QUANTITE }}</p>
                </div>
            </div>

            <div id="tots">
                <div id="sousTot">
                    <p id="soustotal" class="font-subtitle-16">sous-total <b>{{ formatPrix(Math.floor(sum/1.2)) }} €</b></p>
                    <p id="tva" class="font-subtitle-16">tva <b>{{ formatPrix(sum-Math.floor(sum/1.2)) }} €</b></p>
                </div>
                <div id="Tot">
                    <p id="total" class="font-subtitle-16">total <b>{{ formatPrix(sum) }} €</b></p>
                </div>
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>

<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import {ref, toRaw, watch} from "vue";
import ButtonSubmit from "./Components/ButtonSubmit.vue";
import Error from "./Components/Error.vue";
import Field from "./Components/Field.vue";

let props = defineProps({
    "user" : Object,
    "adresses" : Array,
    "produits": Object
})

let magasinsRecomm = ref([])

let currentAdresse = ref(0)

let paiementState = ref(false)

let sum = 0

let totArticle = 0

for(let i = 0;i<props.produits.PRODUITS.length;i++){
    totArticle += props.produits.PRODUITS[i].QUANTITE
}

for(let i = 0;i<props.produits.PRODUITS.length;i++){
    sum += props.produits.PRODUITS[i].PRODUIT.PRIX * props.produits.PRODUITS[i].QUANTITE
}

let data = {}

let adresseMethod = ref("domicile")

const paimentData = ref({})

let currentStep = ref(2)

const errorMesage = ref("")

const currentMagasin = ref()

watch(paiementState,async value=>{
    if(value){
        document.body.style.overflowY = "hidden"
    }else{
        document.body.style.overflowY = "scroll"
    }
})

function searchMagasins(codePostale){
    fetch("/adresse/getMagasins/"+codePostale,{
        method : "GET",
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    }).then(async response => {
        magasinsRecomm.value = await response.json()
    })
}

async function paye(){
    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    const response = await fetch("/checkout/valider",{
        method : "POST",
        body : JSON.stringify({
            "adresse" : data["adresse"].ID,
            "livraison" : adresseMethod.value,
            "paiement" : paimentData.value
        }),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    })
    if(response.status !== 200){
        const reader = response.body.getReader()
        errorMesage.value = new TextDecoder().decode((await reader.read()).value)
        return
    }
    paiementState.value = true
    await sleep(0)
    document.getElementById("paimentBackground").style.top = window.scrollY+"px"
    await sleep(Math.random()*3000 + 3000)
    if(response.status === 200){
        window.location="/account/commandes"
    }
    paiementState.value = false
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
    if(adresseMethod.value === "domicile"){
        data["adresse"] = toRaw(props["adresses"])[currentAdresse.value]
    }else{
        data["adresse"] = toRaw(currentMagasin.value)
    }
    currentStep.value += 2
}

function changeadresseMethod(){
    if(adresseMethod.value === "domicile")adresseMethod.value = "magasin"
    else adresseMethod.value = "domicile"
}

/* Gère l'espace du prix */
const formatPrix = (prix) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "decimal",
        maximumFractionDigits: 0, // Pas de décimales
    }).format(prix);
};


</script>

<style scoped>
#paimentPopupContent img{
    width: 10vw;
}
#paimentPopupContent{
    margin-left: 50%;
    margin-top: 25%;
    transform: translate(-50%,-50%);
    text-align: center;
    font-size: 2vw;
    position: absolute;
    height: fit-content;
    width: fit-content;
}
#paiementPopupWrapper{
    width: 50%;
    height: 50%;
    background-color: white;
    border-radius: 20px;
    left: 50%;
    position: relative;
    top: 50vh;
    transform: translate(-50%,-50%);
}
#paimentBackground{
    width: 100vw;
    height: 100vh;
    position: absolute;
    top: 0px;
    left: 0px;
    background-color: rgba(0,0,0,50%);
    z-index: 1000;
}
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
    margin-top: 2.5vh;
}
#paiementWrapper input{
    border: none;
    border-bottom: solid 1px black;
    background-color: transparent;
    width: 100%;
    margin-top: 3.5vh;
}

.bgGris {
    background-color: #FBF9F7;
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
    border-bottom: solid 1px black !important;
    padding: 2px;
    justify-content: space-around;
    background: none;
    border: none;
    cursor: pointer;
    margin-bottom: 50px;
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
    width: clamp(200px, 2vw, 400px);
}
#livraison {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}
#paiementWrapper {
    padding: 35px 0 35px 20px;
    border-bottom: 1px solid black;
}

#Recap{
    flex: 1 1 20%;
    padding: 25px;
    margin-right: 3%;
    margin-top: 80px;
    background-color: #FBF9F7;
    height: max-content;
}

#produitComander{
    display: grid;
    grid-template-columns: 1fr auto;
    margin-top: 30px;
}
#Tot {
    border-top: 1px solid black;
    width: 80%;
    margin: 30px auto 0;
}
#sousTot, #total{
    padding: 10px 15px 10px 15px;
}

#soustotal, #tva, #total {
    display: grid;
    grid-template-columns: 1fr auto;
}


#tots {
    background-color: #F1F1F1F1;
    margin-top: 40px;
}

#recapFirst {
    border-bottom: 1px solid black;
}

#articles {
    margin-bottom: 30px;
}

#quantite {
    padding-left: 10px;
}

#Recap b {
    text-align: right;
}


@media (max-width: 1000px) {
    #Page {
        display: grid;
        grid-template-columns: 1fr;
        grid-template-rows: repeat(3, auto);
        justify-items: center;
    }

    #Title {
        grid-row: 1;
    }

    #DonneeCommande {
        grid-row: 2;
        width: 90%;
    }

    #Recap {
        grid-row: 3;
        width: 90%;
        margin: 0;
        margin-top: 80px;
    }
}

@media (max-width: 450px) {
    #info {
        width: 300px;
    }
}

</style>
