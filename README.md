# BlastMail 📧

> Um sistema completo para criação, agendamento e análise de campanhas de e-mail marketing, construído com o poder do ecossistema Laravel.

![BlastMail Screenshot](https://img.shields.io/badge/status-concluído-green)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blueviolet)
![Laravel](https://img.shields.io/badge/Laravel-12.x-red)
![License](https://img.shields.io/badge/license-MIT-blue)

## 📝 Sobre o Projeto

O **BlastMail** é uma plataforma robusta de e-mail marketing desenvolvida como parte do curso de Laravel da **Rocketseat**. O sistema permite gerenciar todo o ciclo de vida de uma campanha de e-mail, desde a criação e personalização de templates até o envio agendado e o rastreamento detalhado das interações dos usuários.

O uso de filas (Queues) do Laravel garante que os envios sejam processados em segundo plano, proporcionando uma experiência de usuário fluida e evitando sobrecarga no servidor, mesmo com grandes volumes de e-mails.

---

## ✨ Funcionalidades Principais

-   [x] **Criação de Campanhas:** Interface intuitiva para configurar nome, assunto e conteúdo das campanhas.
-   [x] **Templates Personalizados:** Editor HTML integrado para criar e gerenciar templates de e-mail reutilizáveis.
-   [x] **Gerenciamento de Contatos:** Importação de listas de e-mails via arquivos `.csv` e gerenciamento manual de contatos.
-   [x] **Envios com Filas:** Sistema de filas assíncrono para processar envios de forma performática e confiável.
-   [x] **Agendamento de Campanhas:** Programe suas campanhas para serem enviadas em datas e horários específicos.
-   [x] **Rastreamento de Interações:** Monitore taxas de abertura (via pixel de rastreamento) e cliques em links.
-   [x] **Relatórios de Desempenho:** Visualize métricas detalhadas para cada campanha, como total de envios, aberturas e cliques.

---

## 🛠️ Tecnologias Utilizadas

O projeto foi construído com as seguintes tecnologias:

-   **PHP 8.2+**
-   **Laravel 12.x**
-   **Banco de Dados Relacional** (MySQL, PostgreSQL)
-   **Redis** (para gerenciamento das filas)
-   **Blade** (para templating)
-   **Tailwind CSS** (para estilização)

---

## 🚀 Começando

Para executar o BlastMail em seu ambiente de desenvolvimento local, siga os passos abaixo.

### Pré-requisitos

Certifique-se de ter as seguintes ferramentas instaladas:

-   [PHP 8.2 ou superior](https://www.php.net/)
-   [Composer](https://getcomposer.org/)
-   [Node.js e NPM](https://nodejs.org/en/)
-   [Git](https://git-scm.com/)
-   Um servidor de banco de dados (ex: MySQL, MariaDB)
-   [Redis](https://redis.io/)

### Instalação

1.  **Clone o repositório:**

    ```bash
    git clone [https://github.com/jeffersonlferreira/BlastMail.git](https://github.com/jeffersonlferreira/BlastMail.git)
    cd blastmail
    ```

2.  **Instale as dependências do PHP:**

    ```bash
    composer install
    ```

3.  **Instale as dependências do front-end:**

    ```bash
    npm install && npm run build
    ```

4.  **Configure o ambiente:**

    -   Copie o arquivo de exemplo `.env.example` para `.env`.
        ```bash
        cp .env.example .env
        ```
    -   Gere a chave da aplicação.
        ```bash
        php artisan key:generate
        ```

5.  **Ajuste as variáveis de ambiente no arquivo `.env`:**
    Configure as credenciais do seu banco de dados, Redis e o driver de e-mail.

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=blastmail
    DB_USERNAME=root
    DB_PASSWORD=

    QUEUE_CONNECTION=redis
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    MAIL_MAILER=smtp
    # Configure suas credenciais de SMTP para os envios
    ```

6.  **Execute as migrações e seeders:**
    Isso criará as tabelas no banco de dados e poderá popular com dados de exemplo.

    ```bash
    php artisan migrate --seed
    ```

7.  **Inicie os serviços:**
    -   Inicie o servidor de desenvolvimento do Laravel.
        ```bash
        php artisan serve
        ```
    -   Em um **novo terminal**, inicie o worker da fila para processar os e-mails.
        ```bash
        php artisan queue:work
        ```

Pronto! A aplicação estará disponível em `http://127.0.0.1:8000`.

---

## 📈 Como Funciona o Rastreamento

-   **Aberturas:** Um pixel de rastreamento (uma imagem transparente de 1x1) é inserido no corpo de cada e-mail. Quando o cliente de e-mail do usuário carrega as imagens, uma requisição é feita ao servidor, registrando a abertura.
-   **Cliques:** Todos os links no corpo do e-mail são reescritos para apontar para uma rota de redirecionamento na aplicação. Ao clicar, o sistema registra o clique e então redireciona o usuário para o destino original.

---

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.
