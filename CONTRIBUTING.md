# Contribuindo

Ahhh o doce mundo dos softwares livres é algo simplesmente lindo. Só de pensar em
pessoas de pontos diferentes do mundo se ajudando com o objetivo de melhorar
o software em questão, cada um com seu ponto de vista e a plena liberdade para
utilizar, sugerir mudanças e inclusive realizar modificações no código.

Você que chegou aqui com esse objetivo, nós do PHP Santa Catarina agradecemos!

Testes de unidades, documentação, novas funcionalidades, sugestões... temos muitas
áreas que com certeza você tem plenas possibilidades de nos ajudar! Aliás, você
já conferiu nossa lista de [tarefas](https://github.com/PHPSC/phpsc-conf/issues)?

## Ferramentas básicas

Antes de explicarmos a estrutura básica do projeto é necessario conhecermos
as ferramentas fundamentais que utilizamos para organizar o desenvolvimento.

### git-flow

A partir [deste post](http://nvie.com/posts/a-successful-git-branching-model)
surgiu uma extensão para o git, o [git-flow](https://github.com/nvie/gitflow).

Esta ferramenta é extremamente útil para agilizar o desenvolvimento usando
branchs.

Leia o [post](http://nvie.com/posts/a-successful-git-branching-model) e instale
a ferramenta para poder acompanhar as explicações que virão mais abaixo.

### Composer

O [composer](http://getcomposer.org) é uma ferramenta de gerenciamento de
dependências de projetos PHP. Com ele é possível realizar a instalação de
todas as bibliotecas que o software necessita utilizando um único comando.

Recomendamos também a leitura dos slides [desta palestra](http://www.slideshare.net/rdohms/composer-for-busy-developers-dpc13)
do nosso amigo [Rafael Dohms](https://github.com/rdohms).

### Doctrine Console

Utilizamos o [doctrine 2](http://docs.doctrine-project.org/en/latest/index.html)
como ORM neste projeto, e para os processos manipulação (criação, atualização, remoção)
do schema do banco de dados utilizamos o [doctrine console](http://docs.doctrine-project.org/en/latest/reference/tools.html).
O arquivo de configuração já existe e você deve apenas utilizar o doctrine
console, como iremos explicar abaixo.

Caso não conheça bem o [doctrine 2](http://docs.doctrine-project.org/en/latest/index.html),
recomendamos fortemente a leitura da documentação para o mapeamento utilizando
annotations.

## Requisitos mínimos

No momento estamos limitados ao PHP 5.3, então todas as modificações devem
ser compatíveis com esta versão da linguagem.

## Code style

Todas as alterações DEVEM seguir a [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

## Preparando o terreno

Você quer realizar alterações no código então? Muito bem, para
começar a brincadeira você deve seguir os passos abaixo:

1. Crie um [fork](https://help.github.com/articles/fork-a-repo) e clone o projeto;
1. Instale as dependências utilizando o composer (```composer install```);
1. Defina as permissões de diretórios com o phing (```vendor/bin/phing post-install```);
1. Aponte o branch local master para origin/master (```git
branch master origin/master```);
1. Inicialize o git-flow (```git flow init -d```);
1. Crie seu branch
    * Caso seja uma nova funcionalidade você deve executar o
      comando ```git flow feature start $feature```, onde $feature
      é um identificador para a funcionalidade a ser implementada;
    * Caso você opte por corrigir um bug você deverá inicializar
      um hotfix branch ```git flow hotfix start $hotfix```, onde
      $hotfix deverá ser a versão atual, incrementando o número
      da última posição (PATCH).

Se tiver dúvidas a respeito do versionamento, leia [esta especificação](https://github.com/wendtecnologia/semver/blob/2.0.0-rc.1/semver.pt-br.md) ([ou sua versão original, em inglês](http://semver.org/spec/v2.0.0.html)).

## Configuração do container de injeção dependência

Dentro da pasta ```config``` estão os arquivos de configuração
do container de injeção de dependência do projeto (services.xml e environment.xml).

Estes arquivos seguem o padrão estabelecido pelo [componente de
injeção de dependências do symfony 2](http://symfony.com/doc/current/components/dependency_injection/introduction.html),
o qual também recomendamos a leitura.

Para realizar a configuração, você deve copiar o arquivo **environment.xml**
para o arquivo **environment.local.xml**. No arquivo recém criado você realizará
as suas alterações, mas antes você deve remover dele a tag ```<services>``` e seu conteúdo.

O arquivo **environment.local.xml** conterá todas as suas configurações locais, e 
ele está registrado para ser ignorado pelo GIT, portanto nunca será enviado ao
repositório.

**Importante:** o arquivo **services.xml** é o arquivo principal de carregamento
do container de injeção de dependência e o cache do container é atualizado somente
quando ele sofre alguma modificação, portanto caso você altere os outros arquivos
XML você deve executar o comando ```touch services.xml``` ou realizar uma mudança
simples no arquivo e salvar para que seja atualizada a data de modificação do mesmo.

## Criação do banco de dados

Como mencionado anteriormente utilizamos o doctrine console para a administração
do schema do banco dados. Siga os passos abaixo para criar todas as tabelas do banco
de dados:

1. Crie um schema vazio com o mesmo nome que foi configurado no container de injeção
   de dependências (ele deve ser criado com o charset **UTF-8**), conforme demonstrado abaixo:

    ```sql
    mysql -uroot -e "CREATE SCHEMA phpsc DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;"
    ```
    
1. Execute o seguinte comando a partir da raiz do projeto. Isto irá criar todas as tabelas no banco de dados:

    ```./vendor/bin/doctrine migrations:migrate```

1. Realize a importação das fixtures padrões utilizando o comando 

    ```./vendor/bin/doctrine doctrine:fixtures:execute --import```

## Alterações no banco de dados

Utilizamos a extensão de migrations do doctrine para controlarmos as alterações do banco de dados, para
criar uma nova versão deve-se executar o seguinte comando a partir da raiz do projeto: 

   ```./vendor/bin/doctrine migrations:diff```
    
Ele irá criar um arquivo dentro da pasta ```db-versions``` com as queries de modificação do schema, **confira muito bem** as
queries geradas (tanto os comandos que adicionam as modificações quanto os que revertem elas).

O comando ```./vendor/bin/doctrine migrations:migrate``` é responsável por executar as migrações que ainda não foram aplicadas,
garantindo assim que o banco de dados esteja sempre sincronizado com as modificações do código fonte.


## FAQ - Perguntas Frequentes

Se estiver enfrentando problemas com o ambiente de desenvolvimento, ou se tiver alguma dúvida, dê uma olhadinha em nosso [FAQ](https://github.com/PHPSC/phpsc-conf/wiki/FAQ). Talvez você consiga uma resposta lá.
