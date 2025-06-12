# Start from the official Nginx Alpine image
FROM nginx:1.27.3-alpine

# Set an argument for the user and group ID
ARG UID=1000
ARG GID=1000

# This is the idempotent part.
# First, check if a group with GID 1000 exists. If not, create www-data with GID 1000.
# Then, check if a user with UID 1000 exists. If not, create www-data with UID 1000
# and add it to the www-data group. This logic prevents "already exists" errors.
RUN if ! getent group ${GID} > /dev/null; then \
        addgroup -g ${GID} www-data; \
    fi && \
    if ! getent passwd ${UID} > /dev/null; then \
        adduser -D -S -h /var/cache/nginx -s /sbin/nologin -G www-data -u ${UID} www-data; \
    fi

# Tell Nginx to run as this user.
RUN sed -i 's/user  nginx;/user  www-data;/g' /etc/nginx/nginx.conf