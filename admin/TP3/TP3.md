1. Une première VM

🌞 Générer un Vagrantfile



🌞 Modifier le Vagrantfile

les lignes qui suivent doivent être ajouter dans le bloc où l'objet config est défini
ajouter les lignes suivantes :


# Désactive les updates auto qui peuvent ralentir le lancement de la machine
config.vm.box_check_update = false 

# La ligne suivante permet de désactiver le montage d'un dossier partagé (ne marche pas tout le temps directement suivant vos OS, versions d'OS, etc.)
config.vm.synced_folder ".", "/vagrant", disabled: true


🌞 Faire joujou avec une VM

# on peut allumer tout de suite une VM issue de cette box, le Vagrantfile est censé être fonctionnel
$ vagrant up

# une fois la VM allumée...
$ vagrant status
$ vagrant ssh


# on peut éteindre la VM avec
$ vagrant halt

# et la détruire avec
$ vagrant destroy -f



Je vous conseille vivement d'avoir l'interface de VirtualBox ouverte en même temps pour voir ce que Vagrant fait. Pour mieux capter :)



2. Repackaging
Il est possible de repackager une box Vagrant, c'est à dire de prendre une VM existante, et d'en faire une nouvelle box. On peut ainsi faire une box qui contient notre configuration préférée.
Le flow typique :

on allume une VM avec Vagrant
on se connecte à la VM et on fait de la conf
on package la VM en une box Vagrant
on peut instancier des nouvelles VMs à partir de cette box
ces nouvelles VMs contiendront tout de suite notre conf

🌞 Repackager la box que vous avez choisie

elle doit :

être à jour
disposer des commandes vim, ip, dig, ss, nc

avoir un firewall actif
SELinux (systèmes RedHat) et/ou AppArmor (plutôt sur Ubuntu) désactivés


pour repackager une box, vous pouvez utiliser les commandes suivantes :


# On convertit la VM en un fichier .box sur le disque
# Le fichier est créé dans le répertoire courant si on ne précise pas un chemin explicitement
$ vagrant package --output super_box.box

# On ajoute le fichier .box à la liste des box que gère Vagrant
$ vagrant box add super_box super_box.box

# On devrait voir la nouvelle box dans la liste locale des boxes de Vagrant
$ vagrant box list


🌞 Ecrivez un Vagrantfile qui lance une VM à partir de votre Box

et testez que ça fonctionne !


3. Moult VMs
Pour cette partie, je vous laisse chercher des ressources sur Internet pour les syntaxes. Internet regorge de Vagrantfile d'exemple, hésitez po à m'appeler si besoin !
🌞 Adaptez votre Vagrantfile pour qu'il lance les VMs suivantes (en réutilisant votre box de la partie précédente)

vous devez utiliser une boucle for dans le Vagrantfile

pas le droit de juste copier coller le même bloc trois fois, une boucle for j'ai dit !




Name
IP locale
Accès internet
RAM




node1.tp3.b2
10.3.1.11
Ui
1G


node2.tp3.b2
10.3.1.12
Ui
1G


node3.tp3.b2
10.3.1.13
Ui
1G



📁 partie1/Vagrantfile-3A dans le dépôt git de rendu
🌞 Adaptez votre Vagrantfile pour qu'il lance les VMs suivantes (en réutilisant votre box de la partie précédente)

l'idéal c'est de déclarer une liste en début de fichier qui contient les données des VMs et de faire un for sur cette liste
à vous de voir, sans boucle for et sans liste, juste trois blocs déclarés, ça fonctionne aussi




Name
IP locale
Accès internet
RAM




alice.tp3.b2
10.3.1.11
Ui
1G


bob.tp3.b2
10.3.1.200
Ui
2G


eve.tp3.b2
10.3.1.57
Nan
1G



📁 partie1/Vagrantfile-3B dans le dépôt git de rendu

La syntaxe Ruby c'est vraiment dégueulasse.