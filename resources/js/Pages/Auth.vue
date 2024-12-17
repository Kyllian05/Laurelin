<template>
    <div id="globalWrapper">    
        <div id="leftWrapper">
            <img src="/public/images/joliBijoux.png">
        </div>
        <div id="rightWrapper">
            <div id="backWrapper" onclick="window.location = '/'">
                <span class="material-symbols-outlined">
                    keyboard_backspace
                </span>
                <p>Retour</p>
            </div>
            <div id="formWrapper">
                <img src="/public/images/logo.png" id="logo">
                <div id="authChoiceWrapper">
                    <button :class="{autChoiceActive :  authMethod == 'login'}" @click="changeAuthMethod()">Connexion</button>
                    <button :class="{autChoiceActive :  authMethod == 'register'}" @click="changeAuthMethod()">Inscription</button>
                </div>
                <Form v-if="authMethod=='login'" :fields="[{'name':'Adresse e-mail'},{'name':'Mot de passe',type:'password'}]" button-text="Connexion" :check-boxs="['Se souvenir de moi']" :links="[{'text':'Mot de passe oublié ?','link':'youtube.com'}]"></Form>
                <Form v-else :fields="[{'name':'First Name'},{'name':'Last Name'},{'name':'Adresse e-mail'},{'name':'Mot de passe','type':'password'}]" :check-boxs="['J\'ai lu et j\' accepte les termes et conditions']" button-text="S'inscrire"></Form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import Form from "./Components/Form.vue"
    import { defineProps,ref } from "vue";

    let props = defineProps(["authMethod"])

    let authMethod = ref(props.authMethod)
    
    function changeAuthMethod(){
        if(authMethod.value == "login")authMethod.value = "register"
        else authMethod.value = "login"
    }
</script>

<style scoped>
    .autChoiceActive{
        border-bottom: solid 2px black !important;
    }
    #authChoiceWrapper button{
        background: none;
        border: none;
        font-family: "Tenor Sans", sans-serif;
        cursor: pointer;
    }
    #authChoiceWrapper{
        display: flex;
        flex-direction: row;
        width: 80%;
        margin-left: 50%;
        transform: translateX(-50%);
        border-bottom: solid 1px gray;
        justify-content: space-around;
        margin-bottom: 3vh;
    }
    #formWrapper{
        width: 50%;
        margin-left: 50%;
        margin-top: 10vh;
        /*margin-top: 50vh;
        transform: translate(-50%,-50%);*/
        transform: translateX(-50%);
    }
    #logo{
        filter: brightness(0%) grayscale(100%);
        width: 60%;
        margin-left: 50%;
        transform: translateX(-50%);
    }
    #backWrapper{
        position: absolute;
        display: flex;
        flex-direction: row;
        gap: 1vw;
        align-items: center;
        margin-left: 2vw;
        margin-top: 1vw;
        cursor: pointer;
    }
    #leftWrapper img{
        position: absolute;
        width: 80%;
        margin-left: 50%;
        margin-top: 50vh;
        transform: translate(-50%,-50%);
        aspect-ratio: 1/1;
        object-fit: cover;
        box-shadow: 4px 4px 16px rgba(0,0,0,15);
    }
    #leftWrapper{
        width: 50vw;
        float: left;
        align-items: center;
        background-color: #EEEEEE;
        height: 100vh;
        justify-content: center;
        position: relative;
    }
    #rightWrapper{
        width: 50vw;
        float: right;
        height: 100vh;
    }
    #globalWrapper{
        overflow-y: hidden;
        max-height: 100vh;
    }
    p{
        font-family: "Tenor Sans", serif;
        margin: 0px;
    }
    
</style>

<style>
.material-symbols-outlined {
    font-variation-settings:
    'FILL' 0,
    'wght' 100,
    'GRAD' 0,
    'opsz' 24;
    transform: scale(1.5);
}
</style>