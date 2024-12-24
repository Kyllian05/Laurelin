<template>
    <div id="formWrapper">
        <Form :fields="[{'name':'Nouveau mot de passe','type':'password'}]" :check-boxs="[]" button-text="Valider" dest=""></Form>
    </div>
</template>

<script setup>
    import Form from "./Components/Form.vue"

    const props = defineProps({
        "ID":Number,
        "token":String
    })

</script>

<style scoped>
    #formWrapper{
        width: 25vw;
        margin-left: 50%;
        margin-top: 25%;
        transform: translate(-50%,-50%);
    }
</style>
