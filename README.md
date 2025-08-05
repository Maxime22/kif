# KIF
## Application
1) Lancer le serveur : 
```
make start
```

2) Lancer le container de datatabase pour les actions en base de donnée :
```
make up
```

## Mailer
1) Changer la configuration de messenger pour décommenter sync et mettre toutes les classes en sync
2) Installer l'exécutable de mailpit via https://github.com/axllent/mailpit?tab=readme-ov-file
3) Lancer la commande `mailpit` (avoir installé la version Linux dans WSL et pas la version Windows dans powershell, ne pas hésiter à relancer Symfony pour que les modifications prennent effet)
4) Se rendre sur localhost:8025