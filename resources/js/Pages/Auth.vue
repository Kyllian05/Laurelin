<template>
    <div id="globalWrapper">
        <Error :message="errorMesage" v-if="errorMesage != ''" @click="errorMesage = ''"></Error>
        <div id="leftWrapper">
            <img id="globalImage" src="/public/images/login-bijoux.avif">
        </div>
        <div id="rightWrapper">
            <div id="backWrapper" onclick="window.location = '/'">
                <span class="material-symbols-rounded">
                    arrow_back
                </span>
                <p class="font-body-m">Retour</p>
            </div>
            <div id="formWrapper">
                <img src="/public/images/logo.png" id="logo">
                <div id="authChoiceWrapper">
                    <button :class="{autChoiceActive :  authMethod === 'login'}" @click="changeAuthMethod()" class="font-subtitle-16">Connexion</button>
                    <button :class="{autChoiceActive :  authMethod === 'register'}" @click="changeAuthMethod()" class="font-subtitle-16">Inscription</button>
                </div>
                <Form v-if="authMethod === 'login'" :fields="[{'name':inputs['login']['fields'][0],type:'email'},{'name':inputs['login']['fields'][1],type:'password'}]" button-text="Connexion" :check-boxs="[inputs['login']['checkBoxs'][0]]" :texts="['Mot de passe oublié']" dest="/auth/login"  @textClicked="handleClick" succeed-message="Vous êtes connecté" @error-occured="message =>errorMesage = message"></Form>
                <Form v-else :fields="[{'name':inputs['register']['fields'][0]},{'name':inputs['register']['fields'][1]},{'name':inputs['register']['fields'][2],type:'email'},{'name':inputs['register']['fields'][3],'type':'password'}]" :check-boxs="[inputs['register']['checkBoxs'][0]]" button-text="S'inscrire" dest="/auth/register" succeed-message="Vous êtes inscrit, veuillez consulter vos mails pour valider votre adresse" @error-occured="message =>errorMesage = message"></Form>
            </div>
        </div>
    </div>
    <div v-if="recoverPassword" id="recoverWrapper">
        <div id="recoverContent">
            <span class="material-symbols-rounded" id="recoverArrow" @click="recoverPassword = false">arrow_back</span>
            <div id="recoverFormWrapper">
                <Form :fields="[{'name':'Adresse mail','type':'email'}]" button-text="Envoyer le mail de récupération" :check-boxs="[]" dest="/sendRecoveryMail"></Form>
            </div>
        </div>
    </div>
</template>

<script setup>
import Form from "./Components/Form.vue"
import {defineProps, ref} from "vue";
import Error from "./Components/Error.vue";

const props = defineProps(["authMethod", "inputs"])

let authMethod = ref(props.authMethod)

let recoverPassword = ref(false)

const errorMesage = ref("")

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}
function changeAuthMethod() {
    if (authMethod.value === "login") authMethod.value = "register"
    else authMethod.value = "login"
}

function handleClick(source) {
    if (source === "Mot de passe oublié") {
        recoverPassword.value = true
    }
}
</script>

<style scoped>
#recoverArrow {
    font-size: 3vw;
    cursor: pointer;
}

#recoverFormWrapper {
    width: 50%;
    margin-left: 50%;
    transform: translateX(-50%);
}

#recoverContent {
    background-color: rgba(255, 250, 250);
    width: 40vw;
    margin-left: 50%;
    margin-top: 25%;
    transform: translate(-50%, -50%);
    padding-bottom: 5vh;
    border-radius: 30px;
}

#recoverWrapper {
    position: absolute;
    width: 100vw;
    height: 100vh;
    top: 0px;
    left: 0px;
    background-color: rgba(0, 0, 0, 0.4);
}

.autChoiceActive {
    border-bottom: solid 2px black !important;
}

#authChoiceWrapper button {
    background: none;
    border: none;
    padding-bottom: 8px;
    cursor: pointer;
}

#authChoiceWrapper {
    display: flex;
    flex-direction: row;
    width: 100%;
    margin-left: 50%;
    transform: translateX(-50%);
    border-bottom: solid 1px #E9E9E9;
    justify-content: space-around;
    margin-bottom: 32px;
}

#formWrapper {
    width: 50%;
    margin-left: 50%;
    margin-top: 45vh;
    transform: translate(-50%, -50%);
}


#logo {
    filter: brightness(0%) grayscale(100%);
    width: 60%;
    margin-left: 50%;
    transform: translateX(-50%);
}

#backWrapper {
    position: absolute;
    display: flex;
    flex-direction: row;
    gap: 8px;
    align-items: center;
    margin-left: 32px;
    margin-top: 32px;
    cursor: pointer;
}

#backWrapper:hover p {
    text-decoration: underline;
}

#globalImage {
    position: absolute;
    width: 80%;
    margin-left: 50%;
    margin-top: 50vh;
    transform: translate(-50%, -50%);
    aspect-ratio: 1/1;
    object-fit: cover;
    box-shadow: 0 0 16px rgba(0, 0, 0, 0.40);
}


#leftWrapper {
    width: 50vw;
    float: left;
    align-items: center;
    background-color: #F0F0F0;
    height: 100vh;
    justify-content: center;
    position: relative;
}

#rightWrapper {
    width: 50vw;
    float: right;
    max-height: 100vh;
}

#globalWrapper {
    overflow-y: hidden;
    max-height: 100vh;
}

@media screen and (max-width: 1100px) {
    #leftWrapper {
        display: none;
    }

    #rightWrapper {
        width: 80%;
        float: none;
        height: 100%;
    }

    #globalWrapper {
        display: flex;
        justify-content: center;
        align-items: center;
    }
}


@media (min-width: 1100px) and (max-width: 1250px) {
    #formWrapper {
        width: 60%;
    }
}

@media (max-width: 750px) {
    #formWrapper {
        width: 80%;
    }
}

@media (max-width: 480px) {
    #authChoiceWrapper {
        flex-wrap: wrap;
        gap: 15px;
        border-bottom: 0;
    }

    #authChoiceWrapper button {
        border-bottom: solid 1px #E9E9E9;
    }
}

</style>
