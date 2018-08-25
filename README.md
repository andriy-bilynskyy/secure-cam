# secure-cam

### NGINX
 
	sudo apt-get install nginx
	sudo apt-get install php5-fpm
	sudo nano /etc/nginx/sites-enabled/default

	index index.php index.html index.htm;

	# pass the PHP scripts to FastCGI server listening on 127.0.0.1:9000
	#
	location ~ \.php$ {
		include snippets/fastcgi-php.conf;

		# With php5-cgi alone:
	#       fastcgi_pass 127.0.0.1:9000;
		# With php5-fpm:
		fastcgi_pass unix:/var/run/php5-fpm.sock;
	}

	sudo /etc/init.d/nginx reload
	sudo /etc/init.d/nginx start

### Allow start/stop service from php
	
        sudo visudo

        add line www-data ALL = NOPASSWD: /usr/sbin/service you-stream_serv stop, /usr/sbin/service you-stream_serv start

        sudo visudo -c
