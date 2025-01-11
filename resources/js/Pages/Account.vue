<template>
    <Header currentPage="account"></Header>
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
                    <div class="commandSideWrapper">
                        <button class="font-body-s" :class="commande['Etat'] == 2 ? 'commandFinished' : ''">{{ etatText[commande["Etat"]] }}</button>
                        <p class="font-body-s">TOTAL : {{ getCommandeSum(commande) }}€</p>
                    </div>
                </div>
            </div>
            <div v-else-if="dynamicPage == 'favoris'" id="favorisWrapper">
                <div v-if="favorisImagesCount < dynamicFavoris.length">
                    Loading
                </div>
                <div v-show="favorisImagesCount >= dynamicFavoris.length" v-for="favori in dynamicFavoris" class="favorisWrappers">
                    <div style="position: relative;width: fit-content" class="imageWrapper">
                        <div>
                            <a :href="'/produit/'+favori.ID" target="_blank">
                                <img :src="favori.IMAGES[0]" class="favorisImage" @load="favorisImageLoaded()">
                            </a>
                            <span class="material-symbols-rounded favoriteRemoveSymbol displayHover" @click="supprimerFavoris(favori.ID)">
                            remove
                            </span>
                            <div class="buttonAcheter displayHover">
                                <ButtonAcheter white-border="false" :id="favori.ID"></ButtonAcheter>
                            </div>
                        </div>
                    </div>
                    <p>{{ favori.NOM }}</p>
                    <p>{{ favori.Prix }}€</p>
                </div>
            </div>
            <div v-else>
                <div v-if="ajoutAdresseState" id="ajoutAdresseWrapper">
                    <div id="ajoutAdresseContent">
                        <span class="material-symbols-rounded" @click="ajoutAdresseState = false" id="ajoutAdresseClose">close</span>
                        <p class="font-body-s">AJOUTER UNE NOUVELLE ADRESSE</p>
                        <div style="width: 70%;margin-left: 50%;transform: translateX(-50%)">
                            <Field name="Numéro" @input="value => updateNewAdresseValue('Numéro',value)"></Field>
                            <Field name="Nom de rue" @input="value => updateNewAdresseValue('Nom de rue',value)"></Field>
                            <Field name="Code Postale" @input="value => searchVille(value)"></Field>
                            <!-- TODO: UI-->
                            <select v-if="villesSuggest.length > 0" v-model="villeChoice">
                                <option v-for="ville in villesSuggest" :value="{'Nom':ville['NOM'],'Code Postal':ville['CODE_POSTAL']}">{{ ville["NOM"] }} {{ ville["CODE_POSTAL"] }}</option>
                            </select>
                            <ButtonSubmit buttonText="Valider" @click="newAdresseClicked"></ButtonSubmit>
                        </div>
                    </div>
                </div>
                <button id="addAdressButton" @click="ajoutAdresseState = true">
                    <span class="material-symbols-rounded">
                        add
                    </span>
                    <p class="font-body-s">AJouter une adresse</p>
                </button>
                <div v-for="adresse in dynamicAdresse" class="adresseWrappers">
                    <p>{{ adresse["Numéro"] }} {{ adresse["Rue"] }}, {{ adresse["Code Postal"] }} {{ adresse["Ville"] }}</p>
                    <div>
                        <span class="material-symbols-rounded garbageIcon" @click="deleteAdresse(adresse['ID'])">
                            delete
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>

<script setup>
import {defineProps, ref, watch} from "vue"
import Form from "./Components/Form.vue"
import Header from "./Components/Header.vue"
import Footer from "./Components/Footer.vue"
import ButtonAcheter from "./Components/ButtonAcheter.vue";
import Field from "./Components/Field.vue";
import ButtonSubmit from "./Components/ButtonSubmit.vue";

let props = defineProps(
    {
        "page":String,
        "info":Object,
        "commandes":Object,
        "favoris" : Object,
        "adresses" : Array,
    })

    const favorisImagesCount = ref(0)

    const dynamicFavoris = ref(props.favoris)

    const dynamicPage = ref(props.page)

    const dynamicAdresse = ref(props.adresses)

    const ajoutAdresseState = ref(false)

    const villesSuggest = ref([]);

    const villeChoice = ref()

    const ajoutAdresseData = ref({
        "Numéro":"",
        "Nom de rue":""
    })

    watch(ajoutAdresseState,async (newState)=>{
        if(newState){
            document.body.style.overflowY = "hidden"
        }else{
            document.body.style.overflowY = "scroll"
        }
    })

    let navConv = {
        "info" : {text:"Informations personnelles",icon:"person"},
        "commandes" : {text: "Mes commandes",icon:"shopping_cart"},
        "favoris" : {text:"Mes produits favoris",icon:"favorite"},
        "adresses" : {text:"Mes adresses",icon:"home"}
    }

    let etatText = {
        "0" : "En cours de préparation",
        "1" : "Expediée",
        "2" : "Terminée"
    }

    let infoFields = [[],[]]

    for(let i = 0;i<3;i++){
        infoFields[0].push({"name":Object.keys(props.info)[i],"value":props.info[Object.keys(props.info)[i]]})
    }

    infoFields[1].push({"name":Object.keys(props.info)[3],"value":props.info[Object.keys(props.info)[3]],"required":false})

    function updateNewAdresseValue(field,value){
        ajoutAdresseData.value[field] = value
    }

    function newAdresseClicked(){
        fetch("/adresse/ajout",{
            method : "POST",
            body : JSON.stringify({
                "Numéro" : ajoutAdresseData.value["Numéro"],
                "Nom de rue" : ajoutAdresseData.value["Nom de rue"],
                "Ville" : villeChoice.value
            }),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type":"application/json"
            },
        }).then(async response => {
            dynamicAdresse.value.push(await response.json())
            ajoutAdresseState.value = false
        })
    }

    function searchVille(codePostal){
        if(codePostal == ""){
            villesSuggest.value = []
        }else{
            fetch("/adresse/getVilles/"+codePostal,{
                method:"GET",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type":"application/json"
                },
            }).then( async response =>{
                villesSuggest.value = await response.json()
            })
        }
    }

    function favorisImageLoaded(){
        favorisImagesCount.value++
    }

    function deleteAdresse(id){
        fetch("/adresse/supprimer",{
            method:"POST",
            body: JSON.stringify({"ID":id}),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type":"application/json"
            },
        }).then(async response =>{
            if(response.status == 200){
                dynamicAdresse.value = dynamicAdresse.value.filter((adresse) => adresse["ID"] != id)
            }
        })
    }

    function getCommandeSum(commande){
        let sum = 0
        commande["Produits"].forEach((produit)=>{
            sum += produit["Prix"] * produit["Quantité"]
        })
        return sum
    }

    function supprimerFavoris(id){
        fetch("/supprimerFavoris",{
            method : "POST",
            body : JSON.stringify({
                produit : id
            }),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type":"application/json"
            },
        }).then(async response => {
            if(response.status == 200){
                dynamicFavoris.value = dynamicFavoris.value.filter((favori) => favori["ID"] != id)
            }
        })
    }
</script>

<style scoped>
    .displayHover{
        opacity: 0;
        transition-duration: .25s;
    }
    .buttonAcheter{
        position: absolute;
        bottom: 0px;
        left: 0px;
        margin-left: 50%;
        transform: translateX(-50%);
        width: max-content;
    }
    #ajoutAdresseContent p{
        text-align: center;
        font-size: 19px;
        position: relative;
        letter-spacing: 1px;
        margin-top: 5vh;
        margin-bottom: 5vh;
        margin-top: 0px;
    }
    #ajoutAdresseClose:hover{
        background-color: rgba(0,0,0,20%);
        border-radius: 50%;
    }
    #ajoutAdresseClose{
        cursor: pointer;
        font-size: 3vw;
        position: absolute;
        margin-top: -9vh;
        margin-left: 1vh;
    }
    #ajoutAdresseContent{
        width: 50vw;
        background-color: white;
        margin-left: 50vw;
        margin-top: 50vh;
        transform: translate(-50%,-50%);
        border-radius: 20px;
        overflow: hidden;
        padding-bottom: 10vh;
        padding-top: 10vh;
    }
    #ajoutAdresseWrapper{
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0,0,0,20%);
        z-index: 1000;
    }
    #addAdressButton p{
        letter-spacing: 1px;
    }
    #addAdressButton{
        background-color: black;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 15px;
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 1vw;
        gap: 1vw;
    }
    .adresseWrappers div span{
        padding: 0.5vw;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(0,0,0,20%);
        cursor: pointer;
    }
    .adresseWrappers div{
        display: flex;
        flex-direction: row;
        gap: 1vw;
        height: fit-content;
    }
    .adresseWrappers{
        display: flex;
        flex-direction: row;
        position: relative;
        justify-content: space-between;
        height: 10vh;
        align-items: center;
        border-bottom: solid 1px black;
    }
    .garbageIcon{
        color: rgb(200,0,0);
        background-color: rgba(255,0,0,0.1);

    }
    .favoriteRemoveSymbol{
        position: absolute;
        right: 0.5vw;
        top: 0.5vw;
        background-color: white;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(0,0,0,20%);
    }

    .favorisWrappers div:hover .displayHover{
        opacity: 1;
    }
    .favorisWrappers div:hover img{
        filter: brightness(70%);
    }
    .favorisWrappers{
        text-align: center;
    }
    #favorisWrapper{
        display: grid;
        grid-template-columns: repeat(3,1fr);
        height: max-content;
    }
    .favorisImage{
        width: 15vw;
        cursor: pointer;
        transition-duration: .25s;
        aspect-ratio: 1/1;
        object-fit: cover;
        object-position: top;
    }
    .commandSideWrapper p{
        margin-top: 2vh;
    }
    .commandSideWrapper{
        position: absolute;
        right: 2vw;
        top: 2vw;
        text-align: center;
    }
    .commandFinished{
        color: white;
        background-color: black!important;
    }
    .commandWrapper button{
        border: solid 2px black;
        border-radius: 25px;
        padding-top: 1vh;
        padding-bottom: 1vh;
        padding-left: 2.5vw;
        padding-right: 2.5vw;
        background-color: transparent;
        font-size: 16px;
    }
    .commandWrapper{
        padding-bottom: 2vh;
        padding-top: 2vh;
        padding-left: 1vw;
        position: relative;
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
        padding-left: 10vw;
        padding-top: 119px;
        height: fit-content;
        min-height: 100vh;
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
        height: fit-content;
    }
    #formWrapper2{
        margin-top: 5vh;
    }
</style>
