# I. Init


1. sudo c pa bo

🌞 Ajouter votre utilisateur au groupe docker

````
[ethan@localhost ~]$ sudo groupadd docker
[ethan@localhost ~]$ sudo usermod -aG docker ethan
[ethan@localhost ~]$ docker run hello-world

Hello from Docker!

````

1. Un premier conteneur en vif



🌞 Lancer un conteneur NGINX

avec la commande suivante :

````
[ethan@localhost ~]$ docker run -d -p 9999:80 nginx
Status: Downloaded newer image for nginx:latest
````

🌞 Visitons

vérifier que le conteneur est actif avec une commande qui liste les conteneurs en cours de fonctionnement
````
[ethan@localhost ~]$ docker ps -a
CONTAINER ID   IMAGE         COMMAND                  CREATED          STATUS                      PORTS                                   NAMES
38825fbb176a   nginx         "/docker-entrypoint.…"   5 minutes ago    Up 5 minutes                0.0.0.0:9999->80/tcp, :::9999->80/tcp   modest_robinson
````
afficher les logs du conteneur
````
[ethan@localhost ~]$ docker container logs 38825fbb176a
/docker-entrypoint.sh: /docker-entrypoint.d/ is not empty, will attempt to perform configuration
````
afficher toutes les informations relatives au conteneur avec une commande docker inspect

````
[ethan@localhost ~]$ docker inspect nginx
[
    {
        "Id": "sha256:4f67c83422ec747235357c04556616234e66fc3fa39cb4f40b2d4441ddd8f100",
        ...
                "Metadata": {
            "LastTagTime": "0001-01-01T00:00:00Z"
        }
    }
]
````

afficher le port en écoute sur la VM avec un sudo ss -lnpt
```
[ethan@localhost ~]$ sudo ss -lnpt
State                Recv-Q               Send-Q                             Local Address:Port                             Peer Address:Port              Process
LISTEN               0                    4096                                     0.0.0.0:9999                                  0.0.0.0:*                  users:(("docker-proxy",pid=1628,fd=4))
```

ouvrir le port 9999/tcp (vu dans le ss au dessus normalement) dans le firewall de la VM
depuis le navigateur de votre PC, visiter le site web sur http://10.10.10.10:9999
```
[ethan@localhost ~]$ sudo firewall-cmd --add-port=9999/tcp --permanent
success
[ethan@localhost ~]$ sudo firewall-cmd --reload
```

🌞 On va ajouter un site Web au conteneur NGINX

créez un dossier nginx

pas n'importe où, c'est ta conf caca, c'est dans ton homedir donc /home/<TON_USER>/nginx/

dedans, deux fichiers : index.html (un site nul) site_nul.conf (la conf NGINX de notre site nul)
exemple de index.html :
```
[ethan@localhost /]$ cd /home/ethan/nginx
[ethan@localhost nginx]$ nano index.html
[ethan@localhost nginx]$ nano site_nul.conf
```

🌞 Visitons

vérifier que le conteneur est actif
````
[ethan@localhost nginx]$ docker run -d -p 9999:8080 -v /home/ethan/nginx/index.html:/var/www/html/index.html -v /home/ethan/nginx/site_nul.conf:/etc/nginx/conf.d/site_nul.conf nginx
c7e8f7b3bf6b58036286c8abeacbd519b81c9e3d60a0de25e3c350c8da95ae3d
[ethan@localhost nginx]$ docker ps -a
CONTAINER ID   IMAGE         COMMAND                  CREATED             STATUS                          PORTS                                               NAMES
c7e8f7b3bf6b   nginx         "/docker-entrypoint.…"   5 seconds ago       Up 5 seconds                    80/tcp, 0.0.0.0:9999->8080/tcp, :::9999->8080/tcp   lucid_proskuriakova
````


1. Un deuxième conteneur en vif

🌞 Lance un conteneur Python, avec un shell

````
[ethan@localhost nginx]$ docker run -it python bash

root@3e227cc6832e:/#
````

🌞 Installe des libs Python


aiohttp
```
root@3e227cc6832e:/# pip install aiohttp
```
aioconsole
```
root@3e227cc6832e:/# pip install aioconsole
```


tapez la commande python pour ouvrir un interpréteur Python
taper la ligne import aiohttp pour vérifier que vous avez bien téléchargé la lib
```
>>> import aiohttp
>>>
```
(-_-) il était sensé ce passer quelque chose? 

# II. Images

1. Images publiques
🌞 Récupérez des images

avec la commande docker pull

récupérez :

l'image python officielle en version 3.11 (python:3.11 pour la dernière version)
l'image mysql officielle en version 5.7
l'image wordpress officielle en dernière version
l'image linuxserver/wikijs en dernière version

````
[ethan@localhost nginx]$ docker pull python
[ethan@localhost nginx]$ docker pull mysql
[ethan@localhost nginx]$ docker pull wordpress
[ethan@localhost nginx]$ docker pull linuxserver/wikijs
````

listez les images que vous avez sur la machine avec une commande docker
```
[ethan@localhost nginx]$ docker image list
REPOSITORY           TAG       IMAGE ID       CREATED         SIZE
linuxserver/wikijs   latest    8192cd2676c2   2 days ago      455MB
nginx                latest    4f67c83422ec   7 days ago      188MB
wordpress            latest    b516ff0216fb   4 weeks ago     685MB
mysql                latest    fcd86ff8ce8c   5 weeks ago     578MB
python               latest    12e5ab9d51c8   8 weeks ago     1.02GB
hello-world          latest    d2c94e258dcb   13 months ago   13.3kB
```

🌞 Lancez un conteneur à partir de l'image Python
````
[ethan@localhost nginx]$ docker run -it python bash
root@2c6502cc935c:/# python
Python 3.12.3 (main, May 14 2024, 07:23:41) [GCC 12.2.0] on linux
Type "help", "copyright", "credits" or "license" for more information.
>>>
````


1. Construire une image
Pour construire une image il faut :

créer un fichier Dockerfile

exécuter une commande docker build pour produire une image à partir du Dockerfile


🌞 Ecrire un Dockerfile pour une image qui héberge une application Python

l'image doit contenir

````
FROM debian
RUN apt update
RUN apt -y install python3
RUN pip install emoji
COPY app.py /app.py
ENTRYPOINT ["python3","/app.py"]
````
````
[ethan@localhost python_app_build]$ ls
app.py  Dockerfile`
````

🌞 Build l'image

````
[ethan@localhost python_app_build]$ cat Dockerfile
FROM debian:latest
RUN apt update -y
RUN apt install -y python3 python3-pip
RUN apt install python3-emoji
COPY app.py /app.py
ENTRYPOINT ["python3", "/app.py"]
````
PS: Mon PC à bluescreen plusieur fois en ssh donc j'écris le logs à la main
```
[ethan@localhost python_app_build]$docker build . -t python:version_de_ouf
...
[+] Building 31,6s (10/10) FINISHED                                     docker default
```


🌞 Lancer l'image
````
[ethan@localhost python_app_build]$ docker run python:version_de_ouf
Cet exemple d'application est vraiment naze 👎
````

# III. Docker compose

Pour la fin de ce TP on va manipuler un peu docker compose.
🌞 Créez un fichier docker-compose.yml

dans un nouveau dossier dédié /home/<USER>/compose_test
````
[ethan@localhost ~]$ mkdir compose_test
[ethan@localhost ~]$ cd compose_test/
[ethan@localhost compose_test]$ nano docker-compose.yml
````


````
version: "3"

services:
  conteneur_nul:
    image: debian
    entrypoint: sleep 9999
  conteneur_flopesque:
    image: debian
    entrypoint: sleep 9999
````



🌞 Lancez les deux conteneurs avec docker compose
````
[ethan@localhost compose_test]$ docker compose up -d
WARN[0000] /home/ethan/compose_test/docker-compose.yml: `version` is obsolete
[+] Running 3/3
 ✔ conteneur_flopesque Pulled                                                                                                                                                                               2.5s
   ✔ c6cf28de8a06 Already exists                                                                                                                                                                            0.0s
 ✔ conteneur_nul Pulled                                                                                                                                                                                     2.8s
[+] Running 3/3
 ✔ Network compose_test_default                  Created                                                                                                                                                    0.1s
 ✔ Container compose_test-conteneur_flopesque-1  Started                                                                                                                                                    0.3s
 ✔ Container compose_test-conteneur_nul-1        Started
 ````

🌞 Vérifier que les deux conteneurs tournent

````
[ethan@localhost compose_test]$ docker compose ps
WARN[0000] /home/ethan/compose_test/docker-compose.yml: `version` is obsolete
NAME                                 IMAGE     COMMAND        SERVICE               CREATED              STATUS              PORTS
compose_test-conteneur_flopesque-1   debian    "sleep 9999"   conteneur_flopesque   About a minute ago   Up About a minute
compose_test-conteneur_nul-1         debian    "sleep 9999"   conteneur_nul         About a minute ago   Up About a minute
````


🌞 Pop un shell dans le conteneur conteneur_nul

````
[ethan@localhost ~]$ docker run -it conteneur_flopesque bash
root@0d3d42f520ef:/# ping conteneur_flopesque
ping: conteneur_flopesque: Name or service not known
root@0d3d42f520ef:/# ping 10.10.10.10
PING 10.10.10.10 (10.10.10.10) 56(84) bytes of data.
64 bytes from 10.10.10.10: icmp_seq=1 ttl=64 time=0.063 ms
````