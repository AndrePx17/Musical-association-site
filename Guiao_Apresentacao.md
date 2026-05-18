# Guião de Apresentação: Associação Musical de Pedroso

Este guião serve como base para a apresentação do projeto, destacando os pontos principais e as funcionalidades implementadas de acordo com os requisitos de DA.

---

## 1. Introdução (Muito Simples)
**Objetivo:** Contextualizar o projeto.

*   "Bom dia/Boa tarde a todos. O projeto que vou apresentar hoje é o site oficial da **Associação Musical de Pedroso**."
*   "O objetivo principal foi criar uma plataforma que permitisse à associação gerir as suas notícias e horários de forma dinâmica, oferecendo ao público uma interface moderna e fácil de usar"

---

## 2. Desenvolvimento (Funcionalidades)
**Objetivo:** Demonstrar que todos os critérios técnicos foram cumpridos.

### A. Tecnologias e Design
*   **Stack Tecnológica:** "O projeto foi desenvolvido utilizando **PHP** para o backend, **MySQL** para a base de dados, **HTML/CSS/JS** para o frontend e **Docker** para garantir um ambiente de desenvolvimento consistente."
*   **Design Responsivo:** "Utilizei a framework **Bootstrap 5.3.3**, o que garante que o site seja totalmente responsivo, adaptando-se perfeitamente a computadores, tablets e telemóveis."
*   **JavaScript:** "Incluí JavaScript para tornar a página mais interativa, como por exemplo, nos **modais dinâmicos** que permitem ler notícias ou editar conteúdos sem recarregar a página."

### B. Funcionalidades de Gestão (CRUD)
*   **CRUD Completo:** "O sistema permite a gestão total de conteúdos. No backoffice, é possível:
    *   **Inserir** novas notícias e horários;
    *   **Consultar** a listagem de todos os registos;
    *   **Atualizar/Editar** informações existentes através de formulários dedicados;
    *   **Eliminar** registos que já não sejam necessários."

### C. Área Reservada e Segurança
*   **Sessões e Autenticação:** "Implementei uma área reservada protegida por **sessões (PHP Sessions)**. Apenas utilizadores autenticados podem aceder ao painel de controlo (Backoffice)."
*   **Encriptação de Senhas:** "A segurança foi uma prioridade. As palavras-passe dos utilizadores não são guardadas em texto limpo; utilizei a função `password_hash` para encriptação."
*   **Prepared Statements:** "Para prevenir ataques de SQL Injection, todas as comunicações com a base de dados utilizam *Prepared Statements*."

### D. Dinamismo e Ficheiros
*   **Upload de Ficheiros:** "O sistema inclui um módulo de **upload de imagens** para as notícias, permitindo que o administrador escolha fotos diretamente do seu computador para o servidor."
*   **Menus Dinâmicos:** "A barra de navegação é dinâmica: as opções mudam automaticamente consoante o utilizador esteja ou não autenticado (exibindo 'Login' ou 'Painel de Controlo' conforme o caso)."

---

## 3. Conclusão
**Objetivo:** Resumir o valor do trabalho.

*   "Em resumo, o site da Associação Musical de Pedroso é uma aplicação Web completa, que separa claramente o **Front-end** (público) do **Backoffice** (gestão)."
*   "O código foi organizado de forma modular, com uma separação clara entre lógica (PHP) e design (Bootstrap), facilitando futuras atualizações."
*   "Obrigado pela vossa atenção. Estou agora disponível para qualquer questão."

---

### Notas para o Aluno:
*   **Dica:** Durante a apresentação, mostra o Backoffice (`admin.php`) e faz uma pequena demonstração de criar uma notícia com upload de imagem. Isso prova que o CRUD e o Upload funcionam.
*   **Dica 2:** Mostra o site a encolher no browser para provar que é responsivo.
