#!/bin/bash

echo "__   ___     _   _   _        _   _   _ _   _  ____ _   _ _____ ____   ";  
echo "\ \ / / |   | \ | | | |      / \ | | | | \ | |/ ___| | | | ____|  _ \  ";
echo " \ V /| |   |  \| | | |     / _ \| | | |  \| | |   | |_| |  _| | |_) | ";
echo "  | | | |___| |\  | | |___ / ___ \ |_| | |\  | |___|  _  | |___|  _ <  ";
echo "  |_| |_____|_| \_| |_____/_/   \_\___/|_| \_|\____|_| |_|_____|_| \_\ ";

echo ""

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
if podman images | grep 'docker.io/library/nginx.*alpine' && podman images | grep 'docker.io/library/php.*8.2-fpm' && podman images | grep 'docker.io/library/mysql.*latest' && podman images | grep 'docker.io/library/haproxy.*alpine' && podman images | grep 'docker.io/portainer/portainer-ce.*latest' && podman images | grep 'docker.io/library/vault.*1.13.3'; then
	echo "Les images sont déjà installées"
else
	echo "Pulling des images pour le lancement des conteneurs.."
	podman pull docker.io/library/mysql:latest
	podman pull docker.io/library/php:8.2-fpm
	podman pull docker.io/library/nginx:alpine
	podman pull docker.io/library/haproxy:alpine
	podman pull docker.io/portainer/portainer-ce:latest
	podman pull docker.io/library/vault:1.13.3
	#podman pull grafana/grafana
	#podman pull grafana/loki
	#podman pull grafana/promtail    
	#podman pull docker.io/balabit/syslog-ng:latest
fi

chmod 666 ./website/logs/logs.txt
#chmod 006 ./website/logs/logs.txt

#Partie syslog-ng et grafana : 

#echo ""
#echo "--> L'application peut être implémentée avec un syslog-ng. Cette partie est automatique : elle installe et configure rsyslog sur votre PC hôte pour l'envoi des logs et ajoute un conteneur syslog-ng."
#echo "--> Voulez-vous ajouter syslog-ng lors du déploiement de l'application (taille de l'image : 538 Mo) ? (O/N)"
#read rep_syslogng

#if [ "$rep_syslogng" == "O" ] || [ "$rep_syslogng" == "o" ]; then
	
	#bash ./scripts/rsyslog.sh
	#echo "Syslog-ng ajouté avec succès."
    
	#echo ""
	#echo "--> Voulez-vous également implémenter une interface graphique avec Grafana, Promtail et Loki (taille des images : 683 Mo) ? (O/N)"
	#read rep_ihm

	#if [ "$rep_ihm" == "O" ] || [ "$rep_ihm" == "o" ]; then
		#bash ./scripts/grafana.sh
		#echo "Interface graphique pour syslog-ng (Grafana, Promtail, Loki) ajoutée avec succès."
		#echo "Lancement des conteneurs..."
		#podman-compose -f docker-compose-grafana.yaml up -d
	#else
		#echo "Interface graphique pour syslog-ng (Grafana, Promtail, Loki) non implémentée."
		#echo "Lancement des conteneurs"
		#podman-compose -f docker-compose-syslog.yaml up -d
	#fi

#else
	#echo "Syslog-ng non implémenté."
	#echo "Lancement des conteneurs"
	#podman-compose -f docker-compose-sans.yaml up -d
#fi

echo ""
echo "--> Voulez-vous gérer les logs de l'application ? (O/N)"
read rep_logs

if [ "$rep_logs" == "O" ] || [ "$rep_logs" == "o" ]; then
	echo "Faites votre choix"
	echo "1- Syslog-ng (Vue en CLI - Gourmand en ressources)"
	echo "2- Grafana (Vue en GUI - Modérément gourmand en ressources)"
	read rep_log_choix

	case $rep_log_choix in
		1)
			echo "Syslog-ng sélectionné. Ajout en cours..."
        		bash ./scripts/rsyslog.sh
        		echo "Syslog-ng ajouté avec succès."
        		echo "Lancement des conteneurs..."

        # Vérifier si la configuration existe déjà dans le fichier
        		if grep -q "\[registries.search\]" "/etc/containers/registries.conf" && grep -q "registries = \['docker.io'\]" "/etc/containers/registries.conf"; then
            			echo "La configuration existe déjà dans le fichier pour le syslog"
        		else
            # Ajouter la configuration au fichier
            			echo -e "\n[registries.search]" >> "/etc/containers/registries.conf"
            			echo "registries = ['docker.io']" >> "/etc/containers/registries.conf"
            			echo "Configuration ajoutée au fichier."
        		fi
        		podman-compose -f docker-compose-syslog.yaml up -d
			sleep 1
			echo "Lancement partie crontab..."
			sleep 1
			#Partie crontab (car ne fonctionne pas avec un dockerfile) :

			#mail_expe=$(cat .env | grep "SMTP_USERNAME_OWN" | cut -d '=' -f2)
			
			commande1=$(cat .env | grep "COMMANDE_1" | cut -d '=' -f2)
			commande2=$(cat .env | grep "COMMANDE_2" | cut -d '=' -f2)
			commande3=$(cat .env | grep "COMMANDE_3" | cut -d '=' -f2)
			commande4=$(cat .env | grep "COMMANDE_4" | cut -d '=' -f2)
			commande5=$(cat .env | grep "COMMANDE_5" | cut -d '=' -f2)	

			podman exec -it syslog-ng /bin/bash -c "${commande1//$/\\$}"
			podman exec -it syslog-ng /bin/bash -c "${commande2//$/\\$}"
			podman exec -it syslog-ng /bin/bash -c "${commande3//$/\\$}"
			podman exec -it syslog-ng /bin/bash -c "${commande4//$/\\$}"
			podman exec -it syslog-ng /bin/bash -c "${commande5//$/\\$}"

			#source : https://tldp.org/LDP/abs/html/parameter-substitution.html


			#sudo podman exec -it syslog-ng /bin/bash -c "apt-get update && apt-get install -y cron && touch /var/spool/cron/crontabs/root && apt-get install -y swaks && mkdir /var/log/BACKUP-\$(date +\%Y-\%m-\%d) && mkdir /var/log/BACKUP-MONTH-YEAR-\$(date +\%Y-\%m)"
			#sudo podman exec -it syslog-ng /bin/bash -c "echo '58 * * * * sha256sum /var/log/YLN/* >> /var/log/BACKUP-\$(date +\%Y-\%m-\%d)/integrite-\$(date +\%Y-\%m-\%d-\%H).txt && tar -zcf /var/log/YLN_backup.tar.gz -C /var/log YLN && mv /var/log/YLN_backup.tar.gz /var/log/BACKUP-\$(date +\%Y-\%m-\%d)/YLN_backup-\$(date +\%Y-\%m-\%d-\%H).tar.gz' >> /var/spool/cron/crontabs/root"
			#On ne peut pas garantir l'intégrité des données le jour même du coup...
			#podman exec -it syslog-ng /bin/bash -c "echo '59 23 * * * tar -zcf /var/log/BACKUP-\$(date +\%Y-\%m-\%d).tar.gz -C /var/log BACKUP-\$(date +\%Y-\%m-\%d) && swaks  --to nathan.martel@etu.univ-tours.fr --from ayressios@gmail.com --server smtp.gmail.com --port 587 --auth LOGIN --auth-user ayressios@gmail.com --auth-password sueyuktjcoymcbrs --tls --attach /var/log/BACKUP-\$(date +\%Y-\%m-\%d).tar.gz --header \"Subject: Backup de la journée \$(date +\%Y-\%m-\%d)\" --body \"Logs du syslog pour la journée du \$(date +\%Y-\%m-\%d)\" && mv /var/log/BACKUP-\$(date +\%Y-\%m-\%d).tar.gz /var/log/BACKUP-MONTH-YEAR-\$(date +\%Y-\%m)' >> /var/spool/cron/crontabs/root"
			#sudo podman exec -it syslog-ng /bin/bash -c "echo '59 23 1 * * rm -rf /var/log/BACKUP-MONTH-YEAR-\$(date +\%Y-\%m -d \"last month\") && mkdir /var/log/BACKUP-MONTH-YEAR-\$(date +\%Y-\%m)' >> /var/spool/cron/crontabs/root"
			#sudo podman exec -it syslog-ng /bin/bash -c "service cron start"

			#------------------#

			#podman exec -it syslog-ng /bin/bash -c "echo '0 * * * * tar -zcf /var/log/YLN_backup.tar.gz -C /var/log YLN | swaks --to nathan.martel@etu.univ-tours.fr --from sae501502@gmail.com --server smtp.gmail.com --port 587 --auth LOGIN --auth-user sae501502@gmail.com --auth-password xqifxpjrieknuntn --tls --attach /var/log/YLN_backup.tar.gz --header \"Subject: Backup \$(date +\%Y-\%m-\%d\%H\%M)\" --body \"Logs du syslog à \$(date +\%Y-\%m-\%d-\%H)\" && rm -rf /var/log/YLN_backup.tar.gz' >> /var/spool/cron/crontabs/root"
			#podman exec-it syslog-ng /bin/bash -c "echo '59 23 * * * tar -zcf /var/log/YLN_backup_$(date +\%Y\%m\%d).zip -C /var/log YLN && rm -rf /var/log/YLN && swaks --to nathan.martel@etu.univ-tours.fr --from sae501502@gmail.com --server smtp.gmail.com --port 587 --auth LOGIN --auth-user sae501502@gmail.com --auth-password xqifxpjrieknuntn --tls --attach /var/log/YLN_backup_$(date +\%Y\%m\%d).zip --header \"Subject: Backup de la journée \$(date +\%Y-\%m-\%d)\" --body \"Logs du syslog pour la journée du \$(date +\%Y-\%m-\%d)\"'"

			;;	
		2)
			echo "Grafana sélectionné. Ajout en cours..."
			bash ./scripts/grafana.sh
			echo "Interface graphique pour syslog-ng (Grafana, Promtail, Loki) ajoutée avec succès."
			echo "Lancement des conteneurs..."
			podman-compose -f docker-compose-grafana.yaml up -d
			;;
		*)
			echo "Choix invalide. Aucune gestion des logs implémentée."
			;;
	esac

else
	echo "Gestion des logs non implémentée."
	echo "Lancement des conteneurs sans gestion des logs..."
	podman-compose -f docker-compose-sans.yaml up -d
fi

#Et l'adresse IP du conteneur Haproxy pour accéder à l'application
AdresseIP=$(podman inspect haproxy | grep -oP '"IPAddress": "\K[^"]+') 
AdresseIP_Portainer=$(podman inspect portainer | grep -oP '"IPAddress": "\K[^"]+')

res_fqdn_site="172.18.0.253	yln.fr"
res_fqdn_portainer="172.18.0.254	portainer.yln.fr"

if grep -q "$res_fqdn_site" /etc/hosts; then
	echo "Résolution déjà présente du site"
else
    	echo "$res_fqdn_site" >> /etc/hosts
    	echo "Résolution ajoutée du site"
fi

if grep -q "$res_fqdn_portainer" /etc/hosts; then
        echo "Résolution déjà présente de portainer"
else
        echo "$res_fqdn_portainer" >> /etc/hosts
        echo "Résolution ajoutée de portainer"
fi

echo "--> Voulez-vous surveiller en temps réel les performances des conteneurs ? (O/N)"
read rep_netdata

if [ "$rep_netdata" == "O" ] || [ "$rep_netdata" == "o" ]; then
    podman pull docker.io/netdata/netdata
    #podman run -d --name=netdata -p 172.18.0.20:19999:19999 -v /proc:/host/proc:ro -v /sys:/host/sys:ro -v /run/podman/podman.sock:/var/run/docker.sock:z --cap-add SYS_PTRACE --security-opt apparmor=unconfined --network sae501-502-theotime-martel_sae netdata/netdata


    podman run -d --name=netdata -p 19999:19999 -v /proc:/host/proc:ro -v /sys:/host/sys:ro -v /run/podman/podman.sock:/var/run/docker.sock:z -v ./netdataconfig/netdata.conf:/etc/netdata/netdata.conf:z --cap-add SYS_PTRACE --security-opt apparmor=unconfined --network sae501-502-theotime-martel_sae netdata/netdata


    echo "Netdata ajouté avec succès pour surveiller les performances des conteneurs."
else
    echo "Surveillance des performances non implémentée"
fi

echo ""
echo "--> L'adresse IP de l'application sur laquelle se rendre est https://$AdresseIP:8443 ou https://yln.fr:8443"
#sudo firefox "https://yln.fr:8443"
echo "Vous pouvez faire un CTRL + [clique gauche] sur l'URL ci-dessus."
echo ""
echo "--> La gestion des conteneurs se fait sur https://$AdresseIP_Portainer:9443 ou sur https://portainer.yln.fr:9443"
echo "";
if podman ps | grep -q "grafana"; then
	AddresseIpGrafana=$(podman inspect grafana | grep -oP '"IPAddress": "\K[^"]+')
	res_fqdn_grafana="172.18.0.10	grafana.yln.fr"

	if grep -q "$res_fqdn_grafana" /etc/hosts; then
        	echo "Résolution déjà présente de grafana"
	else
        	echo "$res_fqdn_grafana" >> /etc/hosts
        	echo "Résolution ajoutée de grafana"
	fi

	echo "--> Pour l'accès grafana, vous pouvez vous rendre sur https://$AddresseIpGrafana:3000 ou sur https://grafana.yln.fr:3000"
	
fi

if podman ps | grep -q "netdata"; then
	AddresseIpnetdata=$(podman inspect netdata | grep -oP '"IPAddress": "\K[^"]+')
	res_fqdn_netdata="$AddresseIpnetdata	netdata.yln.fr"
	if grep -q "$res_fqdn_netdata" /etc/hosts; then
                echo "Résolution déjà présente de netdata"
        else
                echo "$res_fqdn_netdata" >> /etc/hosts
                echo "Résolution ajoutée de netdata"
        fi

        echo "--> Pour la surveillance des conteneurs, vous pouvez vous rendre sur http://$AddresseIpnetdata:19999 ou sur http://netdata.yln.fr:19999"

fi
