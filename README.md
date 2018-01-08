# STI-Project-2
A second phase of the first STI project aiming to add  more security features to the web app

## Getting started
### Run the app using docker
> To run our `dockerized` web app, you should have `ce version 17.10.x` or higher in your host system.

***Steps***

-	First clone the repo
-	Place yourself at the root of the repo
-	In your command line or `Docker QuickStart Terminal` type 

```bash
docker build --rm -t sti/securephpserver .
docker run -d --rm --name securephpserver -p 80:80 -v <path to the html folder on your local machine>:/var/www/html/ sti/securephpserver
```	
> You can run ```./start-server <servername> <path to html folder oin your local machine>``` instead of typing the second line above.
	Beware of the fact that for docker to share volumes correctly, the drive in which your html folder is stored should be shared between your local machine and your docker engine. Please refer to the [official documentation](https://docs.docker.com/engine/admin/volumes/volumes/) for detailed information. 
-	Open your favorite browser and type the url `<your docker machine ip>:80/`.
	Where `<your docker machine ip>` is the ip address of your docker engine. This could be `localhost` if you're using `docker` natively for `linux`, `windows` or `mac` or `192.168.99.100` if you're using `Docker-Toolbox`. 

