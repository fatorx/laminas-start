# Iniciando com Laminas

## Instalação do exemplo

```bash
$ composer install
```

At this point, you can visit http://localhost:8080 to see the site running.

You can also run composer from the image. The container environment is named
"laminas", so you will pass that value to `docker-compose run`:

```bash
$ docker-compose run laminas composer install
```

## 