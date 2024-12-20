<template>
    <div id="firstWrapper">
      <div class="footer-section">
          <img src="/public/images/logo.png" alt="Logo Laurelin" class="logo">
        </div>
        <div class="footer-section">
          <h2 class="section-title">Laurelin</h2>
        <ul>
          <li class="subsection-title">Nos Produits</li>
          <ul>
            <li v-for="(link, product) in content.Laurelin['Nos Produits']" :key="product">
              <a :href="link">{{ product }}</a>
            </li>
          </ul>
          <li class="subsection-title">À Propos de Nous</li>
          <ul>
            <li v-for="(link, about) in content.Laurelin['A Propos de Nous']" :key="about">
              <a :href="link">{{ about }}</a>
            </li>
          </ul>
          <li class="subsection-title">Contact</li>
          <ul>
            <li v-for="(link, contact) in content.Laurelin.Contact" :key="contact">
              <a :href="link">{{ contact }}</a>
            </li>
          </ul>
        </ul>
      </div>
  
      <!-- Legal and Confidentiality Section -->
      <div class="footer-section">
        <h2 class="section-title">Légal et Confidentialité</h2>
        <ul>
          <li class="subsection-title">Mentions Légales</li>
          <ul>
            <li v-for="(link, legal) in content['Legal et Confidentialité']['Mentions Légales']" :key="legal">
              <a :href="link">{{ legal }}</a>
            </li>
          </ul>
          <li class="subsection-title">Support</li>
          <ul>
            <li v-for="(link, support) in content['Legal et Confidentialité'].Support" :key="support">
              <a :href="link">{{ support }}</a>
            </li>
          </ul>
        </ul>
      </div>
  
      <div class="footer-section">
        <h2 class="section-title">Canaux Principaux</h2>
        <div class="social-links">
          <a href="https://www.instagram.com/laurelin.jewels/">
            <img src="#" alt="Instagram" class="icon">
          </a>
          <a href="https://x.com/LaurelinJewels">
            <img src="#" alt="X" class="icon">
          </a>
          <a href="#">
            <img src="#" alt="Pinterest" class="icon">
          </a>
        </div>
      </div>
    </div>
    <div id="secondWrapper">
        <a href="https://fr.wikipedia.org/wiki/Jeffrey_Epstein">Notre association</a>     
    </div>
  </template>
  
  <script setup>
  const content = {
    "Laurelin": {
      "Nos Produits": {
        "Bagues": "/bagues",
        "Bracelet": "/bracelet",
        "Collier": "/collier",
        "Parures": "/parures",
        "Boucles d'Oreilles": "/boucles-d-oreilles",
      },
      "A Propos de Nous": {
        "Créateur": "/createur",
        "Créations": "/creations",
        "Durabilité": "/durabilite",
      },
      "Contact": {
        "Nous contacter": "/contact",
        "Questions fréquentes": "/faq",
      },
    },
    "Legal et Confidentialité": {
      "Mentions Légales": {
        "Conditions d’utilisation": "/conditions-utilisation",
        "Conditions de vente": "/conditions-vente",
        "Politique de confidentialité": "/politique-confidentialite",
        "Politique de cookies": "/politique-cookies",
      },
      "Support": {
        "Accessibilité": "/accessibilite",
        "Effectuer un signalement": "/signalement",
      },
    },
  };
  </script>
  
  <style scoped>
  /* General Wrapper */
  #firstWrapper {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 2rem 3rem;
    background-color: #f9f9f9;
    gap: 1rem;
  }
  #secondWrapper {
    height: 400px;
    background-image: url('/public/images/paysage.jpg');
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
}

#secondWrapper a {
    color: white;
    font-size: 50px;
    text-decoration: none;
}
#secondWrapper a:hover {
    cursor: pointer;
    color: white;
    transition-duration: 0.5s;
    scale: 1.1;
}
  .footer-section {
    flex: 1;
    margin: 0 1rem;
  }
  
  .section-title {
    font-size: 20px;
    color: #c79600;
    margin-bottom: 1rem;
  }
  
  .logo-title-container {
    display: flex;
    align-items: center;
    padding: 10px;
    justify-content: space-between;
  }
  
  .logo {
    width: 150px;
    height: auto;
  }
  
  .subsection-title {
    font-size: 16px;
    margin: 1rem 0 0.5rem;
  }
  
  ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  

  
  a {
    text-decoration: none;
    color: #333;
    font-size: 14px;
  }
  
  a:hover {
    text-decoration: underline;
  }
  
  .social-links {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .icon {
    width: 24px;
    height: 24px;
  }

  @media (max-width: 768px) {
    #firstWrapper {
      flex-direction: column;
    }
  
    .footer-section {
      margin-bottom: 2rem;
    }
  }
  </style>
  