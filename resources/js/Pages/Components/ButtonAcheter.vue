<template>
    <button class="font-body-m" id="boutton_acheter" @click="ajoutAuPanier()" :class="whiteBorder ? 'blackBorder' : 'whiteBorder'">
        <span class="material-symbols-rounded">shopping_bag</span>
        Ajouter au panier
    </button>
</template>

<script setup>
let props = defineProps({
    "whiteBorder" : Boolean,
    "id" : Number,
    "taille" : Number
})

let emits = defineEmits(["ajout"])

function ajoutAuPanier(){
    fetch("/panier/ajout",{
        method : "POST",
        body : JSON.stringify({
            produit : props.id,
            taille : props.taille
        }),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type":"application/json"
        },
    }).then(async response => {
        if(response.status === 200){
            emits("ajout")
        } else {
            document.cookie += ";redirect=/produit/"+props.id
            window.location.href = "/auth/login"
        }
    })
}
</script>

<style scoped>
.whiteBorder{
    border: 1px solid white;
}
.blackBorder{
    border: 1px solid black;
}
#boutton_acheter{
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: clamp(45px, 3vw, 65px);
    margin-bottom: 45px;
    border-radius: 10px;
    background-color: black;
    color: white;
    cursor: pointer;
    padding: 0px .5vw;
    transition: color 0.5s ease, background-color 0.5s ease, transform 0.1s ease;
}

#boutton_acheter:hover {
    background-color: white;
    color: black;
}
#boutton_acheter:disabled {
    cursor: default;
    background-color: grey;
    color: white;

}
#boutton_acheter:disabled:hover {
    background-color: grey;
    color: white;
}
</style>
