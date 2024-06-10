## 1. Une première VM

🌞 Générer un Vagrantfile
```
PS C:\Users\Ethan\Vagrant\Test> vagrant init generic/ubuntu2204
```


🌞 Modifier le Vagrantfile

```
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://vagrantcloud.com/search.
  config.vm.box = "generic/ubuntu2204"
  
  config.vm.box_check_update = false 

  # La ligne suivante permet de désactiver le montage d'un dossier partagé (ne marche pas tout le temps directement suivant vos OS, versions d'OS, etc.)
  config.vm.synced_folder ".", "/vagrant", disabled: true
  ```


🌞 Faire joujou avec une VM

````
PS C:\Users\Ethan\Vagrant\Test> vagrant up  
Bringing machine 'default' up with 'virtualbox' provider...
==> default: Box 'generic/ubuntu2204' could not be found. Attempting to find and install...
    default: Box Provider: virtualbox
    default: Box Version: >= 0
...
    default: Guest Additions Version: 6.1.38
    default: VirtualBox Version: 7.0
````
```
PS C:\Users\Ethan\Vagrant\Test> vagrant status  
>> 
Current machine states:

default                   running (virtualbox)
```
```
PS C:\Users\Ethan\Vagrant\Test> vagrant ssh 
vagrant@ubuntu2204:~$ ui
Command 'ui' not found, but can be installed with:
```
```
PS C:\Users\Ethan\Vagrant\Test> vagrant halt
==> default: Attempting graceful shutdown of VM...
```
```
vagrant destroy -f
```

## 2. Repackaging

🌞 Repackager la box que vous avez choisie

```
PS C:\Users\Ethan\Vagrant\Test> vagrant up
Bringing machine 'default' up with 'virtualbox' provider...
==> default: Importing base box 'generic/ubuntu2204'...
...
    default: Guest Additions Version: 6.1.30
    default: VirtualBox Version: 7.0
```

elle doit :

être à jour
disposer des commandes vim, ip, dig, ss, nc
```
vagrant@ubuntu2204:~$ vim --help
VIM - Vi IMproved 8.2 (2019 Dec 12, compiled Dec 05 2023 17:58:57)
vagrant@ubuntu2204:~$ ip --help
Usage: ip [ OPTIONS ] OBJECT { COMMAND | help }
vagrant@ubuntu2204:~$ dig -h
Usage:  dig [@global-server] [domain] [q-type] [q-class] {q-opt}
            {global-d-opt} host [@local-server] {local-d-opt}
            [ host [@local-server] {local-d-opt} [...]]
vagrant@ubuntu2204:~$ ss -h
Usage: ss [ OPTIONS ]
       ss [ OPTIONS ] [ FILTER ]
vagrant@ubuntu2204:~$ nc -h
OpenBSD netcat (Debian patchlevel 1.218-4ubuntu1)
```


avoir un firewall actif
SELinux (systèmes RedHat) et/ou AppArmor (plutôt sur Ubuntu) désactivés
```
vagrant@ubuntu2204:/$ sudo systemctl stop apparmor 
vagrant@ubuntu2204:/$ sudo systemctl disable apparmor
Synchronizing state of apparmor.service with SysV service script with /lib/systemd/systemd-sysv-install.
Executing: /lib/systemd/systemd-sysv-install disable apparmor

vagrant@ubuntu2204:/$ ssystemctl statusatus firewalld
● firewalld.service - firewalld - dynamic firewall daemon
     Loaded: loaded (/lib/systemd/system/firewalld.service; enabled; vendor preset: enabled)
     Active: active (running) since Mon 2024-06-10 13:19:54 UTC; 51s ago
       Docs: man:firewalld(1)
```


pour repackager une box, vous pouvez utiliser les commandes suivantes :


````
PS C:\Users\Ethan\Vagrant\Test> vagrant box list  
>> 
generic/rhel8      (virtualbox, 4.3.12, (amd64))
generic/ubuntu2204 (virtualbox, 4.3.12, (amd64))
````


🌞 Ecrivez un Vagrantfile qui lance une VM à partir de votre Box

````
# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure("2") do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://vagrantcloud.com/search.
  config.vm.box = "super_box"
````


 ## 3. Moult VMs
Pour cette partie, je vous laisse chercher des ressources sur Internet pour les syntaxes. Internet regorge de Vagrantfile d'exemple, hésitez po à m'appeler si besoin !

🌞 Adaptez votre Vagrantfile pour qu'il lance les VMs suivantes (en réutilisant votre box de la partie précédente)

vous devez utiliser une boucle for dans le Vagrantfile

pas le droit de juste copier coller le même bloc trois fois, une boucle for j'ai dit !

[Vagrantfile A](./partie1//Vagrantfile-3A/)


📁 partie1/Vagrantfile-3A dans le dépôt git de rendu
🌞 Adaptez votre Vagrantfile pour qu'il lance les VMs suivantes (en réutilisant votre box de la partie précédente)

[Vagrantfile B](./partie1//Vagrantfile-3B/)


📁 partie1/Vagrantfile-3B dans le dépôt git de rendu

La syntaxe Ruby c'est vraiment dégueulasse.