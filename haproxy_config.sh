#!/bin/sh

#Adresses IP des conteneurs
IP_NGINX1=$(podman inspect nginx1 | grep -oP '"IPAddress": "\K[^"]+')
IP_NGINX2=$(podman inspect nginx2 | grep -oP '"IPAddress": "\K[^"]+')

# Fichier de config HAProxy
cat <<EOF > ./loadbalancing/haproxy.cfg
global

    user root

defaults
    mode http
    timeout connect 5000ms
    timeout client 5000ms
    timeout server 5000ms

frontend main
    bind *:80
    bind *:443 ssl crt ./opensslkeys/myserver.pem
    default_backend nginx

backend nginx
    balance roundrobin
    server nginx1 $IP_NGINX1:80 check
    server nginx2 $IP_NGINX2:80 check
EOF
