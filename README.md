# Simple-MVC-Structure
Este é um microframework MVC desenvolvido em PHP.

## Requisitos
    * Composer
    * PHP >= 7.2

## Configurações
    * Crie arquivo "config.php" ou renomeie o arquivo "config.example.php" no diretório root.
    * Use o comando "composer update" ou "composer install" para baixar a pasta "vendor" com o "autoload"
    * Inicie o servidor na pasta "public"

## Cuidados
    Em Linux, você deve abilitar o rewrite do apache, para uso do ".htaccess".
    Você pode ver um exemplo neste vídeo https://youtu.be/GsxhN4HBnC8?t=1893

## Uso
    Rotas
        A primeira palavra após o hostname, será considerada a classe controller.
        A segunda palavra após o hostname, será considerada o método do controller. Se não houver esta informação, o padrão será "index".
        O restante da rota será considerada como um array de parâmetros.

        Exemplos:
            "localhost/home" == (new HomeController)->index();
            "localhost/buscar/usuarios" == (new BuscarController)->usuarios();
            "localhost/excluir/produtos/6/7" == (new ExcluirController)->produtos([6, 7]);
    
    Títulos e Favicons
        Todo controller, estende o método "view" da classe controller principal.
        Este método permite importar um arquivo da pasta "app/Views" e enviar parâmetros para esta página.

        Exemplos:
            Para importar o arquivo "app/Views/home/index.php" usamos,

                $this->view("home/index");

            Como segundo parâmetro deste método, podemos enviar um array com diversos argumentos.
            Nesses argumentos, podemos enviar o título da página e o favicon que ela terá.

                $this->view("buscar/usuarios", [
                    "title"=>"Página de Busca", "favicon"=>"buscar.ico"
                ])
