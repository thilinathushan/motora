# Start from the official Nginx Alpine image
FROM nginx:1.27.3-alpine

# Set arguments for the desired UID and GID
ARG UID=1000
ARG GID=1000

# This is the definitive "delete and recreate" logic.
# It ensures no conflicts with the base image's users/groups.
RUN \
    # First, delete the user and group if they exist, to avoid conflicts.
    if getent passwd www-data > /dev/null; then deluser www-data; fi && \
    if getent group www-data > /dev/null; then delgroup www-data; fi && \
    \
    # Now, create our user and group with the correct IDs.
    addgroup -g ${GID} www-data && \
    adduser -D -S -h /var/cache/nginx -s /sbin/nologin -G www-data -u ${UID} www-data

# Tell Nginx to run its worker processes as our new www-data user.
RUN sed -i 's/user  nginx;/user  www-data;/g' /etc/nginx/nginx.conf