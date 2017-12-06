FROM phusion/baseimage

EXPOSE 443

RUN apt-get update && \
    apt-get install -y nginx && \
    apt-get install -y php5-fpm

# Add dds-hashcode to container
RUN mkdir /apps
COPY . /apps

# Setup nginx configuration
RUN rm /etc/nginx/sites-enabled/default && \
    cp /apps/docker-config/nginx/default.conf /etc/nginx/sites-enabled/default

# Setup upload directory
RUN chmod 777 /apps/example/app/upload

CMD service php5-fpm stop && service php5-fpm start && service nginx restart && \
    tail -f /var/log/nginx/error.log