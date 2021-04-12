FROM nginx:1.19.6

COPY ./build/nginx/nginx.conf /etc/nginx/
COPY ./build/nginx/symfony.conf /etc/nginx/conf.d/

RUN echo "upstream php-upstream { server php:9001; }" > /etc/nginx/conf.d/upstream.conf

CMD ["nginx"]

EXPOSE 80
EXPOSE 443
