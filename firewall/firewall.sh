#!/bin/bash

#Ejemplos: http://www.thegeekstuff.com/2011/03/iptables-inbound-and-outbound-rules/
PATH=$PATH:/sbin/

iptables -P INPUT DROP
iptables -P OUTPUT DROP
iptables -P FORWARD ACCEPT

ip6tables -P INPUT DROP
ip6tables -P OUTPUT DROP
ip6tables -P FORWARD ACCEPT

#No responder a ping en ipv4, ni aunque falle el firewall
echo 1 > /proc/sys/net/ipv4/icmp_echo_ignore_all

#Permitir hacer ping al exterior
iptables -A OUTPUT -p icmp --icmp-type echo-request -j ACCEPT
iptables -A INPUT -p icmp --icmp-type echo-reply -j ACCEPT

#DNS
iptables -A OUTPUT -p udp --dport 53 -j ACCEPT
iptables -A INPUT -p udp --sport 53 -j ACCEPT
ip6tables -A OUTPUT -p udp --dport 53 -j ACCEPT
ip6tables -A INPUT -p udp --sport 53 -j ACCEPT

#loopback
iptables -A INPUT -i lo -j ACCEPT
iptables -A OUTPUT -o lo -j ACCEPT
ip6tables -A INPUT -i lo -j ACCEPT
ip6tables -A OUTPUT -o lo -j ACCEPT

#Permitir: SSH, HTTP y HTTPS
iptables -A INPUT -p tcp -m multiport --dports 25,26,80,443,465,993 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A INPUT -p tcp -m multiport --sports 22,25,26,80,443,465,993 -m state --state ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -m multiport --sports 21,22,25,26,80,443,465,993 -m state --state ESTABLISHED -j ACCEPT
iptables -A OUTPUT -p tcp -m multiport --dports 21,22,25,26,80,443,465,993 -m state --state NEW,ESTABLISHED -j ACCEPT

ip6tables -A INPUT -p tcp -m multiport --dports 25,26,80,443,465,993 -m state --state NEW,ESTABLISHED -j ACCEPT
ip6tables -A INPUT -p tcp -m multiport --sports 22,25,26,80,443,465,993 -m state --state ESTABLISHED -j ACCEPT
ip6tables -A OUTPUT -p tcp -m multiport --sports 21,22,25,26,80,443,465,993 -m state --state ESTABLISHED -j ACCEPT
ip6tables -A OUTPUT -p tcp -m multiport --dports 21,22,25,26,80,443,465,993 -m state --state NEW,ESTABLISHED -j ACCEPT

#Abrir puerto MySQL para las ips seleccionadas:
iptables -A INPUT -i venet0 -p tcp -s 213.186.33.87 --dport 3306 -m state --state NEW,ESTABLISHED -j ACCEPT
#iptables -A INPUT -i venet0 -p tcp -s 83.37.199.41 --dport 3306 -m state --state NEW,ESTABLISHED -j ACCEPT
iptables -A OUTPUT -o venet0 -p tcp --sport 3306 -m state --state ESTABLISHED -j ACCEPT

#Prevenir ataques DOS (puede dar problemas con proxies)
iptables -A INPUT -p tcp --dport 25 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
iptables -A INPUT -p tcp --dport 26 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
iptables -A INPUT -p tcp --dport 80 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
iptables -A INPUT -p tcp --dport 443 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
iptables -A INPUT -p tcp --dport 465 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
iptables -A INPUT -p tcp --dport 993 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
iptables -A INPUT -p tcp --dport 3306 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
ip6tables -A INPUT -p tcp --dport 25 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
ip6tables -A INPUT -p tcp --dport 26 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
ip6tables -A INPUT -p tcp --dport 80 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
ip6tables -A INPUT -p tcp --dport 443 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
ip6tables -A INPUT -p tcp --dport 465 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
ip6tables -A INPUT -p tcp --dport 993 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT
ip6tables -A INPUT -p tcp --dport 3306 -m limit --limit 25/minute --limit-burst 100 -j ACCEPT

