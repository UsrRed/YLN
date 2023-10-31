#Ce fichier permet de savoir qu'elle est l'adresse IP de l'application.
#
#--> Il faut lancer ce fichier après le lancement de l'application, soit après le lancement du docker-compose

#!/bin/sh

if [ $# -ne 0 ]; then #Si le nombre n'arguments passés n'est pas de 0, message d'information
    echo "Ce scipt ne prend pas de paramètre, il doit être exécuté comme ceci : './IpMonSite.sh' ou 'bash IpMonSite.sh"
    exit 1
fi

AdresseIP=$(podman inspect nginx | grep -oP '"IPAddress": "[^"]+' | cut -d ':' -f 2) #Tout sauf un "" car il y a un attribut IpAddress="" par moment

if podman ps | grep "nginx" && podman ps | grep "php" && podman ps | grep "mysql" #On regarde si les conteneurs sont lancés avec podman ps en greppant le nom des trois conteneurs
then
    echo ""
    echo "L'adresse IP de l'application sur laquelle se rendre est $AdresseIP\""
else
    echo "Le fichier docker-compose n'est pas ou ne s'est pas lancé"
    echo ""
    echo "Lancement du fichier docker-compose ..."
    echo ""
    podman-compose -f ./docker-compose.yaml up -d #Si la sortie podman ps n'inclut pas les trois conteneurs, on lance le .yaml
    sleep 3
    AdresseIP=$(podman inspect nginx | grep -oP '"IPAddress": "[^"]+' | cut -d ':' -f 2) #Même regex, tout sauf un ""
    echo ""
    echo "L'adresse IP de l'application sur laquelle se rendre est $AdresseIP\""
fi
