# LPDIM_Symfony

Intervenante : Sarah KHALIL

## Lundi 05/02 (7h):

### Présentation du Framework 
* MVC vs. HTTP
* Roadmap
* Composants & Stack

### Installation 3.4 (installer + symfony new)

### Serveur php-symfo

### HTTPFundation
* Instanciation kernel avec environnement dev/prod en paramètre + mode debug (profiler + var/logs -> cf. stack logstash+elastic+kibana)
* Vérification de la version PHP
* Transformation superglobals requête PHP en Request de HTTPFoundation grâce à une factory
* $kernel-Whandle($request) : Transmission de la Request au kernel (qui routera) et renvoi du résultat comme une Response (qui sera traduite en réponse PHP puis HTTP)
* Finalisation de l'exécution du kernel (évènements, cache, etc.)

### Kernel:
* Déclaration des bundles à charger + instanciation des bundles spécifiques aux environnements de dev et de test (dont WebProfilerBundle)
* Déclaration des répertoires de travail root, cache, logs
* Instanciation d'un loader permettant de lire des fichier yml de config (notemment celui qui contient la config du FrameworkBundle)
* Chargement du fichier de config lié à l'environnement de la requête (paramètre passé depuis le front controller)
* Transmet la Request au Router

### Routing
* Lors de l'appel $kernel-Whandle($request) du front controller, la Request est passée à un resolver particulier : le router
* Il compare la cible de la Request à une liste de callables listées en config (via yml, xml ou annotations)
* La première configuration de routage chargée est elle même définie dans la config principale (config.yml). Elle fait pointer vers config/routing.yml
* Selon l'environnement d'exécution, la config principale du routage est surchargée par un autre fichier de config (chargé après la config principale). Certaines routes sont donc spécifiques à l'environnement.
* Les résolutions de routes et les erreurs sont accessibles dans le profiler en environnement de dev
* Les routes sont testées une à une apr ordre de chargement (config/routing.yml étant le point d'entrée). Sur un site impliquant beaucoup de routes, il convient donc de les optimisées en s'arrangeant pour les déclarer des plus larges au plus précises.

### Controller
* Prend une Request et doit rendre une Response.
* Si la Response contient du HTML invalide (comme avec un return new Response('fzrgerg')), les assets du Profiler ne peuvent pas être insérés et le profiler n'apparaît pas dans la page.
* Le controller utilise un trait (ControllerTrait) qui offre un comportement render() qui lui-même utilise le container pour appeler le moteur de templating (Twig, par défaut)
* Le controller ordonne donc de calculer la vue conrrespondant à un template. Le chemin de ce template est d'abord cherché dans app/resources/views. Ensuite, il sera recherché dans les bundles, puis sous-bundles...
* Le controller peut aussi rendre la vue comme du HTML (string) plutôt que comme une Response, grâce à renderVue(); Ce contenu doit donc être intégré à une Response (new Response($this->renderView(''template'), HTTP_NOT_FOUND))

### Autre
#### HTTP
Requestline (method+uri+protocol) + headers + body
Responseline (protocol+code) + headers + body

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
#### HTTP 2.0 Asset data push ?
* Rechercher...

#### Middleware controller
* Générer une subrequest depuis un controller C1 qui forward vers un C2. C'est ce dernier qui finira par appeler le render du template.