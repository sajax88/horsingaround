# Create and chown dirs
mkdir -p  /var/www/html/logs && chown -R www-data /var/www/html/logs
mkdir -p  /var/www/html/cache/volt && chown -R www-data /var/www/html/cache/volt

# Install composer dependencies
composer install

# Create demo user and perform other init tasks
php /var/www/html/cli.php init

# Install crontab
crontab < cron.jobs
service cron start
