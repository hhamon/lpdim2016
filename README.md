Framework PHP & Application de Blog
===================================

Ceci est la version finale du « mini framework » PHP développé en cours du 4 au
8 janvier 2015. Le dossier `src/` contient à la fois le code générique du
framework dans le namespace `Framework` et le code du blog dans le namespace
`Application`.

Cette section décrit les différents composants du projet développés pendant le
cours et améliorés par la suite. Le cours *Modules Technos Web* porte en
priorité sur l'apprentissage du langage PHP en mode orienté objet ainsi que sur
des concepts plus fondamentaux tels que le Web, le protocole HTTP, la sécurité
et l'architecture logicielle.

Le Langage PHP
--------------

### Historique de PHP

PHP est l'acronyme récursif pour Hypertext Preprocessor. C'est un langage de
programmation interprété développé initialement par Rasmus Lerdorf en 1994. À sa
sortie, PHP s'appelait PHP/FI, pour *Form Interpreter*. Rasmus a développé PHP
pour ses besoins personnels. Il souhaitait comptabiliser dynamiquement les
visites sur son site et récupérer les données envoyées par des formulaires HTML
afin de les traiter au niveau du serveur. PHP est aussi un langage de
programmation multi-plateformes. Il fonctionne aussi bien sur Windows, que Linux
et Mac OS X. Enfin, c'est aussi est un langage Open-Source, gratuit et publié
sous licence PHP. L'ouverture de son code source permet à n'importe quel
développeur ayant des connaissances en langage C de contribuer au code et le
modifier pour ses propres besoins. À ce jour, les statistiques estiment que plus
de 80% des sites Internet de la planète sont développés avec PHP.

PHP est développé sur la base du langage C. Les scripts sont interprétés à
l'exécution par le moteur PHP (le *Zend Engine*) et sont transformés à la volée
en *opcode*. L'opcode est du code binaire machine comparable à l'assembleur. Il
s'agit d'une liste d'instructions opérationnelles primitives.

C'est en 1997 pour leur projet d'études universitaires qu'Andi Gutmans et Zeev
Suraski décident de modifier le moteur PHP pour introduire un système
d'extensions. C'est principalement cet atout qui a popularisé PHP notamment en
favorisant le support de bases de données relationnelles telles que MySQL.

À la fin de l'année 1998, une nouvelle version de PHP est publiée : PHP 4. C'est
sans doute la version de PHP qui a connu le plus gros succès. Cette nouvelle
mouture intègre un tout nouveau coeur réécrit *from scratch*. C'est aussi
l'arrivée du moteur *Zend Engine* en Mai 2000. La version 4 de PHP est arrivée
avec un nombre important de nouvelles fonctionnalités comme le support des
sessions, la « bufferisation » de sortie et l'intégration avec d'autres serveurs
web. C'est aussi dans PHP 4 que les premières implémentations des concepts de la
programmation orientée objet ont fait leur apparition. En effet, il était déjà
possible de structurer le code avec des classes. Les objets étaient toutefois
passés par copie en argument des fonctions et méthodes mais ce défaut de
conception n'a pas empêché l'arrivée des premiers frameworks et bibliothèques de
code professionnels tels que Creole DB, Smarty et CakePHP.

Ce n'est qu'en juin 2004 que PHP 5.0 voit le jour. Cette version intègre une
nouvelle mouture du moteur interne, le *Zend Engine 2*. Ce moteur vient avec de
bien meilleures performances et offre au langage PHP de nouvelles
fonctionnalités :

* extension `filter`,
* nouvelles fonctions de manipulation des tableaux,
* extension `iconv`,
* gestion des flux,
* etc.

Mais la vraie révolution de PHP 5.0, c'est l'introduction du support complet de
la programmation orientée objet. Le modèle objet de PHP 5.0 est similaire à
celui de Java. De plus, ,PHP 5.0 et 5.1 viennent avec de nouvelles extensions
orientées objet telles que PDO, SimpleXML, DOM et MySQLi. PHP 5.2 fera son
apparition quelques années plus tard introduisant de nouvelles fonctionnalités
et de meilleures performances.

En 2005, une initiative est lancée par Andrei Zmievski pour développer PHP 6 en
introduisant un support complet de l'Unicode. Malheureusement, les changements
nécessaires au niveau du moteur *Zend Engine* et des extensions natives pour
supporter l'Unicode se sont avérés bien trop importants et n'ont pas permis à
PHP 6 de voir le jour. Le projet a été avorté quelques années plus tard.

Après quelques années d'incertitude concernant l'avenir de PHP, la *Core Team*
décide de reprendre le travail sur la base de PHP 5. La version PHP 5.3 voit
donc le jour en juin 2009 et s'accompagne de nouvelles fonctionnalités majeures
comme les espaces de nommage (*namespaces*), les fonctions anonymes, le
ramasse-miettes (*garbage collector*), les extensions Intl, Phar, Fileinfo et
Sqlite3.

PHP 5.4 est publié deux ans plus tard et fournit entre autres le support des
*traits* comme nouveauté majeure. C'est aussi dans PHP 5.4 que les algorithmes
de gestion de la mémoire et des variables ont été largement optimisés pour
offrir de bien meilleure performances par rapport aux versions précédentes. Avec
un cycle régulier de publication des nouvelles versions tous les deux ans en
moyenne, les versions PHP 5.5 et 5.6 voient le jour avec leurs lots de nouvelles
fonctionnalités (générateurs, mot-clé *finally*, cache d'opcode natif, etc.).

Ce n'est qu'à la fin de l'année 2015 que la nouvelle version majeure de PHP est
publiée : PHP 7.0, anciennement appelé *PHPNG* (*New Generation*). Cette
nouvelle mouture intègre un tout nouveau moteur PHP réécrit *from scratch* à
l'initiative de Nikita Popov. PHP 7 est annoncée avec des performances deux fois
supérieures en moyenne par rapport aux versions précédentes de PHP tout en
garantissant une compatibilité rétrograde de la syntaxe. PHP 7 vient aussi avec
tout un tas de nouvelles fonctionnalités dont le tant attendu *typage strict*.

En parallèle, Facebook développe un fork similaire de PHP 7 appelé *HHVM*
(*HipHop Virtual Machine*) qui compile du langage Hack (sorte de syntaxe PHP) en
code C++.

### Contributeurs au Langage PHP

Les développeurs et mainteneurs du coeur de PHP forment aujourd'hui une équipe restreinte d'une quinzaine de bénévoles tout au plus :

* **Rasmus Lerdorf** : le créateur original de PHP,
* **Zeev Suraski** : l'un des co-créateurs de PHP 3 et co-foundateur de la société Zend,
* **Andi Gutmans** : l'un des co-créateurs de PHP 3 et co-foundateur de la société Zend,
* **Pierre Joye** : développeur indépendant en charge du support de PHP sur les plateformes Microsoft Windows et Azure Cloud,
* **Sara Golemon** : développeuse Hack/HHVM chez Facebook, contributrice à PHP,
* **Anthony Ferrara** : contributeur à PHP,
* **Julien Pauli** : contributeur à PHP, release manager de PHP 5.5 / 5.6,
* **Derick Rethans** : contributeur historique à PHP, auteur de l'extension XDebug,
* **Lukas Kawe Smith** : release manager de PHP 5.3,
* **Nikita Popov** : core contributeur à PHP 7.0,
* **Dmitry Stogov** : core contributeur à PHP,
* **Sascha Schumann** : core contributeur à PHP,
* **Anatol Belski** : core contributeur à PHP,
* **Joe Watkins** : core contributeur à PHP,
* **Ilia Alshanetsky** : core contributeur à PHP,
* **Johannes Schlueter** : core contributeur à PHP, développeur chez Oracle,

Et bien d'autres contributeurs historiques depuis les débuts du projet :
[crédits](http://php.net/credits.php).

### Commandes PHP Utiles

Cette section dresse la liste de toutes les options utiles de l'utilitaire `php`
en ligne de commande dans un terminal.

#### Afficher la Version de PHP

Dans un onglet du terminal, l'exécution de la commande `php -v` affiche la
version de PHP et du *Zend Engine*. La sortie est similaire à celle ci-dessous.

```
$ php -v
PHP 5.6.17 (cli) (built: Jan  8 2016 23:49:08)
Copyright (c) 1997-2015 The PHP Group
Zend Engine v2.6.0, Copyright (c) 1998-2015 Zend Technologies
    with Zend OPcache v7.0.6-dev, Copyright (c) 1999-2015, by Zend Technologies
    with Xdebug v2.3.3, Copyright (c) 2002-2015, by Derick Rethans
    with blackfire v0.24.1, https://blackfire.io/, by SensioLabs
```

#### Lister les Modules Installés

La commande `php -m` affiche la liste des modules et extensions actuellement
installés et actifs.

```
$ php -m
[PHP Modules]
apc
apcu
bcmath
blackfire
bz2
...
xsl
Zend OPcache
zip
zlib

[Zend Modules]
Xdebug
Zend OPcache
blackfire
```

#### Lister les Fichiers de Configuration de PHP

La commande `php --ini` affiche la liste du/des fichiers de configuration
`php.ini` chargés.

```
$ php --ini
Configuration File (php.ini) Path: /opt/local/etc/php56
Loaded Configuration File:         /opt/local/etc/php56/php.ini
Scan for additional .ini files in: /opt/local/var/db/php56
Additional .ini files parsed:      /opt/local/var/db/php56/APCu.ini,
/opt/local/var/db/php56/blackfire.ini,
/opt/local/var/db/php56/curl.ini,
/opt/local/var/db/php56/exif.ini,
...
```

#### Afficher la Configuration de PHP

La commande `php -i` affiche la liste des paramètres de configuration de PHP et
les valeurs courantes pour chaque module installé et actif.

```
$ php -i
phpinfo()
PHP Version => 5.6.17

System => Darwin C02MC0KAF5YV.local 14.5.0 Darwin Kernel Version 14.5.0: Tue Sep  1 21:23:09 PDT 2015; root:xnu-2782.50.1~1/RELEASE_X86_64 x86_64
Build Date => Jan  8 2016 23:48:23
Configure Command =>  './configure'  '--prefix=/opt/local' '--mandir=/opt/local/share/man' '--infodir=/opt/local/share/info' '--program-suffix=56' '--includedir=/opt/local/include/php56' '--libdir=/opt/local/lib/php56' '--with-config-file-path=/opt/local/etc/php56' '--with-config-file-scan-dir=/opt/local/var/db/php56' '--disable-all' '--enable-bcmath' '--enable-ctype' '--enable-dom' '--enable-filter' '--enable-hash' '--enable-json' '--enable-libxml' '--enable-pdo' '--enable-session' '--enable-simplexml' '--enable-tokenizer' '--enable-xml' '--enable-xmlreader' '--enable-xmlwriter' '--with-bz2=/opt/local' '--with-mhash=/opt/local' '--with-pcre-regex=/opt/local' '--with-libxml-dir=/opt/local' '--with-zlib=/opt/local' '--without-pear' '--disable-cgi' '--enable-cli' '--enable-fileinfo' '--enable-phar' '--disable-fpm' '--with-libedit=/opt/local' 'CC=/usr/bin/clang' 'CFLAGS=-pipe '-Os' '-arch' 'LDFLAGS=-L/opt/local/lib '-Wl,-headerpad_max_install_names' '-arch' 'CPPFLAGS=-I/opt/local/include' 'CXX=/usr/bin/clang++' 'CXXFLAGS=-pipe '-Os' '-arch' '-stdlib=libc++''

...
```

#### Vérifier la Syntaxe d'un Script PHP

La commande `php -l` (`l` pour `Lint`) affiche si oui ou non le fichier PHP
passé en paramètre contient une erreur de syntaxe.

```
$ php -l test.php
PHP Parse error:  syntax error, unexpected ''Hello World' (T_ENCAPSED_AND_WHITESPACE) in test.php on line 3

Parse error: syntax error, unexpected ''Hello World' (T_ENCAPSED_AND_WHITESPACE) in test.php on line 3

Errors parsing test.php
```

#### Démarrer le Serveur Web Natif de PHP

Depuis PHP 5.4, la commande `php -S` démarre le serveur natif de PHP. Cette
commande prend en paramètre le nom de domaine et le numéro de port sur lequel le
serveur web natif écoute. Une option facultative `-t` permet aussi de définir le
dossier qui sert de racine du serveur web (*Document Root*).

```
$ php -S localhost:8000 -t web/
PHP 5.6.17 Development Server started at Mon Jan 11 19:39:36 2016
Listening on http://localhost:8000
Document root is /Volumes/Development/Sites/LPDIM2016/web
Press Ctrl-C to quit.
```

#### Obtenir de la Documentation

Quand une connexion Internet n'est pas à portée pour consulter la documentation
officielle sur le site `php.net`, la commande `php` suivie d'une option `--rf`,
`--rc`, `--re`, `--rz` ou `--ri` retourne le manuel d'utilisation d'une
structure du langage.

```
$ php -h
Usage: php [options] [-f] <file> [--] [args...]
   php [options] -r <code> [--] [args...]
   php [options] [-B <begin_code>] -R <code> [-E <end_code>] [--] [args...]
   php [options] [-B <begin_code>] -F <file> [-E <end_code>] [--] [args...]
   php [options] -S <addr>:<port> [-t docroot]
   php [options] -- [args...]
   php [options] -a

...

  --rf <name>      Show information about function <name>.
  --rc <name>      Show information about class <name>.
  --re <name>      Show information about extension <name>.
  --rz <name>      Show information about Zend extension <name>.
  --ri
```

Par exemple, la commande `php --rc SimpleXMLElement` retourne la documentation
technique complète de la classe `SimpleXMLElement`. La commande `php --rf`
affiche quant à elle la documentation technique d'une fonction native du
langage.

```
$ php --rf strtoupper
Function [ <internal:standard> function strtoupper ] {

  - Parameters [1] {
    Parameter #0 [ <required> $str ]
  }
}
```

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
Enfin, n'oubliez pas de configurer les accès à la base de données.

```yaml
# config/settings.yml
parameters:
    database.dsn:      mysql:host=localhost;port=3306;dbname=lp_dim
    database.user:     root
    database.password: ~
    app.environment: dev
    # Switch this setting to false to disable stack traces in error pages.
    app.debug: true
```

Exécution des tests unitaires
-----------------------------

Les tests unitaires sont écrits et exécutés à l'aide de l'outil `PHPUnit`.
PHPUnit est un framework d'automatisation des tests unitaires développé par
Sebastian Bergmann. Il s'agit d'un portage en PHP du très célèbre `JUnit`.
PHPUnit offre toute une palette de fonctionnalités telles que l'exécution des
tests unitaires, la génération des rapports de couverture de code (HTML, Clover,
etc.) ou bien encore la génération de rapports compatibles `JUnit`.

C'est la commande `bin/phpunit.phar` qui suffit à lancer la suite de tests
unitaires.

    $ php bin/phpunit.phar

Cette commande lit automatiquement le fichier de configuration `phpunit.xml` à
la racine du projet afin de savoir où se trouvent les fichiers de tests
unitaires à exécuter. L'exécution de cette commande produit un résultat
similaire à celui ci-dessous :

    PHPUnit 5.0.10 by Sebastian Bergmann and contributors.
    
    ...............................................................  63 / 136 ( 46%)
    ............................................................... 126 / 136 ( 92%)
    ..........                                                      136 / 136 (100%)
    
    Time: 2.52 seconds, Memory: 14.75Mb
    
    OK (136 tests, 330 assertions)

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
NodeJS ou bien encore `gem` pour Ruby. Composer sert à installer les paquets PHP
tiers dont le projet a besoin pour fonctionner mais aussi de les mettre à jour
quand de nouvelles versions de ces derniers sont disponibles. Par défaut, les
paquets sont installés dans le dossier `vendor/` que Composer crée à la racine
du projet.

    $ tree -L 2 vendor/
    vendor/
    ├── autoload.php
    ├── composer
    │   ├── ClassLoader.php
    │   ├── LICENSE
    │   ├── autoload_classmap.php
    │   ├── autoload_namespaces.php
    │   ├── autoload_psr4.php
    │   ├── autoload_real.php
    │   └── installed.json
    ├── monolog
    │   └── monolog
    ├── psr
    │  └── log
    ├── symfony
    │  └── yaml
    └── twig
        └── twig

Par exemple, comme le montre le snippet ci-dessous, le projet réalisé en cours a
besoin d'un certain nombre d'outils tiers tels que Twig, Symfony et Monolog. Ces
dépendances sont décrites à la section `require` du fichier `composer.json`
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
        "symfony/yaml": "^3.0",
        "monolog/monolog": "^1.17"
    }
}
```

Chaque paquet possède un nom du type `organisation/paquet` auquel est associée
une expression de version. Le symbole *tilde* - `~` - signifie *à partir de*
mais il exclut cependant les versions majeures. Le symbole `^` est identique à
`~` mais il inclut aussi les versions majeures. Par conséquent, Composer ira
chercher pour chaque paquet la version la plus à jour à partir de celle indiquée
dans le fichier `composer.json`. Il est aussi possible d'exprimer les versions à
l'aide d'une valeur fixe (`3.0.1`), d'un intervalle (`>=2.5-dev,<2.6-dev`),
d'une branche Git (`dev-master`) ou bien encore d'un numéro de commit dans une
branche (`dev-master#ea6f2130b4cab88635d35a848d37b35f1e43d407`).

La commande `composer.phar require` sert à ajouter de nouvelles dépendances au
projet. La commande ci-dessous ajoute le paquet `symfony/finder` au projet et
Composer se charge de le télécharger, l'installer et l'initialiser pour qu'il
soit prêt à l'emploi. À condition bien sûr que ce paquet ne soit pas
incompatible avec une dépendance déjà installée.

    $ php bin/composer.phar require symfony/finder

Pour mettre à jour un paquet ou bien toutes les dépendances, il suffit
d'exécuter la commande `update` de l'utilitaire `composer.phar`. De plus, pour
trouver un paquet, Composer s'appuie sur l'annuaire
[Packagist.org](http://packagist.org) qui référence tous les paquets Open-Source
connus.

    $ # MAJ toutes les dépendances
    $ php bin/composer.phar update
    $
    $ # MAJ que le paquet symfony/yaml
    $ php bin/composer.phar update symfony/yaml

L'installation d'un nouveau paquet ou bien la mise à jour d'une ou des
dépendances entraîne la régénération du fichier `composer.lock`. Ce fichier
contient la liste de toutes les dépendances installées dans le dossier `vendor/`
ainsi que les numéros des versions précisément installées. Il convient donc de
commiter ce fichier dans le dépôt Git car la commande `install` de Composer
installe les dépendances telles qu'elles sont décrites dans le fichier
`composer.lock` sans chercher à les mettre à jour. C'est donc idéal pour
s'assurer que les versions des paquets seront les bonnes en production lors du
déploiement ou bien tout simplement lorsqu'un autre développeur récupère le
code du dépôt.

Lorsque toutes les dépendances sont installées, les classes des paquets sont
directement instanciables dans le code sans avoir besoin de les inclure au
préalable avec les instructions `require` ou `include` de PHP. Composer vient
avec des mécanismes d'autochargement des classes pour chaque paquet installé. La
section `autoload` du fichier `composer.json` décrit quant à elle les mécanismes
d'autochargement des classes du projet.

```json
{
    "autoload": {
        "psr-4": {
            "Acme\\": "acme/",
            "": "src/"
        }
    }
}
```

La description ci-dessus signifie que les classes du projet à autocharger
doivent tout d'abord respecter la convention `PSR-4` établie par le
[PHP-FIG](http://www.php-fig.org/). Les classes dont l'espace de nommage débute
par la chaîne `Acme\` sont à trouver dans un dossier `acme/` tandis que toutes
les autres classes seront autochargées depuis le dossier `src/`.

    $ php bin/composer.phar dumpautoload --optimize

La commande `dumpautoload` de l'utilitaire `composer.phar` régénère les fichiers
d'autochargement si nécessaire. Elle s'accompagne aussi d'une option
`--optimize` qui optimise le processus d'autochargement. Cette option est
particulièrement utile sur un serveur de production.

PSR & FIG
---------

L'acronyme *PSR* signifie *PHP Standard Recommendation* ou littéralement
*Recommandation Standard PHP* en français. Une *PSR* est en fait une règle
standard et universelle de codage définie par le *PHP FIG* afin que les
frameworks et bibliothèques de code modernes soient interopérables. Il existe
aujourd'hui plusieurs standards *PSR* édités par le *FIG*.

*FIG* est l'acronyme pour *Framework Interoperability Group*. Il s'agit d'un
groupe de développeurs PHP fondé à la fin de l'année 2009 à la suite de la
conférence annuelle ZendCon aux USA. Les principaux développeurs des frameworks
et bibliothèques PHP tels que Symfony, Zend, CakePHP, Doctrine, Twig, Drupal,
etc. qui étaient présents à cette conférence ont eu l'idée de se rassembler et
mettre en commun leur savoir pour définir des règles d'interopérabilité du code.
L'objectif était ainsi de pouvoir plus facilement utiliser le code d'un
framework dans un autre sans trop d'efforts.

Depuis le début de l'année 2010, plusieurs règles *PSR* ont vu le jour pour
standardiser entre autres les mécanismes d'autochargement des classes ou bien
les styles et conventions de codage. Le tableau ci-dessous dresse la liste des
PSRs actuellement validés ou bien en cours de travail.

| PSR    | Statut   | Explications
|--------|----------|------------------------------------------------------------------------------------------|
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
| PSR-12 | En cours | Évolution de PSR-2 qui prend en compte les nouvelles règles syntaxiques de PHP 7.        |

Cette liste est disponible sur le site du [PHP-FIG](http://www.php-fig.org/).

Conception du Framework
-----------------------

Les sections qui suivent décrivent les différents composants bas niveau qui font
office de fondations du framework.

### Le Composant `Framework\Http`

Le coeur du framework repose sur un noyau léger (« *micro-kernel* ») responsable
de transformer un objet de requête (classe `Framework\Http\Request`) en objet de
réponse (classe `Framework\Http\Response`). Ces deux objets modélisent les
messages du protocole HTTP sous forme orientée objet pour rendre le code plus
naturel et intuitif à utiliser que les APIs natives du langage PHP (variables
superglobales, fonction `header()`, instruction `echo`, etc.).

Une requête HTTP prend la forme suivante :

```
POST /movie HTTPS/1.1
host: www.allocinoche.com
user-agent: Firefox/33.0
content-type: application/json
content-length: 61

{ 'movie': 'Intersté Lard', 'director': 'Christopher Mulan' }
```

Une réponse HTTP à la requête précédente aura par exemple la forme ci-dessous :

```
HTTPS/1.1 201 Created
content-type: application/json
content-length: 74
host: www.allocinoche.com
location: /movie/12345/interste-lard.html

{ 'id': 12345, 'movie': 'Intersté Lard', 'director': 'Christopher Mulan' }
```

Dans le prologue de la réponse, le code à trois chiffres s'intitule le
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
d'un prologue, d'une liste d'en-têtes et d'un corps facultatif. L'objet de
requête peut être construit directement à partir de son constructeur ou bien à
partir des variables superglobales de PHP ou d'un message HTTP complet.

```php
use Framework\Http\Request

$request = new Request('POST', '/movie', 'HTTPS', '1.1');
$request = Request::createFromGlobals();
$request = Request::createFromMessage("POST /movie HTTPS/1.1\n...");
```

L'objet de requête possède une série de méthodes publiques pour récupérer de
manière atomique des informations telles que l'URL, les données passées en POST,
les en-têtes ou bien encore le corps. Le listing de code ci-dessous décrit une
partie de l'API implémentée par la classe `Framework\Http\Request`.

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
d'un prologue, d'une liste d'en-têtes et d'un corps facultatif. L'objet de
réponse peut être construit directement à partir de son constructeur ou bien à
partir d'un objet de requête ou d'un message HTTP complet.

```php
use Framework\Http\Response

$response = new Response(200, 'HTTP', '1.0', [ 'Content-Type' => 'text/html' ], '<html>...');
$response = Response::createFromRequest($request);
$response = Response::createFromMessage('HTTP/1.1 304 Not Modified');
```

L'objet de réponse possède une série de méthodes publiques pour récupérer de
manière atomique des informations telles que le code de statut, les en-têtes ou
bien le corps. Le snippet ci-dessous présente l'API implémentée par la classe
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

La classe `Framework\Http\RedirectResponse` modélise quant à elle une réponse de
redirection. Son constructeur reçoit l'URL vers laquelle le client doit être
redirigé. Le code de statut est fixé à `302` par défaut mais peut être n'importe
lequel dans l'intervalle `300` à `308`. L'objet de redirection force l'url dans
une en-tête HTTP de type `Location`.

Enfin, toutes les classes de requêtes et de réponses dérivent du même super-type
abstrait `Framework\Http\AbstractMessage`. Cette classe *abstraite* n'est pas
instanciable et solutionne trois problématiques : 

1. Définir un super-type commun,
2. Factoriser des attributs et méthodes communs aux classes dérivées,
3. Éviter la duplication de code dans les classes dérivées.

La classe `AbstractMessage` définit aussi la méthode `getMessage()` comme finale
à l'aide du mot-clé `final`. Ce mot-clé empêche les classes dérivées de
redéfinir cette méthode et d'en changer son comportement. Ainsi, le framework
est garanti de toujours obtenir un message HTTP composé d'en-têtes et d'un
corps.

De plus, la méthode `getMessage()` est une implémentation du
*patron de conception* (aka *Design Pattern*) « **Patron de Méthode** »
(aka *Template Method*) qui permet d'empêcher la redéfinition complète d'un
algorithme mais autorise néanmoins la redéfinition de certaines parties de
celui-ci au travers de méthodes protégées judicieusement choisies. Le mot-clé
`final` sur la signature de la méthode interdit la redéfinition de celle-ci dans
les classes dérivées. Cependant, la méthode `getMessage()` autorise la
redéfinition d'une étape de l'algorithme complet grâce à la méthode protégée
`createPrologue()`. Cette dernière est d'ailleurs déclarée abstraite (mot-clé
`abstract`) pour obliger les classes dérivées à l'implémenter.

**Important** : il suffit qu'il y ait seulement une seule méthode déclarée
abstraite dans une classe pour que celle-ci doive aussi être déclarée abstraite.

Pour plus d'informations sur l'API du composant `Http`, consultez les tests
unitaires.

### Le Composant `Framework\Routing`

L'espace de nommage `Framework\Routing` offre des classes pour router les URLs
HTTP sur des contrôleurs de l'application. La configuration des routes est
définie à l'aide d'objets de type `Route` et `RouteCollection`. Chaque objet de
route stocke un tableau associatif de paramètres qui renferme entre autres la
classe de contrôleur à instancier à la clé `_controller`. Chaque classe de
contrôleur doit implémenter la méthode magique `__invoke()` afin d'être
*invocable* par le framework. De plus, chaque route encapsule son chemin, ses
paramètres associés, les contraintes des variables de l'URL ainsi que les
contraintes sur les méthodes HTTP autorisées.

```php
use Framework\Routing\Route;

$route = new Route(
    '/hello/{name}',
    'Application\\Controller\\HelloWorldAction',
    ['GET', 'POST'],
    ['name' => '[a-z]+']
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
routes grâce à la collection de routes, et se charge de faire correspondre l'URL
de la requête à une route. La méthode `match()` du routeur reçoit un objet
`RequestContext` duquel il extrait l'URL demandée par le client et la méthode
HTTP utilisée. Si l'URL et la méthode HTTP correspondent à une route, alors le
routeur retourne simplement un tableau associatif des attributs de la route
(son nom, la classe de contrôleur associée et les paramètres de la route). À
l'inverse, si l'URL correspond à aucune des routes enregistrées, le routeur lève
une exception de type `Framework\Routing\RouteNotFoundException`.

Le composant `Router` fournit des stratégies d'import de fichiers de
configuration des routes. Les routes peuvent être aussi bien définies
directement dans un fichier PHP pur comme dans un format statique tel que le
XML. La classe `XmlFileLoader` est responsable de lire un fichier XML de
définition des routes et d'importer celles-ci dans le routeur. La classe
`CompositeFileLoader` est une stratégie composite qui permet de charger un
fichier de définition des routes quelle que soit son extension (php ou xml).

Pour plus d'informations sur l'API du composant `Routing`, consultez les tests
unitaires.

### Le Composant `Kernel`

La classe `Kernel` constitue le coeur du « micro-framework » développé en TP.
C'est lui qui est responsable de traduire un objet de requête en un objet de
réponse. Pour ce faire, il utilise la méthode `handle()` de l'interface
`Framework\KernelInterface` et s'appuie sur son registre de services
(*Service Locator*) passé à son constructeur. Cette méthode délègue ensuite à un
service `http_kernel` de type `Framework\HttpKernel` qui lui aussi implémente
l'interface `Framework\KernelInterface`. L'objet `Kernel` agit donc comme une
implémentation du patron *Proxy* en décorant l'objet `HttpKernel`.

La méthode `handle()` de l'objet `HttpKernel` propage des événements de
pré-traitement et de post-traitement de la requête grâce au service
`event_manager` de type `Framework\EventManager\EventManager`. Ces événements
rendent possible l'extension du noyau en certains points stratégiques du
traitement de la requête sans changer l'algorithme de la méthode `handle()`.
Celle-ci est d'ailleurs déclarée finale. Le fait d'utiliser un gestionnaire
d'événements dans cette méthode finale pour étendre dynamiquement le noyau est
une autre implémentation du patron de conception *Patron/Gabarit de Méthode*.

**Note :** le composant `EventManager` est une implémentation du patron de
conception *Médiateur* (aka *Mediator*), lui même inspiré du patron de
conception *Observateur* (aka *Observer*). Le Médiateur est un patron de
conception comportemental qui favorise le découplage entre des objets qui
doivent communiquer ensemble.

La méthode privée `doHandleRequest()` de la classe `Framework\HttpKernel` tente
de convertir l'objet de requête en objet de réponse en invoquant un contrôleur.
Avant de parvenir au contrôleur, le noyau doit d'abord demander au service
routeur si l'URL de la requête correspond à une route enregistrée. Cette
opération est réalisée par l'objet `Framework\RouterListener` qui écoute le tout
premier événement propagé `Framework\KernelEvents::REQUEST`. Si le routeur
parvient à faire correspondre la requête du client avec une route, alors les
paramètres de celle-ci sont stockés dans la requête en tant qu'attributs pour
un usage ultérieur. Si aucune route ne correspond à la requête, le routeur émet
alors une exception qui est instantanément capturée et transformée en objet
réponse par le noyau HTTP.

Puis, le noyau HTTP récupère depuis la fabrique de contrôleurs, le contrôleur à
invoquer. La fabrique le contrôleurs construit le contrôleur à partir du tableau
d'attributs de la requête. Si celui-ci ne contient pas suffisamment d'éléments
pour construire le contrôleur, la fabrique lève alors une exception et
interrompt le flux d'exécution du code.

Ensuite, le noyau HTTP propage un événement `Framework\KernelEvents::CONTROLLER`
qui permet aux écouteurs de celui-ci de modifier le contrôleur juste avant qu'il
soit invoqué.

Il ne reste alors au noyau HTTP plus qu'à invoquer dynamiquement le contrôleur à
l'aide de la fonction native PHP `call_user_func_array()`. Le contrôleur reçoit
de la part du noyau la référence à la requête et retourne à ce dernier un objet
de réponse. Si aucune réponse n'est retournée, le noyau HTTP lève une exception.

Lorsque l'objet de réponse est retourné, le noyau HTTP propage un événement
`Framework\KernelEvents::RESPONSE`. N'importe lequel des écouteurs qui écoutent
cet événement peut modifier la réponse générée.

Le noyau de l'application est utilisé comme une implémentation du patron
d'architecture *Contrôleur Frontal* (aka *Front Controller*). Il s'agit du point
d'entrée unique sur l'application. C'est le fichier `web/index.php` qui démarre
et utilise le noyau.

```php
# web/index.php
require_once __DIR__.'/../bootstrap.php';

use Framework\Http\Request;
use Framework\Http\StreamableInterface;
use Framework\Kernel;

$serviceLocator = require __DIR__.'/../bootstrap.php';

$kernel = new Kernel($serviceLocator);
$response = $kernel->handle(Request::createFromGlobals());

if ($response instanceof StreamableInterface) {
    $response->send();
}
```

Si une exception est levée au cours du flux du traitement de la requête, le
noyau HTTP l'attrape et propage un événement `Framework\KernelEvents::EXCEPTION`
pour la traiter. Les écouteurs de cet événement peuvent enregistrer l'exception
dans un journal d'erreurs puis générer une vue adaptée à retourner au client. La
classe `Application\ErrorHandler` génère les pages d'erreurs appropriées pour
l'utilisateur final en fonction du type de l'exception à traiter. La classe
`LoggerHandler` se charge d'enregistrer le message de l'exception dans un
journal d'erreurs grâce à une implémentation de « *logger* » de la bibliothèque
Open-Source Monolog.

### Le Composant `Templating`

Le composant `Templating` fournit deux interfaces
(`Framework\Templating\RendererInterface` et `Framework\Templating\ResponseRendererInterface`)
pour développer des moteurs de rendu. Par défaut, le composant vient avec une
implémentation basique de moteur de rendu. Il s'agit de la classe
`Framework\Templating\PhpRenderer`. Cet objet implémente l'interface
`Framework\Templating\ResponseRendererInterface` et offre un
mécanisme simple pour évaluer des gabarits PHP purs. La seconde implémentation,
la classe `Framework\Templating\BracketRenderer` remplace des variables du
template délimitées par des doubles crochets : `[[name]]`.

```php
use Framework\Template\BracketRenderer

$engine = new BracketRenderer(__DIR__.'/app/views');

$output = $engine->render('movie/show.tpl', [
    'title' => 'Jurassic Pork',
    'director' => 'Steven Spielbergue',
]);
```

Le composant `Templating` est aussi livré avec une classe
`Framework\Templating\TwigRendererAdapter` qui adapte le moteur de rendu
**Twig** dans le framework. En effet, l'API attendue par le framework est
différente de celle qu'expose Twig. L'interface
`Framework\Templating\ResponseRendererInterface` et la classe concrète
`Twig_Environment` sont incompatibles par nature, ce qui empêche d'utiliser Twig
comme moteur de rendu dans le framework.

Pour répondre à cette problématique sans trop d'efforts, le framework embarque
un *adaptateur* qui rend compatible l'API publique de l'objet `Twig_Environment`
avec l'interface `Framework\Templating\ResponseRendererInterface` attendue par
le framework. Le patron de conception *Adaptateur* (aka *Adapter*) implémenté
par la classe `Framework\Templating\TwigRendererAdapter` favorise la
*composition d'objets* plutôt que *l'héritage* pour résoudre ce problème.

```php
use Framework\Templating\TwigRendererAdapter;

$twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__.'/app/views'), [
    'debug'            => true,
    'auto_reload'      => true,
    'strict_variables' => true,
    'cache'            => __DIR__.'/cache/twig',
]);

$engine = new TwigRendererAdapter($twig);

$output = $engine->renderResponse('movie/show.twig', [
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
sont ensuite partagés par le registre pour ne pas les récréer inutilement s'ils
sont demandés à nouveau par le code client.

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

> L'utilisation de fonctions anonymes (*Lambda* ou *Closures*) permet de
> retarder jusqu'au tout dernier moment l'exécution du code qui fabrique le
> service. C'est le principe du « *lazy loading* » ou exécution retardée.

L'objet `ServiceLocator` est instancié et configuré depuis le contrôleur frontal
`web/index.php`. Les registres de services sont aussi connus sous les
appellations de *conteneurs de services*, *conteneurs d'injection de dépendance*
ou bien encore *conteneurs d'inversion de contrôle*.

Pour en savoir plus sur le composant `ServiceLocator`, consultez les tests
unitaires et le *Contrôleur Frontal* (le fichier `web/index.php`).

### Le Composant `EventManager`

Le composant `EventManager` fournit une implémentation du patron de conception
*Médiateur*, lui même inspiré du patron *Observateur*. Son objectif consiste à
découpler des objets qui possèdent de nombreuses connexions avec d'autres. La
classe `Framework\EventManager\EventManager` joue ce rôle de médiateur.

Les écouteurs configurés sont d'abord enregistrés sur le médiateur. Un écouteur
n'est ni plus ni moins qu'une structure invocable en PHP :

* une chaîne de caractères contenant le nom d'une fonction PHP,
* un objet qui implémente la méthode magique `__invoke()`,
* un tableau contenant un objet et le nom d'une méthode publique à appeler sur
  celui-ci,
* un tableau contenant le nom d'une classe et le nom d'une méthode statique
  publique de cette classe,
* une fonction anonyme (`Closure`).

Chaque écouteur écoute un événement spécifique. Plusieurs écouteurs peuvent être
attachés sur le même événement et déclenchés avec des priorités différentes.

```php
use Framework\EventManager\EventManager;
use Framework\ExceptionEvent;

$eventManager = new EventManager();
$eventManager->addEventListener('kernel.exception', function (ExceptionEvent $event) {
    $exception = $event->getException();
    $request = $event->getRequest();
    // ...
});
$eventManager->addEventListener(
    'kernel.exception',
    function (ExceptionEvent $event) {
        // ...
    },
    100 // Priorité
});
```

Chaque écouteur écoute un événement, ici `kernel.exception`. Lorsqu'il sera
déclenché plus tard, l'écouteur recevra en argument un objet d'événement qui
encapsule certaines informations utiles pour l'écouteur. Dans cet exemple,
l'événement `kernel.exception` est en fait un objet de type `ExceptionEvent` qui
encapsule des objets tels que la requête et l'exception à gérer.

Lorsque tous les écouteurs sont enregistrés sur le gestionnaire d'événements, ce
dernier peut alors propager des événements depuis le code client qui le
référence. Cela permet ainsi de découpler l'objet client de ses nombreux
écouteurs.

```php
class HttpKernel implements KernelInterface
{
    private $dispatcher;

    final public function handle(Request $request)
    {
        try {
            $response = $this->doHandleRequest($request);
        } catch (\Exception $exception) {
            $event = $this->dispatcher->dispatch(KernelEvents::EXCEPTION, new ExceptionEvent($exception, $request));
            if (!$event->hasResponse()) {
                throw new \RuntimeException('No exception handler has generated a Response', 0, $exception);
            }
    
            $response = $event->getResponse();
        }
  
        return $this->doHandleResponse($request, $response);
    }
}
```

À l'appel de la fonction `dispatch()`, le répartiteur d'événements invoque
successivement dans l'ordre de priorité les écouteurs de l'événement et transmet
à chacun l'objet `$event`. Si un écouteur est capable de fixer un objet
`Response` dans l'objet `ExceptionEvent` alors la propagation de l'événement est
immédiatement stoppée et les écouteurs enregistrés restants ne sont pas appelés.

```php
namespace Framework\EventManager;

class EventManager implements EventManagerInterface
{
    public function dispatch($name, Event $event)
    {
        if (!$this->hasListeners($name)) {
            return $event;
        }

        foreach ($this->getSortedListeners($name) as $listener) {
            call_user_func_array($listener, [ $event ]);
            if ($event->isStopped()) {
                // Stop looping if a listener
                // has stopped the event propagation.
                break;
            }
        }

        return $event;
    }

    // ...
}
```

Dans l'application de blog, des instances des classes `ErrorHandler` et
`LoggerHandler` de l'espace de nommage `Application` sont enregistrés
comme écouteurs de l'événement `kernel.exception`. Le répartiteur d'événements
est quant à lui enregistré comme service dans le registre de services.

### Le Composant `Session`

Le composant `Session` fournit une fine couche d'abstraction par dessus le
mécanisme de session de base de PHP. Grâce à cette abstraction, il est désormais
plus facile d'adapter le stockage des données de la session utilisateur dans
différentes technologies : système de fichier, Redis, Memcached, APC, etc.

La classe `Framework\Session\Session` agit comme une sorte de façade et offre
une API simplifiée pour manipuler la session. Cette classe implémente le contrat
de l'interface `Framework\Session\SessionInterface` qui définit des méthodes
publiques pour démarrer la session, la détruire, stocker des valeurs à
l'intérieur ou bien récupérer ces dernières.

```php
namespace Framework\Session;

interface SessionInterface
{
    /**
     * Starts the session.
     *
     * @return bool True if the session is already started, false otherwise
     */
    public function start();

    /**
     * Destroys the session.
     *
     * @return bool True if the session was correctly destroyed, false otherwise.
     */
    public function destroy();

    /**
     * Stores new data into the session.
     *
     * @param string $key   The session variable name
     * @param mixed  $value The session variable value (must be serializable at some point)
     *
     * @return bool True if succeeded, false otherwise.
     */
    public function store($key, $value);

    /**
     * Fetches a data from the session.
     *
     * @param string $key     The session variable name
     * @param mixed  $default The default value to return
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null);

    /**
     * Returns the session's unique identifier.
     *
     * @return string
     */
    public function getId();

    /**
     * Saves the session data to the persistent storage.
     *
     * @return bool True if successful, false otherwise
     */
    public function save();
}
```
Pour implémenter complètement ce contrat, la classe `Session` dépend d'un
adaptateur de système de stockage. Il s'agit d'un objet qui répond au contrat de
l'interface `Framework\Session\Driver\DriverInterface`. Cette définit les
méthodes publiques qui permettent de manipuler le système de stockage des
données et offrir une abstraction des fonctions natives de PHP de manipulation
des fichiers, de bases de données ou bien de cache clé/valeur comme Redis, APCu
ou Memcached.

Le composant vient avec deux implémentations par défaut de cette interface. La
première est la classe `ArrayDriver`. Son usage est uniquement réservé aux tests
unitaires puisque cet objet simule une session non persistente dans un tableau
de données. C'est la raison pour laquelle sa documentation d'API utilise le
marqueur `@internal` pour signifier son usage strictement interne. La seconde
implémentation de la `DriverInterface` est la classe `NativeDriver`. Il s'agit
d'une classe qui encapsule les appels au système natif de gestion des sessions
de PHP.

> Dans l'application du blog suivant le principe MVC, la classe `Session` est
> définie dans le registre de services comme un service configuré avec son
> adaptateur natif.

Application de Blog suivant le patron MVC
-----------------------------------------

Les sections suivantes décrivent les choix techniques implémentés pour réaliser
une application de blogging construite autour du patron MVC. Les trois couches
du modèle MVC sont détaillées ci-dessous.

### La Couche Contrôleur

La couche *Contrôleur* encapsule la logique applicative. Elle est responsable
d'analyser la requête de l'utilisateur afin d'en déduire le *Modèle* à
interroger et la *Vue* à rendre. Il s'agit donc d'une simple colle entre les
couches de *Modèle* et de *Vue*. C'est aussi la glue entre le code de
l'application et le code du framework (l'infrastructure). Par conséquent, un
*Contrôleur* ne contient que très peu de lignes de code (pas plus de 20 lignes
de code en général).

Dans l'application de blogging, les *Contrôleurs* sont des objets dont les
classes se trouvent dans l'espace de nommage `Application\Controller`. Chaque
classe de contrôleur représente une seule et unique fonctionnalité de
l'application (afficher tous les billets, créer un billet, consulter un billet
etc.). Tous ces objets ont la particularité d'implémenter la méthode magique
`__invoke()` qui permet de les utiliser comme s'ils étaient des fonctions PHP
classiques.

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
  
        return $this->render('blog/show.twig', ['post' => $post]);
    }
}
```

Dans l'exemple ci-dessus, la méthode `__invoke()` de la classe
`Application\Controller\Blog\GetPostAction` a pour tâche de retrouver un billet
du blog par son identifiant unique. Pour ce faire, l'action analyse d'abord la
requête afin de récupérer l'identifiant unique du billet dans ses attributs.
L'attribut `id` est en fait un paramètre dynamique de la route `blog_post`.Ce
paramètre doit forcément être un ou plusieurs chiffres qui se suivent comme le
décrit le marqueur `<requirement/>` du fichier de configuration des routes
(`app/config/routes.xml`).

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
`Framework\Http\HttpNotFoundException`.

En remontant jusqu'au noyau, cette exception sera ensuite convertie en réponse
HTTP de type `404`. Enfin, si le billet a bien été récupéré, le *Contrôleur*
choisit alors de rendre la *Vue* adaptée. Dans cet exemple, la *Vue* est
modélisée par le gabarit Twig `blog/show.twig` qui reçoit le billet dans une
variable `post`. La méthode `render()` de la superclasse abstraite
`Framework\Controller\AbstractController` évalue le gabarit avec ses variables,
puis construit un objet de type `Framework\Http\Response` qui encapsule le
résultat final.

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
tels que `SQLite`, `MySQL`, `Mariadb`, `PostgreSQL`, `Oracle`, `Sybase` etc. Cet
objet est d'ailleurs lui aussi défini comme un service dans le registre des
services.

La responsabilité de l'entrepôt `BlogPostRepository` consiste à faire une
interface avec la table `blog_post` en encapsulant toutes les requêtes SQL
exécutée sur cette dernière. L'exécution des requêtes SQL est déléguée à l'objet
`PDO` qui construit des *requêtes préparées* ainsi qu'une *transaction*
supplémentaire dans le cas d'opérations d'écriture sur la base.

En centralisant les requêtes SQL dans l'entrepôt, cela offre aussi aux
développeurs la possibilité de les adapter plus tard s'ils décident de changer
le système de stockage des données sous-jacent. Dans ce cas, il suffira de
réécrire seulement la couche des entrepôts tandis que le reste du code de
l'application sera inchangé et fonctionnel.

### La Couche Vue

La couche `Vue` (ou `Présentation`) représente les données à présenter au
client. Il s'agit par exemple d'une réponse dans un format textuel tel que HTML,
XML, JSON etc. ou bien dans un format binaire comme une image ou un fichier PDF.
Il peut aussi tout simplement s'agir d'une réponse de redirection vers une autre
page.

> Comme la notion de *Vue* du patron MVC n'est pas spécialement adaptée à la
> topologie du Web, Paul M. Jones propose une redéfinition plus subtile de MVC :
> le patron [ADR](http://pmjones.io/adr/).
>
> *ADR* est l'acronyme pour *Action*, *Domain* et *Responder*. Dans
> l'architecture ADR, la couche *Action* correspond au *Contrôleur*, la couche
> *Domain* correspond au *Modèle* et, enfin, la couche *Responder* à la *Vue*.
>
> C'est en fait la couche de présentation des données qui est remaniée dans le
> patron ADR. Contrairement aux applications traditionnelles dites *mainframe*,
> les applications Web ne retournent pas nécessairement un résultat *visuel*.
> Une réponse HTTP est parfois constituée que d'en-têtes comme dans le cas d'une
> redirection vers une autre ressource. C'est pourquoi Paul M. Jones préfère
> promouvoir le concept de *Répondeur* plutôt que de *Vue* ou de *Présentation*.

Dans l'application de blogging développée en TP, la couche `Vue` choisie repose
sur le moteur de rendu [Twig](http://twig.sensiolabs.org). Twig est aujourd'hui
une bibliothèque Open-Source développée et maintenue par SensioLabs. Ce moteur
de rendu offre une palette de fonctionnalités modernes telles que l'héritage de
gabarits, l'échappement automatique des variables, les filtres de formatage ou
bien les inclusions de vues.

```html+twig
{% extends "layout.twig" %}

{% block title 'Mon Blog' %}

{% block body %}
    <h1>Bienvenue sur mon blog</h1>

    {% for post in posts %}
        <h2>{{ post.title }}</h2>
        {{ include('blog/details.twig') }}
    {% endfor %}

{% endblock %}
```

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
publié dans les éditions *The Pragmatic Programmers*. Ces règles sont
universelles et agnostiques du langage de programmation employé. Il s'agit de
les mettre en pratique quel que soit le langage de programmation orienté objet
utilisé. Les neuf règles de la gymnastique des objets sont décrites ci-dessous.

### 1. Seulement un niveau d'indentation

Pour améliorer la lisibilité du code, sa testabilité, sa maintenabilité et sa
réutilisabilité, il est recommandé de ne pas dépasser plus d'un niveau
d'indentation par fonction. Le code qui se trouve dans chaque niveau
supplémentaire peut être déporté dans une nouvelle fonction. Le code ci-après
montre une fonction qui ne respecte pas encore cette règle.

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

Dans cet exemple, on constate aisément que la méthode `merge()` de la classe
`RouteCollection` dispose de deux niveaux d'indentation. La première indentation
devant le mot-clé `foreach` compte pour un niveau 0. Par conséquent, le code à
l'intérieur de la boucle doit être extrait et encapsulé dans une méthode dédiée
comme le montre le listing ci-dessous :

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
pour la simplifier et ainsi de suite jusqu'à obtenir un seul niveau
d'indentation partout. Cette règle de gymnastique encourage non seulement à
produire du code plus lisible mais c'est aussi un moyen de favoriser l'écriture
de toutes petites fonctions ayant qu'une seule responsabilité.

### 2. Éviter l'usage du mot-clé `else`

Cette règle est simple ! Il s'agit de bannir autant que faire se peut l'usage du
mot-clé `else` dans les structures conditionnelles interrompant l'exécution du
programme le plus tôt possible. Il existe trois manières de s'éviter l'usage du
mot-clé `else` dans une fonction.

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
à l'exécution du programme si ce collaborateur n'est pas nul avant d'appeler une
méthode sur celui-ci. Le snippet ci-après dévoile un exemple de collaborateur
facultatif.

```php
namespace Application;

use Framework\Http\Response;
use Framework\Routing\Exception\RouteNotFoundException;
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

        $vars = ['exception' => $exception];
        if ($exception instanceof RouteNotFoundException) {
            return $this->render('errors/404.twig', $vars, Response::HTTP_NOT_FOUND));
        }

        // ...
    }
}
```

Dans cet exemple, la fonction `handleException()` teste l'existence de l'objet
`logger`. S'il existe, alors sa méthode `critical()` est appelée pour
enregistrer une erreur critique dans les journaux d'erreur. Ici, c'est encore
acceptable car le nombre de conditions de ce type est limité. Néanmoins, l'idéal
consiste à rendre la dépendance obligatoire tel que l'illustre le code
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
Il est donc maintenant aisé de proposer différentes implémentations de celle-ci
selon l'environnement d'exécution de l'application. En environnement de
développement, une véritable implémentation de journal d'erreurs sera injectée
tandis que dans un mode de production, il s'agira de passer un objet « logger »
null tel que celui présenté ci-après.

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
« *Null Object* » élimine les conditions et rend ainsi le code plus lisible et
plus facilement testable. En effet, en éliminant la condition, il ne reste plus
qu'un seul et unique chemin d'exécution pour le code contre deux auparavant. Il
faut donc écrire un seul test unitaire pour couvrir le code au lieu de deux.
Grâce au patron « *Null Object* », le code s'utilise de la manière suivante :

```php

$twig = new \Twig_Environment(new \Twig_Loader_Filesystem(__DIR__.'/views'));
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
types orientés objets. Cette règle est toutefois largement discutable pour un
langage tel que PHP dont la philosophie même est d'offrir un typage dynamique.

Cependant, elle trouve du sens à partir du moment où l'on cherche à garantir une
cohérence et une forte cohésion des types du programme. Une classe `String` qui
encapsule une valeur scalaire de chaîne de caractères peut aussi offrir des
méthodes de manipulation de la chaîne (`toLower()`, `toUpper()`, `explode()`,
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
une classe simplifie leur usage par rapport à de simples variables scalaires et
fonctions PHP.

### 4. Maximum un opérateur d'objet par ligne

Cette règle stipule qu'il ne doit pas y avoir plus d'un opérateur d'objet par
ligne. En d'autres termes, le symbole `->` ne doit pas être utilisé plus d'une
fois sur une ligne.

Dit autrement, un objet doit communiquer avec son collaborateur le plus proche
et non les collaborateurs de ses collaborateurs. C'est ce que l'on appelle plus
communément la « Loi de Déméter » ou principe de connaissance minimale.

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
        $this->getCell($x, $y)->add($piece);
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
l'objet que l'on teste ainsi que ses collaborateurs les plus proches. Il n'y a
plus besoin de connaître les collaborateurs des collaborateurs.

> Contrairement à Java où le mot-clé `this` est facultatif, la variable `$this`
> est obligatoire en PHP pour référencer l'objet courant. Par conséquent, on
> accepte jusqu'à deux opérateurs d'instance par ligne au lieu d'un pour valider > cette quatrième règle de gymnastique des objets.

### 5. Ne pas abréger

Il est loin le temps où les espaces de mémoire vive et de stockage étaient
limités et se comptaient en quelques octets, voire kilo-octets. Inutile donc
d'abréger les noms des variables, des méthodes et des classes. Des noms bien
choisis pour les structures de données favorisent largement la lisibilité et la
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
d'instance par classe. Pourquoi deux et pas trois, cinq ou dix ? Moins il y en a
dans la classe et plus cela force le développeur à mieux encapsuler les autres
attributs dans d'autres classes. Ainsi chaque classe encapsule de manière
atomique un concept, une règle métier ou une responsabilité.

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

Il est en fait plutôt conseillé de créer une classe de collection spécifique
pour chaque type d'objet qu'elle encapsule. Ainsi, la classe de collection
spécifique contrôlera qu'elle reçoit bien des objets qu'elle supporte. De plus,
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
sur les collections. Ici, la classe `RouteCollection` traite de manière uniforme
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
sans aucun contrôle

Par exemple, que se passerait-il si le possesseur du compte bancaire souhaite
retirer 150 EUR bien que son compte bancaire ne dispose que d'un solde de 100
EUR ? Au niveau du programme, il est semblerait légitime de se demander si cette
opération est acceptable ou non en fonction de la politique de la banque qui
tient le compte. La banque autorise-t-elle une facilité de caisse pendant une
période de découvert ? Si oui, jusqu'à quel montant ? Le code actuel ne permet
pas de le prendre en compte.

La première instruction quant à elle semble montrer que le client du compte
bancaire vient de déposer 300 sur son compte. Hors, s'agit-il de 300 EUR, 300
USD ou bien 300 cacahuètes ? Le risque ici c'est de se retrouver avec un nouveau
solde de compte bancaire incohérent !

Enfin, la dernière instruction modifie la devise du compte bancaire. Par
conséquent, le solde du compte bancaire initialement en Euro passe subitement en
Dollars américains. Avec un taux de change défavorable, le client du compte
bancaire se retrouve lésé par sa banque ! Cette opération ne devrait d'ailleurs
pas être possible. En définitive, cette méthode ne devrait jamais être définie
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
        $deposit = Money::fromString($amount);

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
toute une palette de nouvelles méthodes pour comparer et modifier des valeurs
fiduciaires.

De plus, la portée du constructeur de la classe `BankAccount` a été transformée
en privé afin de forcer l'usage du constructeur statique `BankAccount::open()`.
Ce dernier construit des objets et initialise des objets `Money` à partir de
chaînes de caractères. Le véritable constructeur contrôle quant à lui que ces
paramètres sont cohérents pour compléter la construction de l'objet
`BankAccount`. En d'autres termes, la devise du solde initial du compte bancaire
et la devise du montant autorisé du découvert doivent être identiques.

Le choix de la méthode statique intitulée `open()`, c'est pour mieux
correspondre au vocabulaire du monde bancaire. En effet, ce n'est pas le client
qui « crée » un compte bancaire chez sa banque mais c'est elle qui lui « ouvre »
un nouveau compte.

```php
$account = BankAccount::open('500.00 EUR', '1000.00 EUR');
```

Une fois le compte bancaire créé, son possesseur peut alors soit déposer de
l'argent dessus ou bien en retirer. L'accesseur `getBalance()` et son mutateur
`setBalance()` associés ont disparu au profit de deux nouvelles méthodes plus
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

En programmation informatique, **SOLID** est un acronyme représentant cinq
principes de base pour la programmation orientée objet. Ces cinq principes sont
censés apporter une ligne directrice permettant le développement de logiciels
plus fiables, plus robustes, plus maintenables, plus extensibles et plus
testables.

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
  type `E` alors cette même méthode redéfinie dans une sous-classe `C'` qui
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
  ou interfaces) plutôt que des types concrets (classes concrètes) afin de
  diminuer les couplages et favoriser d'autres implémentations.

Objets de Valeur
----------------

Un « Objet de Valeur » (ou *Value Object* en anglais) est une instance qui
encapsule un certain nombre d'attributs et dont l'état global représente une
seule et unique valeur. Les objets de valeur répondent aussi aux trois règles
suivantes :

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

La classe `Point` ci-dessus encapsule les coordonnées cartésiennes d'un point
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
Deux objets qui renferment le même état sont donc égaux et permutables.

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
et puissant de manipuler et de tester unitairement des données atomiques.

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
* Ils encouragent des bonnes pratiques orientées objet (principe SOLID,
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

Les 23 patrons de conception sont répartis en trois familles :

* Les patrons **Créationnaux** encapsulent les protocoles de création et
  d'initialisation de certains objets. Les patrons *Singleton* et
  *Méthode de Fabrique* sont des exemples de patrons créationnels.

* Les patrons **Structuraux** organisent les classes d'un programme les unes par
  rapport aux autres. Ces patrons sont aussi ceux qui favorisent le découplage
  et la réutilisabilité du code en encourageant notamment la composition
  d'objets plutôt que l'héritage. Les principaux patrons structuraux sont entre
  autres le *Composite*, le *Décorateur*, la *Façade* et l'*Adaptateur*.

* Les patrons **Comportementaux** organisent la manière dont les objets
  collaborent et communiquent les uns avec les autres. Ils sont chargés de mieux
  distribuer les responsabilités des objets et expliquent le fonctionnement de
  certains algorithmes. Les patrons *Gabarit de Méthode*, *Itérateur*,
  *Médiateur* et *Stratégie* sont trois exemples de patrons comportementaux.

### Le Patron Singleton

> Ce patron vise à assurer qu'il n'y a toujours qu'une seule et unique instance
> d'une classe en fournissant une interface pour la manipuler. C'est un des
> patrons les plus simples et les plus couramment implémentés. L'objet qui ne
> doit exister qu'en une seule instance comporte une méthode pour obtenir cette
> unique instance et un mécanisme pour empêcher la création d'autres instances.
>
> *Wikipédia*.

Par exemple, dans un logiciel modélisant l'organisation politique de la
République française, une classe `PresidentDeLaRepublique` peut implémenter le
patron *Singleton*. Ainsi, ce patron garantira qu'il y a toujours qu'un seul et
unique Président de la République en fonction à l'instant T.

```php
PresidentDeLaRepublique::actuellementEnFonction('François Hollande');

$president1 = PresidentDeLaRepublique::getInstance();
$president2 = PresidentDeLaRepublique::getInstance();

var_dump($president1 === $president2); // true
```
Dans ce snippet, la comparaison avec le symbole `===` vérifie que les objets
`$president1` et `$president2` encapsulent non seulement le même état interne
et qu'il s'agit aussi de deux références vers la même instance en mémoire.

### Le Patron Méthode de Fabrique

> La Fabrique (*Méthode de Fabrique*) est un patron de conception créationnel
> utilisé en programmation orientée objet. Elle permet d'instancier des objets
> dont le type est dérivé d'un type abstrait. La classe exacte de l'objet n'est
> donc pas connue par l'appelant.
>
> *Wikipédia*.

Il existe plusieurs types de fabriques :

* La *fabrique statique* qui utilise une fonction statique d'une classe pour
  créer un objet. Dans le projet du mini-framework, un exemple de fabrique
  statique est la méthode statique `createFromMessage()` des classes `Request`
  et `Response`.

* La *fabrique simple* est un idiome de programmation qui vise à encapsuler la
  création d'un objet à l'intérieur d'une méthode d'instance. Par exemple, la
  méthode `getService()` de la classe `ServiceLocator` est un exemple
  d'implémentation d'une *fabrique simple* qui centralise la création d'objets.

* La *méthode de fabrique* est la vraie définition du patron *Fabrique*. Il
  s'agit de définir dans un type abstrait (classe abstraite ou interface) une
  méthode publique dédiée à la création et à l'initialisation d'un objet. Dans
  le cas d'une classe abstraite, celle-ci pré-implémente cette méthode publique
  de fabrique. Cette méthode publique délègue ensuite une partie du processus à
  une méthode protégée abstraite implémentée par chaque sous-classe de fabrique
  concrète. Chaque fabrique concrète est responsable de produire un et un seul
  type d'objet. Retrouvez le patron Fabrique dans les slides de présentation de
  conférence [ici](https://speakerdeck.com/hhamon/design-patterns-the-practical-approach-in-php).

### Le Patron Composite

> Ce patron permet de composer une hiérarchie d'objets, et de manipuler de la
> même manière un élément unique, une branche, ou l'ensemble de l'arbre. Il
> permet en particulier de créer des objets complexes en reliant différents
> objets selon une structure en arbre.
> 
> Ce patron impose que les différents objets aient une même interface, ce qui
> rend uniformes les opérations de manipulation de la structure. Par exemple
> dans un traitement de texte, les mots sont placés dans des paragraphes
> disposés dans des colonnes, elles mêmes dans des pages. Pour manipuler
> l'ensemble, une classe composite implémente une interface. Cette dernière est
> héritée par les objets qui représentent les textes, les paragraphes, les
> colonnes et les pages.
>
> *Wikipédia*.

Dans le projet de mini-framework, la classe `CompositeFileLoader` du composant
`Routing` est une implémentation du patron `Composite`. Elle permet d'imbriquer
un ou plusieurs objets de même type et de les utiliser comme s'ils étaient qu'un
seul et unique objet.

### Le Patron Proxy

> Un proxy est une classe qui se substitue à une autre classe. Par convention et
> simplicité, le proxy implémente la même interface que la classe à laquelle il
> se substitue. L'utilisation de ce proxy ajoute une indirection à l'utilisation
> de la classe à substituer.
>
> *Wikipédia*.

Dans l'exemple du mini-framework, l'objet `Kernel` est un substitut (*proxy*)
à l'objet `HttpKernel`. Ces deux classes implémente une interface commune :
`KernelInterface`. La méthode `handle()` de l'objet `Kernel` agit comme un proxy
pour la même de même nom de l'objet `HttpKernel` imbriqué.

### Le Patron Décorateur

> Ce patron permet d'attacher dynamiquement des responsabilités à un objet. Une
> alternative à l'héritage en favorisant la composition d'objets. Ce patron est
> inspiré des poupées russes.
> 
> Un objet peut être caché à l'intérieur d'un autre objet décorateur qui lui
> rajoutera des fonctionnalités, l'ensemble peut être décoré avec un autre objet > qui lui ajoute des fonctionnalités et ainsi de suite. Cette technique
> nécessite que l'objet décoré et ses décorateurs implémentent le même contrat
> publique, qui est typiquement définie par une classe abstraite ou une
> interface.
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
> seraient incompatibles.
> 
> Il est utilisé dans le cas où un programme se sert d'une bibliothèque de
> classe, et que, à la suite d'une mise à jour de la bibliothèque, cette
> dernière ne correspond plus à l'utilisation qui en est faite, parce que son
> interface a changé. Un objet adapteur expose alors l'ancienne interface en
> utilisant les fonctionnalités de la nouvelle.
>
> *Wikipédia*.

Dans le projet du mini-framework, la classe `TwigRendererAdapter` est un exemple
d'implémentation du patron *Adaptateur* qui adapte l'interface incompatible de
la classe `Twig_Environment` avec celle attendue par le framework :
`ResponseRendererInterface`.

### Le Patron Gabarit de Méthode

> Ce patron définit la structure générale d'un algorithme en déléguant certains
> passages afin de permettre à des sous-classes de modifier l'algorithme en
> conservant sa structure générale. C'est un des patrons les plus simples et les
> plus couramment utilisés en programmation orientée objet. Il est utilisé
> lorsqu'il y a plusieurs implémentations possibles d'un calcul.
>
> Une classe d'exemple (ou *template*) comporte des méthodes d'exemple, qui,
> utilisées ensemble, implémentent un algorithme par défaut. Certaines méthodes
> peuvent être vides ou abstraites. Les sous-classes de la classe template
> peuvent remplacer tout ou partie de certaines méthodes et ainsi créer un
> algorithme dérivé.

L'implémentation de ce patron se traduit par une classe qui définit une méthode
publique ou protégée, et déclarée comme *finale*. De ce fait, la méthode ne peut
plus être redéfinie par les sous-classes, ce qui garantit que son algorithme
sera toujours exécuté de la même manière. En revanche, certaines étapes de cet
algorithme peuvent être redéfinies ou implémentées grâce à des méthodes
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
> indépendamment de l'application qui les utilise. Il comporte trois
> intervenants : le contexte, la stratégie et les implémentations.
>
> La stratégie est l'interface commune aux différentes implémentations -
> typiquement une classe abstraite ou une interface. Le contexte est l'objet qui
> va associer un algorithme avec un processus.
>
> *Wikipédia*.

Dans le projet du mini-framework, l'interface `FileLoaderInterface` du composant
`Routing` agit comme une stratégie pour l'objet `Router` qui l'utilise. En
proposant plusieurs implémentations de l'interface, donc plusieurs stratégies,
le routeur peut ainsi charger la configuration des routes depuis un fichier PHP
ou bien un fichier XML. Pour supporter un format YAML, il suffirait alors de
créer une nouvelle classe `YamlFileLoader`.

### Le Patron Itérateur

> Ce patron permet d'accéder séquentiellement aux éléments d'un ensemble sans
> connaître les détails techniques du fonctionnement de l'ensemble. C'est un des
> patrons les plus simples et les plus fréquents. Selon la spécification
> originale, il consiste en une interface qui fournit les méthodes `next()` et
> `current()`.
>
> *Wikipédia*.

Les itérateurs sont particulièrement adaptés pour les objets de type collection.
Un objet qui collectionne une série d'objets en son sein peut implémenter le
patron « *Itérateur* » afin de simplifier les opérations d'accès aux objets
collectionnés. L'autre avantage d'un objet itérateur c'est sa capacité à être
utilisé facilement comme argument de la structure `foreach` de PHP.

Dans le projet du mini-framework, la classe `RouteCollection` du composant
`Routing` est un exemple d'implémentation du patron *Itérateur* en PHP grâce à
l'interface native `Iterator` de PHP.

### Le Patron Médiateur

> Dans ce patron, il y a un objet qui définit comment plusieurs objets
> communiquent entre eux en évitant à chacun de faire référence à ses
> interlocuteurs. Ce patron est utilisé quand il y a un nombre non négligeable
> de composants et de relations entre les composants.
> 
> Par exemple dans un réseau de cinq composants, il peut y avoir jusqu'à vingt
> relations (chaque composant vers quatre autres). Un composant médiateur est
> placé au milieu du réseau et le nombre de relations est diminué : chaque
> composant est relié uniquement au médiateur. Le médiateur joue un rôle
> similaire à un sujet dans le patron Observateur et sert d'intermédiaire pour
> assurer les communications entre les objets.
>
> *Wikipédia*.

Dans le projet du mini-framework, pour diminuer les nombreux couplages entre
l'objet `HttpKernel` et ses collaborateurs, ce dernier utilise un médiateur. Il
s'agit de l'objet `EventManager` qui propage des événements et notifie les
écouteurs associés. Quand le noyau HTTP propage un signal dans le framework, des
écouteurs se déclenchent successivement à son écoute, puis le traitent.

Journalisation des Erreurs avec Monolog
---------------------------------------

Pour améliorer le suivi d'erreurs dans l'application de Blog, un gestionnaire
de journalisation des erreurs, la classe `Application\ErrorHandler`, a été
développé. Cette classe est en fait un écouteur connecté à l'événement
`kernel.exception` du noyau HTTP, et qui reçoit dans son constructeur un objet
de journalisation des erreurs.

```php
# bootstrap.php
$dic->register('event_manager', function (ServiceLocator $dic) {
    $manager = new EventManager();
    // ...
    $manager->addEventListener(KernelEvents::EXCEPTION, [new LoggerHandler($dic->getService('logger')), 'onKernelException']);

    return $manager;
});
```

L'objet de journalisation des erreurs est une instance de la classe
`Monolog\Logger`. Cette classe provient de la bibliothèque de code Open-Source
Monolog développée et maintenue par Jordi Boggiano, le co-créateur de Composer.
Monolog est aussi une implémentation concrète de l'interface standard PSR-3 du
PHP-FIG, ce qui le rend compatible avec la plupart des frameworks modernes tels
que Symfony, Laravel et Zend Framework. Dans l'application du Blog, l'objet de
journalisation des erreurs est défini comme service global dans le registre des
services.

```php
# bootstrap.php
$dic->setParameter('app.debug', true);
$dic->setParameter('logger.log_file', __DIR__.'/app/cache/app.log');

$dic->register('logger', function (ServiceLocator $dic) {
    $level = $dic->getParameter('app.debug') ? Logger::WARNING : Logger::CRITICAL;

    $logger = new \Monolog\Logger('blog');
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($dic->getParameter('logger.log_file'), $level));

    return $logger;
});
```

L'objet « *Logger* » délègue l'enregistrement des erreurs dans un fichier sur le
disque grâce à un collaborateur : l'objet `StreamHandler`. Monolog fournit
d'autres implémentations concrètes de *handlers* d'erreur afin de stocker les
erreurs dans Redis, Memcached, syslog, Raven, base de données, etc. Le fichier
des erreurs est quant à lui configuré dans un paramètre global du registre :
`logger.log_file`.

Enfin, le journal d'erreurs Monolog enregistrera une nouvelle entrée dans le
journal en fonction de la sévérité de celle-ci. Si le mode debug (`app.debug`)
est configuré à la valeur `true`, alors toutes les erreurs de sévérité
supérieures ou égales au niveau d'avertissement (*warning*) seront enregistrées.
Dans le cas contraire, seules les erreurs critiques seront sauvegardées, les
autres, de sévérités inférieures, seront ignorées.

Gestion Sémantique de Version
-----------------------------

Le versionnage sémantique est un ensemble de bonnes pratiques qui définissent
comment les numéros des versions d'un logiciel doivent être incrémentés en
fonction des types de patches intégrés au code :

1. correctif de bogues ou de faille de sécurité,
2. ajout d'une nouvelle fonctionnalité,
3. ajout d'un changement qui brise la compatibilité avec les versions
   précédentes du logiciel.

En gestion sémantique de version, chaque version est exprimée avec un triplet
de valeurs de type `x.y.z`. Dans cette notation, les lettres `x`, `y` et `z`
représentent respectivement les numéros de version *majeure*, *mineure* et
*patch*. Par exemple : `2.6.97`.

L'incrémentation de l'un ou de l'autre de ces trois numéros quand une nouvelle
version du logiciel est publiée est soumis au respect des règles suivantes :

1. le numéro de version MAJEURE quand il y a des changements
   rétro-incompatibles (cassure de compatibilité avec les versions antérieures),
2. le numéro de version MINEURE quand il y a des changements
   rétro-compatibles (ajout de nouvelles fonctionnalités),
3. le numéro de version de PATCH quand il y a des corrections d'anomalies
   rétro-compatibles (correctif d'un bogue ou d'une faille de sécurité).

Les logiciels Open-Source modernes comme Symfony suivent à la lettre cette bonne
pratique pour gérer leurs versions. Cela assure aux utilisateurs du framework
des mises à jour du code de leur application sans risque de casser la
compatibilité dans le cas où les mises à jour concernent les versions mineures
et patch. Plus d'informations sur le versionnage sémantique
[ici](http://semver.org/lang/fr/).

Concepts abordés en cours
-------------------------

Le tableau récapitulatif ci-dessous résume tous les concepts abordés en cours
et sur lesquels porteront l'évaluation écrite du mardi 12 janvier 2015.

| Thématique        | Concepts abordés en cours                                                         |
|-------------------|-----------------------------------------------------------------------------------|
| Standards         | Protocole HTTP, PHP PSR, PHP FIG                                                  |
| P. Orientée Objet | classes, méthodes, interfaces, abstract, final, static, principe SOLID            |
| Tests unitaires   | PHPUnit, TDD, Code Coverage                                                       |
| Outils            | Composer, PHPUnit, Twig, Symfony, XDebug, Git                                     |
| Design Patterns   | Template Method, Adapter, Factory Method, Composite, Strategy, Mediator, Observer |
| Architecture      | MVC, Service Locator, Dependency Injection, Lazy Loading                          |
| Base de données   | MySQL, Innodb, SQL, transaction, requêtes préparées                               |
| Sécurité          | XSS, DoS, SQL injections, chercher des failles                                    |
| Performances      | Opcode Cache (OPCache, APC, XCache...), Lazy Loading                              |
| Symfony           | Communauté, philosophie, licence, architecture, configuration, bundles, composants|
| Doctrine          | Mapping des entités, persistence, requêtes DQL, transactions, configuration       |

Modalités d'évaluation
----------------------

Pour l'évaluation écrite du mardi 12 janvier 2015, vous serez évalués sur les
concepts abordés en cours (voir section plus haut). L'évaluation ne portera pas
sur l'écriture de code PHP mais sur l'analyse de code PHP ainsi que sur des
concepts théoriques et fondamentaux (classes, interfaces, design patterns, tests
unitaires, injection de dépendances, composer, phpunit, sécurité...).
