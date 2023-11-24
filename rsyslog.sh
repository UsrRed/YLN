#!/bin/bash

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

#Configuration containeurs : 

registries=$(find / -name "registries.conf" 2>/dev/null | head -n 1)

if ! grep "unqualified-search-registries = \['docker.io'\]" "$registries"; then
	echo "unqualified-search-registries = ['docker.io']" >> "$registries"
	echo "Configuration regitries ajoutée"
else 
	echo "Configuration registries déjà présente"
fi

#Configuration journald : 

journald=$(find / -name "journald.conf" 2>/dev/null | head -n 1)

if grep -q "^[[:space:]]*ForwardToSyslog=yes" "$journald"; then
	echo "La ligne ForwardToSyslog=yes existe déjà dans le fichier de configuration."
else
    
	echo "ForwardToSyslog=yes" >> "$journald"
	echo "Configuration journald déjà présente"
fi

#On redémarre les services (journald et rsyslog) : 

if [ -x "$(command -v systemctl)" ]; then
    systemctl restart systemd-journald
    systemctl restart rsyslog
    echo "Services journald et rsyslog redémarrés avec succès"

elif [ -x "$(command -v service)" ]; then
    service systemd-journald restart
    service rsyslog restart
    echo "Services journald et rsyslog redémarrés avec succès"
fi

#Pull de l'image syslog-ng : 

podman pull balabit/syslog-ng:latest
podman-compose down && podman-compose up -d
