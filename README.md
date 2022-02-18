# Simple-MVC-Structure

## Descrição

Este é um microframework MVC desenvolvido em PHP.

## Requisitos

* Composer
* PHP 7 ou superior

## Instruções

* Crie um arquivo "config.php" ou renomeie o arquivo "example.php" no diretório "config".
* Use o comando "composer update" ou "composer install" para baixar a pasta "vendor" com o "autoload".
* Inicie o servidor na pasta "public".

### Exemplo

Clonar o projeto:

    git clone https://github.com/yuri97real/Simple-MVC-Structure.git

Entre na pasta e crie o arquivo de configurações:

    cd Simple-MVC-Structure/
    cp config/example.php config/config.php

Baixar as dependências com o composer:

    composer update

Iniciar o servidor:

    php -S localhost:3333 -t public/

## Rotas e Namespaces

Você pode definir as rotas no arquivo "public/routes.php".
Lá estará o objeto $router, que contém os 4 verbos principais.

### Exemplo 1 (Rotas Simples)

    $router->namespace("Controllers");

    $router->get("/route/example", "classe::index");
    $router->post("/route/example", "classe::create");

### Exemplo 2 (Rotas Com Parâmetros)

    $router->get("/route/example/{id}", "classe::metodo");

### Exemplo 3 (Páginas Estáticas)

    $router->post("/route/example/{id}", function($req, $res) {

        $res->view("path/file.html");

    });

## Dados

Numa requisição, há 3 formas principais de se receber dados e parâmetros:
    
### Params

São dados recebidos na url e são obrigatórios.

Exemplo:

    Rota:
    "/produto/25"

No exemplo acima, o "id" é obrigatório para buscar os dados do produto 25.

### Query

São dados recebidos na url e são opcionais.

Exemplo:

    Rota:
    "/produtos/?pagina=2&filtro=blusa"

Podemos considerar que o exemplo acima é uma listagem de produtos.
A listagem é independente da paginação ou do filtro.

### Body

São dados recebidos no corpo da requisição, geralmente por formulários ou no formato JSON.

Exemplo:

    Rota:
    "/criar/usuario"

Podemos considerar que estamos acessando a rota acima com o método POST.

Nesse caso, é esperado que os dados do usuário a ser criado, estejam no corpo da requisição:

    {
        "name": "yuri seabra maciel",
        "age": 24,
    }

## Acessando Dados

Para acessar os dados nos 3 formatos listados acima, você pode utilizar o parâmetro <strong>request</strong>.

### Exemplo

    use Core\iRequest;
    use Core\iResponse;

    class ClassExample {

        public function method(iRequest $request, iResponse $response) {

            $params = $request->params();
            $query = $request->query();
            $body = (object) $request->body();

            echo $params["id"];
            echo $query["page"];
            echo $body->category;

        }

    }

## Conexão Com Banco de Dados

Configurações como:

* driver
* host
* port
* dbname
* username
* password
* options

Devem ser informados no arquivo "config/config.php" para conexão com o banco de dados.

Normalmente, é necessário alterar somente <strong>usuário</strong> e <strong>senha</strong>.

### Uso

1. Crie suas camadas de modelos na pasta "app/Models".
2. Estenda suas classes modelos com a principal.

### Exemplo

Crie um modelo de produtos.

    namespace App\Models;
    
    use Core\Model;

    class ProductModel extends Model {

        /*
            * @param    string  $query
            * @param    array   $values
            * @param    bool    $list
            *
        */

        public function getAll() {

            return $this->exec("SELECT * FROM PRODUCTS");

        }

        public function getByID(int $id) {

            $query = "SELECT * FROM PRODUCTS WHERE id = ?";
            $values = [1];
            $fetch_all = false;

            return $this->exec($query, $values, $fetch_all);

        }

        public function getByCategories(array $categories) {

            $query = "SELECT * FROM PRODUCTS WHERE category IN (?, ?, ?)";
            return $this->exec($query, $categories);

        }

    }

Chame o método no controlador correspondente:

    use Core\iRequest;
    use Core\iResponse;

    use App\Models\ProductModel;

    class ProductController {

        public function index(iRequest $request, iResponse $response) {

            $model = new ProductModel;
            $products = $model->getAll();

            $response->json($products);

        }

    }

Note que os exemplos acima, utilizam a sintaxe pura do SGBD (Sistema Gerenciador de Banco de Dados) MySQL/MariaDB.

Dito isso, minha recomendação é, utilize um *query builder* ou similar. Em resumo, pode ser uma biblioteca ou componente que cria as queries, utilizando a mesma sintaxe da linguagem de progração do back-end, que neste caso, é o PHP.

A grande vantagem dessa abordagem, é poder alternar o driver do SGBD sem se preocupar com a sintaxe do mesmo.

Recomendo ver alguns, como o <a href="https://packagist.org/packages/illuminate/database" target="_blank">Illuminate Database</a> ou o <a href="https://packagist.org/packages/coffeecode/datalayer" target="_blank">Coffeecode Datalayer</a>.
    
## HTML, Títulos e Favicons

O parâmetro <strong>response</strong> possui métodos de resposta, inclusive páginas HTML.

O método "view" do <strong>response</strong>, permite importar um arquivo da pasta "app/Views" e enviar parâmetros para ele.

Para importar o arquivo "app/Views/home/index.php", por exemplo, usamos:

    $response->view("home/index");

Como segundo parâmetro deste método, podemos enviar um array com diversos argumentos.

### Exemplo 1

    use Core\iRequest;
    use Core\iResponse;

    class Product {

        public function index(iRequest $request, iResponse $response) {

            $products = ["camisa", "blusa"];

            $response->view("list/products.php", [
                "products"=> $products,
            ]);

        }
    }

No exemplo acima, é esperado que seja renderizado o conteúdo HTML que está no arquivo "app/Views/list/products.php".

### Exemplo 2

No próprio arquivo de rotas "public/routes.php", é possível renderizar um conteúdo HTML.

    $router = new Core\Router;

    $router->namespace("Controllers");

    $router->get("/produtos", function($req, $res) {

        $products = ["camisa", "blusa"];

        $response->view("list/products.php", [
            "products"=> $products,
        ]);

    })

## CSS e JavaScript

Arquivos CSS e JavaScript devem estar no diretório "public", para acesso direto no html.

### Exemplo

Se um arquivo estiver no caminho "public/css/themes.css", basta referenciá-lo no html desta forma:

    <link stylesheet="css" href="/css/themes.css">

## API

Para este tipo de requisição, a resposta deve ser no formato JSON:

    $response->json(array $items);

O método acima, deve exibir um array no formato JSON.

## Recomendações

<strong>Se estiver usando Windows com *Xampp*, *Laragon* e similares, pode ignorar esta etapa.</strong>

Essas ferramentas, já contam com diversas configurações para flexibilizar o desenvolvimento.

## Linux

Em Linux, ao baixar o pacote <strong>PHP</strong>, algumas bibliotecas vem desativadas.

Portanto, se você for um iniciante ou não tiver experiência com gerenciamento de pacotes, logo abaixo estão algumas instruções e comandos extras que você pode executar para evitar futuras dores de cabeça.

### HTACCESS

Você deve habilitar o rewrite do apache, para uso do ".htaccess".
Você pode ver um exemplo neste <a href="https://youtu.be/GsxhN4HBnC8?t=1893" target="_blank">vídeo</a>.

### PACOTES

Dependendo da sua distribuição Linux, algumas das extensões abaixo podem vir desativadas por padrão. Você pode executar o comando abaixo para ativá-las em distros baseadas no Ubuntu, Debian e similares.

    sudo apt install php-gd php-mysql php-zip php-curl php-pdo

No Fedora, troque apenas "apt" por "dnf".

    sudo dnf install php-gd php-mysqlnd php-zip php-curl php-pdo

É possível ativá-las manualmente também. Dessa forma, é possível ter o mesmo efeito em quase todas as distros Linux.

Para isso, você deve procurar pelo arquivo "php.ini".

    php --ini

O comando acima irá listar os diretórios do PHP. O que precisamos é o "Loaded Configuration File", que tem o caminho do arquivo "php.ini".

No arquivo "php.ini", as extensões desativadas do PHP, estão comentadas com um <strong>#</strong> na frente do nome da extensão. Descomente somente aquelas que precisar.

Nos meus projetos e na maior parte dos componentes que eu utilizo, eu uso:

* gd
* mysql ou pgsql
* pdo
* zip
* curl

Os itens acima, são extensões do PHP.