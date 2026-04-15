📚 Digital Bookstore - Advanced Web
Este é um projeto de uma livraria digital desenvolvido para a disciplina de Advanced Web. A aplicação foca em fornecer uma interface dinâmica para exibição de livros, consumindo dados de um banco de dados MySQL via PHP, com um front-end responsivo.


🚀 Funcionalidades
• Exibição Dinâmica: Carregamento de livros diretamente do banco de dados.
• Algoritmo de Seleção: Otimizado para exibir uma seleção aleatória de títulos na Home.
• Interface Responsiva: Grid de livros adaptável a diferentes resoluções de tela.
• Tratamento de Imagens: Sistema inteligente que verifica a existência da capa do livro no servidor antes da exibição.


🛠️ Tecnologias Utilizadas
O projeto foi construído utilizando as seguintes tecnologias:
• PHP 8.x: Lógica de back-end e conexão com banco de dados.
• MySQL: Armazenamento persistente de dados.
• HTML5/CSS3: Estrutura e estilização moderna.
• Flexbox: Para o layout da grade de produtos (book-grid).


💻 Estrutura do Código
No arquivo principal (index.php), o projeto utiliza uma arquitetura modular:
• Modularização: Inclusão de db.php e navbar.php para reaproveitamento de código e separação de responsabilidades.
• Segurança: Utilização de htmlspecialchars() na exibição de dados para prevenir ataques de XSS.
• Queries: Consultas SQL dinâmicas com verificação de integridade de resultados.
