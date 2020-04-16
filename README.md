Requistos
---------

* PHP 5.6 >= 7.2
* Postgresql >= 9.2
* Docker
* Node
* Yarn
* Ansible


Dev local app
--------------

Instrucciones

1. git clone git@github.com:Beatt/sf-boilerplate-imms.git
2. cd sf-boilerplate-imms
3. Crear archivo app/config/parameters.yml e incluir la siguiente config
    1. ```
       parameters:
           database_host: 127.0.0.1
           database_port: 33417
           database_name: main
           database_user: main
           database_password: main
           mailer_transport: smtp
           mailer_host: 127.0.0.1
           mailer_user: null
           mailer_password: null
           secret: ThisTokenIsNotSoSecretChangeIt
       ```
       [Nota: Si están usando su base de datos montada en su maquina deberán cambiar las claves de `database_`]
4. docker-compose up -d
5. bin/console server:run
6. yarn encore dev --watch
7. http://localhost:8001

Deploy digitalocean
--------------

1. cd sf-boilerplate-imms
2. ansible-playbook ansible/deploy.yml -i ansible/hosts.ini --ask-vault-pass
3. Contraseña: imss1234
4. Escribir rama a desplegar
5. Enter
