FROM debian:buster

RUN apt-get update && apt-get install -y --no-install-recommends \
  curl \
  wget \
  git \
  zip \
  unzip \
  vim \
  nano \
  apt-utils \
  ca-certificates

RUN apt-get install -my nginx

RUN usermod -u 1000 www-data

WORKDIR /etc/nginx
COPY nginx.conf nginx.conf
ADD sites-available sites-available

RUN rm sites-enabled/default
RUN rm sites-available/default
RUN ln -s /etc/nginx/sites-available/blog.conf /etc/nginx/sites-enabled

WORKDIR /var/www/html/blog
EXPOSE 80
EXPOSE 443
CMD ["nginx"]
