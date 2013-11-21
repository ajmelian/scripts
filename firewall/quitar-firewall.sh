#!/bin/bash

PATH=$PATH:/sbin/

iptables -P INPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -P OUTPUT ACCEPT

iptables -F INPUT
iptables -F OUTPUT
iptables -F FORWARD
iptables -F

iptables -X
iptables -t nat -F
iptables -t nat -X
iptables -t mangle -F
iptables -t mangle -X

ip6tables -F INPUT
ip6tables -F OUTPUT
ip6tables -F FORWARD
ip6tables -F

ip6tables -P INPUT ACCEPT
ip6tables -P FORWARD ACCEPT
ip6tables -P OUTPUT ACCEPT

