<template>
    <Header></Header>
    <div id="mainWrapper">
        <div id="navWrapper">
            <div v-for="nav in navList" :class="navConv[page] == nav ? 'currentNav' : ''">
                <p class="font-body-s">{{ nav }}</p>
            </div>
        </div>
        <div id="contentWrapper">
            <div v-if="page == 'info'">
                <div id="formWrapper1">
                    <Form :fields="infoFields[0]" :check-boxs="[]" buttonText="Valider les modifications" :displayColumn="true" dest="/updateInfo" succeed-message="Les modifications ont bien étés effectuées"></Form>
                </div>
                <div id="formWrapper2">
                    <Form :fields="infoFields[1]" :check-boxs="[]" buttonText="Valider les modifications"  dest="/updateInfo" style="margin-top: 5vh"></Form>
                </div>
            </div>
        </div>
    </div>
    <Footer></Footer>
</template>

<script setup>
import { defineProps } from "vue"
import Form from "./Components/Form.vue"
import Header from "./Components/Header.vue"
import Footer from "./Components/Footer.vue"

let props = defineProps(
    {
        "page":String,
        "info":Object
    })

    let navConv = {
        "info" : "Informations personnelles"
    }

    let navList = ["Informations personnelles","Mes commandes","Mes produits favoris","Mes adresses"]

    let infoFields = [[],[]]

    for(let i = 0;i<3;i++){
        infoFields[0].push({"name":Object.keys(props.info)[i],"value":props.info[Object.keys(props.info)[i]]})
    }

    infoFields[1].push({"name":Object.keys(props.info)[3],"value":props.info[Object.keys(props.info)[3]],"required":false})

</script>

<style scoped>
    #navWrapper div:hover{
        background-color: black;
        color: white;
    }
    #navWrapper div p{
        width: max-content;
        text-align: center;
        margin-left: 50%;
        transform: translateX(-50%);
    }
    #mainWrapper{
        display: flex;
        flex-direction: row;
        gap: 10vw;
        width: fit-content;
        margin-left: 50%;
        transform: translateX(-50%);
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
        padding-left: 4vw;
        padding-right: 4vw;
        text-align: center;
        align-content: center;
        cursor: pointer;
        transition-duration: .2s;
    }
    #contentWrapper{
        width: 50vw;
    }
    #formWrapper2{
        margin-top: 5vh;
    }
</style>
