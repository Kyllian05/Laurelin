<template>
    <Header currentPage="Nos bijoux"></Header>

    <div class="hero">
        <div class="text-overlay">
            <p class="over-text">La collection</p>
            <p class="font-title-24">Bijoux Laurelin</p>
        </div>
    </div>

    <div class="cat-wrapper">
        <div class="cat" v-for="categorie in categories" @click="handleClick(`/categories/${categorie.NOM}`)">
            <div class="black-frame"></div>
            <img :src="'/pictures/categories/'+categorie.ID+'.webp'">
            <p class="cat-text-overlay">{{ categorie.NOM }}</p>
        </div>
    </div>

    <div class="collections">
        <h3 class="font-title-24">Les collections Laurelin</h3>
        <div class="collections-wrapper">
            <div class="collection-item" v-for="collection in collections">
                <img :src="`/pictures/collections/${collection.ID}.avif`" @click="handleClick(`/collections/${collection.NOM}`)">
                <h5 class="font-subtitle-16">{{collection.NOM}}</h5>
                <span class="collection-year">{{collection.ANNEE}}</span>
                <p class="font-body-m">{{collection.DESCRIPTION}}</p>
                <a :href="`/collections/${collection.NOM}`" class="collection-btn font-body-l">Découvrir la collection</a>
            </div>
        </div>
    </div>

    <Footer></Footer>
</template>

<script setup>
import Footer from "./Components/Footer.vue"
import Header from "./Components/Header.vue";

defineProps({
    categories: Array,
    collections: Array,
})

function handleClick(url) {
    window.location.href = encodeURI(url)
}
</script>

<style scoped>
    /* Top */
    .hero {
        width: 100vw;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: url("/public/images/nos_bijoux.jpg") no-repeat center center/cover;
    }
    .text-overlay {
        color: white;
        text-align: center;
    }
    .text-overlay .over-text {
        font-size: 18px;
        margin-bottom: 16px;
    }
    .text-overlay .font-title-24 {
        font-size: 28px;
    }

    /* Catégories */
    .cat-wrapper {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 32px;
        padding: 32px 0;
    }
    .cat {
        width: 100vw;
        height: 50vh;
        position: relative;
        cursor: pointer;
    }
    .cat img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
    .cat p {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 100%;
        text-align: center;
        transform: translate(-50%, -50%);
        font-family: "Tenor Sans", serif;
        font-size: 32px;
        line-height: 40px;
        letter-spacing: 4px;
        text-transform: uppercase;
        color: #ffffff;
        z-index: 2;
        transition: letter-spacing .3s ease-in-out;
    }
    .cat .black-frame {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.1);
        transition: background .3s;
        z-index: 1;
    }
    .cat:hover p {
        letter-spacing: 12px;
    }
    .cat:hover .black-frame {
        background: rgba(0, 0, 0, 0.25);
    }

    /* Collections */
    .collections {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 64px;
        padding: 64px 0;
    }
    .collections h3 {
        text-align: center;
    }
    .collections-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 32px;
        padding: 0 64px;
    }
    .collection-item {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        gap: 16px;
    }
    .collection-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 1/1;
        object-fit: cover;
        cursor: pointer;
    }
    .collection-item .collection-year {
        font-size: 12px;
    }
    .collection-btn {
        color: #000000;
    }
    .collection-btn:hover {
        text-decoration: underline;
    }

    /* Media queries */
    @media screen and (min-width: 1000px) {
        .text-overlay .over-text {
            font-size: 24px;
            margin-bottom: 24px;
        }
        .text-overlay .font-title-24 {
            font-size: 48px;
        }
        .cat p {
            font-size: 48px;
        }
    }
    @media screen and (max-width: 900px) {
        .collections-wrapper {
            flex-direction: column;
        }
    }
</style>
