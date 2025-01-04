<template>
    <div class="notre-histoire">
      <Header current-page="Notre histoire" />
  
      <section class="hero">
        <div class="hero-overlay">
          <h1 class="hero-title">À propos de Laurelin</h1>
        </div>
      </section>
  
      <section class="reputation">
        <div class="reputation-content">
          <div class="reputation-title">
            <h2>Une réputation inégalée, de qualité, et de savoir-faire</h2>
          </div>
          <div class="reputation-details">
            <h3 class="small-title">
              Depuis ses débuts, Laurelin a repoussé les limites de l'artisanat de luxe. 
              Chaque création est pensée comme une œuvre d'art, unique et intemporelle. 
              La maîtrise des techniques ancestrales se marie avec une innovation constante. 
              Nous sélectionnons uniquement les matériaux les plus précieux et durables. 
              Notre savoir-faire incarne un héritage qui traverse les générations.
            </h3>
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
  
      <section class="le-createur" @click="redirectTo('/page-definie-plus-tard')">
        <div class="section-content left">
          <div class="background-blur"></div>
          <div class="text-box text-left">
            <h3>Le Créateur</h3>
            <p>Les innovations de Laurelin au cours du temps marquent l'histoire du bijou à jamais.</p>
          </div>
        </div>
      </section>
  
      <section class="nos-creations" @click="redirectTo('/page-definie-plus-tard')">
        <div class="section-content right">
          <div class="background-blur"></div>
          <div class="text-box text-right">
            <h3>Nos Créations</h3>
            <p>L'histoire de la feuille est inextricablement liée à l'esprit innovateur de Jeffrey Souchet, son créateur.</p>
          </div>
        </div>
      </section>
  
      <section class="localisation" @click="redirectTo('/page-definie-plus-tard')">
        <div class="section-content left">
          <div class="background-blur"></div>
          <div class="text-box text-left black-text">
            <h3>Durabilité</h3>
            <p>La vision à long terme est au cœur de la philosophie de Laurelin.</p>
          </div>
        </div>
      </section>
  
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Footer from "./Components/Footer.vue";
  import Header from "./Components/Header.vue";
  
  const redirectTo = (url) => {
    window.location.href = url;
  };
  </script>
  <style scoped>
  body {
    margin: 0;
    overflow-x: hidden;
  }
  
  .notre-histoire {
    font-family: 'Arial', sans-serif;
    color: #333;
    line-height: 1.6;
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
    font-size: 48px;
    text-align: center;
  }
  
  .reputation {
    background-color: #f5f5f5;
    padding: 48px 32px;
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
    font-size: 56px;
    font-weight: bold;
    color: #C79600;
    margin-bottom: 30px;
  }
  
  .reputation-details {
    flex: 1;
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
  }
  
  .reputation-details .small-title {
    font-size: 20px;
    margin-bottom: 30px;
    max-width: 500px;
    word-wrap: break-word;
  }
  
  .reputation-details p {
    font-size: 20px;
    color: #444;
    line-height: 1.6;
    max-width: 500px;
    margin-top: 10;
  }
  
  .le-createur,
  .nos-creations,
  .localisation {
    position: relative;
    height: 700px;
    width: 100%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px;
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
    transition: filter 0.3s ease;
    cursor: pointer;
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
  
  .section-content:hover .background-blur {
    filter: blur(5px);
  }
  
  .text-box {
    position: relative;
    z-index: 2;
    max-width: 500px;
    padding: 16px;
  }
  
  .text-box h3 {
    font-size: 30px;
    color: white;
    margin: 0;
    transition: transform 0.3s ease-in-out;
  }
  
  .text-box p {
    font-size: 20px;
    color: white;
    margin: 0;
    word-wrap: break-word;
    transition: transform 0.3s ease-in-out;
  }
  
  .text-box.text-left {
    margin-right: 600px;
    text-align: left;
  }
  
  .text-box.text-right {
    margin-left: 600px;
    text-align: right;
  }
  
  .section-content:hover .text-box h3,
  .section-content:hover .text-box p {
    transform: scale(1.1);
  }
  
  .text-box.black-text h3,
  .text-box.black-text p {
    color: black;
  }
  
  @media (max-width: 768px) {
    .hero {
      height: 50vh;
    }
  
    .hero-title {
      font-size: 32px;
      padding: 0 16px;
    }
  
    .reputation-content {
      flex-direction: column;
      text-align: center;
      align-items: center;
    }
  
    .reputation-title h2 {
      font-size: 36px;
    }
  
    .reputation-details .small-title,
    .reputation-details p {
      font-size: 16px;
      text-align: center;
      max-width: 100%;
    }
  
    .text-box.text-left,
    .text-box.text-right {
      margin: 0;
      text-align: center;
    }
  
    .text-box h3 {
      font-size: 24px;
    }
  
    .text-box p {
      font-size: 16px;
    }
  
    .le-createur,
    .nos-creations,
    .localisation {
      height: auto;
      padding: 16px;
    }
  }
  </style>
  