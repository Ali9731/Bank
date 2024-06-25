<p align="center">
<h1 align="center">Bank Test Project</h1>
</p>

## important points
the project uses docker</br>

laravel pint for check  psr-12 rules</br>

./commander bash script for easier use</br>

seeder completely fills all required data</br>

sms service with strategy pattern</br>

the card validation regex is not compatible with iranian credit cards but its compatible with faker credit card numbers it can be replaced with another regex</br>



> Important: This Project Requires : docker - docker compose - php composer - php v8



## Project command

1 - To get docker images, install composer,migrations and db seed run this command
```
./commander initiate
```
then choose yes for every questions in cli </br>

Or
```
# get packages
        1 - composer install
    
        2-  docker-compose up -d

        3 - docker exec -it bank_laravel.test_1 bash -c "php artisan migrate && php artisan db:seed"
```

then choose yes for every questions in cli

2 - To test code with linter run this command
```
./commander lint
```

3 - To run project
```
./commander up
```
4 - To close project
```
./commander down
```
## Api
- List of system cards
```
"localhost/api/cards"
method : get
```

- Create transaction
```
"localhost/api/transaction"
method : post
parameters : 
            from_card => 16 digits ,
            to_card => 16 digits ,
            amount => between 1,000 and 50,000,000
```
- Top users
```
"localhost/api/top-users"
method : get

```
