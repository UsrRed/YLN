#!/bin/bash

# Vérification si HAProxy est déjà installé

if ! [ -x "$(command -v haproxy)" ]; then
    echo "HaProxy n'est pas installé. Installation en cours..."
    apt-get update
    apt-get install -y haproxy
else
    echo "HaProxy est déjà installé."
fi
echo "#############################################"
#Vérification si HAProxy est déjà lancé
if systemctl is-active --quiet haproxy; then
    echo "HAProxy est déjà en cours d'exécution"
else
    echo "Démarrage de HAProxy..."
    systemctl start haproxy
fi
echo "############################################"
#Vérification des images si elles sont bien présentes
if podman images | grep "nginx" && podman images | grep "php" && podman ps | grep "mysql"; then
    echo "Images des conteneurs présentes sur l'OS physique"
else
    echo "Pulling des images pour le lancement des conteneurs.."
    bash ./info/ScriptImage
fi
echo "###########################################"
# Vérification si les conteneurs sont déjà en cours d'exécution
if podman ps | grep "nginx" && podman ps | grep "php" && podman ps | grep "mysql"; then
    echo "Les conteneurs sont déjà en cours d'exécution."
else
    echo "Démarrage des conteneurs..."
    podman-compose -f docker-compose.yaml up -d
fi

# Exécution du script haproxy_config.sh
bash ./loadbalancing/haproxy_config.sh

# Démarrage de HAProxy avec votre configuration
bash haproxy -f loadbalancing/haproxy.cfg &
echo "###########################################"
# Exécution du script IpMonSite
bash ./info/IpMonSite.sh

