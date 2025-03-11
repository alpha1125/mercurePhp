# Mercure example w/PHP #

# NOT FOR PROD use, example only #

Symfony, has a nice ability to install self-signed certificates. This gets around a bunch of certificate issues. 

Using Symfony built in server, and Mercure hub, we can create a simple example of a publisher and subscriber.  As well as private topics example.

```zsh
symfony local:server:ca:install
```


In the current project root:
run the following: 
```zsh
# Extract the private key
openssl pkcs12 -in ~/.symfony5/certs/default.p12 -nocerts -nodes -out ./cert/mercure.key

# Extract the certificate
openssl pkcs12 -in ~/.symfony5/certs/default.p12 -clcerts -nokeys -out ./certs/mercure.crt
```

# Start the server
```zsh
docker-compose up -d
symfony server:start -d
```

The "-d" in both commands is to run them in the background.

Assuming symfony is running on port 8000 https://localhost:8000


Nav to it, you should see the main page (client/subscriber). You can open a bunch of them in different tabs/windows/browsers.


in the CLI console again , you should run the following:

```zsh
php publish.php
```

you should then see in your browser the message being published to the client/subscriber.



# Example 1: simple 
## index.php
https://localhost:8000/index.php # open a few of these.

```zsh
php publish.php # sends a message to all open pages (including different browsers) that have the above page open.
```

## indexUser.php
https://localhost:8000/indexUser.php?user=1
https://localhost:8000/indexUser.php?user=2
...


```zsh
php publishUser.php user=1 # will send a message to user 1
php publishUser.php user=2 # will send a message to user 2
...

php publish.php # will send a message to all users
```


# Excercise for the reader
Docker will need to hardened, this is using the official image. 


