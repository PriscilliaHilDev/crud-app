# Étape 1 : Construction de l'application
FROM php:8.2-fpm AS builder

# Installer les dépendances système et Node.js 20.x
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    gnupg2 \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install --global yarn \
    && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP nécessaires
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier uniquement les fichiers package.json et yarn.lock pour les dépendances frontend
COPY package.json yarn.lock ./

# Installer les dépendances de Vue.js
RUN yarn install

# Copier le reste du projet Laravel dans le conteneur
COPY . .

# Installer Composer et les dépendances PHP de Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# Étape 2 : Image finale
FROM php:8.2-fpm

# Copier les fichiers nécessaires depuis l'étape de construction
COPY --from=builder /var/www/html /var/www/html

# Exposer le port pour le serveur de développement de Vite (Vue.js)
EXPOSE 5173

# Exposer le port pour PHP-FPM
EXPOSE 9000

# Commande pour lancer le serveur de développement et PHP-FPM
CMD ["bash", "-c", "cd /var/www/html && yarn run dev & php-fpm"]
