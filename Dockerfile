# Time->Tech Blog - PHP Application Dockerfile
FROM php:8.2-apache

# 시스템 패키지 설치
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# PHP 확장 설치
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo \
    pdo_mysql \
    gd

# Apache mod_rewrite 활성화
RUN a2enmod rewrite

# Apache 설정 - .htaccess 허용
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# 작업 디렉토리 설정
WORKDIR /var/www/html

# 소스 파일 복사
COPY . /var/www/html/

# uploads 디렉토리 생성 및 권한 설정
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/uploads

# PHP 설정 (업로드 크기 등)
RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 12M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini

# 포트 노출
EXPOSE 80

# Apache 실행
CMD ["apache2-foreground"]
