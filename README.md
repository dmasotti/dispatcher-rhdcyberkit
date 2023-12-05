# Docker PHP-FPM 8.2 & Nginx 1.24 on Alpine Linux for Cyberkit Dispatcher 
RHD Cyberkit Dispatcher - PHP-FPM 8.2 & Nginx 1.24 container image for Docker, built on [Alpine Linux](https://www.alpinelinux.org/).

Repository: 


* Built on the lightweight and secure Alpine Linux distribution
* Multi-platform, supporting AMD4, ARMv6, ARMv7, ARM64
* Very small Docker image size (+/-40MB)
* Uses PHP 8.2 for the best performance, low CPU usage & memory footprint
* Optimized for 100 concurrent users
* Optimized to only use resources when there's traffic (by using PHP-FPM's `on-demand` process manager)
* The services Nginx, PHP-FPM and supervisord run under a non-privileged user (nobody) to make it more secure
* The logs of all the services are redirected to the output of the Docker container (visible with `docker logs -f <container name>`)
* Follows the KISS principle (Keep It Simple, Stupid) to make it easy to understand and adjust the image to your needs

[![Docker Pulls](https://img.shields.io/docker/pulls/trafex/php-nginx.svg)](https://hub.docker.com/r/trafex/php-nginx/)
![nginx 1.24](https://img.shields.io/badge/nginx-1.24-brightgreen.svg)
![php 8.2](https://img.shields.io/badge/php-8.2-brightgreen.svg)
![License MIT](https://img.shields.io/badge/license-MIT-blue.svg)


## Usage
Build the image :

    docker build -t dispatcher-rhdcyberkit:<version> -t dispatcher-rhdcyberkit:latest  .

Save the image for exporting:

    docker save -o dispatcher-rhdcyberkit_v0.0.1.tar  dispatcher-rhdcyberkit:v0.0.1

Start the Docker container:

    docker run -p 80:80 dispatcher-rhdcyberkit:latest

See the PHP info on http://localhost, or the static html page on http://localhost/test.html

## Configuration docker-composer for traefik

version: '3.8'

services:
  dispatcher:
    image: dispatcher-rhdcyberkit:latest
    container_name: dispatcher
    hostname: 'dispatcher'
    restart: always
    tty: true
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.dispatcher.rule=Host(`dmrhd.ddns.net`) && PathPrefix(`/tenant1`)"
      - "traefik.http.services.dispatcher.loadbalancer.server.port=80"
      - "traefik.http.routers.dispatcher.middlewares=dispatcher"
      - "traefik.http.middlewares.dispatcher.stripprefix.prefixes=/tenant1"
      
    networks:
      - traefik_network
    environment:
      RHD_REDIRECT: "http://dmrhd.ddns.net/tenant1/inxx.php"
      MYSQL_HOST: mysql
      MYSQL_USER: mysql
      MYSQL_PASSWORD: password
      MYSQL_DATABASE: tenant1
    
networks:
  traefik_network:
    external: true

## Traefik configuration for docker-composer 

version: '3'
 
services:
  traefik:
    image: traefik:latest
    container_name: traefik
    hostname: traefik
    restart: always
    command:
      - "--api.insecure=true"
      - "--providers.docker=true"
      - "--providers.docker.exposedbydefault=false"
    ports:
      - "80:80"
 
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
    labels:
      - "traefik.enable=true"
      - "traefik.docker.network=traefik-network"
      - "traefik.http.routers.traefik.service=api@internal"
      - 'traefik.http.routers.traefik.middlewares=strip'
      - "traefik.http.routers.traefik.rule=Host(`dmrhd.ddns.net`)"
      - 'traefik.http.middlewares.strip.stripprefix.prefixes=/traefik'
    networks:
      - traefik_network
 
  
networks:
  traefik_network:
    external: true
  default:  