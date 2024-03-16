### API REST PHP PURO

#### Crud desenvolvido baseado no seguinte case:

 1. Uma área administrativa onde o(s) usuário(s) devem acessar através de login e senha.

 2. Criar um gerenciador de clientes (Criar, Listar, Editar e Excluir)

    2.1. O cadastro do Cliente deve conter: Nome; Data Nascimento;CPF; RG; Telefone.

    2.2. O Cliente pode ter 1 ou N endereços.

Instruções para iniciar projeto em sua máquina local para fins de desenvolvimento e teste.

📋 Pré-requisitos

    - Composer
    - PHP >= 8.0

🔧 Instalação

 - Clone o repositório:
   - git clone ``` ```

 
 - copiar env_example: ```cp .env_example .env ```

    - incluir valores nas variáveis, conforme solicitado

 
 - Instalando dependecias: ```composer update```


 - Executar projeto 
   - ```php -S localhost:8000 public/index.php```


🛠️ Api construída com

    - PHP: Linguagem utilizada.
    - Composer: Gerenciador de dependencias.
    - Mysql: Banco de dados.