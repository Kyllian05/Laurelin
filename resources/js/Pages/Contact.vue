<template>
    <Header current-page="Contact" />
    <section class="contact-section">
      <div class="contact-container">
        <div class="contact-left">
          <h2>Entrer en contact</h2>
          <p>
            Vous avez une question ou besoin d'aide ? Notre équipe chez Laurelin est là pour vous aider.
            Appelez-nous ou envoyez-nous un e-mail et commençons !
          </p>
        </div>
        <div class="contact-right">
          <form class="contact-form">
            <button type="button" class="untoggle-button" @click="alert('Ce bouton n\'a pas encore de fonctionnalité.')" >
              +33 6 49 20 72 71
            </button>
 
            <a
              href="mailto:contact.laurelin@gmail.com"
              class="contact-email-button"
            >
              contact.laurelin@gmail.com
            </a>

            <button type="button" @click="toggleTextarea" >
            Posez-nous n'importe quelle question ci-dessous...
            <span :class="{'arrow': true, 'rotated': isTextareaVisible}">▼</span>
          </button>
            <textarea
              v-show="isTextareaVisible"
              placeholder="Nom Complet"
              rows="1"
            ></textarea>
            <textarea
              v-show="isTextareaVisible"
              placeholder="Votre Mail"
              rows="1"
            ></textarea>
            <textarea
              v-show="isTextareaVisible"
              placeholder="Qu'avez-vous à nous dire..."
              rows="5"
            ></textarea>
            
            <button type="submit" v-show="isTextareaVisible">Send</button>
          </form>
        </div>
      </div>
    </section>
    <Footer />
  </template>
  
  <script setup>
  import { ref } from "vue";
  import Footer from "./Components/Footer.vue";
  import Header from "./Components/Header.vue";
  

  const isTextareaVisible = ref(false);
  
  const toggleTextarea = () => {
    isTextareaVisible.value = !isTextareaVisible.value;
  };
  </script>
  
  <style scoped>
  .contact-section {
    background-color: #ffffff;
    padding: 50px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Tenor Sans", serif;
    font-size: 24px;
  }
  
  .contact-container {
    display: flex;
    flex-wrap: wrap;
    max-width: 1200px;
    width: 100%;
    gap: 40px;
    margin-top: 200px;
    margin-bottom: 200px;
  }
  
  .contact-left {
    flex: 1;
    min-width: 300px;
  }
  
  .contact-left h2 {
    font-size: 48px;
    color: #c79600;
    margin-bottom: 20px;
  }
  
  .contact-left p {
    font-size: 18px;
    color: #444;
    line-height: 1.6;
  }
  
  .contact-right {
    flex: 1;
    min-width: 300px;
    background-color: #ededed;
    border-radius: 10px;
    padding: 10px;
  }
  
  .contact-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    border-radius: 10px;
  }
  
  .contact-form input,
  .contact-form textarea {
    width: 100%;
    padding: 15px;
    font-size: 18px;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  
  .contact-form button {
    padding: 15px;
    background-color: #ffffff;
    color: #000;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    font-family: "Tenor Sans", serif;
    font-size: 18px;
  }

  .contact-form button:hover {
    background-color: #000;
    color: #fff;
    cursor: pointer;
  }
  .contact-form .untoggle-button:hover{
    cursor: default;
  }
  
  .contact-form textarea {
    resize: none;
  }
  
  .contact-email-button {
    text-decoration: none;
    color: #000;
    background-color: #ffffff;
    padding: 15px;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    display: inline-block;
    text-align: center;
    
  }
  
  .contact-email-button:hover {
    background-color: #000;
    color: #fff;
  }

.arrow {
  display: inline-block;
  margin-left: 10px;
  font-size: 18px;
  transition: transform 0.3s ease;
}

.rotated {
  transform: rotate(180deg);
}
  </style>
  
