FROM node:14

WORKDIR /app

COPY ./frontend/vue /app

RUN npm install

EXPOSE 5173

CMD ["npm", "run", "dev"]