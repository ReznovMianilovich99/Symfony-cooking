FROM php:8.2-cli

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app/.

# Ensure necessary directories exist
RUN mkdir -p /var/log/nginx && mkdir -p /var/cache/nginx

# Install dependencies
RUN composer install --ignore-platform-reqs

# Set the port Symfony will use
ENV PORT=8000

# Expose the application's port
EXPOSE 8000

# Start the Symfony server
CMD ["symfony", "server:start", "--port=8000"]