<p>Projeto Laravel para controle de finanças pessoais de recebimentos e despesas</p>

# PASSO A PASSO #

1) Após clonar o projeto no Github, abrir o seu terminal na pasta do projeto e digite na sequência dos seguintes comandos:

composer install

cp .env.example .env


2) Abra o arquivo .env e configure as variáveis de ambiente, especialmente as configurações do banco de dados. Por exemplo:

<p>DB_CONNECTION=mysql</p>
<p>DB_HOST=127.0.0.1</p>
<p>DB_PORT=3306</p>
<p>DB_DATABASE=seu_banco_de_dados</p>
<p>DB_USERNAME=seu_usuario</p>
<p>DB_PASSWORD=sua_senha</p>

Inclua também as chaves do google recaptcha, se cadastrando e criando chaves para localhost:

<p>GOOGLE_RECAPTCHA_KEY=</p>
<p>GOOGLE_RECAPTCHA_SECRET=</p>

3) Laravel usa uma chave de aplicação para segurança. Gere essa chave usando o Artisan digitando o seguinte comando no terminal:

php artisan key:generate

4) Execute as migrações do banco de dados com o seeder:

php artisan migrate --seed

5) Inicie o servidor de desenvolvimento:

php artisan serve
