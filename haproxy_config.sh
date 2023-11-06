#!/bin/sh

# Adresses IP des conteneurs
IP_NGINX1=$(podman inspect nginx1 | grep -oP '"IPAddress": "\K[^"]+')
IP_NGINX2=$(podman inspect nginx2 | grep -oP '"IPAddress": "\K[^"]+')

# Fichier de config HAProxy
echo "global" > ./loadbalancing/haproxy.cfg
echo "    user root" >> ./loadbalancing/haproxy.cfg
echo "" >> ./loadbalancing/haproxy.cfg

echo "defaults" >> ./loadbalancing/haproxy.cfg
echo "    mode http" >> ./loadbalancing/haproxy.cfg
echo "    timeout connect 5000ms" >> ./loadbalancing/haproxy.cfg
echo "    timeout client 5000ms" >> ./loadbalancing/haproxy.cfg
echo "    timeout server 5000ms" >> ./loadbalancing/haproxy.cfg
echo "" >> ./loadbalancing/haproxy.cfg

echo "frontend main" >> ./loadbalancing/haproxy.cfg
echo "    bind *:80" >> ./loadbalancing/haproxy.cfg
echo "    bind *:443 ssl crt ./opensslkeys/myserver.pem" >> ./loadbalancing/haproxy.cfg
echo "    default_backend nginx" >> ./loadbalancing/haproxy.cfg
echo "" >> ./loadbalancing/haproxy.cfg

echo "backend nginx" >> ./loadbalancing/haproxy.cfg
echo "    balance roundrobin" >> ./loadbalancing/haproxy.cfg
echo "    server nginx1 $IP_NGINX1:80 check" >> ./loadbalancing/haproxy.cfg
echo "    server nginx2 $IP_NGINX2:80 check" >> ./loadbalancing/haproxy.cfg

#sleep 2

#haproxy -f loadbalancing/haproxy.cfg &
