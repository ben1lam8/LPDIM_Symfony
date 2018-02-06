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
9. [Doctrine (DBAL & ORM)](#doctrine-(dbal--orm))
10. [Dependency Injection](#dependency-injection)
11. [Autres](#autres)

## Présentation du Framework 
* Anecdote nom : Simple Framework = SF = SymFony. Simple = Sensio en ???
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
* Ex: un DateTime en model correspond à plusieurs widget select box en vue.
* La représentation intermédiaire d'un formulaire, entre son model et sa vue, est appelée 'normalized'.
* La génération d'une vue de formulaire suit un processus dont les étapes sont toujours dans le même ordre. Ces étapes donnent lieux à des évènements (FormEvents). En créant des listeners/subscribers abonnés à ces évenements, il est possible d'influencer le rendu de la vue.
* Le bouton submit du formulaire est à détacher du Type. C'est le moteur qui doit le générer. Il convient donc d'éclater le rendu du formulaire dans le moteur : form_start(form), form_widget(form), form_rest(form), <input type="submit">, form_end(form)
* Il est possible de ne demander le rendu que d'un type du formulaire : form_row(form.type). Sur de longs formulaires, cela peut devenir fastidieux...
* Chaque form_row est elle même composée du label du Type, de son/ses widgets, et des messages d'erreurs de validation. (form_label(form.type), form_widget(form.type), form_errors(form.type))
* Par défault, HTML5 impose un attribut required sur chaque champ de formulaire. Pour désactiver ce comportement, il faut explmicitement définir le type comme non required (lors de son insertion avec $builder->add(Type::class, ['required'=> false])). Il est aussi possible de passer une valeur d'attibut "novalidation" à la méthode form_start depuis la vue.
* Pour faciliter le rendu complexe d'un formulaire (et pour que ce soit moins moche...), plutôt que de sur-décomposer ses widgets, labels, etc., on utilise un FormTheme.
* Un FormTheme est un template qui définit des blocks spécifiques contenant du code HTML. Lorsque le nom de ce block est appelé depuis un autre template, c'est le rendu définit dans le template qui est généré.
* Un FormTheme custom se range généralement dans app/resources/views/form. Il faut ensuite le définir en config comme étant le thème utilisé (cf CookBook & doc) : config.yml -> twig -> form_themes -> -MyBundle::myformtheme.html.twig .
* Un FormTheme par défaut est fournit dans le bundle twig (cf config:dump-reference twig). Le bundle fournit aussi d'autres themes spécifiques à des thèmes de frameworks CSS connus (ex: bootstrap). Attention : le css n'est pas founi...
* $form->handleRequest($request); lie le formulaire à la requête en cours. Lance la validation des Types.
* $form->isValid() compte le nombre d'erreurs de validation découverte lors du handleRequest.
* L'entité associée au form lors de sa création étant vide lors de la première passe du controller, ses getters DOIVENT avoir un type de retour nullable en php 7+. Les retours nullables ne sont possibles qu'en php 7.1. Donc : ne pas typer les retours d'entité en php 7.
* Doctrine passe par un EntityManager, un objet qui garde en mémoire les opérations à faire sur la base. Le flush valide/commit les opérations demandées. Il peut être aussi utile de clearer l'EntityManager lorsque beaucoup d'opérations lui ont été imposées.
* Pas de persist() dans un update, un flush() suffit ! (moins d'opérations, dont les vérif' de création)

## Doctrine (DBAL & ORM)
* DoctrineBundle utilise la librairie Doctrine en contexte Symfony pour gérer les accès au BDD (PDO + DBAL) ainsi que le mapping des entités (ORM)
* bin/console doctrine:database:create
* bin/console doctrine:schema:create
* bin/console doctrine:schema:update --dump-sql -> bin/console doctrine:schema:update --force (mais pas très pro)
* Pour mettre à jour un schéma, il vaut mieux passer par des migrations, grâce au bundle DoctrineMigrationBundle
* Les migrations sont tracables, et rollbackables. C'est plus lisible dans une PR et c'est défaisable si les conséquences sont graves pour la base.
* bin/console doctrine:migrations:diff génère un fichier app/DoctrineMigration/Versionxxxxxx.php qui représente un instantanné du schéma (seulement).
* bin/console doctrine:migrations:execute xxxxxxxx va executer la class Versionxxxxxxxx générée par diff et donc re-générer le schéma comme il l'était lors du diff.
* Tellement générique que finit par être peu performant sur les grosses bases qui utilisent des moteurs atypiques. Préférer une abstraction plus bas niveau (style PDO) avec une administration de base en béton.
* Relations One-to-One, One-to-Many, etc.

## Dependency Injection
* Les fichiers de config (dev, prod et normal) sont associés à un fichier yml appelé parameters.yml qui contient des clés et valeurs utilisables dans les autres fichiers de config
* Lors de l'execution du composer install, un script est appelé (?). Celui-ci va lire le parameters.yml.dist et générer le parameters.yml correspondant, qui sera ensuite utilisable depuis les autres fichiers de config.

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

### Composer
* Composer est un gestionnaire de dépendances PHP.
* composer.json liste les dépendance d'un projet. Cette définition comprend des règles de validation des versions de dépendances.
* chaque dépendance peut elle-même peut avoir des dépendances.
* la commande composer install va lire les dépendances du composer.json, résoudre les versions demandées puis résoudre les dépendances des dépendances.
* composer install génère ensuite un composer.lock qui garde les versions résolues de toutes les dépendances de tous les niveaux.
* Enfin, composer install télécharge toutes ces dépendances.
* Lors d'un appel ultérieur à composer install, c'est ce fichier .lock qui sera lu, s'il est présent. Les dépendances téléchargées seront donc identique au projet initial.
* La commande composer update supprime ce .lock et poursuit comme un composer install. Si le composer.json a changé ou si les règles de validation des versions sont trop larges, les nouvelles dépendances téléchargées peuvent différer de celle du projet initial. Ceci peut être très dangereux pour la stabilité d'un projet.

### Deboggage
* bin/console config:dump-reference <bundle-prefix> : affiche les informations relatives aux variables d'un bundle.
* bin/console debug:<service> : idem pour un service