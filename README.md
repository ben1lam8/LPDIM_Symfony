# USMB - Licence Pro DIM - Symfony

Intervenante : Sarah KHALIL

## Dates
* Lundi 05/02 (7h)
* Mardi 06/02 (7h)

## Contenu
1. [Présentation du Framework](#présentation-du-framework)
2. [Installation](#installation)
3. [HTTPFundation/Front Controllers](#httpfundationfront-controllers)
4. [Kernel](#kernel)
5. [Routing](#routing)
6. [Controller](#controller)
7. [Twig & Templating](#twig--templating)
8. [Form](#form)
9. [Autres](#autres)

## Présentation du Framework 
* MVC vs. HTTP
* Roadmap
* Composants & Stack

## Installation
* 3.4 
* installer + symfony new
* Serveur php-symfo

## HTTPFundation/Front Controllers
* Fronts controllers (FC) = points d'entrée
* FC = Instanciation kernel avec environnement dev/prod en paramètre + mode debug (profiler + var/logs -> cf. stack logstash+elastic+kibana)
* FC = Vérification de la version PHP
* HTTPF = Transformation superglobals requête PHP en Request grâce à une factory
* $kernel->handle($request) : Transmission de la Request au kernel (qui routera) et renvoi du résultat comme une Response (qui sera traduite en réponse PHP puis HTTP)
* Finalisation de l'exécution du kernel (évènements, cache, etc.)

## Kernel:
* Déclaration des bundles à charger + instanciation des bundles spécifiques aux environnements de dev et de test (dont WebProfilerBundle)
* Déclaration des répertoires de travail root, cache, logs
* Instanciation d'un loader permettant de lire des fichiers yml de config (notamment celui qui contient la config du FrameworkBundle)
* Chargement du fichier de config lié à l'environnement de la requête (paramètre passé depuis le front controller)
* Transmet la Request au Router

## Routing
* Lors de l'appel $kernel->handle($request) du front controller, la Request est passée à un resolver particulier : le router
* Il compare la cible de la Request à une liste de callables listées en config (via yml, xml ou annotations)
* La première configuration de routage chargée est elle même définie dans la config principale (config.yml). Elle fait pointer vers config/routing.yml
* Selon l'environnement d'exécution, la config principale du routage est surchargée par un autre fichier de config (chargé après la config principale). Certaines routes sont donc spécifiques à l'environnement.
* Les résolutions de routes et les erreurs sont accessibles dans le profiler en environnement de dev
* Les routes sont testées une à une par ordre de chargement (config/routing.yml étant le point d'entrée). Sur un site impliquant beaucoup de routes, il convient donc de les optimiser en s'arrangeant pour les déclarer des plus larges au plus précises.
* Les routes peuvent contenir des paramètres qui seront parsés puis passés au controller.
* Ces formats de routes et de paramètres sont configurables aussi en annotation, en yml ou en xml
* Les routes sont aussi configurables pour imposer une méthode HTTP ou un schéma SSL/TLS (http/https)
* Il est possible de définir un prefixe de route à un ensemble de méthodes-controller en ajoutant une mention de niveau supérieur (en annotation de classe, en clé de niveau supérieur en yml/xml)
* Deboggage : bin/console debug:router <route>

## Controller
* Prend une Request et doit rendre une Response.
* Si la Response contient du HTML invalide (comme avec un return new Response('fzrgerg')), les assets du Profiler ne peuvent pas être insérés et le profiler n'apparaît pas dans la page.
* Le controller utilise un trait (ControllerTrait) qui offre un comportement render() qui lui-même utilise le container pour appeler le moteur de templating (Twig, par défaut)
* Le controller est donc chargé de traiter la Request pour la transformer en Response. Il dispose pour cela d'un objet Request contenant tous les éventuels paramètres liés à la Request.
* Le Request convertit automatiquement le contenu de la query string ou du post_body en ParameterBag. Pour récupérer un paramètre, il faut donc demander à la Request de retourner la valeur d'une clé de son ParameterBag (attribut de Request appelé 'query'): $request->query->get('username');
* Le controller ordonne de calculer la vue conrrespondant à un template. Le chemin de ce template est d'abord cherché dans app/resources/views. Ensuite, il sera recherché dans les bundles, puis sous-bundles...
* Le controller peut aussi rendre la vue comme du HTML (string) plutôt que comme une Response, grâce à renderVue(); Ce contenu doit donc être intégré à une Response (new Response($this->renderView('template'), HTTP_NOT_FOUND))
* Il est possible de passer un tableau de variables vers le template (2eme paramètre de render()), pour que le rendu soit dynamique.
* 25 lignes max

## Twig & Templating
* Sorte de pré-processing de fichiers HTML avec un syntaxte supplémentaire : {# comment #}, {{ display }},  {% execute %}
* Composition par blocs
* Inclusion (include), héritage (extends), héritage horizontal (use), inclusion surchargée (embed)
* Il est possible de récupérer un tableau de variables reçu depuis le controller (2eme paramètre de render())
* Depuis le moteur, une variable globale est accessible : app. Elle contient notamment des données concernant la Request et son ParameterBag ainsi que la route ayant abouti à la génération de cette vue.
* 100 lignes max

## Form
* Ce composant permet de fournir un objet liant le modèle à la vue. Les données peuvent être typées différemment de chaque coté mais le composant gère ces changements.
* Un composant FormType fait ce lien entre une donnée et son champ de formulaire. Il relie donc une entité à un widget.
* Si la conversion de donnée entité-widget est trop complexe pour être 'deviné' par le framework, il faut fournir un DataTransformer entre le vue et le modèle.
* Ex: transfomer un array (model) en checkboxes (vue), il faut un ModelTransformer (sous-classe de DataTransformer pour le sens model vers vue). Ce type de DataTransformer simple est sans doute déjà implémenté dans Symfony...
* La génération d'une vue de formulaire suit un processus dont les étapes sont toujours dans le même ordre. Ces étapes donnent lieux à des évènements (FormEvents). En créant des listeners/subscribers abonnés à ces évenements, il est possible d'influencer le rendu de la vue.

## Autres
### HTTP
Request line (method+uri+protocol) + headers + body
Response line (protocol+code) + headers + body

Méthodes (verbs) HTTP à connaître pour une bonne API (modèle de Richardson) :
* GET           get                             return 200, 304, 404
* POST          create                          return 200, 201, 204
* PUT           update/direct-to-entity         return 
* PATCH         update/route-to-logic
* DELETE        delete
* HEAD          get-response-headers-only
* OPTIONS       get-available-actions
* TRACE         get-vias ?

Headers custom: X-...
Voir easter-eggs dans les headers (exemple de sensiolabs.com)
x-jobs: If you see this header, send us an email to job@sensiolabs.com with this reference

### HTTP 2.0 Asset data push ?
* Rechercher...

### Middleware controller
* Générer une subrequest depuis un controller C1 qui forward vers un C2. C'est ce dernier qui finira par appeler le render du template.

### Master/Sub Request
* Master Request : Request issues d'une requête HTTP d'un client web
* Sub Request : Request générée par l'application Symfony elle-même, notamment pour les forwards entre controllers
* attention, bug connu : transférer des paramètres issus d'un ParameterBag de Master Request en méthod POST dans un ParameterBag de Sub Request peut dégrader les données...

### ESI
* Les requêtes du cache HTTP concernant les ESI doivent être des Master Request
