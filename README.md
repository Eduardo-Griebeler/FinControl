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

1. Instale o [XAMPP](https://www.apachefriends.org/).
2. Clone ou baixe este repositório.
3. Coloque a pasta `FinControl` dentro do diretório `htdocs` do XAMPP.
4. Inicie o Apache e o MySQL.
5. Acesse o phpMyAdmin e importe o arquivo SQL do banco de dados.
6. Copie `includes/conexao.exemplo.php` e renomeie a cópia para `conexao.php`.
7. Configure os dados de acesso ao banco de dados no arquivo `includes/conexao.php`.
8. Acesse `http://localhost/FinControl/` pelo navegador.

## Banco de dados

O projeto utiliza MySQL. O script de criação do banco de dados está disponibilizado neste repositório para permitir a configuração do sistema.

## Autor

Desenvolvido por Eduardo Griebeler.
