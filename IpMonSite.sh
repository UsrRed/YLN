#Ce fichier permet de savoir qu'elle est l'adresse IP de l'application.
#
#--> Il faut lancer ce fichier après le lancement de l'application, soit après le lancement du docker-compose

#!/bin/sh

if [ $# -ne 0 ]; then #Si le nombre n'arguments passés n'est pas de 0, message d'information
    echo "Ce scipt ne prend pas de paramètre, il doit être exécuté comme ceci : './IpMonSite.sh' ou 'bash IpMonSite.sh"
    exit 1
fi

#Normalement l'adresse IP du haproxy ne change, c'est 172.18.0.4, mais on s'assure quand meme

AdresseIP=$(podman inspect haproxy | grep -oP '"IPAddress": "\K[^"]+') #Tout sauf un "" car il y a un attribut IpAddress="" par moment

#AdresseIP=$(ip a | grep podman* | grep inet | grep -oP '(?<=inet )[\d.]+') #espace après inet pour ne prendre que l'adresse IP après inet, donc pas le brd et on regarde des chiffres ou des points (\d.)



if podman ps | grep "nginx" && podman ps | grep "php" && podman ps | grep "mysql" && podman ps | grep "haproxy" #On regarde si les conteneurs sont lancés avec podman ps en greppant le nom des trois conteneurs
then
    echo ""
    echo "L'adresse IP de l'application sur laquelle se rendre est https://$AdresseIP:8443";
else
    echo "Le fichier docker-compose n'est pas ou ne s'est pas lancé"
    echo ""
    echo "Lancement du fichier docker-compose ..."
    echo ""
    podman-compose -f ./docker-compose.yaml up -d #Si la sortie podman ps n'inclut pas les trois conteneurs, on lance le .yaml
    sleep 3
    #AdresseIP=$(ip a | grep podman* | grep inet | grep -oP '(?<=inet )[\d.]+')
    echo ""
    echo "L'adresse IP de l'application sur laquelle se rendre est https://$AdresseIP:8443";
fi
