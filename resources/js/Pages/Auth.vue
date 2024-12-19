<template>
    <div id="globalWrapper">
        <div id="leftWrapper">
            <img src="/public/images/login-bijoux.avif">
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
                <Form v-if="authMethod === 'login'" :fields="[{'name':inputs['login']['fields'][0],type:'email'},{'name':inputs['login']['fields'][1],type:'password'}]" button-text="Connexion" :check-boxs="[inputs['login']['checkBoxs'][0]]" :links="[{'text':'Mot de passe oublié ?','link':'youtube.com'}]" dest="/auth/login"></Form>

                <Form v-else :fields="[{'name':inputs['register']['fields'][0]},{'name':inputs['register']['fields'][1]},{'name':inputs['register']['fields'][2],type:'email'},{'name':inputs['register']['fields'][3],'type':'password'}]" :check-boxs="[inputs['register']['checkBoxs'][0]]" button-text="S'inscrire" dest="/auth/register"></Form>
            </div>
        </div>
    </div>
</template>

<script setup>
    import Form from "./Components/Form.vue"
    import { defineProps,ref } from "vue";

    let props = defineProps(["authMethod","inputs"])

    console.log(props.inputs)

    let authMethod = ref(props.authMethod)

    function changeAuthMethod(){
        if(authMethod.value === "login")authMethod.value = "register"
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
        padding-bottom: 8px;
        cursor: pointer;
    }
    #authChoiceWrapper{
        display: flex;
        flex-direction: row;
        width: 100%;
        margin-left: 50%;
        transform: translateX(-50%);
        border-bottom: solid 1px #E9E9E9;
        justify-content: space-around;
        margin-bottom: 32px;
    }
    #formWrapper{
        width: 50%;
        margin-left: 50%;
        margin-top: 10vh;
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
        gap: 8px;
        align-items: center;
        margin-left: 32px;
        margin-top: 32px;
        cursor: pointer;
    }
    #backWrapper:hover p{
        text-decoration: underline;
    }
    #leftWrapper img{
        position: absolute;
        width: 80%;
        margin-left: 50%;
        margin-top: 50vh;
        transform: translate(-50%,-50%);
        aspect-ratio: 1/1;
        object-fit: cover;
        box-shadow: 0 0 16px rgba(0, 0, 0, 0.40);
    }
    #leftWrapper{
        width: 50vw;
        float: left;
        align-items: center;
        background-color: #F0F0F0;
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
</style>
