Framework PHP & Application de Blogging
=======================================

Ceci est la version finale du « mini framework » PHP développé en cours du 4 au
8 janvier 2015. Le dossier `src` contient à la fois le code générique du
framework dans le namespace `Framework` et le code du blog dans le namespace
`Application`.

Cette section décrit les différents composants du projet qui ont été développés
pendant le cours et améliorés par la suite. Le cours *Modules Technos Web* porte
en priorité sur l'apprentissage du langage PHP en mode orienté objet ainsi que
sur des concepts plus fondamentaux tels que le Web, le protocole HTTP, la
sécurité et l'architecture logicielle.

Installation du projet
----------------------

Commencez par cloner le dépôt de code Github à l'aide de la commande suivante :

    $ git clone https://github.com/hhamon/lpdim2016.git Blog

Puis, exécutez la série de commandes suivantes pour installer le projet :

    $ cd Blog/
    $ cp app/config/settings.yml.dist app/config/settings.yml
    $ php composer.phar install
    $ chmod -R 777 app/cache/ app/logs/

*Attention :* assurez-vous d'avoir le fichier `composer.phar` dans le dossier
`bin/` du projet. Vous pouvez télécharger l'utilitaire `composer.phar` depuis le
site officiel de Composer.

    https://getcomposer.org/

Il ne vous reste alors plus qu'à créer une base de données sur votre serveur
MySQL à l'aide d'un gestionnaire client (ligne de commande, PHPMyAdmin, MySQL
Workbench etc.). Lorsque la base de données est créée, exécutez le fichier
`app/data/db.sql` pour construire les tables et insérer des données d'exemple.

Enfin, n'oubliez pas de configurer les accès à la base de données ainsi que le
chemin du fichier de logs dans le fichier `config/settings.yml`.

```yaml
# config/settings.yml
parameters:
    database.dsn:      mysql:host=localhost;port=3306;dbname=lp_dim
    database.user:     root
    database.password: ~
    monolog.log_file:  /Volumes/Development/Sites/Blog/logs/dev.log
```

Le chemin vers le fichier de logs doit être absolu.

Exécution des tests unitaires
-----------------------------

Les tests unitaires sont écrits et éxecutés à l'aide de l'outil `PHPUnit`.
PHPUnit est un framework d'automatisation des tests unitaires développé par
Sebastian Bergmann. Il s'agit d'un portage en PHP du très célèbre `JUnit`.
PHPUnit offre toute une palette de fonctionnalités telles que l'exécution des
tests unitaires, la génération de rapports de couverture de code (HTML,
Clover etc.) ou bien encore la génération de rapports compatibles `JUnit`.

Pour lancer la suite de tests unitaires, il suffit d'exécuter la ligne de
commande suivante :

    $ php bin/phpunit.phar

Cette commande lit automatiquement le fichier de configuration `phpunit.xml` à
la racine du projet afin de savoir où se trouvent les fichiers de tests
unitaires à exécuter. L'exécution de cette commande produit un résultat
similaire à celui ci-dessous :

    PHPUnit 5.1.3 by Sebastian Bergmann and contributors.
    
    .....................................    37 / 37 (100%)
    
    Time: 269 ms, Memory: 5.50Mb
    
    OK (37 tests, 127 assertions)

Enfin, il a été vu en cours que PHPUnit est aussi capable de générer des
rapports de couverture de code aux formats HTML et Clover (XML). Il suffit pour
cela d'exécuter la commande `phpunit.phar` avec les options `--coverage-html` et
`--coverage-clover` comme ci-dessous :

    $ php bin/phpunit.phar --coverage-html ./coverage --coverage-clover ./clover.xml

Le dossier `coverage/` ainsi créé à la racine du projet contient un fichier
`index.html` à ouvrir dans un navigateur. Le fichier `clover.xml` peut quant à
lui être utilisé par PHPStorm via le plugin `PHPUnit Code Coverage`.

Composer
--------

Composer est un gestionnaire de dépendances pour les applications PHP modernes.
Il s'agit d'un outil similaire à `apt-get` / `aptitude` pour Ubuntu, `npm` pour
NodeJS ou bien encore `gem` pour Ruby. Composer se charge d'installer les
paquets PHP tiers dont le projet a besoin pour fonctionner ainsi que de les
mettre à jour quand de nouvelles versions de ces derniers sont publiées. Par
défaut, les paquets sont installés dans le dossier `vendor/` que Composer crée à
la racine du projet.

    $ tree -L 2 vendor/
    vendor/
    ├── autoload.php
    ├── composer
    │   ├── ClassLoader.php
    │   ├── LICENSE
    │   ├── autoload_classmap.php
    │   ├── autoload_namespaces.php
    │   ├── autoload_psr4.php
    │   ├── autoload_real.php
    │   └── installed.json
    ├── monolog
    │   └── monolog
    ├── psr
    │   └── log
    ├── symfony
    │   └── yaml
    └── twig
        └── twig

Par exemple, comme le montre le snippet ci-dessous, le projet réalisé en cours a
besoin d'un certain nombre d'outils tiers tels que Twig ou Symfony. Ces
dépendances sont décrites à la section `require` dans le fichier `composer.json`
situé à la racine du projet.

```php
{
    "name": "hhamon/lpdim2016",
    "description": "Another PHP framework",
    "type": "library",
    "license": "proprietary",
    "authors": [
        {
            "name": "Hugo Hamon",
            "email": "hugo.hamon@sensiolabs.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Tests\\": "tests/"
        }
    },
    "minimum-stability": "alpha",
    "require": {
        "twig/twig": "^1.23",
        "symfony/yaml": "^3.0"
    }
}
```

Chaque paquet possède un nom du type `organisation/paquet` auquel est associé
une expression de version. Le symbôle tilde `~` signifie *à partir de* mais en
excluant les versions majeures. Le symbôle `^` est identique à `~` mais inclut
aussi les versions majeures. Par conséquent, Composer ira chercher pour chaque
paquet la version la plus à jour à partir de celle indiquée dans le fichier
`composer.json`. Il est aussi possible d'exprimer les versions à l'aide d'une
valeur fixe (`3.0.1`), d'un intervalle (`>=2.5-dev,<2.6-dev`), d'une branche Git
(`dev-master`) ou bien encore d'un numéro de commit dans une branche
(`dev-master#ea6f2130b4cab88635d35a848d37b35f1e43d407`).

Pour ajouter un nouveau paquet, il suffit d'exécuter la commande `require`
suivante et Composer se charge de l'installer dans le projet s'il est compatible
avec les autres paquets installés.

    $ php bin/composer.phar require symfony/finder

Pour mettre à jour un paquet ou bien toutes les dépendances, il suffit
d'exécuter la commande `update` de l'utilitaire `composer.phar`. De plus, pour
trouver un paquet, Composer s'appuie sur l'annuaire
[Packagist.org](http://packagist.org) qui référence tous les paquets Open-Source
installables.

    $ # MAJ toutes les dépendances
    $ php bin/composer.phar update
    $
    $ # MAJ que le paquet symfony/yaml
    $ php bin/composer.phar update symfony/yaml

L'installation d'un nouveau paquet ou bien la mise à jour d'une ou des
dépendances entraîne la regénération du fichier `composer.lock`. Ce fichier
contient la liste de toutes les dépendances installées dans le dossier `vendor/`
ainsi que les numéros des versions précisément installées. Il convient donc de
commiter ce fichier dans le dépôt Git car la commande `install` de Composer
installe les dépendances telles qu'elles sont décrites dans le fichier
`composer.lock` sans chercher à les mettre à jour. C'est donc idéal pour
s'assurer que les versions des paquets seront les bonnes en production lors du
déploiement ou bien tout simplement lorsque quelqu'un d'autre récupère le dépôt.

Lorsque toutes les dépendances sont installées, les classes des paquets sont
directement instanciables dans le code sans nécessiter de les inclure avec les
instructions `require` ou `include` de PHP. Composer vient avec des mécanismes
d'autochargement des classes pour chaque paquet installé. La section `autoload`
du fichier `composer.json` décrit quant à elle les mécanismes d'autochargement
des classes du projet.

```json
{
    # ...
    "autoload": {
        "psr-4": {
            "Acme\\": "acme/",
            "": "src/"
        }
    },
    # ...
}
```

La description ci-dessus signifie que les classes du projet à autocharger
doivent tout d'abord respecter la convention `PSR-4` établie par le
[PHP-FIG](http://www.php-fig.org/). Les classes dont l'espace de nommage débute
par la chaîne `Acme\` sont à trouver dans un dossier `acme/` tandis que toutes
les autres classes seront autochargées depuis le dossier `src/`.

    $ php bin/composer.phar dumpautoload --optimize

La commande `dumpautoload` de l'utilitaire `composer.phar` regénère les fichiers
d'autochargement si nécessaire. Elle s'accompagne aussi d'une option `--optimize`
qui optimise le processus d'autochargement. Cette option est particulièrement
utile sur un serveur de production.

PSR & FIG
---------

L'acronyme *PSR* signifie *PHP Standard Recommendation* ou littéralement
*Recommandation Standard PHP* en français. Une *PSR* est en fait une règle
standard et universelle de codage définie par le *PHP FIG* afin que les
frameworks et bibliothèques de code moderne soient interopérables. Il existe
aujourd'hui plusieurs standards *PSR* édités par le *FIG*.

*FIG* est l'acronyme pour *Framework Interoperability Group*. Il s'agit d'un
groupe de développeurs PHP fondé à la fin de l'année 2009 à la suite de la
conférence annuelle ZendCon. Les principaux développeurs des frameworks et
bibliothèque PHP tels que Symfony, Zend, CakePHP, Doctrine, Twig, Drupal, etc.
qui étaient présents à cette conférence ont eu l'idée de se rassembler et mettre
en commun leur savoir pour définir des règles d'interopérabilité du code.
L'objectif était ainsi de pouvoir plus facilement utiliser le code d'un
framework dans un autre compétiteur sans trop d'efforts.

Depuis le début de l'année 2010, plusieurs règles *PSR* ont vu le jour pour
standardiser entre autres les mécaniques d'autochargement des classes ou bien
les styles et conventions de codage. Le tableau ci-dessous dresse la liste des
PSRs actuellement validés ou bien en cours de travail.

| PSR    | Statut   | Explications
|--------|----------+------------------------------------------------------------------------------------------|
| PSR-0  | Obsolète | Mécanismes standards d'autochargement des classes.                                       |
| PSR-1  | Acceptée | Conventions et styles de codage obligatoires.                                            |
| PSR-2  | Acceptée | Conventions et styles de codage facultatifs.                                             |
| PSR-3  | Acceptée | Interface commune pour développer des implémentations d'objet « logger ».                |
| PSR-4  | Acceptée | Amélioration de PSR-0 et dépréciation des classes préfixées.                             |
| PSR-5  | En cours | Standards de documentation API du code pour les outils comme phpDocumentor.              |
| PSR-6  | Acceptée | Interface commune pour développer des bibliothèques d'adatateurs d'outils de cache.      |
| PSR-7  | Acceptée | Interface commune pour modéliser des messages HTTP (requêtes et réponses).               |
| PSR-8  | En cours | (Easter Egg) Interface commune pour envoyer des câlins (hugs) à la communauté PHP.       |
| PSR-9  | En cours | Format standard pour reporter des failles de sécurité dans un logiciel.                  |
| PSR-10 | En cours | Processus de communication standard des failles de sécurité d'un logiciel.               |
| PSR-11 | En cours | Interface commune pour définir des conteneurs d'injection de dépendances interopérables. |
| PSR-12 | En cours | Évolution de PSR-2 qui prend en compte les nouvelles règles syntaxique de PHP 7.         |

Cette liste est disponible sur le site du [PHP-FIG](http://www.php-fig.org/).

Conception du Framework
-----------------------

Les sections qui suivent décrivent les différents composants bas niveau qui font
office de fondations du framework.

### Le Composant `Framework\Http`

Le coeur du framework repose sur un noyau léger (« *micro-kernel* ») en charge
de transformer un objet de requête (classe `Framework\Http\Request`) en objet de
réponse (classe `Framework\Http\Response`). Ces deux objets modélisent les
messages du protocole HTTP sous forme orientée objet pour rendre le code plus
naturel et intuitif à utiliser que les APIs natives du langage PHP (variables
superglobales, fonction `header()`, etc.).

Une requête HTTP prend la forme suivante :

```
POST /movie HTTPS/1.1
Host: www.allocinoche.com
User-Agent: Firefox/33.0
Content-Type: application/json
Content-Length: 61

{ 'movie': 'Intersté Lard', 'director': 'Christopher Mulan' }
```

Une réponse HTTP à la requête précédente aura par exemple la forme ci-dessous :

```
HTTPS/1.1 201 Created
Content-Type: application/json
Content-Length: 74
Host: www.allocinoche.com
Location: /movie/12345/interste-lard.html

{ 'id': 12345, 'movie': 'Intersté Lard', 'director': 'Christopher Mulan' }
```

Dans le prologue de la réponse, le code à trois chiffres s'appelle le
*code de statut* et détermine le statut de la réponse. Les codes de statut sont
classés en cinq catégories :

| Intervalle | Description        |
|------------|--------------------|
| 100 à 199  | Information        |
| 200 à 299  | Succès             |
| 300 à 399  | Redirections       |
| 400 à 499  | Erreurs du client  |
| 500 à 599  | Erreurs du serveur |

Les codes de statut les plus courants et qui sont à connaître en priorité sont
les suivants :

| Code   | Raison                          | Signification                                                               |
|--------|---------------------------------|-----------------------------------------------------------------------------|
| `200`  | `OK`                            | Tout s'est bien passé                                                       |
| `201`  | `Created`                       | La ressource vient d'être créée sur le seveur                               |
| `204`  | `No Content`                    | Aucun contenu, utilisé pour accuser une suppression réussie de la ressource |
| `301`  | `Moved Permanently`             | Redirection permanente vers la ressource                                    |
| `302`  | `Moved Temporarily`             | Redirection temporaire vers la ressource                                    |
| `304`  | `Not Modified`                  | La ressource est la même sur le client et le serveur                        |
| `400`  | `Bad Request`                   | Le client a formulé une mauvaise requête                                    |
| `401`  | `Unauthorized`                  | Accès refusé si l'utilisateur n'est pas authentifié                         |
| `402`  | `Payment Required`              | Un paiement est nécessaire pour continuer                                   |
| `403`  | `Forbidden`                     | Accès refusé faute de droits suffisants                                     |
| `404`  | `Not Found`                     | La ressource n'existe pas ou n'existe plus                                  |
| `405`  | `Method Not Allowed`            | La méthode HTTP utilisée n'est pas autorisée                                |
| `406`  | `Not Acceptable`                | La requête n'est pas acceptable par le serveur                              |
| `451`  | `Unavailable For Legal Reasons` | La ressource est indisponible pour des raisons de censure                   |
| `500`  | `Internal Server Error`         | Une erreur interne est survenue sur le serveur                              |
| `502`  | `Bad Gateway`                   | Échec de transmission de la requête à l'interface PHP / Python...           |
| `503`  | `Service Unavailable`           | Le service est indisponible, serveur surchargé                              |

La classe `Framework\Http\Request` modélise une requête HTTP standard composée
d'un prologue, d'une liste d'entêtes et d'un corps facultatif. L'objet de
requête peut être construit directement à partir de son constructeur ou bien à
partir des variables superglobales de PHP ou d'un message HTTP complet.

```php
use Framework\Http\Request

$request = new Request('POST', '/movie', 'HTTPS', '1.1');
$request = Request::createFromGlobals();
$request = Request::createFromMessage("POST /movie HTTPS/1.1\n...");
```

L'objet de requête possède une série de méthodes publiques pour récupérer
atomiquement des informations telles que l'URL, les données passées en POST, les
entêtes ou bien encore le corps. Le listing de code ci-dessous décrit une partie
de l'API implémentée par la classe `Framework\Http\Request`.

```php
# Static methods
Request::registerSupportedVerbs($verbs);
Request::createFromGlobals();
Request::createFromMessage($httpMessage);

# Instance Getter methods
$request->getScheme();
$request->getVersion();
$request->getHeaders();
$request->getHeader($name);
$request->getPath();
$request->getMethod();
$request->isMethod($method);
$request->getBody();
$request->getMessage();
$request->getAttribute($name, $default = null);
$request->getPostParameter();

# Instance Setter methods
$request->setAttributes($attributes);
```

La classe `Framework\Http\Response` modélise une réponse HTTP standard composée
d'un prologue, d'une liste d'entêtes et d'un corps facultatif. L'objet de
réponse peut être construit directement à partir de son constructeur ou bien à
partir d'un objet de requête et d'un message HTTP complet.

```php
use Framework\Http\Response

$response = new Response(200, 'HTTP', '1.0', [ 'Content-Type' => 'text/html' ], '<html>...');
$response = Response::createFromRequest($request);
$response = Response::createFromMessage('HTTP/1.1 304 Not Modified');
```

L'objet de réponse possède une série de méthodes publiques pour récupérer
atomiquement des informations telles que le code de statut, les entêtes ou bien
le corps. Le snippet ci-dessous présente l'API implémentée par la classe
`Framework\Http\Response`.

```php
# Static methods
Response::createFromRequest($request);
Response::createFromMessage($httpMessage);

# Instance Getter methods
$response->getScheme();
$response->getVersion();
$response->getHeaders();
$response->getHeader($name);
$response->getBody();
$response->getMessage();
$response->getStatusCode();
$response->getReasonPhrase();

# Instance utility methods
$response->prepare($request);
$response->send();
```

La classe `RedirectResponse` modélise quant à elle une réponse de redirection.
Son constructeur reçoit l'URL vers laquelle le client doit être redirigé. Le
code de statut est fixé à `302` par défaut mais peut être n'importe lequel dans
l'intervalle `300` à `308`. L'objet de redirection force l'url dans une entête
HTTP de type `Location`.

Enfin, toutes les classes de requêtes et de réponses dérivent du même super-type
abstrait `Framework\Http\AbstractMessage`. Cette classe *abstraite* n'est pas
instanciable et sert à deux choses : 

  1. Définir un super-type commun,
  2. Factoriser des attributs et méthodes communs aux classes dérivées.

La classe `AbstractMessage` définit aussi la méthode `getMessage()` comme finale
à l'aide du mot-clé `final`. Ce mot-clé empêche les classes dérivées de redéfinir
cette méthode et d'en changer son comportement. Ainsi, le framework est garanti
de toujours obtenir un message HTTP composé d'entêtes et d'un corps.

De plus, la méthode `getMessage()` est une implémentation du
*patron de conception* (aka *Design Pattern*) « **Patron de Méthode** »
(aka *Template Method*) qui permet d'empêcher la redéfinition complète d'un
algorithme mais autorise néanmoins la redéfinition de certaines parties de
celui-ci au travers de méthodes protégées judicieusement choisies. Le mot-clé
`final` sur la signature de la méthode interdit la redéfinition de la méthode
dans les classes dérivées. Cependant, la méthode `getMessage()` autorise la
redéfinition d'une étape de l'algorithme complet grâce à la méthode protégée
`createPrologue()`. Cette dernière est d'ailleurs déclarée abstraite
(mot-clé `abstract`) pour obliger les classes dérivées à l'implémenter.

**Important** : il suffit qu'il y ait seulement une seule méthode déclarée
abstraite dans une classe pour que celle-ci doive aussi être déclarée abstraite.

Pour plus d'informations sur l'API HTTP, consultez les tests unitaires.

### Le Composant `Framework\Routing`

Le namespace `Framework\Routing` offre des classes pour router les URLs HTTP sur
des contrôleurs de l'application. La configuration des routes est définie à
l'aide de tableaux associatifs. Ces tableaux stockent pour chaque route la
classe de contrôleur à instancier à la clé `_controller`. Chaque classe de
contrôleur doit implémenter la méthode magique `__invoke()`.

Dans la nouvelle version du code, les routes sont modélisées sous forme d'objets
PHP de type `Framework\Routing\Route`. Chaque route encapsule son chemin, des
paramètres et leurs contraintes associées, ainsi que les méthodes HTTP
autorisées.

```php
use Framework\Routing\Route;

$route = new Route(
    '/hello/{name}',
    'Application\\Controller\\HelloWorldAction',
    [ 'GET', 'POST'],
    [ 'name' => '[a-z]+' ]
);
```

La liste des routes est définie par un objet de type
`Framework\Routing\RouteCollection`. Chaque objet de route peut être ajouté à la
collection grâce à sa méthode `add()`. L'objet de collection de routes est aussi
une implémentation du patron de conception *Itérateur* (*Iterator*). Grâce à
l'implémentation des méthodes de l'interface native `Iterator` de PHP, il est
possible d'utiliser la collection comme un argument de la structure `foreach`.

```php
use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('home', new Route('/', 'Application\\Controller\\HomepageAction', ['GET']));
$routes->add('hello', new Route('/', 'Application\\Controller\\HelloAction', ['GET']));
$routes->add('contact', new Route('/', 'Application\\Controller\\ContactAction', ['GET', 'POST']));

foreach ($routes as $name => $route) {
    echo $route->getPath();
}
```

La classe `RouteCollection` vient aussi avec des méthodes `match()`, `merge()`
et `getName()`. La première permet de retourner l'objet de route qui correspond
aux paramètres de requête reçus en argument. La méthode `merge()` prend une
instance de `RouteCollection` à fusionner dans l'instance courante. La méthode
récupère les routes de la collection passée en argument et les ajoute à l'objet
courant. Enfin, la méthode `getName()` retourne le nom de la route pour l'objet
de route passé en argument.

La méthode `getRoutes()` de la collection de routes retourne un tableau
associatif d'objets de type `Framework\Routing\Route`. La clé associative dans
le tableau retourné correspond au nom donné à la route tandis que la valeur
correspond à l'objet `Route`. Cette méthode est appelée par le routeur qui
conserve une référence à la collection de routes.

Le routeur de type `Framework\Routing\Router` connaît la liste de toutes les
routes grâce à la collection de routes, et se charge de faire correspondre l'url
de la requête à une route. La méthode `match()` du routeur reçoit un objet
`RequestContext` duquel il extrait l'url demandée par le client et la méthode
HTTP utilisée. Si l'url et la méthode HTTP correspondent à une route, alors le
routeur retourne simplement un tableau associatif des attributs de la route
(son nom, la classe de contrôleur associée et les attributs de la route). À
l'inverse, si l'url correspond à aucune des routes enregistrées, le routeur lève
une exception de type `Framework\Routing\RouteNotFoundException`.

Le composant `Router` fournit des stratégies d'import de fichiers de
configuration des routes. Les routes peuvent être aussi bien définies
directement dans un fichier PHP pur comme dans un format statique comme le XML.
La classe `XmlFileLoader` est responsable de lire un fichier XML de définition
des routes et d'importer celles-ci dans le routeur. La classe
`CompositeFileLoader` est une stratégie composite qui permet de charger un
fichier de définition des routes quelle que soit son extension (php ou xml).

Pour plus d'informations sur l'API de routage, consultez les tests unitaires.

### Le Composant `Kernel`

La classe `Kernel` constitue le coeur du « micro-framework » développé en TP.
C'est lui qui est responsable de traduire un objet de requête en un objet de
réponse. Pour ce faire, il utilise la méthode `handle()` de l'interface
`Framework\KernelInterface` et s'appuie sur son registre de services
(*Service Locator*) passé à son constructeur.

La méthode `doHandle()` de la classe `Framework\Kernel` tente de convertir la
requête en réponse en invoquant un contrôleur. Avant de parvenir au contrôleur,
le noyau doit d'abord demander au service routeur si l'url de la requête
correspond à une route enregistrée. Si c'est le cas, les paramètres de la route
sont stockés en tant qu'attributs de la requête avec la méthode `setAttributes()`
de la requête. Puis, le noyau récupère le contrôleur à invoquer depuis la
fabrique de contrôleurs. C'est cette dernière qui fabrique le contrôleur à partir
des attributs de la requête. Il ne reste au noyau plus qu'à invoquer
dynamiquement le contrôleur à l'aide de la fonction native PHP
`call_user_func_array()`. Le contrôleur reçoit de la part du noyau la référence
à la requête et retourne à ce dernier un objet de réponse.

Le noyau de l'application est utilisé comme une implémentation du patron
d'architecture *Contrôleur Frontal* (aka *Front Controller*). Il s'agit du point
d'entrée unique sur l'application. C'est le fichier `web/index.php` qui démarre
et utilise le noyau.

```php
use Framework\Kernel;
use Framework\Http\Request;
use Framework\ServiceLocator\ServiceLocator;

$request = Request::createFromGlobals();

$kernel = new Kernel(new ServiceLocator());
$response = $kernel->handle($request);
$response->send();
```

Pour plus d'informations, consultez les tests unitaires.

### Le Composant `Templating`

Le composant `Templating` fournit deux interfaces
(`Framework\Templating\RendererInterface` et `Framework\Templating\ResponseRendererInterface`)
pour développer des moteurs de rendu. Par défaut, le composant vient avec une
implémentation basique de moteur de rendu. Il s'agit de la classe
`Framework\Templating\PhpRenderer`. Cet objet implémente l'interface
`Framework\Templating\ResponseRendererInterface` et offre un
mécanisme simple pour évaluer des templates PHP purs. La seconde implémentation,
`Framework\Templating\BracketRenderer` remplace des variables du template
exprimées avec des doubles crochets : `[[name]]`.

```php
use Framework\Template\BracketRenderer

$engine = new BracketRenderer(__DIR__.'/app/views');

$output = $engine->render('movie/show.tpl', [
    'title' => 'Jurassic Pork',
    'director' => 'Steven Spielbergue',
]);
```

Le composant `Templating` est aussi livré avec une classe
`Framework\Templating\TwigRendererAdapter` qui permet d'adapter le moteur de
rendu **Twig** dans le framework. En effet, l'API attendue par le framework est
différente de celle qu'expose Twig. L'interface
`Framework\Templating\ResponseRendererInterface` et la classe concrète
`Twig_Environment` sont incompatibles par nature, ce qui empêche d'utiliser Twig
comme moteur de rendu dans le framework. Pour répondre à cette problématique
sans trop d'efforts, le framework embarque un *adaptateur* qui adapte l'objet
`Twig_Environment` à l'interface `Framework\Templating\ResponseRendererInterface`
attendue par le framework. Le patron de conception *Adaptateur* (aka *Adapter*)
implémenté par la classe `Framework\Templating\TwigRendererAdapter` favorise la
*composition d'objet* plutôt que *l'héritage* pour résoudre ce problème.

```php
use Framework\Templating\TwigRendererAdapter;

$loader = new \Twig_Loader_Filesystem(__DIR__.'/app/views');

$twig = new \Twig_Environment($loader, [
    'debug'            => true,
    'auto_reload'      => true,
    'strict_variables' => true,
    'cache'            => __DIR__.'/cache/twig',
]);

$engine = new TwigRendererAdapter($twig);

$output = $engine->renderResponse('movie/show.tpl', [
    'title' => 'Jurassic Pork',
    'director' => 'Steven Spielbergue',
]);
```

Pour plus d'informations concernant le composant `Templating`, consultez les
fichiers de tests unitaires.

### Le Composant `ServiceLocator`

Le composant `ServiceLocator` offre une implémentation pragmatique de
*registre de services* pour fabriquer des services à la demande, et ainsi
optimiser les performances de l'application. Seuls les classes des objets
réellement nécessaires pour l'action demandée seront instanciées. Les services
sont ensuite partagés par le registre de services pour ne pas les récrer s'ils
sont demandés à nouveau.

La classe `Framework\ServiceLocator\ServiceLocator` est une implémentation
basique d'un registre de services. L'objet `ServiceLocator` permet non seulement
d'enregistrer des définitions de services à l'aide de fonctions anonymes
(lambdas et closures) mais aussi de stocker des paramètres de configuration. Ces
derniers servent généralement à paramétrer les services enregistrés.

```php
use Application\Repository\BlogPostRepository;
use Framework\ServiceLocator\ServiceLocator;

$locator->setParameter('database.dsn', 'mysql:host=localhost;dbname=lp_dim_2016');
$locator->setParameter('database.user', 'root');
$locator->setParameter('database.password', '');
$locator->setParameter('database.options', [
    \PDO::ATTR_AUTOCOMMIT => false,
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
);

$locator->register('repository.blog_post', function (ServiceLocator $locator) {
    return new BlogPostRepository($locator->getService('database'));
});

$locator->registerService('database', function (ServiceLocator $locator) {
    return new \PDO(
        $locator->getParameter('database.dsn'),
        $locator->getParameter('database.user'),
        $locator->getParameter('database.password'),
        $locator->getParameter('database.config')
    );
});

$post = $locator->getService('repository.blog_post')->find(42);
```

L'objet `ServiceLocator` est instancié et configuré depuis le contrôleur frontal
`web/index.php`. Les registres de services sont aussi connus sous les
appellations de conteneurs de services, conteneurs d'injection de dépendance ou
bien encore conteneurs d'inversion de contrôle.

Pour en savoir plus sur le *Registre de Services*, consultez les tests unitaires
et le *Contrôleur Frontal*, le fichier `web/index.php`.

Application de Blogging suivant le patron MVC
---------------------------------------------

Les sections qui suivent décrivent les choix techniques implémentés pour
réaliser une application de blogging construite autour du patron MVC. Les trois
couches du modèle MVC sont étudiées.

### La Couche Contrôleur

La couche *Contrôleur* encapsule la logique applicative. Elle est responsable
d'analyser la requête de l'utilisateur afin d'en déduire le *Modèle* à
interroger et la *Vue* à rendre. Il s'agit donc d'une simple glue entre les
couches de *Modèle* et de *Vue*. C'est aussi la glue entre le code de
l'application et le code du framework (infrastructure). Par conséquent, un
*Contrôleur* ne contient que très peu de lignes de code (pas plus de 20 lignes
de code en général).

Dans l'application de blogging, les *Contrôleurs* sont des objets dont les
classes se trouvent dans l'espace de nommage `Application\Controller`. Chaque
classe de contrôleur représente une seule et unique fonctionnalité de
l'application (afficher tous les posts, créer un post, consulter un post etc.).
La particularité de ces objets c'est qu'ils implémentent tous une méthode
magique `__invoke()` qui permet de les utiliser comme s'ils étaient des
fonctions classiques.

```php
namespace Application\Controller\Blog;

use Framework\AbstractAction;
use Framework\Http\HttpNotFoundException;
use Framework\Http\Request;

class GetPostAction extends AbstractAction
{
    public function __invoke(Request $request)
    {
        $repository = $this->getService('repository.blog_post');
  
        $id = $request->getAttribute('id');
        if (!$post = $repository->find($id)) {
            throw new HttpNotFoundException(sprintf('No blog post found for id #%u.', $id));
        }
  
        return $this->render('blog/show.twig', [ 'post' => $post ]);
    }
}
```

Dans l'exemple ci-dessus, la méthode `__invoke()` de la classe
`Application\Controller\Blog\GetPostAction` a pour tâche de retrouver un blog
post par son identifiant unique. Pour ce faire, l'action analyse d'abord la
requête afin de récupérer l'identifiant unique du billet dans ses attributs.
L'attribut `id` est en fait un paramètre dynamique de la route `blog_post`.`Ce
paramètre doit forcément être un ou plusieurs chiffres qui se suivent comme le
décrit le marqueur `<requirement/>`.

```xml
<?xml version="1.0" encoding="UTF-8" ?>
<routes>
    <route name="blog_post" path="/blog/article-{id}.html" methods="GET">
        <param key="_controller">Application\Controller\Blog\GetPostAction</param>
        <requirement key="id">\d+</requirement>
    </route>
</routes>
```

Le *Contrôleur* choisit ensuite le *Modèle* à interroger. Il s'agit ici du
service *repository.blog_post* qui encapsule les appels à la base de données. Ce
dernier est capable de récupérer un billet par son identifiant unique grâce à sa
méthode `find()`. Si aucun billet publié correspondant à l'identifiant unique
n'a été trouvé en base de données, l'action lève alors une exception de type
`Framework\Http\HttpNotFoundException`. En remontant jusqu'au noyau, cette
exception sera ensuite convertie en réponse de type `404`. Enfin, si le billet a
bien été récupéré, le *Contrôleur* choisit alors de rendre la *Vue* adaptée.
Dans cet exemple, la *Vue* est modélisée par le gabarit Twig `blog/show.twig`
qui reçoit le billet dans une variable `post`. La méthode `render()` de la
superclasse abstraite `Framework\Controller\AbstractController` se charge
d'évaluer le gabarit avec ses variables, puis de retourner un objet
`Framework\Http\Response` encapsulant le résultat final.

### La Couche Modèle

La couche *Modèle* est la plus importante des trois dans l'architecture MVC
puisqu'elle encapsule toute la *logique métier* de l'application. La logique
métier comprend aussi bien l'interfaçage avec un système de données (une base de
données MySQL par exemple) que les objets en charge de manipuler les données du
système. Ainsi tout objet qui encapsule une logique d'accès, de manipulation ou
de validation des données fait partie du *Modèle*.

Dans l'application de blogging, la couche de *Modèle* est représentée par un
objet `Application\Repository\BlogPostRepository`. Un *entrepôt* (aka
*repository*) est un objet qui représente toute une collection de données et qui
expose des fonctions pour accéder à ces dernières. L'objet `BlogPostRepository`
fait ainsi la passerelle avec la table `blog_post` dans la base de données
relationnelle MySQL.

```php
namespace Application\Repository;

class BlogPostRepository
{
    /**
     * The database handler.
     *
     * @var \PDO
     */
    private $dbh;

    /**
     * Constructor.
     *
     * @param \PDO $database
     */
    public function __construct(\PDO $database)
    {
        $this->dbh = $dbh;
    }
    
    /**
     * Returns one blog post by its unique identifier.
     *
     * @param int|string $id The unique identifier
     *
     * @return array
     */
    public function find($id)
    {
        if (!ctype_digit($id)) {
            throw new \InvalidArgumentException('$id must be an integer.');
        }

        $query = <<<SQL
SELECT * FROM blog_post
WHERE id = :id AND (published_at IS NULL OR published_at < NOW())
SQL;

        $stmt = $this->database->prepare($query);
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // ...
}
```

Cet objet `Application\Repository\BlogPostRepository` est défini comme un
service dans le registre des services. Il reçoit dans son constructeur une
dépendance de type `\PDO` (aka *PHP Data Object*). La classe `PDO` est le
connecteur unifié aux bases de données de PHP comme l'est JDBC en Java. PDO
s'interface sans aucun problème avec des moteurs de bases de données standards
tels que `SQLite`, `MySQL`, `Mariadb`, `PostGreSQL`, `Oracle`, `Sybase` etc. Cet
objet est aussi défini comme un service dans le registre des services.

La responsabilité de l'entreprôt `BlogPostRepository` consiste à faire une
interface avec la table `blog_post` en encapsulant toutes les requêtes SQL sur
cette dernière. L'exécution des requêtes SQL est déléguée à l'objet `PDO` qui se
charge de construire des *requêtes préparées* ainsi qu'une *transaction*
supplémentaire dans le cas d'opérations d'écriture sur la base.

En centralisant les requêtes SQL dans l'entrepôt, cela offre aussi au
développeur la possibilité de les adapter plus tard s'il décide de changer de
système de stockage des données. Dans ce cas, il suffira juste de réécrire les
entrepôts tandis que le reste du code de l'application restera en l'état.

### La Couche Vue

La couche `Vue` (ou `Présentation`) représente les données à présenter au
client. Il s'agit par exemple d'une réponse dans un format textuel tel que HTML,
XML, JSON etc. ou bien dans un format binaire comme une image ou un fichier PDF.
Il peut aussi s'agir tout simplement d'une réponse de redirection vers une autre
page.

Dans l'application de blogging développée en TP, la couche `Vue` choisie repose
sur le moteur de rendu [Twig](http://twig.sensiolabs.org). Twig est aujourd'hui
une bibliothèque Open-Source développée et maintenue par SensioLabs. Ce moteur
de rendu offre une palette de fonctionnalités modernes telles que l'héritage de
gabarits, l'échappement automatique des variables, les filtres de formattage ou
bien encore les inclusions de vues.

    {% extends "layout.twig" %}
    
    {% block title 'Mon Blog' %}
    
    {% block body %}
        <h1>Bienvenue sur mon blog</h1>
    
        {% for post in posts %}
            <h2>{{ post.title }}</h2>
            {{ include('blog/details.twig') }}
        {% endfor %}
    
    {% endblock %}

Le snippet de code Twig ci-dessus utilise l'héritage de gabarits (mot-clé
`extends`), la boucle `for` et l'inclusion de gabarit (fonction `include()`). Il
utilise aussi l'abstraction des types de variables grâce à la notation `.` pour
accéder aux attributs d'un tableau ou d'un objet (ex: `post.title`).

Gymnastique des Objets
----------------------

La gymnastique des objets ou « *Objects Calisthenics* » en anglais est une série
d'exercices pour s'entraîner à améliorer la qualité générale du code que l'on
écrit. Il s'agit de neuf règles de codage à suivre pour rendre le code meilleur.
Cette série d'exercices de gymnastique intellectuelle a été inventée par Jeff
Bay dans son ouvrage [ThoughtWorks Anthology](https://pragprog.com/book/twa/thoughtworks-anthology),
publié par la maison d'édition *The Pragmatic Programmers*. Ces règles sont
universelles et agnostique du langage de programmation employé. Il s'agit de les
mettre en pratique quel que soit le langage de programmation orientée objet
utilisé. Les neuf règles de la gymnastique des objets sont les suivantes :

### 1. Seulement un niveau d'indentation

Pour améliorer la lisibilité du code, sa testabilité, sa maintenabilité et sa
réutilisabilité, il est recommandé de ne pas dépasser plus d'un niveau
d'indentation par fonction. Le code qui se trouve dans chaque niveau
supplémentaire peut être déporté dans une nouvelle fonction.

```php
class RouteCollection
{
    public function merge(RouteCollection $routes, $override = false)
    {
        foreach ($routes as $name => $route) {
            if (isset($this->routes[$name]) && !$override) {
                throw new \InvalidArgumentException(sprintf(
                    'A route already exists for the name "%s".',
                    $name
                ));
            }

            $this->routes[$name] = $route;
        }
    }
}
```

Dans le code ci-dessus, on constate que la méthode `merge()` de la classe
`RouteCollection` dispose de deux niveaux d'indentation. La première indentation
devant le mot-clé `foreach` compte pour un niveau 0. Par conséquent, le code à
l'intérieur de la boucle doit être extrait et encapsulé dans une méthode dédiée
comme le montre le snippet ci-dessous :

```php
class RouteCollection
{
    public function merge(RouteCollection $routes, $override = false)
    {
        foreach ($routes as $name => $route) {
            $this->add($name, $route, $override);
        }
    }

    public function add($name, Route $route, $override = false)
    {
        if (isset($this->routes[$name]) && !$override) {
            throw new \InvalidArgumentException(sprintf(
                'A route already exists for the name "%s".',
                $name
            ));
        }

        $this->routes[$name] = $route;
    }
}
```

Avec ce changement simple, la méthode `merge()` ne révèle plus qu'un seul niveau
d'indentation et il en va de même pour la méthode `add()`. Si cette dernière
avait eu plus d'un niveau d'indentation, alors il aurait fallu aussi la remanier
pour la simplifier et ainsi de suite. Cette règle de gymnastique encourage non
seulement à produire du code plus lisible mais c'est aussi un moyen de favoriser
l'écriture de toutes petites fonctions ayant qu'une seule responsabilité.

### 2. Éviter l'usage du mot-clé `else`

Cette règle est simple ! Il s'agit de bannir autant que faire se peut l'usage du
mot-clé `else` en cherchant à interrompre l'exécution du programme le plus tôt
possible. Il existe trois manières de s'éviter l'usage du mot-clé `else` dans
une fonction.

#### Utiliser une instruction `return`

```php
class ServiceLocator implements ServiceLocatorInterface
{
    public function getService($name)
    {
        // Try to leave the function as early as possible
        // The service is already created, so return it
        // immediately and skip the rest of the method.
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        if (!isset($this->definitions[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'No registered service definition called "%s" found.',
                $name
            ));
        }

        $this->services[$name] = $service = call_user_func_array($this->definitions[$name], [$this]);

        return $service;
    }
}
```

#### Lever une exception.

```php
class ServiceLocator implements ServiceLocatorInterface
{
    public function register($name, \Closure $definition)
    {
        // Try to leave the function as early as possible
        // Throw an exception whenever a method requirement
        // is not met.
        if (!is_string($name)) {
            throw new \InvalidArgumentException('Service name must be a valid string.');
        }

        $this->definitions[$name] = $definition;

        return $this;
    }
}
```

#### Injecter une stratégie dite « Null Object ».

Lorsqu'un objet dépend d'un collaborateur facultatif, il est fréquent de tester
à l'exécution du programme si ce collaborateur n'est pas nul et donc appeler une
méthode sur celui-ci. Le snippet ci-après dévoile un exemple de collaborateur
facultatif.

```php
namespace Application;

use Framework\Templating\ResponseRendererInterface;
use Psr\Log\LoggerInterface;

class ErrorHandler
{
    private $logger;
    private $renderer;

    public function __construct(ResponseRendererInterface $renderer, LoggerInterface $logger = null)
    {
        $this->renderer = $renderer;
        $this->logger = $logger;
    }

    public function handleException(\Exception $exception)
    {
        // Check if logger is injected before logging anything.
        if (null !== $this->logger) {
            $this->logger->critical($exception->getMessage());
        }

        $vars = [
            'exception' => $exception,
        ];

        if ($exception instanceof RouteNotFoundException) {
            return $this->render('errors/404.twig', $vars, Response::HTTP_NOT_FOUND));
        }

        // ...
    }
}
```

Dans cet exemple, la fonction `handleException()` teste l'existence de l'objet
`logger`. S'il existe, alors sa méthode `critical()` est appelée pour
enregistrer une erreur critique dans les journaux de logs. Dans cet exemple,
c'est encore acceptable car la quantité de ce type de condition est limité.
L'idéal consiste à rendre la dépendance obligatoire tel que le montre le code
ci-dessous :

```php
class ErrorHandler
{
    // ...

    public function __construct(ResponseRendererInterface $renderer, LoggerInterface $logger)
    {
        $this->renderer = $renderer;
        $this->logger = $logger;
    }

    public function handleException(\Exception $exception)
    {
        $this->logger->critical($exception->getMessage());

        // ...
    }
}
```

L'interface `LoggerInteface` est ici une implémentation du patron « Stratégie ».
Il est donc maintenant facile de proposer différentes implémentations de
celle-ci selon l'environnement d'exécution de l'application. En environnement de
développement, une véritable implémentation de journal de log sera injectée
tandis qu'en production, il s'agira d'un « logger » null tel que celui présenté
ci-dessous.

```php
namepace Framework\Logger;

use Psr\Log\LoggerInterface;

class NullLogger implements LoggerInterface
{
    public function critical($message, array $context = [])
    {
        // ... do nothing!
    }
}
```

Au final, la classe `ErrorHandler` n'a plus besoin d'être polluée par de
multiples conditions inutiles. L'implémentation du patron d'architecture
« *Null Object* » permet d'éliminer les conditions et rendre ainsi le code plus
lisible et plus facilement testable. En effet, en élimimant la condition, il ne
reste plus qu'un seul et unique chemin d'exécution pour le code contre deux
avant. Il faut donc écrire un seul test unitaire pour couvrir le code au lieu de
deux auparavant. Grâce au patron « *Null Object* », le code s'utilise de la
manière suivante :

```php

$twig = new \Twig_Environment(\new \Twig_Loader_Filesystem(__DIR__.'/views'));
$renderer = new TwigRendererAdapter($twig);

// Development mode with a real logger
$errorHandler = new ErrorHandler($renderer, new RealLogger(__DIR__.'/logs/dev.log'));
$errorHandler->handleException($exception);

// Production mode with a null logger
$errorHandler = new ErrorHandler($renderer, new NullLogger());
$errorHandler->handleException($exception);
```

Les bénéfices directs de cette règle d'évitement du mot-clé `else` pour la
qualité du code sont une meilleure lisibilité, une diminution du code dupliqué
et des complexités cyclomatiques du code plus faibles. En effet, moins il y a de
chemins d'exécution que peut prendre le code et plus faibles sont les
complexités cyclomatiques des fonctions.

### 3. Encapsuler les types primitifs

L'idée de cette règle consiste à encapsuler les types primitifs scalaires
(`int` `bool`, `float`, `string`, etc.) dans des classes afin de forcer des
types orientés objets. Cette règle est discutable pour un langage tel que PHP
dont la philosophie consiste à offrir un typage dynamique.

Néanmoins, elle trouve du sens à partir du moment où l'on cherche à garantir une
cohérence et une forte cohésion des types du programme. Une classe `String` qui
encapsule une valeur scalaire de chaîne de caractères peut aussi offrir des
méthodes de manipulation de celle-ci (`toLower()`, `toUpper()`, `explode()`,
etc.) plutôt que des fonctions natives PHP.

Encapsuler des types primitifs dans des objets a un véritable intérêt quand il
s'agit de regrouper des données communes à un même concept et d'offrir des
méthodes de manipulation de ces données. Un excellent exemple est celui d'une
classe `Money` qui encapsule les attributs d'une valeur fiduciaire (la somme
d'argent et le code ISO de la devise) ainsi que les opérations possibles sur
cette monnaie.

```php
$money1 = Money::createFromString('1 725.78 EUR');
$money2 = Money::createFromString('12.22 EUR');

$money3 = $money1->add($money2);
$money4 = $money3->multiply(10);

// Split the amount of money in 5 equal shares.
$shares = $money4->split(5);
```

Encapsuler les données et opérations de manipulation d'une devise monétaire dans
une classe permet de simplifier leur usage par rapport à de simples variables
scalaires et fonctions PHP.

### 4. Maximum un opérateur d'objet par ligne

Cette règle stipule qu'il ne doit pas y avoir plus d'un opérateur d'objet par
ligne. En d'autres termes, le symbole `->` ne doit pas être utilisé plus d'une
fois sur une ligne.

Dit autrement, un objet doit communiquer avec son collaborateur le plus proche
et non les collaborateurs de ses collaborateurs. C'est ce que l'on appelle plus
communément la « Loi de Demeter » ou principe de connaissance minimale.

Dans la méthode `addPiece()` de la classe `ChessBoard` ci-dessous, l'échiquier
a bien trop de connaissances d'implémentation de la matrice. Ce code montre un
couplage fort entre l'échiquier et les détails d'implémentation comme les objets
de rangée (`Row`) et de colonne (`Column`).

```php
class ChessBoard
{
    private $matrix;

    public function addPiece($x, $y, Piece $piece)
    {
        $this->matrix->getRow($x)->getColumn($y)->add($piece);
    }
}
```

Idéalement, l'échiquier ne devrait parler qu'à son collaborateur le plus proche,
l'objet `Matrix` tel qu'illustré ci-dessous.

```php
class ChessBoard
{
    private $matrix;

    public function addPiece($x, $y, Piece $piece)
    {
        $this->matrix->add($x, $y, $piece);
    }
}
```

De même, l'objet `Matrix` ne doit communiquer qu'avec ses collaborateurs les
plus proches, les objets de rangées.

```php
class Matrix
{
    private $rows;

    public function add($x, $y, Piece $piece)
    {
        $this->getCell($x, $y)->add($y, $piece);
    }

    public function getCell($x, $y)
    {
        return $this->rows[$x]->getColumn($y);
    }
}
```

Enfin, l'objet `Row` ne doit communiquer qu'avec ses collaborateurs les
plus proches, les objets de colonnes.

```php
class Row
{
    private $columns;

    public function getColumn($y)
    {
        return $this->columns[$y];
    }

    public function addPieceOnCell($y, Piece $piece)
    {
        $this->getColumn($y)->add($piece);
    }
}
```

En validant cette règle, le code devient plus lisible mais surtout plus
facilement testable. En effet, dans un test unitaire, il suffit alors de créer
l'objet que l'on teste ainsi que ses collaborateurs les plus proches. Il n'ait
plus besoin de connaître les collaborateurs des collaborateurs.

> Contrairement à Java où le mot-clé `this` est facultatif, la variable `$this`
> est obligatoire pour référencer l'objet courant. Par conséquent, on accepte
> jusqu'à deux opérateurs d'instance par ligne au lieu d'un pour valider cette
> règle de gymnastique des objets.

### 5. Ne pas abréger

Il est loin le temps où les espaces de mémoire vive et de stockage étaient
limités et se comptaient en quelques octets, voire kilo-octets. Inutile donc
d'abréger les noms des variables, des méthodes et des classes. Des noms bien
choisis pour les structures de données favorisent la lisibilité et la
compréhension du code par ses pairs.

Il faut toujours garder à l'esprit que l'on écrit d'abord du code pour soi-même
et pour les autres, et non pour la machine. Une citation est d'ailleurs très
célèbre dans le monde d'informatique.

> There are only two hard things in Computer Science: cache invalidation and
> naming things.
>
> -- *Phil Karlton*

Traduite littéralement en français, cette citation signifie qu'*il existe
seulement deux choses difficiles à réaliser en ingénierie informatique :
invalider des caches et savoir nommer les choses correctement.*

### 6. Développer des petites classes

Ce principe est tout simplement une réciproque du principe de responsabilité
unique de SOLID. Il s'agit de développer de petites classes ne dépassant pas un
certain seuil de nombre de lignes de code. Idéalement, il convient de ne pas
dépasser entre 150 et 200 lignes de code par classe en fonction de la verbosité
du langage de programmation utilisé.

En suivant ce principe, cela force le développeur à remanier régulièrement son
code afin d'extraire dans de nouvelles classes plus petites des responsabilités
identifiées. Le nombre de lignes de code dans une classe ainsi que le nombre de
ses attributs et de ses collaborateurs donnent facilement des indices sur le
niveau de complexité de celle-ci. Plus il y a de méthodes, d'attributs et de
collaborateurs, et plus il y a de risques pour que la classe ait plus d'une
responsabilité.

### 7. Limiter le nombre de propriétés d'instance dans une classe

Dans son ouvrage, Jeff Bay recommande de ne pas définir plus de deux variables
d'instance par classe. Pourquoi deux et pas trois, cinq ou bien dix ? Moins il y
en a dans la classe et plus cela force le développeur à mieux encapsuler les
autres attributs dans d'autres classes. Ainsi chaque classe encapsule de manière
atomique un concept ou une responsabilité.

Il est bien sûr très difficile en PHP de respecter cette règle. Le plus
important c'est de définir un seuil raisonnable du nombre maximum d'attributs
d'instance par classe, et le respecter autant de fois que c'est possible. Au
final, il y aura toujours des classes qui valideront ce seuil et d'autres qui le
dépasseront. Un maximum de cinq à sept propriétés d'instance maximum par classe
est un seuil raisonnable dans la plupart des cas.

### 8. Éviter les classes de collection de premier ordre

Lorsqu'il s'agit de manipuler des listes d'objets, il est recommandé d'avoir
recours à des objets de collection plutôt que de simples tableaux. Les classes
de collections génériques, dites de premier ordre, ne sont pas conseillées. Par
exemple, une classe générique nommée `Collection` ou `List`.

Il est en fait plutôt recommandé de créer une classe de collection spécifique
pour chaque type d'objet qu'elle encapsule. Ainsi, la classe de collection
spécifique contrôlera qu'on lui ajoute bien des objets qu'elle attend. De plus,
la collection offrira des méthodes spécifiques pour filtrer les éléments, leur
appliquer des opérations en lot ou bien en extraire certains qui correspondent à
un critère spécifique.

```php
namespace Framework\Routing;

class RouteCollection
{
    /** @var Route[] */
    private $routes;

    public function __construct()
    {
        $this->routes = [];
    }

    public function getName(Route $route)
    {
        foreach ($this->routes as $name => $oneRoute) {
            if ($route === $oneRoute) {
                return $name;
            }
        }

        throw new \RuntimeException('Route is not registered in the collection.');
    }

    public function merge(RouteCollection $routes, $override = false)
    {
        if ($routes === $this) {
            throw new \LogicException('A routes collection cannot merge into itself.');
        }

        foreach ($routes as $name => $route) {
            $this->add($name, $route, $override);
        }
    }

    public function match($path)
    {
        foreach ($this->routes as $route) {
            if ($route->match($path)) {
                return $route;
            }
        }
    }

    public function add($name, Route $route, $override = false)
    {
        if (isset($this->routes[$name]) && !$override) {
            throw new \InvalidArgumentException(sprintf(
                'A route already exists for the name "%s".',
                $name
            ));
        }

        $this->routes[$name] = $route;
    }
}
```

La classe `RouteCollection` ci-dessus extraite du mini-framework développé en
cours est un exemple d'implémentation de cet exercice de gymnastique qui porte
sur les collection. Ici, la classe `RouteCollection` traite de manière uniforme
des objets de type `Route`. Elle offre des mécanismes pour ajouter de nouvelles
routes à la collection, fusionner deux collections entre elles ou bien
rechercher l'objet de route qui correspond au critère spécifié.

### 9. Limiter l'usage d'accesseurs et mutateurs publiques

Afin de garantir le principe d'encapsulation du paradigme orienté objet, les
bonnes pratiques recommandent généralement de définir les attributs d'un objet
avec une portée privée, voire protégée. Cela limite ainsi la portée de
l'attribut uniquement à la classe dont il est issu, voire aux classes dérivées
dans le cadre d'une portée protégée. En revanche, l'accès direct aux attributs
depuis l'extérieur de l'objet est ainsi strictement interdit.

Dans ces conditions, l'objet agit donc comme une boîte noire dont les composants
internes ne sont pas visibles de l'extérieur par les entités qui le manipulent.
Seules des méthodes publiques de l'objet permettent au code extérieur de lire
l'état de celui-ci ou bien de le modifier.

Bien qu'un attribut est défini avec une portée privée, l'erreur consiste à lui
associé un couple d'accesseur et mutateur publiques. Les accesseurs sont les
méthodes dites « *getter* » et les mutateurs sont les méthodes
traditionnellement dénommées « *setter* ». Le snippet ci-dessous montre un
exemple de classe qui expose à outrance ce type de méthodes.

```php
class BankAccount
{
    private $amount;
    private $currency;

    public function __construct($initialBalance, $currency)
    {
        $this->amount = $initialBalance;
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function setBalance($newAmount)
    {
        $this->amount = $newAmount;
    }

    public function getBalance()
    {
        return $this->amount;
    }
}
```

Exposer un couple de méthodes *getter* et *setter* pour chaque attribut privé
revient quelque part à le rendre publique et donc violer le principe
d'encapsulation. L'état de l'objet peut ainsi être dévoilé et modifié par le
code client sans réel contrôle comme le montre le listing ci-dessous.

```php
$account = new BankAccount(500, 'EUR');

// Owner has just deposited 300 on his\her bank account
$account->setBalance($account->getBalance() + 300);

// Owner has just withdrawed 150 from his\her bank account
$account->setBalance($account->getBalance() - 150);

// Owner has changed the currency of his\her bank account
$account->setCurrency('USD');
```

Comme le montre le code ci-dessus, le solde du compte bancaire est publiquement
dévoilé par le *getter* mais il est aussi sauvagement modifié par le *setter*
sans aucun contrôle. Par exemple, que se passerait-il si le possesseur du compte
bancaire souhaite retirer 150 EUR bien que son compte bancaire ne dispose que
d'un solde de 100 EUR ? Au niveau du programme, il est semblerait légitime de se
demander si cette opération est acceptable ou non en fonction de la politique de
la banque qui tient le compte. La banque autorise-t-elle une facilité de caisse
pendant une période de découvert ? Si oui, jusqu'à quel montant ? Le code actuel
ne permet pas de le prendre en compte. La première instruction quant à elle
semble montrer que le client du compte bancaire vient de déposer 300 sur son
compte. Hors, s'agit-il de 300 EUR, 300 USD ou bien 300 cacahuètes ? Le risque
ici c'est de se retrouver avec un nouveau solde de compte bancaire incohérent !
Enfin, la dernière instruction modifie la devise du compte bancaire. Par
conséquent, le solde du compte bancaire initialement en Euro passe subitement en
Dollars américains. Avec un taux de change défavorable, le client du compte
bancaire se retrouve lésé par sa banque ! Cette opération ne devrait d'ailleurs
pas être possible. Par conséquent, cette méthode ne devrait jamais être définie
dans la classe `BankAccount` avec une portée publique.

Cette neuvième et dernière règle de gymnastique des objets recommande donc
d'éviter d'exposer publiquement des méthodes accesseurs et mutateurs sur
l'objet. À la place, l'objet doit proposer uniquement des méthodes utiles pour
le manipuler et contrôler son nouvel état comme le montre la nouvelle classe
ci-dessous.

```php
class BankAccount
{
    private $balance;
    private $maxAllowedOverdraft;

    private function __construct(Money $initialBalance, Money $maxAllowedOverdraft)
    {
        static::compareMoney($initialBalance, $maxAllowedOverdraft);

        $this->balance = $initialBalance;
        $this->maxAllowedOverdraft = $maxAllowedOverdraft;
    }

    public static function open($initialBalance, $maxAllowedOverdraft)
    {
        return new self(
            Money::fromString($initialBalance),
            Money::fromString($maxAllowedOverdraft)
        );
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public static function compareMoney(Money $money1, Money $money2)
    {
        if (!$money1->isSameCurrency($money2)) {
            throw new CurrencyMismatchException($money1->getCurrency(), $money2->getCurrency());
        }
    }

    public function deposit($amount)
    {
        $withdrawal = Money::fromString($amount);

        // Check both current money and given money are compatible together
        static::compareMoney($this->balance, $deposit);

        // Update new balance
        $this->balance = $this->balance->add($deposit);

        // Return new actual balance after money deposit
        return $this->balance;
    }

    public function withdraw($amount)
    {
        $withdrawal = Money::fromString($amount);

        // Check both current money and given money are compatible together
        static::compareMoney($this->balance, $withdrawal);

        // Check the bank account overdraft allowance
        $newBalance = $this->balance->subtract($withdrawal);
        $minAllowedBalance = $this->maxAllowedOverdraft->negate();
        if ($newBalance->lowerThan($minAllowedBalance)) {
            throw new MaxAllowedOverdraftReachedException()
        }

        // Update new balance
        $this->balance = $newBalance;

        // Return new actual balance after money withdrawal
        return $this->balance;
    }
}
```

Dans cette nouvelle classe, les anciens attributs primitifs `$amount` et
`$money` ont été regroupés au sein d'une même classe `Money` afin de valider la
règle #3 (*Encapsuler les types primitifs*). Cette classe `Money` offre ainsi
toute une palette de nouvelle méthode pour comparer et modifier des valeurs
fiduciaires. De plus, la portée du constructeur de la classe `BankAccount` a été
transformée en privé afin de forcer l'usage du constructeur statique
`BankAccount::open()`. Ce dernier construit des objets et initialise des objets
`Money` à partir de chaînes de caractères. Le véritable constructeur contrôle
quant à lui que ces paramètres sont cohérents pour compléter la construction de
l'objet `BankAccount`. En d'autres termes, la devise du solde initial du compte
bancaire et la devise du montant autorisé du découvert doivent être identiques.

Le choix de la méthode statique intitulée `open()`, c'est pour mieux
correspondre au vocabulaire du monde bancaire. En effet, ce n'est pas le client
qui « crée » un compte bancaire chez sa banque mais celle-ci qui lui « ouvre »
un nouveau compte.

```php
$account = BankAccount::open('500.00 EUR', '1000.00 EUR');
```

Une fois le compte bancaire créé, son possesseur peut alors soit déposer de
l'argent dessus ou bien en retirer. L'accesseur `getBalance()` et son mutateur
`setBalance()` associé ont disparu au profit de deux nouvelles méthodes plus
expressives : `deposit()` et `withdraw()`.

```php
$account = BankAccount::open('500.00 EUR', '1000.00 EUR');
$account->deposit('250.50 EUR');
$account->withdraw('62.00 EUR');
$account->withdraw('1250.00 EUR');
```

Les deux méthodes `deposit()` et `withdraw()` créent des instances de `Money` à
partir des représentations en chaînes de caractères des montants à créditer ou
débiter du compte. Puis, elles comparent que ces montants à créditer ou débiter
du compte bancaire sont cohérents. Leur devise doit en effet correspondre à la
devise du solde actuel du compte bancaire.

Une fois ce contrôle effectué, le nouveau montant d'espèces déposé est ajouté au
solde actuel du compte bancaire. L'état du nouveau solde est mis à jour puis
retourné en sortie de la fonction `deposit()`.

Dans le cas du retrait d'argent, le montant à débiter est en plus validé à
condition que la somme débitée n'entraîne pas un dépassement du maximum autorisé
de découvert. Si tout est bon, alors le montant est débité du compte bancaire et
le nouveau solde est mis à jour avant d'être retourné en sortie de la fonction
`withdraw()`.

Le Principe SOLID
-----------------

En programmation informatique, SOLID est un acronyme représentant cinq principes
de base pour la programmation orientée objet. Ces cinq principes sont censés
apporter une ligne directrice permettant le développement de logiciels plus
fiables, plus robustes, plus maintenables, plus extensibles et plus testables.

* **S**ingle Responsability Principle ou « principe de responsabilité unique »
  qui stipule qu'une classe doit avoir une et une seule responsabilité. Si l'on
  parvient à identifier dans une classe au moins deux raisons valables de la
  modifier, c'est que cette classe dispose d'au moins deux responsabilités.

* **O**pen Close Principle ou « principe Ouvert / Fermé » qui stipule qu'une
  classe doit être ouverte à l'extension et fermée aux modifications. En
  d'autres termes, il doit être possible de facilement enrichir les capacités
  d'un objet sans que cela implique de modifier le code source de sa classe. Des
  patrons de conception comme la *Stratégie* ou le *Décorateur* répondent à ce
  principe en favorisant la composition d'objets plutôt que l'héritage.

* **L**iskov Substitution Principle ou « principe de substitution de Liskov »
  qui définit qu'une instance de type T doit pouvoir être remplacée par une
  instance de type G, tel que G sous-type de T, sans que cela modifie la
  cohérence du programme. En d'autres termes, il s'agit de conserver les mêmes
  prototypes ainsi que les mêmes conditions d'entrée / sortie lorsqu'une classe
  dérive une classe parente et redéfinit ses méthodes. Cela vaut aussi pour les
  types d'exceptions. Si une méthode de la classe parente lève une exception de
  type `E` alors cette même méthode rédéfinie dans une sous-classe `C'` qui
  hérite de `C` doit aussi lever une exception de type `E` dans les mêmes
  conditions.

* **I**nterface Segregation Principle ou « principe de ségrégation d'interface »
  qui recommande de découper de grosses interfaces en plus petites interfaces
  spécialisées. Ainsi les classes clientes peuvent implémenter une ou plusieurs
  petites interfaces spécialisées plutôt qu'une grosse interface afin d'obtenir
  seulement les méthodes dont elles ont besoin.

* **D**ependency Injection Principle ou « principe d'injection des dépendances »
  qui stipule que les objets doivent recevoir leurs dépendances (collaborateurs)
  en paramètres de leur constructeur ou de méthodes *setter*. Les collaborateurs
  sont donc créés en dehors de l'objet puis injectés dans ce dernier. La
  réciproque de ce principe dit aussi que les objets doivent dépendre
  d'abstractions plutôt que d'implémentations. Cela signifie qu'il est
  préférable de typer des arguments avec des types abstraits (classes concrètes
  ou interfaces) plutôt que des types concrets (classes concrètes).

Objets de Valeur
----------------

Un « Objet de Valeur » (ou *Value Object* en anglais) est une instance qui
encapsule un certain nombre d'attributs et dont l'état globale représente une
valeur. Les objets de valeur répondent aussi aux trois règles suivantes :

1. L'état interne d'un objet de valeur ne possède pas d'identité propre. En
   d'autres termes, l'objet de valeur ne contient pas d'identifiant unique qui
   le distingue d'un autre objet de valeur représentant la même valeur.
2. L'état interne d'un objet de valeur n'est plus modifiable une fois que
   l'objet a été construit. Cette propriété lui confère une capacité de cache
   pour une durée infinie. L'état de l'objet est garanti d'être toujours le
   même.
3. Deux objets de valeur contenant chacun la même valeur (donc le même état)
   sont permutables et utilisables indifféremment l'un de l'autre.

De nombreux concepts du quotidien sont propices à une modélisation sous la forme
d'objets de valeur :

* Une valeur monétaire,
* Une date dans le calendrier,
* Un poids,
* Une masse,
* Un élément chimique de la table périodique des éléments,
* Une molécule,
* Un point dans un plan à deux dimensions,
* Un point dans un plan à trois dimensions,
* Une adresse postale,
* Des coordonnées GPS,
* Une couleur RVB,
* Une couleur CMJN,
* etc.

La classe ci-dessous représente un point dans un plan à deux dimensions.

```php
class Point
{
    private $x;
    private $y;

    public function __construct($x, $y)
    {
        if (!is_int($x)) {
            throw new \InvalidArgumentException('$x must be a valid integer.');
        }

        if (!is_int($y)) {
            throw new \InvalidArgumentException('$y must be a valid integer.');
        }

        $this->x = $x;
        $this->y = $y;
    }

    public static function fromString($coordinates)
    {
        if (!is_string($coordinates)) {
            throw new \InvalidArgumentException('$coordinates must be a valid string that represents coordinates: (x,y)');
        }

        if (!preg_match('#^\((?P<x>-?\d+),(?P<y>-?\d+)\)$#', $coordinates, $point)) {
            throw new \InvalidArgumentException('Invalid 2D coordinates representation.');
        }

        return new self((int) $point['x'], (int) $point['y']);
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function translate($a, $b)
    {
        return new self($this->x + $a, $this->y + $b);
    }

    public function negate()
    {
        return new self(-$this->x, -$this->y);
    }
}
```

La classe `Point` ci-dessous encapsule les coordonnées cartésiennes d'un point
dans un plan à deux dimensions (abscisse et ordonnée). Le constructeur vérifie
que les coordonnées respectent certaines contraintes. Pour des raisons de
simplicité, les coordonnées doivent être exprimées uniquement en entiers
(positifs ou négatifs). Il existe aussi un constructeur statique qui offre un
moyen alternatif de construire l'objet à partir d'une représentation en chaîne
du duo de coordonnées.

```php
$a = Point::fromString('(0,0)');
$b = Point::fromString('(1,2)');
$c = Point::fromString('(-1,-6)');
$d = Point::fromString('(-4,12)');
```

Comme il s'agit d'objets de valeur, aucun d'entre eux n'a d'identité propre.
Deux objets renfermant le même état sont donc égaux et permutables.

```php
$a = Point::fromString('(0,0)');
$b = Point::fromString('(0,0)');

var_dump($a == $b); // true
```

De plus, il est impossible de changer les coordonnées d'un point une fois que
celui-ci a été construit par le constructeur. Par conséquent, les méthodes de
manipulation des points (négation, translation, rotation, etc.) retournent
toutes de nouvelles instances de points.

```php
$a = Point::fromString('(1,2)');
$b = $a->negate();
$c = $a->translate(2, 3);
```

Dans un système informatique, les objets de valeur offrent ainsi un moyen simple
et puissant de manipuler et tester unitairement des données atomiques.

Patrons de Conception
---------------------

Les patrons de conception (aka *design patterns*) sont des solutions
architecturales abstraites et conceptuelles pour répondre à des problématiques
récurrentes du génie logiciel. Les patrons de conception ont été inventés à
l'origine dans les années 70 par Christopher Alexander puis formalisés dans les
années 90 par le GoF (*Gang of Four*) dans leur ouvrage *Elements of Reusable
Object-Oriented Software* paru en 1995. Le Gang des Quatre était composé des
quatre ingénieurs informaticiens suivants :

* Erich Gamma
* Richard Helm
* Ralph Johnson
* John Vlissides

Ils ont conçu les 23 patrons de conception décrits dans leur ouvrage.  Les
patrons de conception sont des solutions abstraites. Elles sont donc agnostiques
des langages de programmation. Un patron de conception peut être implémenté dans
un langage de programmation particulier (PHP, Java, JavaScript, C#, etc.) à
condition que ce dernier supporte le paradigme de la programmation orientée
objet. Les patrons de conception ont aussi les autres avantages suivants :

* Ils apportent un vocabulaire commun pour les développeurs,
* Ils favorisent le découplage et l'extensibilité du code,
* Ils favorisent la testabilité unitaire du code,
* Ils encouragent des bonnes pratiques orientée objet (principe SOLID,
  composition d'objets au lieu de l'héritage, etc.),
* Ils permettent de capitaliser un savoir et un savoir-faire.

Christopher Alexander décrit les patrons de conception de la manière suivante :

> Chaque patron décrit un problème qui se manifeste constamment dans notre
> environnement, et donc décrit le cœur de la solution à ce problème, d’une
> façon telle que l’on puisse réutiliser cette solution des millions de fois,
> sans jamais le faire deux fois de la même manière.
>
> *Christopher Alexander, 1977*

### Familles de Patrons

Les 23 patrons de conception sont réparis en trois familles :

* Les patrons **Créationnaux** encapsulent les protocoles de création et
  d'initialisation de certains objets. Les patrons *Singleton* et
  *Méthode de Fabrique* sont des exemples de patrons créationnels.

* Les patrons **Structuraux** organisent les classes d'un programme les unes par
  rapport aux autres. Ces patrons sont aussi ceux qui favorisent le découplage
  et la réutilisabilité du code en encourageant notamment la composition
  d'objets plutôt que l'héritage. Les principaux patrons structuraux sont entre
  autres le *Composite*, le *Décorateur*, la *Façade* et l'*Adaptateur*.

* Les patrons **Comportementaux** organisent la manière dont les objets
  collaborent les uns avec les autres. Ils sont chargés de mieux distribuer les
  responsabilités des objets et expliquent le fonctionnement de certains
  algorithmes. Les patrons *Gabarit de Méthode*, *Itérateur* et *Stratégie* sont
  trois exemples de patrons comportementaux.

### Le Patron Singleton

> Ce patron vise à assurer qu'il n'y a toujours qu'une seule instance d'une
> classe en fournissant une interface pour la manipuler. C'est un des patrons
> les plus simples. L'objet qui ne doit exister qu'en une seule instance
> comporte une méthode pour obtenir cette unique instance et un mécanisme pour
> empêcher la création d'autres instances.
>
> *Wikipédia*.

### Le Patron Méthode de Fabrique

> La Fabrique (*Méthode de Fabrique*) est un patron de conception créationnel
> utilisé en programmation orientée objet. Elle permet d'instancier des objets
> dont le type est dérivé d'un type abstrait. La classe exacte de l'objet n'est
> donc pas connue par l'appelant.
>
> *Wikipédia*.

Il existe plusieurs types de fabriques :

* La *fabrique statique* qui utilise une fonction statique d'une classe pour
  créer un objet. Dans le projet du mini framework, un exemple de fabrique
  statique est la métode statique `createFromMessage()` des classes `Request` et
  `Response`.

* La *fabrique simple* est un idiome de programmation qui vise à encapsuler la
  création d'un objet à l'intérieur d'une méthode d'instance. Par exemple, la
  méthode `getService()` de la classe `ServiceLocator` est un exemple
  d'implémentation d'une *fabrique simple* qui centralise la création d'objets.

* La *méthode de fabrique* est la vraie définition du patron *Fabrique*. Il
  s'agit de définir dans un type abstrait (classe abstraite ou interface) une
  méthode publique dédiée à la création et à l'initialisation d'un objet. Dans
  le case d'une classe abstraite, celle-ci pré-implémente cette méthode publique
  de fabrique. Cette méthode publique délègue ensuite une partie du processus à
  une méthode protégée abstraite implémentée par chaque classe de fabrique
  concrète. Chaque fabrique concrète est responsable de produire un et un seul
  type d'objet. Retrouvez le patron Fabrique dans les slides de présentation de
  conférence [ici](https://speakerdeck.com/hhamon/design-patterns-the-practical-approach-in-php).

### Le Patron Composite

> Ce patron permet de composer une hiérarchie d'objets, et de manipuler de la
> même manière un élément unique, une branche, ou l'ensemble de l'arbre. Il
> permet en particulier de créer des objets complexes en reliant différents
> objets selon une structure en arbre. Ce patron impose que les différents
> objets aient une même interface, ce qui rend uniformes les manipulations de la
> structure. Par exemple dans un traitement de texte, les mots sont placés dans
> des paragraphes disposés dans des colonnes dans des pages; pour manipuler
> l'ensemble, une classe composite implémente une interface. Cette interface est
> héritée par les objets qui représentent les textes, les paragraphes, les
> colonnes et les pages.
>
> *Wikipédia*.

Dans le projet de mini-framework, la classe `CompositeFileLoader` du composant
`Routing` est une implémentation du patron `Composite`. Elle permet d'imbriquer
un ou plusieurs objets de même type et de les utiliser comme s'ils étaient qu'un
seul et unique objet.

### Le Patron Décorateur

> Ce patron permet d'attacher dynamiquement des responsabilités à un objet. Une
> alternative à l'héritage en favorisant la composition d'objets. Ce patron est
> inspiré des poupées russes. Un objet peut être caché à l'intérieur d'un autre
> objet décorateur qui lui rajoutera des fonctionnalités, l'ensemble peut être
> décoré avec un autre objet qui lui ajoute des fonctionnalités et ainsi de
> suite. Cette technique nécessite que l'objet décoré et ses décorateurs
> implémentent la même interface, qui est typiquement définie par une classe
> abstraite ou une interface.
>
> *Wikipédia*.

Retrouvez le patron Décorateur dans les slides de présentation de conférence
[ici](https://speakerdeck.com/hhamon/design-patterns-the-practical-approach-in-php).

### Le Patron Façade

> Ce patron fournit une interface unifiée sur un ensemble d'interfaces d'un
> système. Il est utilisé pour réaliser des interfaces de programmation. Si un
> sous-système comporte plusieurs composants qui doivent être utilisés dans un
> ordre précis, une classe façade sera mise à disposition, et permettra de
> contrôler l'ordre des opérations et de cacher les détails techniques des
> sous-systèmes.
>
> *Wikipédia*.

Retrouvez le patron Façade dans les slides de présentation de conférence
[ici](https://speakerdeck.com/hhamon/practical-design-phpatterns).

### Le Patron Adaptateur

> Ce patron convertit l'interface d'une classe en une autre interface exploitée
> par une application. Permet d'interconnecter des classes qui sans cela
> seraient incompatibles. Il est utilisé dans le cas où un programme se sert
> d'une bibliothèque de classe, et que, à la suite d'une mise à jour de la
> bibliothèque, cette dernière ne correspond plus à l'utilisation qui en est
> faite, parce que son interface a changé. Un objet adapteur expose alors
> l'ancienne interface en utilisant les fonctionnalités de la nouvelle.
>
> *Wikipédia*.

Dans le projet du mini-framework, la classe `TwigRendererAdapter` est un exemple
d'implémentation *Adaptateur* qui adapte l'interface incompatible de la classe
`Twig_Environment` avec celle attendue par le framework
(`ResponseRendererInterface`).

### Le Patron Gabarit de Méthode

> Ce patron définit la structure générale d'un algorithme en déléguant certains
> passages afin de permettre à des sous-classes de modifier l'algorithme en
> conservant sa structure générale. C'est un des patrons les plus simples et les
> plus couramment utilisés en programmation orientée objet. Il est utilisé
> lorsqu'il y a plusieurs implémentations possibles d'un calcul. Une classe
> d'exemple (ou *template*) comporte des méthodes d'exemple, qui, utilisées
> ensemble, implémentent un algorithme par défaut. Certaines méthodes peuvent
> être vides ou abstraites. Les sous-classes de la classe template peuvent
> remplacer certaines méthodes et ainsi créer un algorithme dérivé.

L'implémentation de ce patron se traduit par une classe qui définit une méthode
publique ou protégée, et déclarée comme *finale*. De ce fait, la méthode ne peut
plus être redéfinie par les sous-classes, ce qui garantit que son algorithme
sera toujours exécuté de la même manière. En revanche, certaines étapes de cet
algorithme pevent être redéfinies ou implémentées grâce à des méthodes
protégées.

Dans le projet du mini-framework un exemple d'implémentation du patron
*Gabarit de Méthode* est la méthode publique finale *getMessage()* de la classe
*AbstractMessage* du composant *Http*. Cette méthode est déclarée finale pour
garantir que cet algorithme soit toujours exécuté de la même manière et qu'il ne
puisse pas être redéfini par des sous-classes. En revanche, une partie de
l'algorithme est laissé à la charge des sous-classes en les forçant à
implémenter la méthode abstraite `createPrologue()`.

### Le Patron Stratégie

> Dans ce patron, une famille d'algorithmes de même nature sont encapsulés de
> manière à être interchangeables. Les algorithmes peuvent changer
> indépendamment de l'application qui s'en sert. Il comporte trois intervenants :
> le contexte, la stratégie et les implémentations. La stratégie est l'interface
> commune aux différentes implémentations - typiquement une classe abstraite ou
> une interface. Le contexte est l'objet qui va associer un algorithme avec un
> processus.
>
> *Wikipédia*.

Dans le projet du mini-framework, l'interface `FileLoaderInterface` du composant
`Routing` agit comme un stratégie pour l'objet `Router` qui l'utilise. En
proposant plusieurs implémentations de l'interface, donc plusieurs stratégies,
le routeur peut ainsi charger la configuration des routes depuis un fichier PHP
ou bien un fichier XML. Pour supporter un format YAML, il suffirait alors de
créer une nouvelle classe `YamlFileLoader`.

### Le Patron Itérateur

> Ce patron permet d'accéder séquentiellement aux éléments d'un ensemble sans
> connaître les détails techniques du fonctionnement de l'ensemble. C'est un des
> patrons les plus simples et les plus fréquents. Selon la spécification
> originale, il consiste en une interface qui fournit les méthodes `Next`() et
> `Current()`.
>
> *Wikipédia*.

Les itérateurs sont particulièrement adaptés pour les objets de type collection.
Un objet qui collectionne une série d'objets en son sein peut implémenter le
patron itérateur afin de simplifier les opérations d'accès aux objets
collectionnés. L'autre avantage d'un objet itérateur c'est sa capacité à être
utilisé facilement comme argument de la structure `foreach` de PHP.

Dans le projet du mini-framework, la classe `RouteCollection` du composant
`Routing` est un exemple d'implémentation du patron *Itérateur* en PHP grâce à
l'interface native `Iterator` de PHP.

Concepts abordés en cours
-------------------------

Le tableau récapitulatif ci-dessous résume tous les concepts abordés en cours
et sur lesquels porteront l'évaluation écrite.

| Thématique        | Concepts abordés en cours                                                         |
|-------------------|-----------------------------------------------------------------------------------|
| Standards         | Protocole HTTP, PHP PSR, PHP FIG                                                  |
| P. Orientée Objet | classes, méthodes, interfaces, abstract, final, static, principe SOLID            |
| Tests unitaires   | PHPUnit, TDD, Code Coverage                                                       |
| Outils            | Composer, PHPUnit, Twig, Symfony, XDebug, Git                            |
| Design Patterns   | Template Method, Adapter, Factory Method, Composite, Strategy, Mediator, Observer |
| Architecture      | MVC, Service Locator, Dependency Injection, Lazy Loading                          |
| Base de données   | MySQL, Innodb, SQL, transaction, requêtes préparées                               |
| Sécurité          | XSS, DoS, SQL injections, chercher des failles                                    |
| Performances      | Opcode Cache (OPCache, APC, XCache...), Lazy Loading                              |

Modalités d'évaluation
----------------------

Pour l'évaluation écrite du mardi 12 janvier 2015, vous serez évalués sur les
concepts abordés en cours (voir section plus haut). L'évaluation ne portera pas
sur l'écriture de code PHP mais sur l'analyse de code PHP ainsi que sur des
concepts théoriques et fondamentaux (classes, interfaces, design patterns, tests
unitaires, injection de dépendances, composer, phpunit, sécurité...).
