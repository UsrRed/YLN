#!/bin/bash

if [ $# -ne 0 ]; then
    echo "Ce script ne prend pas de paramètre, il doit être exécuté sans paramètres"
    exit 1
fi

#Pour installer podman en fonction du SE de l'utilisateur
if [ -x "$(command -v apt-get)" ]; then
    sudo apt-get update 
    sudo apt-get install -y podman 
elif [ -x "$(command -v dnf)" ]; then
    sudo dnf install -y podman 
elif [ -x "$(command -v yum)" ]; then
    sudo yum install -y podman 
else
    echo "Système d'exploitation inconnu"
    exit 1
fi

#Pour installer podman-compose
if [ -x "$(command -v apt-get)" ]; then
    sudo apt-get install -y podman-compose 
elif [ -x "$(command -v dnf)" ]; then
    sudo dnf install -y podman-compose 
elif [ -x "$(command -v yum)" ]; then
    sudo yum install -y podman-compose  
else
    echo "Système d'exploitation non pris en charge."
    exit 1
fi

#Pour récupérer des images avec podman
if podman images | grep 'docker.io/library/nginx.*alpine' && podman images | grep 'docker.io/library/php.*8.2-fpm' && podman images | grep 'docker.io/library/mysql.*latest' && podman images | grep 'docker.io/library/haproxy.*alpine'; then
    echo "Les images sont déjà installées"
else
    echo "Pulling des images pour le lancement des conteneurs.."
    podman pull docker.io/library/mysql:latest
    podman pull docker.io/library/php:8.2-fpm
    podman pull docker.io/library/nginx:alpine
    podman pull docker.io/library/haproxy:alpine
    #podman pull docker.io/balabit/syslog-ng:latest
fi

echo "Voulez-vous rajouter un syslog-ng sur l'application" 
#
#
#
#
#RAJOUTERRRRRRR#############################################################################################################################################
#


#On démarre les conteneurs avec podman-compose
podman-compose -f docker-compose.yaml up -d

#Et l'adresse IP du conteneur Haproxy pour accéder à l'application
AdresseIP=$(podman inspect haproxy | grep -oP '"IPAddress": "\K[^"]+') 
echo "--> L'adresse IP de l'application sur laquelle se rendre est https://$AdresseIP:8443"
echo "--> Vous pouvez faire un CTRL + [clique gauche] sur l'URL ci-dessus."




