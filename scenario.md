ROUTAGE API:
====================================================

Actions: USER ENTITY (SCHEMA)
---------------------

*User basic CRUD Generate*
* * *
+ ***Get All User : /api/users * (Admin) (F)[search[orderStatus, orderDelivery => exact], custom[]]***
+ ***Get one user : /api/users/{id}***
+ ***Put user Info: /api/users/{id} * (Admin & Member)***
+ ***Delete one user : /api/users/{id} * (A)***

*User basic SubOperation Generate*
 + ***Get collection order for one user: /api/users/{id}/orders * (A, U(Current)) (F)[]***
 + ***Get collection comment for one user: /api/users/{id}/comment * (A) (F)[]***
 + ***Get collection comment for current user: /api/users/{id}/comment * (M) (F)[]***
 + ***Get collection orders for one user: /api/users/{id}/order * (A)***
 + ***Get collection orders for current user: /api/users/{id}/order * (M)***
* * *

### ***Feature***: En tant que acheteur ou admin, j'aimerai me connecter au système pour effectuer les actions qui require une identification
###***Scénario: User Authenfication:***
***Action POST : /api/login_check (override reponses Token and add roles user informations)***
### Envoi des données en POST ***header "application/ld+json" et accept header "application/ld+json"*** ######

+ Field Email & Mot de passe
>> Verification de l'existance du couple Email & mot de passe dans la source de données :
>> 1. Si la reponse nous retourne un code 200 
>> 1.1. On retourne la reponse de connexion avec les informations de Token et de roles, avec un header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
>> 2. Si la reponse nous retourne un code 401
>> 2.1. On retourne un message d'erreur de (Bad credend-tials)

---------------------------------------

### ***Feature***: En tant que utilisateur, j'aimerai pouvoir créer un compte pour effectuer mes actions en tant que user identifier
###***Scenario: Creation de compte utilisateur:***
***Action POST : /api/register***
### Envoi des données en POST ***header "application/ld+json" et accept header "application/ld+json"*** ######
+ Initial DataList : {email, teléphone, password, familyName, additionalName, *roles } (*) Ajout manuel
1. Avant validation des données , formatage du numero de téléphone au format PhoneNumber (Type de doctrine)
2. Après formatage du phone, validation des données effectuer par le système
 * Si nous rencontrons des Erreurs de validation, on retourne une erreur de type Validation exception et un code 400
 * Si les entrées sont valide & la reponse nous retourne un code 201
    * On Envoi un email de Bienvenue avec les informations users pour le mail
    * On Retourne une reponse (array d'objet) User entity avec un header "Content-Type" should be equal to "application/ld+json; charset=utf-8"
 * Si la reponse nous retourne un code 400
    * On retourne un message d'erreur Invalid input avec la liste des erreurs de validation
 * Si la reponse nous retourne un code 404
    * On retourne un message d'erreur Resource Not Found  
 
 ---------------------------------------
    
 ### ***Feature***: En tant que utilisateur ou Gestionnaire, j'aimerai pouvoir changer mon mot de passe en cas de perte ou oublie
 ###***Scenario: Request forgot password:***
 ***Action POST : /users/forgot-password-request***
 
####Prérequis:
+ Creation d'un DTO (Email field) et creation de l'action (POST)
+ Creation d'une entité RequestPassword (avec les informations [Password-old, email, created])
+ Modification de l'entity user avec ajout d'un field code et hasRequestPassword qui va sauvagarder le code de recupération et le status de recupération du mot de passe 
 ### Envoi des données en POST ***header "application/ld+json" et accept header "application/ld+json"*** ######
 
1. Envoi de l'email
2. Verification du format de l'email
+ Si le format de l'email est valide
    ##### Event => (POST VALID) - API_PLATFORM DESIGN_PATTERN:
    + Verification de l'existance de l'adresse Email dans la base de donnée (Entity User)
    + Si la verification retourne true
        + Creation d'une sauvegarde de l'ancien mot de passe du email this.
        + Géneration du code de verification
        + Mise à jour du User entity avec les informations (code & hasRequest)
        + On déclanche l'envoi d'un email avec un code et message (***Créer un service d'envoi de mail***)
        + Retourne une reponse de type 200, avec une donnée de [success => TRUE ]
    + Si la verification de l'existance retourne false
        + On retourne un code d'erreur Resource Not Found    
      
---------------------------------------

 ### ***Feature*** ***(Use-Case => User has not authenticate )*** : En tant que utilisateur ou Gestionnaire, j'aimerai renseigner mon nouveau mot de passe, en remplacement de celui oublié 
 ###***Scenario: Request check code receive & email:***
 ***Action POST : /users/validate-password-request-code***
 
 ####Prérequis:
 + Mise à jour du DTO, avec le field code pour verification de l'existance avec l'email (Manager request)
 
 ### Envoi des données en POST ***header "application/ld+json" et accept header "application/ld+json"*** ######

1. Envoi de l'email ***(Permettre la modif de l'email dans le front)*** et le code envoyé pas mail [Verification du format effectuer coté Schema]
2. Verification de l'existance de l'adresse Email et du code
+ Si l'existance est vérifié alors :
   + On retourne l'email et le ID du User correspondant
   + Une reponse avec un code 200
+ Si l'existance n'est pas verifier alors on retourne un code de type 404, ressource NOT FOUND

---------------------------------------

 ### ***Feature*** ***(Use-Case => User has not authenticate )*** : En tant que utilisateur ou Gestionnaire, j'aimerai renseigner mon nouveau mot de passe, en remplacement de celui oublié 
 ###***Scenario: Request reset password:***
 ***Action POST : /users/reset-password***   
 
  ####Prérequis:
 + Mise à jour du DTO, avec le field (Password & PlainPassword)
 + Ajout des regles de validations et groupes de validation
 
  ### Envoi des données en POST ***header "application/ld+json" et accept header "application/ld+json"*** ######
  
1. Envoi de l'email ***(Ne pas Permettre la modif de l'email dans le front)*** Password & PlainPassword [Verification du format effectuer coté Schema]
  
2. Verification si l'email envoyer existe et à la valeur hasRequest = true
    + Si la verification retourne TRUE
        + On modifie le HasRequest Password et on met à jour les valeurs Password du user
        + On envoi un email ***(service)*** pour informer l'utilisateur que sont mot de passe à changer.
        + On retourne un code de type 200 avec un message de success
    + Si la reponse retourne false, on renvoi une exception de type 404, avec un Message NOT_FOUND
    + On envoi un email pour avertir le user de ça tentative de fraude

---------------------------------------

 ### ***Feature*** ***(Use-Case => User has authenticate )*** : En tant que utilisateur ou Gestionnaire, j'aimerai mettre à jour mon mot de passe
 ###***Scenario: Request update password:***
 ***Action PUT : api/users/{id}/update-password***
 ***Security : JWT, (Roles => All users)***   
 
  ####Prérequis:
 + Mise à jour du DTO avec action PUT (ItemOperations), ajouter les groupes de validation
 
  ### Envoi des données en PUT ***header "application/ld+json" et accept header "application/ld+json"*** ######
  
1. Envoi du Mot de passe
2. verification coté schema du Mot de passe (PlainPassword & Password field)
    + Si la verification retourne true
        + On met à jour le mot de passe
        + On envoi un email ***(service)*** d'information de modification de mot de passe
        + On retourne une reponse de type 200
    + Si la verification retourne false
        + On retourne une reponse de type 400
            
---------------------------------------

 ###***Feature*** En Tant que Admin, j'aimerai pouvoir créer des comptes utilisateurs
 ###***Scenario: Add user accounts:***
 ***Action PUT : api/users/add***
 ***Security : JWT, (Roles => All users)***
 
 ####Prérequis:
 + Mise à jour du DTO, avec un field Roles de type collection
 + Creation de l'action PUT (ItemOperations), pour la mise à jour des roles users (Group de validation , Normalization)
 
 ### Envoi des données en POST ***header "application/ld+json" et accept header "application/ld+json"*** ######
 
1. Envoi des informations pour la creation de compte (Email, nom, prenom, phoneNumber[Pas obligatoire], roles)
##### Event => (PRE_VALIDATE) - API_PLATFORM DESIGN_PATTERN:
1. Avant Validation des données
    + Formatage des données de numéro de téléphone
    + Generation Password
    + Assignation des informations (Avec is_active = 1)
2. Retourne aucune données

##### Event => (POST_VALIDATE || POST_WRITE) - API_PLATFORM DESIGN_PATTERN:
1. Apres enregistrement du compte utilisateurs, on envoi un mail ***(service mail)*** contenant les informations : Mot de passe et la procedure de connexions
    
 ---------------------------------------
 
  ###***Feature*** En tant que Admin, j'aimerai pouvoir ajouter d'autre rôles à un compte user
  ###***Scenario: Update user roles:***
  ***Action PUT : /api/user/{id}/update-roles***
  ***Security : JWT, (Roles => Admin_Moderator)***
  
 ####Prérequis:
 + Mise à jour du DTO, ajout groupe de validation et Normalization du field roles
 
  ### Envoi des données en PUT ***header "application/ld+json" et accept header "application/ld+json"*** ######

1. Envoi des informations de roles (Un tableau d'information)
##### Event => (POST_WRITE) - API_PLATFORM DESIGN_PATTERN:
 + Envoi d'un mail ***(service mail)*** pour informer l'utilisateurs de ses nouvelles attribution
 + Retourne aucune information
 
 -------------------------------------

Actions: CATEGORY ENTITY (SCHEMA)
 ---------------------
 
 *Category basic CRUD Generate => All auth JWT*
 * * *
 + ***Get all Category: /api/categories/ * (A)***
 + ***Get one Category: /api/category/{id} * (A)***
 + ***Create Categories: /api/category * (A)***
 + ***Put Category: /api/category/{id} * (A)***
 + ***Delete One category: /api/category/{id} * (A)***
 * * *
 *Category basic SubOperation Generate*
  + ***Get all subcategories for one category: /api/category/{id}/sub-category***
 
 ------------------------------- 
  
 Actions: SUBCATEGORY ENTITY (SCHEMA)
 ---------------------
 
 *SubCategory basic CRUD Generate => All auth JWT*
 * * *
 + ***Get all SubCategories: /api/subcategories * (A)***
 + ***Get one SubCategory: /api/subcategory/{id} * (A)***
 + ***Create subcategory: /api/category/{id}/subcategory * (A)***
 + ***Put subcategory: /api/subcategory/{id} * (A)***
 + ***Delete One subcategory: /api/subcategory/{id} * (A)***
 * * *
 *SubCategory basic SubOperation Generate*
  + ***Get Collection product for one subcategory : /api/subcategory/{id}/product * (A)***
  
---------------------------
  
 Actions: BRAND ENTITY (SCHEMA) [Add media in body Data]
 ---------------------
   
 *Brand basic CRUD Generate*
 + ***Create Brand: /api/brand * (A)***
 + ***Get all brand: /api/brands * (A & U)***
 + ***Get one brand: /api/brand/{id} * (A & U)***
 + ***Put one brand: /api/brand/{id} * (A)***
 + ***delete one brand: /api/brand/{id} * (A)***
 * * *
 *Brand basic SubOperation Generate*
  
 + ***Get Collection product for one brand : /api/brand/{id}/product * (A & U) (F)[search[isOnline => exact]]***
 
---------------------------
 
Actions: PAYMENT ENTITY (SCHEMA) [Add media in body Data]
---------------------

 *Payment basic CRUD Generate*

 + ***Create paymentMethod: /api/payment_method * (A)***
 + ***Get all paymentMethod: /api/payment_method * (A & U)***
 + ***Get one payment_method: /api/payment_method/{id} * (A & U)***
 + ***Put one payment_method: /api/payment_method/{id} * (A)***
 + ***delete one payment_method: /api/payment_method/{id} * (A)***
 * * *
  *Payment basic SubOperation Generate*
 + ***Get Collection order for one payment_method : /api/payment_method/{id}/order * (A)***
 
 ---------------------------
  
 Actions: COLLECTION-ITEM ENTITY (SCHEMA) [Add media in body Data]
 ---------------------
 
 *CollectionItem basic CRUD Generate*
 
 + ***Create CollectionItem : /api/collection_items * (A)***
 + ***Get All CollectionItem : /api/collection_items * (A & U)***
 + ***Get One CollectionItem : /api/collection_item/{id} * (A & U)***
 + ***Put one CollectionItem : /api/collection_item/{id} * (A)***
 + ***Delete one CollectionItem: /api/collection_item/{id} (A)***
 * * *
 
*CollectionItem basic SubOperation Generate*

 + ***Get Collection product for one collection: /api/collection_item/{id}/product * (A & U)*** 
 + ***Get Collection banner for one collection : /api/collection_item/{id}/collection_banners * (A & U) (F)[search[isActive => exact]]***
 
 ---------------------------
  
 Actions: COLLECTION-BANNER ENTITY (SCHEMA)
 ---------------------
 
 *CollectionBanner basic CRUD Generate* 
 
+ ***Create CollectionBanner :/api/collection_item/{id}/collection_banners * (A)***
+ ***Get All CollectionBanner : /api/collection_banner * (A & U)***
+ ***Get One CollectionBanner : /api/collection_banner/{id} * (A & U)***
+ ***Put one CollectionBanner : /api/collection_item/{id}/collection_banners/{id} * (A)***
+ ***Delete one CollectionBanner: /api/collection_banners/{id} * (A)***
 * * *
 
*CollectionBanner basic SubOperation Generate*

 + ***Get collection banner for one collection (isActive): /api/collection_item/{id}/collection_banners * (A) (F)[search[isActive => exact]]***


 ---------------------------
  
 Actions: ORDER ENTITY (SCHEMA)
 ---------------------
 
 *Order basic CRUD Generate* 

+ ***Create order for one user: /api/users/{id}/orders * (A,M & U)***
+ ***Get All orders: /api/orders * (f)[search[OrderStatus => exact]] (A)***
+ ***Get one order: /api/order/{id} * (A&M)***
+ ***Put one order : /api/order/{id} * (A&M)***
+ ***Delete one order: /api/order/{id} * (A&M)***

*Order basic SubOperation Generate*

+ ***Get collection ordersProduct for orders: /api/orders/{id}/ordersProduct (@Group) * (A&M)***
+ ***Get current user Collection orders : /api/users/{id}/orders * (M)***

-----------------------------------
###***Feature*** En tant que Admin, j'aimerai pouvoir mettre à jour les status de commandes des utilisateurs
###***Scenario: Update user orders:***
  ***Action PUT : /api/order/{id}/update-order-state * (A&M)***
  ***Security : JWT, (Roles => Admin, Admin_Moderator)***
  
 ####Prérequis:
 + Creation d'un Dto (StatusOrder field et DeliveryDateOrder) et creation de l'action (PUT ci-dessus) [Normalization, G_valid, Swagger_context]
 
  ### Envoi des données en PUT ***header "application/ld+json" et accept header "application/ld+json"*** ######
  
 1. Envoi de la valeur du status et la date de livraison (Pas obligatoire)
 2. Verification niveau (ORM)
##### Event => (POST_VALID) - API_PLATFORM DESIGN_PATTERN:

 + Verification de l'existance de l'order this
    + Si l'order n'existe pas alors on leve une exception de type 404, Not_FOUND
    + Si l'order exist alors on met à jour le field OrderState de order, et on ne retourne rien
    
##### Event => (POST_WRITE) - API_PLATFORM DESIGN_PATTERN:    
 + Envoi d'un email ou sms au client pour l'informer que ça commande à été validé incluant le Code de livraison et Date de livraison        

-----------------------------
  
 Actions: PRODUCT ENTITY (SCHEMA)
 ---------------------
 
*Product basic CRUD Generate* 

+ ***Create product for one subcategory: /api/sub-category/{id}/product * (A) * (A,M)***
+ ***Get all product: /api/products * (f)[search[isActive => exact], ....] (A,M & U)***
+ ***Get one product: /api/products/{id} * (A,M & U)***
+ ***Put one product: /api/products/{id} * (A&M)***
+ ***Delete one product: /api/products/{id} * (A&M)***

*Product basic SubOperation Generate*

+ ***Get collection product search: /api/product/search? [filter:search] & [filter]***
+ ***Get collection offer for one product: /api/product/{id}/offers (f) [search[isActivePrice]]***
+ ***Get collection media for one product: /api/product/{id}/media (f)[search[hasInOne], [isOnline]]***
 
 
 -----------------------------
   
  Actions: OFFER ENTITY (SCHEMA)
  ---------------------
  
 *Offer basic CRUD Generate* 
 
 + ***Create offer for one product: /api/product/{id}/offer * (A,M)***
 + ***Get all offer: /api/offers * (f)[search[isActivePrice => exact], ....] (A,M & U)***
 + ***Get one offer: /api/offers/{id} * (A,M & U)***
 + ***Put one offer: /api/offers/{id} * (A,M)***
 + ***Delete one offer: /api/offers/{id} * (A&M)***
 
 *Offer basic SubOperation Generate*
 
 + ***Get collection offer search: /api/offers/search? [filter:search (by name) or (by price)] & [filter]***
 
 -----------------------------
   
  Actions: MEDIA ENTITY (SCHEMA)
  ---------------------
  
 *MEDIA basic CRUD Generate* 
 
 + ***Create media for one product: /api/product/{id}/add-media * (A,M)***
 + ***Delete one media: /api/media/{id} * (A&M)***
 
 -----------------------------
   
 Actions: COMMENT ENTITY (SCHEMA)
 ---------------------
  
 *Comment basic CRUD Generate* 
 
+ ***Create comment for one user: /api/user/{id}/comments * (A)***
+ ***Get all comment: /api/comments  [filter] ?isOnline***
+ ***Put one comment: /api/comments/{id} * (A)***
+ ***Delete one comments: /api/comments/{id} * (A)***