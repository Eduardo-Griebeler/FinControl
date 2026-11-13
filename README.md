# FinControl

Sistema web de gerenciamento financeiro desenvolvido como projeto acadêmico do curso de Análise e Desenvolvimento de Sistemas.

## Sobre o projeto

O FinControl tem como objetivo auxiliar no controle das finanças pessoais, permitindo registrar receitas e despesas, acompanhar o saldo e visualizar informações financeiras de maneira organizada.

## Funcionalidades

- Cadastro e login de usuários.
- Registro de receitas.
- Registro de despesas.
- Edição e exclusão de lançamentos.
- Histórico de movimentações.
- Filtros por mês e ano.
- Visualização de receitas, despesas e saldo.
- Gráficos para acompanhamento financeiro.
- Modo claro e escuro.

## Tecnologias utilizadas

- HTML
- CSS
- JavaScript
- PHP
- MySQL
- Chart.js
- Font Awesome

## Como executar o projeto localmente

### Pré-requisitos

- XAMPP instalado.
- Navegador de internet.
- Git (opcional, caso queira clonar o repositório).

### Instalação

1. Instale o [XAMPP](https://www.apachefriends.org/).
2. Clone ou baixe este repositório.
3. Coloque a pasta `FinControl` dentro do diretório `htdocs` do XAMPP.
4. Abra o painel do XAMPP e inicie os serviços Apache e MySQL.
5. Acesse o phpMyAdmin pelo endereço `http://localhost/phpmyadmin`.
6. Importe o arquivo `fincontrol.sql`, disponível na pasta principal do projeto.
7. Na pasta `includes`, copie o arquivo `conexao.exemplo.php` e renomeie a cópia para `conexao.php`.
8. Abra `includes/conexao.php` e configure os dados de acesso ao banco de dados local.
9. Acesse `http://localhost/FinControl/` pelo navegador.

## Banco de dados

O projeto utiliza MySQL para armazenar os dados dos usuários, receitas e despesas.

O arquivo `fincontrol.sql` contém o script de criação do banco de dados, incluindo as tabelas, chaves primárias e relacionamentos necessários para executar o sistema.

## Segurança

- Senhas armazenadas utilizando `password_hash()`.
- Verificação de senhas utilizando `password_verify()`.
- Consultas ao banco de dados com prepared statements.
- Controle de acesso às informações de cada usuário por sessão.

## Repositório

[GitHub - FinControl](https://github.com/Eduardo-Griebeler/FinControl)

## Autor

Desenvolvido por Eduardo Griebeler.

Projeto acadêmico do curso de Análise e Desenvolvimento de Sistemas.