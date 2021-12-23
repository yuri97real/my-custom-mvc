# Simple-MVC-Structure

## Descrição

Este é um microframework MVC desenvolvido em PHP.

## Requisitos

* Composer
* PHP 7 ou superior

## Instruções

* Crie um arquivo "config.php" ou renomeie o arquivo "config.example.php" no diretório root.
* Use o comando "composer update" ou "composer install" para baixar a pasta "vendor" com o "autoload".
* Inicie o servidor na pasta "public".

### Exemplo

Clonar o projeto:

    git clone https://github.com/yuri97real/Simple-MVC-Structure.git

Entre na pasta e crie o arquivo de configurações:

    cd Simple-MVC-Structure/
    cp config.example.php config.php

Baixar as dependências com o composer:

    composer update

Iniciar o servidor:

    php -S localhost:3333 -t public/

## Rotas

Você pode definir as rotas no arquivo "public/routes.php".
Lá estará o objeto $router, que contém os 4 verbos principais.

### Exemplo 1 (Rotas Simples)

    $router->get("/rota/exemplo", "classe::index");
    $router->post("/rota/exemplo", "classe::create");
    $router->put("/rota/exemplo", "classe::update");
    $router->delete("/rota/exemplo", "classe::destroy");

### Exemplo 2 (Rotas Com Parâmetros)

    $router->get("/rota/exemplo/{id}", "classe::metodo");

Nos dois exemplos acima, é esperado que os arquivos das classes informadas estejam na pasta "app/Controllers".

Para acessar uma classe numa pasta diferente, use o método <strong>dir</strong>:

    $router->get("/rota/exemplo", "classe::metodo")->dir("outra/pasta");

No exemplo acima, é esperado que o arquivo da classe informada esteja na pasta "app/outra/pasta".

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

    <?php

    use App\Core\iRequest;
    use App\Core\iResponse;

    class ClasseExemplo {

        public function metodo(iRequest $request, iResponse $response) {

            $params = $request->params();
            $query = $request->query();
            $body = $request->body();

            echo $params->id;
            echo $query->pagina;
            echo $body->dados;

        }

    }
    
## HTML, Títulos e Favicons

O parâmetro <strong>response</strong> possui métodos de resposta, inclusive páginas HTML.

O método "view" do <strong>response</strong>, permite importar um arquivo da pasta "app/Views" e enviar parâmetros para ele.

Para importar o arquivo "app/Views/home/index.php", por exemplo, usamos:

    $response->view("home/index");

Como segundo parâmetro deste método, podemos enviar um array com diversos argumentos.

Nesses argumentos, podemos enviar o título da página e o favicon que ela terá.

### Exemplo

    use App\Core\iRequest;
    use App\Core\iResponse;

    class Produto {

        public function index(iRequest $request, iResponse $response) {

            $response->view("listagens/produtos", [
                "title"=>"Listagem de Produtos",
                "favicon"=>"buscar.ico",
            ]);

        }
    }

No exemplo acima, é esperado que seja renderizado o conteúdo HTML que está no arquivo "app/Views/listagens/produtos.php".

## API

Salve as classes na pasta "app/Api".
Informe o diretório no $router.

### Exemplo

    $router->get("/items", "ItemAPI::index")->dir("Api");

No exemplo acima, estamos instanciando um objeto que está no arquivo "app/Api/ItemAPI.php".

Para este tipo de requisição, a resposta deve ser no formato JSON:

    $response->json(array $items);

O método acima, deve exibir um array no formato JSON.

## Cuidados

<strong>Se estiver usando Windows com *Xampp*, *Laragon* e similares, pode ignorar esta etapa.</strong>

Essas ferramentas, já contam com diversas configurações para flexibilizar o desenvolvimento.

## Linux

Em Linux, ao baixar o pacote <strong>PHP</strong>, algumas bibliotecas vem desativadas.

Portanto, se você for um iniciante ou não tiver experiência com gerenciamento de pacotes, logo abaixo estão algumas instruções e comandos extras que você pode executar para evitar futuras dores de cabeça.

### HTACCESS

Você deve habilitar o rewrite do apache, para uso do ".htaccess".
Você pode ver um exemplo neste <a href="https://youtu.be/GsxhN4HBnC8?t=1893">vídeo</a>.

### PACOTES

Dependendo da sua distribuição Linux, algumas das extensões abaixo podem vir desativadas por padrão. Você pode executar o comando abaixo para ativá-las em distros baseadas no Ubuntu, Debian e similares.

    sudo apt install php-gd php-mysql php-zip php-curl php-pdo

No Fedora, troque apenas "apt" por "dnf".

    sudo dnf install php-gd php-mysql php-zip php-curl php-pdo

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