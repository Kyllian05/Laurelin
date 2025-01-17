import requests
import requests.cookies
from bs4 import BeautifulSoup
import time
import threading
import random
import json

URL = "http://172.21.44.118"
#URL = "http://localhost:8000"

#Variable qui inqique si la requete qui ajoute un produit au panier doit indiquer la taille (pas présent dans toute les versions)
SIZEINCART = True

NAMELIST = ["Pol","Thomas","Kyllian","Jauzua","Brieuc","Baptiste","Mathieu","Sohan"]
LASTNAMELIST = ["Lamothe","Souchet","Arnaud","Destain","LeCarluer","Roques","Suils","Birotheau"]
PASSWORD = "abcABC123*."
VILLEID = 29232
RUENAME = "Rue du Test de Charge"
NUMBEROFMETHOD = 13#Le nombre de méthode d'intéraction du Client
CATEGORIES = ["Bagues","Boucles d'oreilles","Bracelets","Colliers"]

NUMBEROFPRODUIT = 45#Le nombre de produit sur le site

class Client():

    result = []

    def __init__(self,id : int):
        SESSION = requests.Session()

        response = SESSION.get(URL)
        if response.status_code != 200:
            raise Exception("Impossible de se connecter au serveur Laravel")

        # Cherche le token CSRF dans la page HTML (il est dans une balise meta)
        soup = BeautifulSoup(response.text, "html.parser")
        csrf_token = soup.find("meta", {"name": "csrf-token"})["content"]

        #Faire une requête POST avec le token CSRF et les cookies
        headers = {
            "X-CSRF-TOKEN": csrf_token,
            "Accept": "application/json",
        }

        self.NAME = random.choice(NAMELIST)
        self.LASTNAME = random.choice(LASTNAMELIST)
        self.EMAIL = str(self.NAME)+str(id)+"@"+str(self.LASTNAME)+".com"

        registerData = {
            "Prénom":self.NAME,
            "Nom":self.LASTNAME,
            "Adresse e-mail":self.EMAIL,
            "Mot de passe":PASSWORD,
            "J'ai lu et j' accepte les condition d'utilisation":True
        }

        registerResponse = SESSION.post(f"{URL}/auth/register", headers=headers, json=registerData)

        if(registerResponse.status_code != 200):
            raise Exception("L'inscription a échoué\nCode erreur : "+str(registerResponse.status_code))

        loginData = {
            "Adresse e-mail":self.EMAIL,
            "Mot de passe":PASSWORD,
            "Se souvenir de moi":True
        }

        loginResponse = SESSION.post(f"{URL}/auth/login", headers=headers, json=loginData)

        if(loginResponse.status_code != 200):
            raise Exception("La connexion a échoué\nCode erreur : "+str(loginResponse.status_code))

        TOKEN = loginResponse.cookies.get("TOKEN")

        HEADERS = {
            "X-CSRF-TOKEN": csrf_token,
            "Accept": "application/json",
            "TOKEN" : TOKEN
        }

        self.SESSION = SESSION
        self.TOKEN = TOKEN
        self.HEADERS = HEADERS
        self.result = []
        self.threads : list[threading.Thread] = []
        self.adressesID = set()
        self.adresseNumeroCount = 0
        self.avaibleFavorite = set([i+1 for i in range(NUMBEROFPRODUIT)])
        self.commentAvaible = set([i+1 for i in range(NUMBEROFPRODUIT)])
        self.IdInPanier = []

        self.__createAdress()

    def simulateAction(self,SIMULATIONTIME):
        startTime = time.time()
        while(time.time() - startTime < SIMULATIONTIME):
            methodNumber = random.randint(1,NUMBEROFMETHOD)
            thread = None
            if(methodNumber == 1):
                thread = threading.Thread(target=self.__getAccount)
            elif(methodNumber == 2):
                thread = threading.Thread(target=self.__createAdress)
            elif(methodNumber == 3):
                thread = threading.Thread(target=self.__deleteAdress)
            elif(methodNumber == 4):
                thread = threading.Thread(target=self.__ajoutFavoris)
            elif(methodNumber == 5):
                thread = threading.Thread(target=self.__supprimerFavoris)
            elif(methodNumber == 6):
                thread = threading.Thread(target=self.__ajoutCommentaire)
            elif(methodNumber == 7):
                thread = threading.Thread(target=self.__suppressionCommentaire)
            elif(methodNumber == 8):
                thread = threading.Thread(target=self.__ajoutPanier)
            elif(methodNumber == 9):
                thread = threading.Thread(target=self.__suppressionPanier)
            elif(methodNumber == 10):
                thread = threading.Thread(target=self.__checkout)
            elif(methodNumber == 11):
                thread = threading.Thread(target=self.__chargeCategorie)
            elif(methodNumber == 12):
                thread = threading.Thread(target=self.__chargeProduit)
            elif(methodNumber == 13):
                thread = threading.Thread(target=self.__getNumberInPanier)
            else:
                raise Exception("Probleme dans le choix de la méthode")
            thread.start()
            thread.join()
            time.sleep(random.randint(5,10)/10)

    def __getAccount(self):
        self.__makeRequest("get","/account")

    def __createAdress(self):
        self.adresseNumeroCount += 1
        requestData = {
            "Ville" : {
                "ID" : VILLEID
            },
            "Numéro" : self.adresseNumeroCount,
            "Nom de rue" : RUENAME
        }
        response = self.__makeRequest("post","/adresse/ajout",requestData)
        self.adressesID.add(json.loads(response.content.decode())["ID"])

    def __deleteAdress(self):
        if(len(self.adressesID) <= 1):
            self.__createAdress()
            return
        ID = random.choice(list(self.adressesID))
        requestData = {
            "ID" : ID
        }
        response = self.__makeRequest("post","/adresse/supprimer",requestData)
        self.adressesID.remove(ID)

    def __ajoutFavoris(self):
        if(len(self.avaibleFavorite) == 0):
            self.__supprimerFavoris()
            return
        ID = random.choice(list(self.avaibleFavorite))
        requestData = {
            "produit" : ID
        }
        self.__makeRequest("post","/ajouterFavoris",requestData)

    def __supprimerFavoris(self):
        if(len(self.avaibleFavorite) == NUMBEROFPRODUIT):
            self.__ajoutFavoris()
            return
        ID = random.choice([i+1 for i in range(NUMBEROFPRODUIT) if i not in self.avaibleFavorite])
        requestData = {
            "produit" : ID
        }
        self.__makeRequest("post","/supprimerFavoris",requestData)

    def __ajoutCommentaire(self):
        if(len(self.commentAvaible) == 0):
            self.__suppressionCommentaire()
            return
        ID = random.choice(list(self.commentAvaible))
        requestData = {
            "produit" : ID,
            "contenu" : random.choice(COMMENTAIRES)
        }
        self.__makeRequest("post","/nouveauCommentaire",requestData)
        self.commentAvaible.remove(ID)

    def __suppressionCommentaire(self):
        if(len(self.commentAvaible) == NUMBEROFPRODUIT):
            self.__ajoutCommentaire()
            return
        ID = random.choice([i+1 for i in range(NUMBEROFPRODUIT) if i not in self.commentAvaible])
        requestData = {
            "produit" : ID
        }
        self.__makeRequest("post","/supprimerCommentaire",requestData)
        self.commentAvaible.add(ID)

    def __ajoutPanier(self):
        ID = random.randint(1,NUMBEROFPRODUIT)
        if(SIZEINCART):
            requestData = {
                "produit" : ID,
                "taille" : 0
            }
        else:
            requestData = {
                "produit" : ID,
            }
        self.__makeRequest("post","/panier/ajout",requestData)
        self.IdInPanier.append(ID)

    def __suppressionPanier(self):
        if(len(self.IdInPanier) == 0):
            self.__ajoutPanier()
            return
        ID = random.choice(self.IdInPanier)
        requestData = {
            "produit" : ID
        }
        self.__makeRequest("post","/panier/supprimer",requestData)
        self.IdInPanier.remove(ID)

    def __checkout(self):
        if(len(self.IdInPanier) > 0):
            self.__ajoutPanier()
            return
        if(len(self.adressesID) == 0):
            self.__createAdress()
            return
        requestData = {
            "livraison" : random.choice(["domicile","magasin"]),
            "paiement" : {
                "nom" : self.NAME,
                "numéro" : random.randint(1000000000000000,9999999999999999),
                "mois" : random.randint(1,12),
                "année" : random.randint(2025,2028),
                "cryptograme" : random.randint(100,999)
            },
            "adresse" : random.choice(list(self.adressesID))
        }
        self.__makeRequest("post","/checkout/valider",requestData)
        self.IdInPanier = []

    def __chargeCategorie(self):
        self.__makeRequest("get","/categories/"+random.choice(CATEGORIES))

    def __chargeProduit(self):
        self.__makeRequest("get","/produit/"+str(random.randint(1,NUMBEROFPRODUIT)))

    def __getNumberInPanier(self):
        self.__makeRequest("get","/getNumberInPanier")

    def __makeRequest(self,method : str,path : str,requestData : dict = None) -> requests.Response:
        startTime = time.time()
        if(method == "post"):
            response = self.SESSION.post(URL+path,headers=self.HEADERS,json=requestData)
        else:
            response = self.SESSION.get(URL+path,headers=self.HEADERS)
        data = {
            "request" : path,
            "time" : time.time()-startTime,
            "status" : response.status_code,
            "timestamp" : time.time()
        }
        Client.result.append(data)
        return response

    def treatPath(path : str) -> str:
        if("/produit/" in path):
            return "/produit"
        elif("/categories/" in path):
            return "/categories"
        else:
            return path

COMMENTAIRES = [
    "Ce collier est absolument magnifique, il attire vraiment l'attention !",
    "J'adore les détails de cette bague, elle est encore plus belle en vrai qu'en photo.",
    "Le bracelet est léger et très confortable à porter au quotidien.",
    "Les pierres précieuses de ces boucles d'oreilles brillent avec éclat, je suis ravie de mon achat !",
    "Un design élégant et raffiné, parfait pour toutes les occasions.",
    "J'ai acheté ce pendentif en cadeau, et il a fait sensation !",
    "La qualité de fabrication est excellente, on sent que c'est un bijou durable.",
    "Très satisfait(e) du service client et du soin apporté à l'emballage.",
    "Un bijou unique qui complète parfaitement ma collection.",
    "Les finitions sont impeccables, je recommande sans hésiter.",
    "Un vrai coup de cœur, je reçois tellement de compliments quand je le porte.",
    "Parfait pour un look sophistiqué ou décontracté, selon les envies.",
    "Les matériaux utilisés sont de très bonne qualité, je suis impressionné(e).",
    "Un excellent rapport qualité-prix pour un bijou si élégant.",
    "J'ai été agréablement surpris(e) par la rapidité de la livraison et la beauté du produit.",
    "Le design est moderne et intemporel, exactement ce que je cherchais.",
    "Ce bijou apporte une touche d'élégance à toutes mes tenues.",
    "La couleur des pierres est éclatante, encore plus belle que sur les photos.",
    "Un emballage soigné et luxueux, parfait pour offrir.",
    "Les dimensions sont parfaites, ni trop grandes ni trop petites.",
    "Ce bijou a une finition impeccable, on sent l'attention portée aux détails.",
    "Une livraison rapide et un produit conforme à la description.",
    "Je le porte tous les jours, et il reste comme neuf !",
    "Un bijou délicat mais robuste, idéal pour un usage quotidien.",
    "Les gravures sont nettes et précises, c'est un travail remarquable.",
    "Le service après-vente est réactif et très professionnel.",
    "Je ne regrette pas mon achat, il vaut chaque centime dépensé.",
    "Un design qui combine tradition et modernité avec brio.",
    "J'ai reçu ce bijou en cadeau, et c'est devenu mon préféré !",
    "La texture est agréable au toucher, et le bijou est facile à porter.",
    "Terroir express propose un piètre service à coté du votre.",
    "Le boeuf bourgignon de Terroir express hantais mes nuits mais ce bijoux a été mon remède !"
]
