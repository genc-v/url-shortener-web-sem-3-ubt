FROM nginx:alpine

COPY ./index.html /usr/share/nginx/html/
COPY ./assets /usr/share/nginx/html/assets
COPY ./components /usr/share/nginx/html/components
COPY ./pages /usr/share/nginx/html/pages
COPY ./utils /usr/share/nginx/html/utils

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
