
<template>
    <Header current-page="CheckOut"></Header>
        <div id="Page">
            <div id="Title" class="font-subtitle-16">Commande</div>
            <div id="DonneeCommande">
                <div id="infoWrapper">
                    <div id="info" class="font-subtitle-16">1 - Informations Personnelles</div>
                    <div id="contenuInfo">
                        <div id="texteInfo" class="font-body-m"> Pour poursuivre votre commande veuillez vous identifiez</div>
                        <button class="font-body-l">Se connecter</button>
                        <button class="font-body-l">S'inscrire</button>
                    </div>
                </div>

                <div id="adresseWrapper">
                    <div id="adresse" class="font-subtitle-16">2 - Adresse de livraison</div>
                    <div id="contenuAdresse">

                        <div id="adresseChoiceWrapper">
                            <button :class="{adresseChoiceActive :  adresseMethod === 'domicile'}" @click="changeadresseMethod()" class="font-subtitle-16">a domicile</button>
                            <button :class="{adresseChoiceActive :  adresseMethod === 'retirer'}" @click="changeadresseMethod()" class="font-subtitle-16">retirer en magasin</button>
                        </div>

                        <div v-if="adresseMethod === 'domicile'">
                            <div id="adresseUser">
                                <!--TODO: met ton vfor avec les adresse des users  -->
                                <input id="radButton" type="radio" name="radio" value="option1">
                                <div id="adresseUserr">
                                    <div id="adresse" class="font-subtitle-16">adresse</div>
                                    <div id="codePostale" class="font-subtitle-16">code postale</div>
                                    <div id="ville" class="font-subtitle-16">ville</div>
                                </div>
                            </div>
                        </div>

                        <div v-else>
                            <select id="selectVille" class="font-subtitle-16">
                                <option value="">Choisir une ville</option>
                                <!---TODO: mettre les villes de la base de dounées-->
                            </select>
                        </div>

                    </div>
                </div>

                <div id="livraisonWrapper">
                    <div id="livraison" class="font-subtitle-16">3 - Options de Livraison</div>
                </div>

                <div id="paiementWrapper">
                    <div id="paiement" class="font-subtitle-16">4 - Options de paiement</div>
                </div>

            </div>

            <div id="Recap">
                <div id="RecapTitle" class="font-subtitle-16">Récapitulatif de commande</div>
                <div id="modif" class="font-body-m">Modifier</div>
                <div id="nbArticles" class="font-body-s">articles</div>
                <div id="produitComander" class="font-subtitle-16">
                    <!----TODO: met ton vfor laaa pooool-->
                </div>
                <div id="sousTot">
                    <div id="soustotal" class="font-subtitle-16">sous-total</div>
                    <div id="tva" class="font-subtitle-16">tva</div>
                </div>
                <div id="Tot">
                    <div id="total" class="font-subtitle-16">total</div>
                </div>

            </div>
        </div>
    <Footer></Footer>
</template>



<script setup>

import Header from "./Components/Header.vue";
import Footer from "./Components/Footer.vue";
import Form from "./Components/Form.vue";
import {defineProps, ref} from "vue";

const props = defineProps(["authMethod"])

let adresseMethod = ref(props.adresseMethod)


function changeadresseMethod(){
    if(adresseMethod.value === "domicile")adresseMethod.value = "retirer"
    else adresseMethod.value = "domicile"
}

</script>


<style scoped>

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
    display: none;
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

#contenuAdresse{
    display: none;
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

#adresseUser {
    display: grid;
    grid-template-columns: 1fr 1fr;
    padding: 0px 0 20px 50px;
    width: 200px;

}

#radButton {
    grid-column: 1;
    width: 20px;
    margin: 25px;
}

#adresseUserr {
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
