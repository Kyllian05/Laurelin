<template>
  <div class="notre-histoire">
    <Header current-page="Notre histoire" />
    <section class="hero">
      <div class="hero-overlay">
        <h1 class="font-title hero-title">À propos de Laurelin</h1>
      </div>
    </section>

    <section class="reputation">
      <div class="reputation-content">
        <div class="reputation-title">
          <h2 class="font-title-24">Une réputation inégalée, de qualité, et de savoir-faire</h2>
        </div>
        <div class="reputation-details">
          <p class="small-title">
            Depuis ses débuts, Laurelin a repoussé les limites de l'artisanat de luxe.
            Chaque création est pensée comme une œuvre d'art, unique et intemporelle.
            La maîtrise des techniques ancestrales se marie avec une innovation constante.
            Nous sélectionnons uniquement les matériaux les plus précieux et durables.
            Notre savoir-faire incarne un héritage qui traverse les générations.
          </p>
          <p>
            Depuis plusieurs décennies, Laurelin s'est imposé comme un pilier dans le monde
            de l'artisanat haut de gamme. De la conception à la réalisation, chaque pièce
            raconte une histoire, riche en détails et en émotions. L'équilibre parfait
            entre tradition et modernité nous permet de proposer des bijoux qui défient le
            temps, tout en restant profondément ancrés dans l'air du temps. Nos artisans,
            formés aux techniques les plus minutieuses, travaillent avec passion pour créer
            des pièces qui évoquent l'élégance et le raffinement. En collaborant avec des
            partenaires locaux et en respectant des normes environnementales strictes, nous
            nous engageons à construire un avenir plus durable, sans compromettre l'excellence
            de nos créations. Rejoignez notre univers et découvrez ce que signifie réellement
            le luxe authentique.
          </p>
        </div>
      </div>
    </section>



    <section class="le-createur" :class="{ expanded: isCreatorExpanded }" @click="toggleSection('creator')">
  <div class="section-content left">
    <div class="background-blur" :class="{ active: isCreatorExpanded || isHoveredCreator }" @mouseenter="hoverSection('creator', true)" @mouseleave="hoverSection('creator', false)"></div>
    <div class="text-box text-left ">
      <h3 class="font-subtitle-16">Le Créateur</h3>
      <p v-if="!isCreatorExpanded">Voir plus...</p>
      <p v-else>Découvrez comment Laurelin a redéfini le monde du luxe avec des créations intemporelles.</p>
      <div v-if="isCreatorExpanded" class="extra-content">
        <p>Les innovations de Laurelin au cours du temps marquent l'histoire du bijou à jamais.</p>
        <ul>
          <li>Laurelin a introduit des techniques artisanales révolutionnaires.</li>
          <li>Ses inspirations proviennent d’une fusion unique entre nature et architecture.</li>
          <li>Une vision qui marie innovation et tradition pour des pièces inoubliables.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="nos-creations" :class="{ expanded: isCreationsExpanded }" @click="toggleSection('creations')">
  <div class="section-content left">
    <div class="background-blur" :class="{ active: isCreationsExpanded || isHoveredCreations }" @mouseenter="hoverSection('creations', true)" @mouseleave="hoverSection('creations', false)"></div>
    <div class="text-box text-left ">
      <h3 class="font-subtitle-16">Nos Créations</h3>
      <p v-if="!isCreationsExpanded">Voir plus...</p>
      <p v-else>Explorez les chefs-d'œuvre qui incarnent l'essence de Laurelin.</p>
      <div v-if="isCreationsExpanded" class="extra-content">
        <p>L'histoire de la feuille est inextricablement liée à l'esprit innovateur de Jeffrey Souchet.</p>
        <ul>
          <li>Chaque création est soigneusement conçue pour capturer l'essence de l'élégance.</li>
          <li>Des matériaux précieux, éthiquement sourcés, confèrent une qualité exceptionnelle.</li>
          <li>Un design qui parle à l'âme et transcende les tendances éphémères.</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section class="localisation" :class="{ expanded: isDurabilityExpanded }" @click="toggleSection('durability')">
  <div class="section-content left">
    <div class="background-blur" :class="{ active: isDurabilityExpanded || isHoveredDurability }" @mouseenter="hoverSection('durability', true)" @mouseleave="hoverSection('durability', false)"></div>
    <div class="text-box text-left ">
      <h3 class="font-subtitle-16">Durabilité</h3>
      <p v-if="!isDurabilityExpanded">Voir plus...</p>
      <p v-else>Notre engagement envers l'éthique et l'environnement guide chaque décision.</p>
      <div v-if="isDurabilityExpanded" class="extra-content">
        <p>La vision à long terme est au cœur de la philosophie de Laurelin.</p>
        <ul>
          <li>Une chaîne d'approvisionnement entièrement traçable et respectueuse des normes éthiques.</li>
          <li>Utilisation de matériaux recyclés et recyclables dans nos emballages.</li>
          <li>Initiatives pour réduire l'empreinte carbone et favoriser des pratiques durables.</li>
        </ul>
      </div>
    </div>
  </div>
</section>


    <Footer />
  </div>
</template>

<script setup>
import Footer from "./Components/Footer.vue";
import Header from "./Components/Header.vue";
import { ref } from "vue";

const isCreatorExpanded = ref(false);
const isCreationsExpanded = ref(false);
const isDurabilityExpanded = ref(false);

const isHoveredCreator = ref(false);
const isHoveredCreations = ref(false);
const isHoveredDurability = ref(false);

const toggleSection = (section) => {
  if (section === "creator") {
    isCreatorExpanded.value = !isCreatorExpanded.value;
    isCreationsExpanded.value = false;
    isDurabilityExpanded.value = false;
  } else if (section === "creations") {
    isCreatorExpanded.value = false;
    isCreationsExpanded.value = !isCreationsExpanded.value;
    isDurabilityExpanded.value = false;
  } else if (section === "durability") {
    isCreatorExpanded.value = false;
    isCreationsExpanded.value = false;
    isDurabilityExpanded.value = !isDurabilityExpanded.value;
  }
};

const hoverSection = (section, isHovered) => {
  if (section === "creator") isHoveredCreator.value = isHovered;
  if (section === "creations") isHoveredCreations.value = isHovered;
  if (section === "durability") isHoveredDurability.value = isHovered;
};
</script>

<style scoped>
body {
  margin: 0;
  overflow-x: hidden;
}

.notre-histoire {
  color: #333;
}

.hero {
  position: relative;
  height: 100vh;
  background: url('/public/images/creation.jpg') no-repeat center center/cover;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-title {
  color: white;
  font-size: 50px;
  text-align: center;
}

.reputation {
  background-color: #f5f5f5;
  padding: 128px 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.reputation-content {
  display: flex;
  width: 100%;
  max-width: 1200px;
  justify-content: space-between;
}

.reputation-title {
  flex: 1;
  text-align: left;
  display: flex;
  justify-content: center;
}

.reputation-title h2 {
    font-size: 40px;
    color: #C79600;
    margin-bottom: 30px;
    line-height: 1.6;
}

.reputation-details {
  flex: 1;
  text-align: left;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}

.reputation-details .small-title {
    margin-bottom: 30px;
    max-width: 500px;
    word-wrap: break-word;
    font-weight: 600;
}

.reputation-details p {
  color: #444;
  max-width: 500px;
}



.le-createur,
.nos-creations,
.localisation {
  position: relative;
  height: 300px;
  width: 100%;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
  margin-top: 8px;
  cursor: pointer;
  transition: all 0.4s ease-in-out;
  color: black;
}

.expanded {
  height: 500px;
}

.background-blur {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  z-index: 1;
  transition: all 0.3s ease-in-out;
  filter: blur(0);
}

.background-blur.active {
  filter: blur(4px);
}

.le-createur .background-blur {
  background-image: url('/public/images/createur.jpg');
}

.nos-creations .background-blur {
  background-image: url('/public/images/bluediamond.jpg');
}

.localisation .background-blur {
  background-image: url('/public/images/durabilite.jpg');
}

.text-box {
  position: relative;
  z-index: 2;
  max-width: 500px;
  padding: 16px;
  transition: all 0.3s ease-in-out;
}

.text-box.text-left {
  margin-right: 600px;
  text-align: left;
}


.extra-content {
  margin-top: 15px;
  color: black;
}

.text-box h3 {
    font-size: 25px;
  }

  .text-box p {
    font-size: 15px;
  }

@media (max-width: 768px) {
  .hero {
    height: 50vh;
  }

  .hero-title {
    font-size: 32px;
    padding: 0 16px;
  }

  .le-createur,
  .nos-creations,
  .localisation {
    height: auto;
    padding: 16px;
  }

  .text-box h3 {
    font-size: 30px;
  }

  .text-box p {
    font-size: 20px;
  }
}
</style>
