<p>Projeto Laravel para controle de finanças pessoais de recebimentos e despesas</p>

# PASSO A PASSO #

1) Após clonar o projeto no Github, abrir o seu terminal na pasta do projeto e digite na sequência dos seguintes comandos:

composer install

cp .env.example .env


2) Abra o arquivo .env e configure as variáveis de ambiente, especialmente as configurações do banco de dados. Por exemplo:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

Inclua também as chaves do google recaptcha, se cadastrando e criando chaves para localhost:

GOOGLE_RECAPTCHA_KEY=
GOOGLE_RECAPTCHA_SECRET=

3) Laravel usa uma chave de aplicação para segurança. Gere essa chave usando o Artisan digitando o seguinte comando no terminal:

php artisan key:generate

4) Execute as migrações do banco de dados com o seeder:

php artisan migrate --seed

5) Inicie o servidor de desenvolvimento:

php artisan serve