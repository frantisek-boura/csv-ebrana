1. Naklonovat repository

```
$ git clone git@github.com:frantisek-boura/csv-ebrana.git
```

2. Stahnout dependencies
   
```
csv-ebrana/csv-app $	composer install
csv-ebrana/frontend $	npm install
```

3. Spustit db container

```
csv-ebrana/csv-app $	docker-compose up -d
```

4. Zjistit port, na kterem bezi db container

```
csv-ebrana/csv-app $	docker container ps -a
```

5. Upravit port DATABASE_URL v souboru .env na port db containeru

```
DATABASE_URL="mysql://root:root@127.0.0.1:<port>/root?serverVersion=8.0.32&charset=utf8mb4"
```
					       
6. Syncnout db schema

```
csv-ebrana/csv-app $	./bin/console doctrine:database:create
csv-ebrana/csv-app $ 	./bin/console doctrine:schema:update --force
```

7. Zapnout servery

```
csv-ebrana/csv-app $	symfony serve
csv-ebrana/frontend $   npm start
```

9. Nahrat pres reactjs aplikaci na server soubor products.csv

![image](https://github.com/frantisek-boura/csv-ebrana/assets/167188267/5b091a0e-825e-4086-a12c-71be79d8e8b3)
