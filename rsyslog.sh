#!/bin/bash

#Regarder si ca fonctionne comme ça ou sinon
#1) journalctl o json | jq -S (faire un apt-get install jq) (essayer SANS au début)
#2) vim /etc/systemd/journald.conf et décommenter ForwardToSyslog=yes
#3) faire un systemctl|service restart systemd-journald
#4) bien faire en sorte que l'importation de syslog-ng fonctionnement correctement (rajouter : 
#unqualified-search-registries = ['docker.io']
#5) refaire un pull de l'image syslog balabit


if [ $# -ne 0 ]; then
    echo "Ce script ne prend pas de paramètre, il doit être exécuté sans paramètres"
    exit 1
fi

#Pour installer rsyslog en fonction du SE de l'utilisateur
if [ -x "$(command -v apt-get)" ]; then
    sudo apt-get update 
    sudo apt-get install -y rsyslog 
elif [ -x "$(command -v dnf)" ]; then
    sudo dnf install -y rsyslog 
elif [ -x "$(command -v yum)" ]; then
    sudo yum install -y rsyslog 
else
    echo "Système d'exploitation inconnu"
    exit 1
fi

# Pour déplacer la configuration rsyslog : 

sudo cp -r rsyslogconfig/* /etc/


# On redémarre la config rsyslog : 

serv=$(ps -p 1 -o comm=)

if [ "$serv" == "systemd" ]; then
    sudo systemctl restart rsyslog
elif [ "$serv" == "init" ] || [ "$serv" == "upstart" ]; then
    sudo service rsyslog restart || sudo service restart rsyslog
else
    echo "Service inconnu, relancez le service rsyslog manuellement"
    exit 1
fi

