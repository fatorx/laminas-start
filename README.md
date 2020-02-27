# Iniciando com Laminas

## Instalação do exemplo

```bash
$ git clone git@github.com:fatorx/laminas-start.git
```

```bash
$ cd laminas-start
```

```bash
$ composer install
```

## Configuração

- Renomear o arquivo local.php.dist para local.php e adicionar suas credencias.
- Executar o comando para gerar o banco de dados : vendor/bin/doctrine-module orm:schema-tool:create
