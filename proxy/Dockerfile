FROM uhopper/ubuntu:12.04

# Install packages
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get -y upgrade && DEBIAN_FRONTEND=noninteractive apt-get -y install supervisor git apache2 libapache2-mod-php5 php5-mysql php5-curl imagemagick php5-imagick

# Add image configuration and scripts
ADD docker_support/start-apache2.sh /start-apache2.sh
ADD docker_support/run.sh /run.sh
ADD docker_support/supervisord-apache2.conf /etc/supervisor/conf.d/supervisord-apache2.conf

ADD docker_support/i-locate.conf /etc/apache2/sites-available/
RUN ln -s /etc/apache2/sites-available/i-locate.conf /etc/apache2/sites-enabled/
RUN a2enmod rewrite


# Configure /app folder with the app
RUN mkdir -p /app && rm -fr /var/www && ln -s /app /var/www
ADD / /app/

# copy python script to generate configuration settings
ADD docker_support/prepare_configurations /prepare_configurations
RUN chmod 755 /prepare_configurations 

# copy .htaccess files with correct aliases
ADD docker_support/.htaccess /.htaccess
RUN cp -f /.htaccess /app/frontend/www 
#RUN cp -f /.htaccess /app/backend/www 
RUN cp -f /.htaccess /app/api/www 

RUN sed -i 's/${custom_alias}/frontend/g' /app/frontend/www/.htaccess
#RUN sed -i 's/${custom_alias}/admin/g' /app/backend/www/.htaccess
RUN sed -i 's/${custom_alias}/api/g' /app/api/www/.htaccess

RUN chmod 755 /*.sh

EXPOSE 6379

EXPOSE 80
CMD ["/run.sh"]
