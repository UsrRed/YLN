#!/bin/bash

# Vérification si HAProxy est déjà installé

echo "############################################"
#Vérification des images si elles sont bien présentes
if podman images | grep "docker.io/library/nginx" && podman images | grep "docker.io/library/php" && podman images | grep "docker.io/library/mysql" && podman images | grep "docker.io/library/haproxy"; then
    echo "Images des conteneurs présentes sur l'OS physique"
else
    echo "Pulling des images pour le lancement des conteneurs.."
    bash ScriptImage.sh
fi
echo "###########################################"
# Vérification si les conteneurs sont déjà en cours d'exécution
if podman ps | grep "nginx" && podman ps | grep "php" && podman ps | grep "mysql" && podman ps | grep "haproxy"; then
    echo "Les conteneurs sont déjà en cours d'exécution."
else
    echo "Démarrage des conteneurs..."
    podman-compose -f docker-compose.yaml up -d
fi

echo "###########################################"
# Pour voir l'adresse IP de l'application
bash IpMonSite.sh
