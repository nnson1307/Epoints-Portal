FROM richarvey/nginx-php-fpm:1.9.1

# Cau hinh timezone +7
RUN apk add tzdata
RUN cp /usr/share/zoneinfo/Asia/Ho_Chi_Minh /etc/localtime
RUN echo "Asia/Ho_Chi_Minh" > /etc/timezone

# Copy vhost config
COPY ./nginx-site.conf /etc/nginx/sites-available/default.conf
COPY ./suppervisord.conf /etc/supervisor/conf.d/init-service.conf

# Create source folder & Copy it
RUN mkdir -p /var/www/html/web
COPY . /var/www/html/web
RUN cd /var/www/html/web/devops && unzip vendor.zip git
RUN mv /var/www/html/web/devops/vendor /var/www/html/web/vendor
RUN cd /var/www/html/web && php composer.phar dump-autoload
RUN cd /var/www/html/web && php artisan laroute:generate

# Create the log file to be able to run tail
RUN echo '' > /root/project_env.sh
RUN echo '*  *  *  *  *    cd /var/www/html/web && php artisan schedule:run >> /var/log/cron.log 2>&1' > /etc/crontabs/root
RUN echo '0 * * * * chmod -R 777 /var/www/html/web/storage >> /var/log/cron.log 2>&1' >> /etc/crontabs/root

# Create the log file to be able to run tail
RUN touch /var/log/cron.log

RUN chmod -R 777 /var/www/html/web/bootstrap
RUN chmod -R 777 /var/www/html/web/storage
RUN chown -R nginx:nginx /var/www/html/web


