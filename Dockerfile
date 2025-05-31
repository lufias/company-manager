FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set document root to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Apply document root setting
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# Install system dependencies for PHP extensions
RUN apt-get update && \
    apt-get install -y libsqlite3-dev pkg-config git unzip libzip-dev supervisor && \
    docker-php-ext-install pdo pdo_sqlite zip && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 20.19.2
RUN curl -fsSL https://nodejs.org/dist/v20.19.2/node-v20.19.2-linux-x64.tar.xz -o node.tar.xz \
    && tar -xf node.tar.xz \
    && cp -R node-v20.19.2-linux-x64/bin/* /usr/local/bin/ \
    && cp -R node-v20.19.2-linux-x64/include/* /usr/local/include/ \
    && cp -R node-v20.19.2-linux-x64/lib/* /usr/local/lib/ \
    && cp -R node-v20.19.2-linux-x64/share/* /usr/local/share/ \
    && rm -rf node.tar.xz node-v20.19.2-linux-x64

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
