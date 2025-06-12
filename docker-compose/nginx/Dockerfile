# Start from the official Nginx Alpine image we are already using
FROM nginx:1.27.3-alpine

# Create a 'www-data' user and group with the same UID (1000)
# as our PHP container. This is the key to fixing all permissions.
RUN addgroup -g 1000 www-data && \
    adduser -D -S -h /var/cache/nginx -s /sbin/nologin -G www-data -u 1000 www-data

# Tell Nginx's main configuration to run its worker processes as this new user.
RUN sed -i 's/user  nginx;/user  www-data;/g' /etc/nginx/nginx.conf