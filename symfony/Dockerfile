FROM php:8.2.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    wget;

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip;

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer;

# Install Symfony CLI
RUN wget https://get.symfony.com/cli/installer -O - | bash;
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony;

# Enable Apache mod_rewrite
RUN a2enmod rewrite;

# Set working directory
WORKDIR /var/www/html

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY composer.* symfony.* ./
RUN composer install --no-scripts;

COPY . .
RUN mkdir -p var/cache var/log; composer run-script post-install-cmd;

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
