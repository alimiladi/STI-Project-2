FROM php:7.2.0-apache-stretch

MAINTAINER Ali Miladi <ali.miladi@heig-vd.ch>

COPY html/ /var/www/html/

COPY conf/php.ini /usr/local/etc/php/

RUN apt-get update && \
	apt-get install -y dos2unix && \
	dos2unix /var/www/html/*.php && \
	dos2unix /var/www/html/*.css && \
	dos2unix /var/www/html/*.js &&\
	mkdir /var/www/databases/ && \
	touch /var/www/databases/database.sqlite && \
	chmod -R a+rw /var/www/databases/ && \
	service apache2 start