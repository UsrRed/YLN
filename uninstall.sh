#!/bin/bash

if [ $# -ne 0 ]; then
	echo "Ce script ne prend pas de paramètre, il doit être exécuté sans paramètres"
	exit 1
fi

echo "__   ___     _   _   _   _ _   _ ___ _   _ ____ _____  _    _     _     "
echo "\ \ / / |   | \ | | | | | | \ | |_ _| \ | / ___|_   _|/ \  | |   | |    "
echo " \ V /| |   |  \| | | | | |  \| || ||  \| \___ \ | | / _ \ | |   | |    "
echo "  | | | |___| |\  | | |_| | |\  || || |\  |___) || |/ ___ \| |___| |___ "
echo "  |_| |_____|_| \_|  \___/|_| \_|___|_| \_|____/ |_/_/   \_\_____|_____|"

echo ""

choix_utilisateur=$(cat recuperation/choix.txt)

if [ "$choix_utilisateur" == "sans" ]; then
	#Application sans gestion de logs

	sudo podman-compose -f docker-compose-sans.yaml down
	sudo podman stop netdata && sudo podman rm -f netdata
	sudo sed -i '/yln/d' /etc/hosts
	
	echo "Voulez-vous supprimer les images des conteneurs utilisées pour l'applicatino YLN ? (O/N)"
	read reponse_image_sans
	if [ "$reponse_image_sans" == "O" ] || [ "$reponse_image_sans" == "o" ]; then
    		
		#podman rmi docker.io/library/mysql:latest
		podman rmi -f docker.io/library/php:8.2-fpm
		podman rmi -f localhost/sae501-502-theotime-martel_php
		podman rmi -f docker.io/library/nginx:alpine
		podman rmi -f docker.io/library/haproxy:alpine
		podman rmi -f localhost/sae501-502-theotime-martel_haproxy
		podman rmi -f docker.io/library/mysql:latest
		podman rmi -f docker.io/portainer/portainer-ce:latest
		podman rmi -f docker.io/library/netdata/netdata
		
		echo "Images supprimées avec succès."

	elif [ "$reponse_image_sans" == "N" ] || [ "$reponse_image_sans" == "n" ]; then

		echo "Aucune image n'a été supprimée."

	else

		echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

	fi

	echo "Voulez-vous supprimer les volumes qui ont servi à la sauvegarde des données de l'application YLN ? (O/N)"
	read volume_sans

	if [ "$volume_sans" == "O" ] || [ "$volume_sans" == "o" ]; then

        	sudo podman volume rm sae501-502-theotime-martel_db_data
		sudo podman volume rm sae501-502-theotime-martel_db_data_esclave
		sudo podman volume rm sae501-502-theotime-martel_portainer_data

		echo "Volumes supprimés avec succès"

	elif [ "$volume_sans" == "N" ] || [ "$volume_sans" == "n" ]; then

		echo "Volumes non supprimés"

	else

        	echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

	fi

	echo "Voulez-vous supprimer le réseau créé par le docker-compose.yaml qui a servi pour l'application YLN (O/N)"
        read reseau_sans

        if [ "$reseau_sans" == "O" ] || [ "$reseau_sans" == "o" ]; then

                sudo podman network rm sae501-502-theotime-martel_sae

		echo "Réseau docker-compose supprimé avec succès"

        elif [ "$reseau_sans" == "N" ] || [ "$reseau_sans" == "n" ]; then

                echo "Réseau du docker-compose.yaml non supprimé"

        else

                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

        fi

elif [ "$choix_utilisateur" == "syslog" ]; then
	#Application avec gestion des logs avec le conteneur syslog-ng
	
	sudo podman-compose -f docker-compose-syslog.yaml down
	sudo podman stop netdata && sudo podman rm -f netdata
	sudo sed -i '/yln/d' /etc/hosts

        echo "Voulez-vous supprimer les images des conteneurs utilisées pour l'applicatino YLN ? (O/N)"
        read reponse_image_syslog
        if [ "$reponse_image_syslog" == "O" ] || [ "$reponse_image_syslog" == "o" ]; then
                
		#podman rmi docker.io/library/mysql:latest
                podman rmi -f docker.io/library/php:8.2-fpm
                podman rmi -f localhost/sae501-502-theotime-martel_php
		podman rmi -f docker.io/library/nginx:alpine
                podman rmi -f docker.io/library/haproxy:alpine
                podman rmi -f localhost/sae501-502-theotime-martel_haproxy
		podman rmi -f docker.io/library/mysql:latest
		podman rmi -f docker.io/portainer/portainer-ce:latest
                podman rmi -f docker.io/netdata/netdata
		podman rmi -f balabit/syslog-ng:latest

                echo "Images supprimées avec succès."

        elif [ "$reponse_image_syslog" == "N" ] || [ "$reponse_image_syslog" == "n" ]; then

                echo "Aucune image n'a été supprimée."

        else

                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

        fi

	echo "Voulez-vous supprimer les volumes qui ont servi à la sauvegarde des données de l'application YLN ? (O/N)"
        read volume_syslog

        if [ "$volume_syslog" == "O" ] || [ "$volume_syslog" == "o" ]; then

                sudo podman volume rm sae501-502-theotime-martel_db_data
                sudo podman volume rm sae501-502-theotime-martel_db_data_esclave
                sudo podman volume rm sae501-502-theotime-martel_portainer_data
		sudo podman volume rm sae501-502-theotime-martel_syslog-ng_data

		echo "Volumes supprimés avec succès"

        elif [ "$volume_syslog" == "N" ] || [ "$volume_syslog" == "n" ]; then

                echo "Volumes non supprimés"

        else

                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

        fi

	echo "Voulez-vous supprimer le réseau créé par le docker-compose.yaml qui a servi pour l'application YLN (O/N)"
        read reseau_syslog

        if [ "$reseau_syslog" == "O" ] || [ "$reseau_syslog" == "o" ]; then

                sudo podman network rm sae501-502-theotime-martel_sae

		echo "Réseau docker-compose supprimé avec succès"

        elif [ "$reseau_syslog" == "N" ] || [ "$reseau_syslog" == "n" ]; then

                echo "Réseau du docker-compose.yaml non supprimé"

        else

                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

        fi

	sudo rm -rf /etc/containers/registries.conf
	sudo mv recuperation/registries.conf /etc/containers/registries.conf
	
	if [ -x "$(command -v apt-get)" ]; then
		sudo apt-get remove -y rsyslog
	elif [ -x "$(command -v dnf)" ]; then
		sudo dnf remove -y rsyslog
	elif [ -x "$(command -v yum)" ]; then
		sudo yum remove -y rsyslog
	else
		echo "Système d'exploitation inconnu pour la suppression du rsyslog"
		exit 1
	fi	

elif [ "$choix_utilisateur" == "grafana" ]; then
	#Application avec gestion des logs avec la stack PLG
	
	sudo podman-compose -f docker-compose-grafana.yaml down
	sudo podman stop netdata && sudo podman rm -f netdata
	sudo sed -i '/yln/d' /etc/hosts

        echo "Voulez-vous supprimer les images des conteneurs utilisées pour l'applicatino YLN ? (O/N)"
        read reponse_image_plg
        if [ "$reponse_image_plg" == "O" ] || [ "$reponse_image_plg" == "o" ]; then
                
		#podman rmi docker.io/library/mysql:latest
                podman rmi -f docker.io/library/php:8.2-fpm
                podman rmi -f localhost/sae501-502-theotime-martel_php
                podman rmi -f docker.io/library/nginx:alpine
                podman rmi -f docker.io/library/haproxy:alpine
                podman rmi -f localhost/sae501-502-theotime-martel_haproxy
                podman rmi -f docker.io/library/mysql:latest
                podman rmi -f docker.io/portainer/portainer-ce:latest
                podman rmi -f docker.io/netdata/netdata
                podman rmi -f docker.io/grafana/grafana
		docker rmi -f docker.io/grafana/loki
		docker rmi -f docker.io/grafana/promtail

                echo "Images supprimées avec succès."

        elif [ "$reponse_image_plg" == "N" ] || [ "$reponse_image_plg" == "n" ]; then

                echo "Aucune image n'a été supprimée."

        else

                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

        fi

	echo "Voulez-vous supprimer les volumes qui ont servi à la sauvegarde des données de l'application YLN ? (O/N)"
        read volume_plg

        if [ "$volume_plg" == "O" ] || [ "$volume_plg" == "o" ]; then

                sudo podman volume rm sae501-502-theotime-martel_db_data
                sudo podman volume rm sae501-502-theotime-martel_db_data_esclave
                sudo podman volume rm sae501-502-theotime-martel_portainer_data
		sudo podman volume rm sae501-502-theotime-martel_grafana_data

		echo "Volumes supprimés avec succès"

        elif [ "$volume_plg" == "N" ] || [ "$volume_plg" == "n" ]; then

                echo "Volumes non supprimés"

        else

                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

        fi

	echo "Voulez-vous supprimer le réseau créé par le docker-compose.yaml qui a servi pour l'application YLN (O/N)"
        read reseau_plg

        if [ "$reseau_plg" == "O" ] || [ "$reseau_plg" == "o" ]; then

                sudo podman network rm sae501-502-theotime-martel_sae

        elif [ "$reseau_plg" == "N" ] || [ "$reseau_plg" == "n" ]; then

                echo "Réseau du docker-compose.yaml non supprimé"

        else

                echo "Réponse invalide. Veuillez répondre 'O' ou 'N'."

        fi

elif [ -z "$choix_utilisateur" ]; then

	echo "L'application n'a pas été lancée, pour la lancer, exécutez le script setup.sh avec la commande suivante : sudo bash setup.sh"

else

	echo "Problème sur la capacité à savoir quelle application a été lancée" 

fi
