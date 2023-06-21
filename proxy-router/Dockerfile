FROM node:18.1.0
WORKDIR /usr/src/app

COPY package.json ./
RUN npm install

COPY . .

EXPOSE 8088

CMD [ "node", "server.js" ]
