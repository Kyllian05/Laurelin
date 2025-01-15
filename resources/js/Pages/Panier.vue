<template>
    <Header currentPage="panier" :update-panier="panierUpdate" @panierUpdated="() => {panierUpdate = false}"></Header>
    <div class="globalWrapper">
        <div class="leftWrapper">
            <h1>Panier</h1>
            <div class="panier">
                <div v-for="produitCmd in panierData.PRODUITS" v-if="panierData.PRODUITS.length > 0">
                    <div class="panierproduit"  v-for="i in produitCmd.QUANTITE">
                        <img alt="Produit" :src="produitCmd.PRODUIT.IMAGES[0]"/>
                        <div class="panierproduitinfo">
                            <h3>{{ produitCmd.PRODUIT.NOM }}</h3>
                            <p>{{ produitCmd.PRODUIT.MATERIAUX }}</p>
                            <p class="addOther" @click="addOtherProduct(produitCmd.PRODUIT.ID)">En ajouter un autre</p>
                            <h2>{{ formatPrix(produitCmd.PRODUIT.PRIX) }}€</h2>
                        </div>
                        <span id="closeButton" class="material-symbols-rounded" @click="supprimerDuPanier(produitCmd.PRODUIT.ID)">close</span>
                    </div>
                </div>
                <div v-else>
                    <p style="margin-top: 5vh; margin-bottom: 3vh">Votre panier est vide</p>
                </div>
                <a href="/bijoux" class="continue">← Continuer ma visite</a>
            </div>
        </div>
        <div class="rightWrapper">
            <div class="panierresume">
            <h2>SOUS TOTAL</h2>
            <p class="incl">INCL. TVA</p>
            <h1>{{ formatPrix(somme) }}€</h1>
            <button onclick="window.location='/checkout'">Poursuivre ma commande</button>
            <p class="secure">PAIEMENT SÉCURISÉ</p>
            <div id="payement">
                <img src="/public/images/CB-Logo.png" alt="logo-CB">
                <img src="/public/images/American-Express-Logo.png" alt="Amex-logo">
                <img src="/public/images/Mastercard-Logo.png" alt="Master-logo">
                <img src="/public/images/apple-pay-Logo.jpg" alt="apple-logo">
                <img src="/public/images/PayPal-Logo.jpg" alt="Paypal-logo">
                <img src="/public/images/Visa-Logo.jpg" alt="visa-logo">
            </div>
            <p class="returns">RETOURS ET ÉCHANGES SOUS 30 JOURS</p>
            <a href="#" class="legal">MENTIONS LÉGALES</a>
            </div>
        </div>

    </div>
    <Footer></Footer>
</template>

<script setup>
import Footer from "./Components/Footer.vue";
import Header from "./Components/Header.vue";
import {ref, watch} from "vue";

const props = defineProps({
    "panier": Object
})

let panierData = ref(props.panier)

console.log(panierData.value)

const panierUpdate = ref(false)

let somme = ref(0)

function updatePanierSomme(){
    let temp = 0
    for(let i = 0; i<panierData.value.PRODUITS.length ;i++){
        temp += panierData.value.PRODUITS[i].PRODUIT.PRIX * panierData.value.PRODUITS[i].QUANTITE
    }
    somme.value = temp
}

function addOtherProduct(id){
    fetch("/panier/ajout",{
        method : "POST",
        body : JSON.stringify({
            produit : id
        }),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    }).then(async response => {
        if(response.status === 200){
            panierData.value = await response.json()
        } else {
            alert("Erreur lors de l'ajout du produit")
        }
    })
}

function supprimerDuPanier(id){
    fetch("/panier/supprimer",{
        method:"POST",
        body:JSON.stringify({"produit" : id}),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    }).then(async response => {
        if(response.status === 200) {
            panierData.value = await response.json()
        }
    })
}


/* Gère l'espace du prix */
const formatPrix = (prix) => {
    return new Intl.NumberFormat("fr-FR", {
        style: "decimal",
        maximumFractionDigits: 0, // Pas de décimales
    }).format(prix);
};

updatePanierSomme()
watch(panierData.value,async () => {
    updatePanierSomme()
})
</script>

<style scoped>
.globalWrapper {
font-family: "Tenor Sans", serif;
display: flex;
justify-content: space-between;
margin: 50px 20px;
}
.leftWrapper {
width: 65%;
margin-top: 64px;
}

.panierproduit {
display: flex;
align-items: center;
justify-content: space-between;
background-color: transparent;
margin: 10px 0;
padding: 10px;
    border-bottom: 1px solid #000;
}

.panierproduit img {
width: 150px;
height: 150px;
border-radius: 5px;
object-fit: cover;
object-position: top;
}

.panierproduitinfo {
flex-grow: 1;
margin-left: 10px;
padding-inline: 10px;
}

.panierproduitinfo h3 {
font-family: "Tenor Sans", serif;
font-size: 16px;
margin: 0;
}

.panierproduitinfo p {
font-family: "Tenor Sans", serif;
font-size: 11px;
margin: 5px 0;
padding-bottom: 10px;
}

.addOther {
font-size: 13px;
color: #000;
text-decoration: underline;
cursor: pointer;

}

.panierproduitinfo h2 {
font-family: "Tenor Sans", serif;
font-size: 18px;
color: #000;
padding-top: 50px;
}
.prix-item {
color: #000;

}

#closeButton {
background: none;
border: none;
font-size: 20px;
cursor: pointer;
justify-content: right;
margin-bottom: 16%;
}

#closeButton:hover {
background: none;
border: none;
font-size: 20px;
cursor: pointer;
color: red;
}

.continue {
display: inline-block;
margin-top: 20px;
font-family: "Tenor Sans", serif;
font-size: 14px;
text-decoration: none;
color: #000;
}

.continue:hover {
text-decoration: underline;
cursor: pointer;;
}


.rightWrapper {
width: 30%;
background-color: #f1f1f1;
padding: 20px;
border-radius: 8px;
margin-top: 110px;
padding-bottom: 35px;
height: fit-content;
}


#payement {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 3px;
}


.rightWrapper img {
    width: 50px;
    height: 30px;
    border-radius: 5px;
    border: 1px solid lightgray;
    object-fit: cover;
}

.panierresume h3 {
font-family: "Tenor Sans", serif;
font-size: 16px;
margin: 0;
}

.incl {
font-size: 12px;
color: #666;
}

.panierresume h1 {
font-family: "Tenor Sans", serif;
font-size: 24px;
margin: 20px 0;
}


.panierresume button{
background-color: black;
color: white;
border: 1px solid black;
width: 100%;
padding: 10px;
margin: 10px 0;
border-radius: 10px;
font-family: "Tenor Sans", serif;
font-size: 14px;
cursor: pointer;
transition: 0.3s;
}

.panierresume button:hover {
background-color: white;
color: black;
border: 1px solid black;
}

.secure, .returns, .legal {
font-size: 12px;
text-align: center;
margin: 10px 0;
}

.legal {
text-decoration: underline;
cursor: pointer;
}

@media (max-width: 800px) {
    .globalWrapper{
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .leftWrapper {
        width: 90%;
    }

    .rightWrapper {
        width: 90%;
    }
}
</style>
