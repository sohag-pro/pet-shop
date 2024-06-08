# Test image locally

1. docker build -t realbex-acr:v1 .
2. docker run -it -p 80:80  --name realbex-acr realbex-acr:v1  /bin/bash
3. service apache2 restart
4. browse `localhost` on your browser


# Deploy in production

1. comment out test env variables in `app/.env` (line: 6, 7, 8, 9)
2. uncomment production variables in `app/.env` (line: 12, 13, 14, 15)
3. docker build -t realbex-acr:v1 .
4. And your procedure to deploy in azure

# login credentials for both test and production

* email: `user@gmail.com`
* password: `password`

