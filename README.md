# Simple-MVC-Structure
Este é um microframework MVC desenvolvido em PHP.

## Requisitos
    * Composer
    * PHP >= 7.2

## Configurações
    * Crie um arquivo "config.php" ou renomeie o arquivo "config.example.php" no diretório root.
    * Use o comando "composer update" ou "composer install" para baixar a pasta "vendor" com o "autoload"
    * Inicie o servidor na pasta "public"

## Cuidados
    Em Linux, você deve habilitar o rewrite do apache, para uso do ".htaccess".
    Você pode ver um exemplo neste vídeo https://youtu.be/GsxhN4HBnC8?t=1893

## Rotas
        Você pode definir as rotas no arquivo "public/index.php".
        Lá estará o objeto $router, que contém os 4 verbos principais.

        Para usar, basta usar: $router->{verbo}("/rota/exemplo", "classe::metodo")

        Exemplos:
            Para rotas GET:
                $router->get("/rota/exemplo", "classe::metodo");
            Para rotas GET com parâmetros:
                $router->get("/rota/exemplo/{id}", "classe::metodo");

        Salve as classes neste formato "{nome da classe}Controller".
        Desta forma, não é necessário informar o namespace.

        Tentará acessar a classe na pasta "app/Controllers".

        Para acessar uma classe numa pasta diferente, use:

            $router->get("/rota/exemplo", "classe::metodo")->dir("outra/pasta");

        Tentará acessar a classe na pasta "app/outra/pasta".

## Dados
        Numa requisição, podemos receber 3 tipos de dados:
            
            1. Params: são dados recebidos na url e são obrigatórios.
                Exemplos: 
                    "/produto/25"
                    No exemplo acima, o "id" é obrigatório para buscar os dados do produto 25.
            2. Query: são dados recebidos na url e são opcionais.
                Exemplos:
                    "/produtos/?pagina=2&filtro=blusa"
                    Podemos considerar que o exemplo acima é uma listagem de produtos.
                    A listagem é independente da paginação ou do filtro.
            3. Body: são dados recebidos no corpo da requisição, geralmente por formulários ou no formato JSON.
                Exemplos:
                    "/criar/usuario"
                    Podemos considerar que estamos acessando a rota acima com o método POST.
                    Os dados do usuário a ser criado, estarão no corpo da requisição.

## Exemplo
        use App\Core\iRequest;
        use App\Core\iResponse;

        class ClasseExemplo {

            public function index(iRequest $request, iResponse $response) {

                $params = $request->params();
                $query = $request->query();
                $body = $request->body();

                echo $params->id;
                echo $query->pagina;
                echo $body->idade;
    
## HTML, Títulos e Favicons
        Todo controller, estende o método "view" da classe controller principal.
        Este método permite importar um arquivo da pasta "app/Views" e enviar parâmetros para esta página.

        Para importar o arquivo "app/Views/home/index.php", por exemplo, usamos:

            $response->view("home/index");

        Como segundo parâmetro deste método, podemos enviar um array com diversos argumentos.
        Nesses argumentos, podemos enviar o título da página e o favicon que ela terá.

        Exemplo:
            use App\Core\iRequest;
            use App\Core\iResponse;

            class User {

                public function index(iRequest $request, iResponse $response) {

                    $response->view("buscar/usuarios", [
                        "title"=>"Página de Busca", "favicon"=>"buscar.ico"
                    ]);

## API
        Salve as classes na pasta "app/Api".
        Informe o diretório no $router.

        Exemplos:
            $router->get("/items", "ItemAPI::index")->dir("Api");

            No exemplo acima, estamos acessando o arquivo "app/Api/ItemAPI.php".

            Para este tipo de requisição, a resposta deve ser neste formato:

                $response->json(array $items);

            O método acima, deve exibir um formato json.
