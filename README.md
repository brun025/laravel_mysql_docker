# Laravel Sistema de Gerenciamento

## adminlte-docker-laravel
AdminLTE rodando em um container Docker com PHP, Laravel, MySQL, nginx e outros pacotes úteis como infyom generator, laravel-permissions, medialibrary, domPDF, yajra-datatables, entre outros

## Seeders
Seeder com Super Admin, Admin e status_user table.

## Setup
Remover as configurações do git

```
rm -rf .git
```

Configurar e subir todos os containers da primeira vez;

```
make setup
```

Subir as migrations e os seeders, abra um novo terminal e digite

```
make key:db
```

Acessar link da aplicação

```
http://localhost
```

## Extras

Parar todos os containers

```
make stop
```

Remover todos os containers

```
make down
```

Subir todos os containers

```
make up
```
