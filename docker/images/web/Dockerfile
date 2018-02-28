FROM alpine:3.7

RUN apk add --update nginx \
    && rm -rf /var/cache/apk/*

WORKDIR /reversi

COPY docker/images/web/nginx_site.conf /etc/nginx/conf.d/reversi.conf

# nginx config
RUN ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log \
    && rm /etc/nginx/conf.d/default.conf \
    && mkdir -p /var/run/nginx

COPY app/public/ ./app/public

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
