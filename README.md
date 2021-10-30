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

## Uso
    Rotas (app/Controllers)
        A primeira palavra após o hostname, será considerada a classe controller.
        A segunda palavra após o hostname, será considerada o método do controller. Se não houver esta informação, o padrão será "default".
        O restante da rota será considerada como um array de parâmetros.

        Exemplos:
            "localhost/home" == (new HomeController)->default();
            "localhost/buscar/usuarios" == (new BuscarController)->usuarios();
            "localhost/excluir/produtos/6/7" == (new ExcluirController)->produtos([6, 7]);
    
    Títulos e Favicons
        Todo controller, estende o método "view" da classe controller principal.
        Este método permite importar um arquivo da pasta "app/Views" e enviar parâmetros para esta página.

        Exemplos:
            Para importar o arquivo "app/Views/home/default.php" usamos,

                $this->view("home/default");

            Como segundo parâmetro deste método, podemos enviar um array com diversos argumentos.
            Nesses argumentos, podemos enviar o título da página e o favicon que ela terá.

                $this->view("buscar/usuarios", [
                    "title"=>"Página de Busca", "favicon"=>"buscar.ico"
                ]);

    API (app/Api)
        Para utilizar rotas no formato de API, basta informar "?json" como query string na url, quando iniciar a requisição.
        
        A única diferença aqui, é o endereço lógico das rotas.

        Exemplos:
            URL: http://localhost/items/all?json

            1. No exemplo acima, estamos acessando o arquivo "app/Api/ItemsAPI.php".

                Para este tipo de requisição, a resposta deve ser neste formato:

                    $this->response(200, array $items);

                O método acima, deve exibir um formato json.

            2. Sem a query "?json", estamos acessando o arquivo "app/Controllers/ItemsController.php".

                Para este tipo de requisição, a resposta deve ser neste formato:

                    $this->view("items/mostrar", [
                        "title"=>"Mostrar Itens", "favicon"=>"mostrar.ico"
                    ]);

                O método acima, deve exibir uma página html.

            3. Outra forma de visualizar informações:

                $data: 0 || "" || [] || {};

                    $this->console($data);

                O parâmetro "data" pode ser um dado de qualquer tipo.
                Utilize este método para testes e visualizar retornos, como numa consulta SQL, por exemplo.
