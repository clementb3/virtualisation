# Projet de base pour Symfony 6 #

## Pour utiliser ce projet : ##

Faire un *fork* sur votre Gitlab pour démarrer votre application. Pour rendre le projet indépendant : éditer
le projet puis `Remove Fork RelationShip` et enfin renommer le projet (`BaseSymfony6` n'est pas très
parlant).

Ceci fait vous pouvez cloner ce projet dans votre compte pour commencer à travailler :
```bash
git clone git@gitlab.iut-valence.fr:monLogin/MonBeauProjet.git
```
ou encore :
```bash
git clone https://gitlab.iut-valence.fr/monLogin/MonBeauProjet.git
```

Ceci fait vous devez installer les modules Symfony (qui ne sont pas intégrés au projet Git) avec :
```bash
composer install
```

Ensuite vous pouvez lancer le serveur de développement avec `symfony server:start`.

Vous pouvez maintenant voir la page d'accueil avec des liens vers les autres pages (définies pour
l'exemple) :
[http://localhost:8000](http://localhost:8000)

- [http://localhost:8000/bonjour](http://localhost:8000/bonjour) et
  [http://localhost:8000/bonjour/ALbert](http://localhost:8000/bonjour/ALbert) qui disent bonjour avec la
  date en français ;
- [http://localhost:8000/icons](http://localhost:8000/icons) : exemples d'icônes Bootstrap ;
- [http://localhost:8000/message/Albert](http://localhost:8000/message/Albert) : initialise des *messages
  flash* qui sont affichés après renvoi sur la page `bonjour`.

### Vous êtes prêts à travailler avec Symfony 6 ! ###

# Que contient ce projet ? #

Symfony 6 propose deux versions à installer : soit une version très légère, convenant à la création d'API de
type REST, soit une version plus complète pour la création de sites Web. C'est cette deuxième version que
nous allons utiliser.

On peut installer Symfony soit avec l'utilitaire `symfony` (installé sur les Linux de l'IUT) soit avec
l'utilitaire de gestion de paquets PHP `composer` (également installé à l'IUT).

Avec `symfony` : 
```bash
symfony new BaseSymfony6 --webapp
```

Avec `composer` :
```bash
composer create-project symfony/skeleton BaseSymfony6
cd BaseSymfony6
composer require webapp
```

La différence est que `symfony` configure le projet pour `git` (avec un `git init`) et ajoute le serveur web de
développement (qu'on peut lancer avec `symfony server:start` et arrêter avec `symfony server:stop`).
On préférera donc en général utiliser l'application `symfony` pour créer de nouveaux projets.

La configuration de cette application Web classique contient :
- **Vues :** `Twig` (gestionnaire de templates), `asset` (fichiers css, js, img), `form` (formulaires) ;
- **Contrôleurs :** `security-bundle` (pour les utilisateurs et les droits d'accès), `form` ;
- **Modèles :** `doctrine/orm` (ORM : *Object Repository Manager*, pour l'accès à la base de données), `maker-bundle`
  (outils d'aide à la création de code) ;
- **Outils bien pratiques :** `web-profiler-bundle` (outils de débogage), un serveur web intégré, pour le
  développement, `flex` (gestion simplifiée des paquets composer).

Nous avons ajouté à l'installation de base le paquet `twig/intl-extra` :
```bash
composer require twig/intl-extra
```
Ce paquet permet d'afficher les dates en français dans les vues Twig.

Nous avons enlevé le *bundle* `sensio/framework-extra-bundle` annoncé comme obsolète par `composer`
mais quand même installé par `symfony`.

Enfin nous avons créé un fichier `.php-version` contenant `8.1` pour forcer Symfony à utiliser la version
8.1 de PHP. Notez que l'intranet utilise la version 7.4 mais le serveur web de `gigondas` est en
8.1.

# Quelques ressources pour les vues #

Pour démarrer la création du design de votre application, vous trouverez dans `public` :

- `js` : répertoire contenant le code JavaScript pour Bootstrap-5.2 (la version `bundle` contient `popper`) ;
- `css` : feuilles de style de Bootstrap et Bootstrap Icons, `base.css` pour les adaptations locales ;
- `img` : le logo de l'IUT et l'icône de l'UGA.

Ces ressources sont importées dans le fichier `templates/base.html.twig`.

