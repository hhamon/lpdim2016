# Projet LP DIM 2016

Ceci est le dépôt de code pour le projet PHP développé en cours de
« Module Techno Web » du 4 janvier 2016 au mardi 12 janvier 2016.

Au cours de ce projet, nous travaillerons sur PHP, Git et l'architecture
logicielle.

Bien sûr, à l'issue du cours, il y aura une interrogation écrite !

## Configuration Globale de Git

Dans votre `HOME`, vous pouvez créer le fichier `.gitconfig` suivant en adaptant
bien sûr les paramètres Github.

```git
; $HOME/.gitconfig
[user]
  name = Prenom Nom
  email = vous@votre-email.com

[github]
  user = votre-identifiant-github

[alias]
  st  = status
  ci  = commit
  co  = checkout
  fp  = format-patch
  lg  = log --graph --pretty=tformat:'%Cred%h%Creset -%C(yellow)%d%Creset%s %Cgreen(%an %cr)%Creset' --abbrev-commit --date=relative
  lga = log --graph --pretty=tformat:'%Cred%h%Creset -%C(yellow)%d%Creset%s %Cgreen(%an %cr)%Creset' --abbrev-commit --date=relative --all

[color]
  diff = auto
  status = auto
  branch = auto
  ui = true

[core]
  pager = cat
  editor = vim
  autocrlf = false
  excludesfile = ~/.gitignore
  whitespace = space-before-tab,tab-in-indent,trailing-space,tabwidth=4

[merge]
  tool = vimdiff

[push]
  default = simple
```
