<template>
    <div id="fieldWrapper" :class="displayColumn ? 'gridDisplay' : ''">
        <Field v-for="field in fields" :name="field.name" :type="field.type" :requied="field.required" :id="'field_'+encodeURI(field.name)" :value="field.value"></Field>
    </div>
    <div id="bottomFormWrapper">
        <div v-for="checkbox in checkBoxs" class="checkBoxDiv">
            <input type="checkbox" :id="'check_'+encodeURI(checkbox)">
            <p class="font-normal-12">{{checkbox}}</p>
        </div>
        <p v-for="text in texts"  class="font-normal-12" id="linksWrapper" @click="emit('textClicked',text)">{{ text }}</p>
    </div>
    <ButtonSubmit :button-text="buttonText" @submit-clicked="sendData"></ButtonSubmit>
</template>

<script setup>
import {defineEmits, defineProps, onMounted} from 'vue';
import ButtonSubmit from "./ButtonSubmit.vue";
import Field from "./Field.vue"

const emit = defineEmits(["textClicked","formSubmitSuccessfully","errorOccured"])

let props = defineProps({
    "fields":Array[Object],
    "buttonText":String,
    "checkBoxs":Array,
    "texts":Array[String],
    "dest":String,
    "displayColumn" : Boolean,
    "succeedMessage" : String
})

async function sendData(){
    let body = {}

    for(let i = 0;i<props.fields.length;i++){
        if(!document.getElementById("field_"+encodeURI(props.fields[i].name)).children[1].checkValidity()){
            alert("Formulaire invalide")
            return
        }
        body[props.fields[i].name] = document.getElementById("field_"+encodeURI(props.fields[i].name)).children[1].value
    }

    for(let i = 0;i<props.checkBoxs.length;i++){
        body[props.checkBoxs[i]] = document.getElementById("check_"+encodeURI(props.checkBoxs[i])).checked
    }

    var redirectCookie = getCookie("redirect")
    fetch(props.dest,{
        method:"POST",
        body: JSON.stringify(body),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    }).then(async response =>{
        if(response.status == 200){
            if (redirectCookie != undefined && props.dest !== "/auth/register") {
                window.location = redirectCookie.replace("%2F","/")
            }else{
                alert(props.succeedMessage);
                emit("formSubmitSuccessfully",await response.json())
            }
        }else{
            const reader = response.body.getReader()
            const text = new TextDecoder().decode((await reader.read()).value)
            console.error(text)
            emit("errorOccured",text)
        }
    })
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}
</script>

<style scoped>
    #fieldWrapper{
        gap: 2vw;
        display: flex;
        flex-direction: column;
    }
    .gridDisplay{
        display: grid !important;
        grid-template-columns: repeat(2,1fr);
    }
    #bottomFormWrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
    }
    #linksWrapper:hover {
        text-decoration: underline;
    }
    #linksWrapper {
        cursor: pointer;
        text-decoration: none;
        color: black;
    }
    input[type="checkbox"]{
        accent-color: #000000;
        cursor: pointer;
    }
    .checkBoxDiv{
        display: flex;
        flex-direction: row;
        gap: 8px;
        align-items: center;
    }
</style>
