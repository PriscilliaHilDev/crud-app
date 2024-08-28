# Dockerfile pour Développement

FROM php:8.2-fpm

# Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    curl \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql gd

# Installer Yarn
RUN npm install --global yarn

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le contenu de votre projet Laravel
COPY . .

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer les dépendances de votre application Laravel
RUN composer install --no-dev --optimize-autoloader

# Copier les fichiers de configuration de Node.js avant d'installer les dépendances
COPY package.json yarn.lock ./

# Installer les dépendances de votre application Vue.js
RUN yarn install

# Exposer le port pour le serveur de développement
EXPOSE 5173

# Commande pour lancer le serveur de développement et PHP-FPM
CMD ["bash", "-c", "yarn run dev & php-fpm"]