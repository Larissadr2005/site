SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema meu_blog
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema meu_blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `meu_blog` DEFAULT CHARACTER SET utf8 ;
USE `meu_blog` ;

-- -----------------------------------------------------
-- Table `meu_blog`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`usuarios` (
  `id` INT AUTO_INCREMENT NOT NULL, 
  `nome` VARCHAR(255) NOT NULL,
  `sobrenome` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(127) NOT NULL,
  `phone1` VARCHAR(45) NULL,
  `tipo_phone1` VARCHAR(16) NULL,
  `phone2` VARCHAR(45) NULL,
  `tipo_phone2` VARCHAR(16) NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meu_blog`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`categorias` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meu_blog`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`posts` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `categoria_id` INT NOT NULL,
  `titulo` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(255) NOT NULL,
  `post_path` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_posts_categorias_idx` (`categoria_id` ASC) VISIBLE,
  INDEX `fk_posts_usuarios1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_posts_categorias`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `meu_blog`.`categorias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_posts_usuarios1`
    FOREIGN KEY (`user_id`)
    REFERENCES `meu_blog`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `meu_blog`.`comentarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`comentarios` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `comentario` VARCHAR(255) NOT NULL,
  `likes` INT NOT NULL,
  `dislikes` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_comentarios_posts1_idx` (`post_id` ASC) VISIBLE,
  INDEX `fk_comentarios_usuarios1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_comentarios_posts1`
    FOREIGN KEY (`post_id`)
    REFERENCES `meu_blog`.`posts` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comentarios_usuarios1`
    FOREIGN KEY (`user_id`)
    REFERENCES `meu_blog`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `meu_blog`.`enderecos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `meu_blog`.`enderecos` (
  `id` INT AUTO_INCREMENT NOT NULL,
  `user_id` INT NOT NULL,
  `nome` VARCHAR(255) NOT NULL,
  `rua` VARCHAR(255) NOT NULL,
  `numero` VARCHAR(16) NOT NULL,
  `cidade` VARCHAR(255) NOT NULL,
  `bairro` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_enderecos_usuarios1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_enderecos_usuarios1`
    FOREIGN KEY (`user_id`)
    REFERENCES `meu_blog`.`usuarios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- DADOS DE TESTE ROBUSTOS
-- -----------------------------------------------------

-- Inserir usuários diversos
-- Senha padrão para todos: 123456 (hash gerado pelo PHP)
INSERT INTO usuarios (nome, sobrenome, email, password, phone1, tipo_phone1, phone2, tipo_phone2, created_at, updated_at) VALUES 
('Admin', 'Sistema', 'admin@test.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(11) 98765-4321', 'Celular', '(11) 3456-7890', 'Comercial', '2024-01-15 10:00:00', NOW()),
('Maria', 'Silva', 'maria.silva@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(11) 99876-5432', 'Celular', NULL, NULL, '2024-02-20 14:30:00', NOW()),
('João', 'Santos', 'joao.santos@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(21) 98765-1234', 'Celular', '(21) 2345-6789', 'Residencial', '2024-02-25 09:15:00', NOW()),
('Ana', 'Costa', 'ana.costa@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(31) 97654-3210', 'Celular', NULL, NULL, '2024-03-01 16:45:00', NOW()),
('Pedro', 'Oliveira', 'pedro.oliveira@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(41) 96543-2109', 'Celular', '(41) 3210-9876', 'Comercial', '2024-03-05 11:20:00', NOW()),
('Carla', 'Ferreira', 'carla.ferreira@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(51) 95432-1098', 'Celular', NULL, NULL, '2024-03-10 13:00:00', NOW()),
('Lucas', 'Almeida', 'lucas.almeida@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(61) 94321-0987', 'Celular', '(61) 3098-7654', 'Residencial', '2024-03-15 08:30:00', NOW()),
('Sofia', 'Rodrigues', 'sofia.rodrigues@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(71) 93210-9876', 'Celular', NULL, NULL, '2024-03-20 15:15:00', NOW()),
('Gabriel', 'Lima', 'gabriel.lima@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(81) 92109-8765', 'Celular', '(81) 2987-6543', 'Comercial', '2024-03-25 12:45:00', NOW()),
('Isabela', 'Martins', 'isabela.martins@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(85) 91098-7654', 'Celular', NULL, NULL, '2024-04-01 10:30:00', NOW()),
('Rafael', 'Pereira', 'rafael.pereira@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(47) 90987-6543', 'Celular', '(47) 3876-5432', 'Residencial', '2024-04-05 14:20:00', NOW()),
('Juliana', 'Barbosa', 'juliana.barbosa@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(19) 99876-5432', 'Celular', NULL, NULL, '2024-04-10 09:50:00', NOW()),
('Eduardo', 'Nascimento', 'eduardo.nascimento@email.com', '$2y$10$cJ0lXJaPOV0DBsZK8rGiCeZgtqyerr6STUf46KD5IzoXFJvvOVNWe', '(27) 98765-4321', 'Celular', '(27) 3765-4321', 'Comercial', '2024-04-15 16:00:00', NOW());

-- Inserir categorias de livros
INSERT INTO categorias (titulo, descricao, created_at, updated_at) VALUES 
('Ficção', 'Romances, contos e obras de ficção em geral', '2024-01-15 10:00:00', NOW()),
('Não-ficção', 'Biografias, ensaios e obras baseadas em fatos', '2024-01-15 10:05:00', NOW()),
('Autoajuda', 'Livros de desenvolvimento pessoal e motivação', '2024-01-15 10:10:00', NOW()),
('Negócios', 'Livros sobre empreendedorismo e gestão', '2024-01-15 10:15:00', NOW()),
('Tecnologia', 'Livros sobre programação e inovação', '2024-02-01 11:00:00', NOW()),
('História', 'Obras históricas e biografias', '2024-02-01 11:05:00', NOW()),
('Filosofia', 'Reflexões filosóficas e pensamento crítico', '2024-02-01 11:10:00', NOW()),
('Ciência', 'Divulgação científica e descobertas', '2024-02-15 12:00:00', NOW()),
('Psicologia', 'Comportamento humano e mente', '2024-02-15 12:05:00', NOW()),
('Literatura Clássica', 'Grandes clássicos da literatura mundial', '2024-03-01 13:00:00', NOW());

-- Inserir resumos de livros
INSERT INTO posts (user_id, categoria_id, titulo, descricao, post_path, created_at, updated_at) VALUES 
(1, 1, 'Bem-vindos à Biblioteca Online!', 'Apresentação da nossa biblioteca digital com resumos de grandes obras da literatura mundial.', 'Olá, leitores!\n\nSejam bem-vindos à nossa Biblioteca Online! Este é um espaço dedicado ao compartilhamento de resumos de grandes obras da literatura mundial.\n\nAqui vocês vão encontrar:\n- Resumos de clássicos da literatura\n- Obras de não-ficção importantes\n- Livros de desenvolvimento pessoal\n- Biografias inspiradoras\n- E muito mais!\n\nNosso objetivo é democratizar o acesso ao conhecimento e ajudar você a descobrir novos livros através de resumos bem elaborados.\n\nBoa leitura!\nEquipe Biblioteca Online', '2024-01-15 10:30:00', NOW()),
(1, 5, 'Código Limpo - Robert C. Martin', 'Um guia essencial para escrever código que seja fácil de ler, manter e estender.', '**Autor:** Robert C. Martin (Uncle Bob)\n**Ano:** 2008\n**Páginas:** 464\n\n**Resumo:**\n\n"Código Limpo" é considerado uma bíblia para desenvolvedores que desejam escrever código de alta qualidade. Martin apresenta princípios fundamentais para criar software profissional.\n\n**Principais conceitos:**\n\n**Nomes Significativos**\n- Use nomes que revelem intenção\n- Evite informações erradas\n- Faça distinções significativas\n- Use nomes pronunciáveis\n\n**Funções**\n- Devem ser pequenas\n- Fazer apenas uma coisa\n- Usar nomes descritivos\n- Ter poucos argumentos\n\n**Comentários**\n- Código bom é autodocumentado\n- Comentários compensam falha de expressão\n- Evite comentários redundantes\n- Use comentários para explicar "por que", não "o que"\n\n**Princípios SOLID**\n- Single Responsibility\n- Open/Closed\n- Liskov Substitution\n- Interface Segregation\n- Dependency Inversion\n\n**Lição Principal:**\n"Qualquer um pode escrever código que um computador entende. Bons programadores escrevem código que humanos entendem."\n\n**Recomendado para:** Desenvolvedores de todos os níveis que querem melhorar a qualidade de seu código.', '2024-01-20 14:00:00', NOW()),
(2, 3, 'Hábitos Atômicos - James Clear', 'Um guia prático sobre como formar bons hábitos e abandonar os ruins através de pequenas mudanças.', '**Autor:** James Clear\n**Ano:** 2018\n**Páginas:** 320\n\n**Resumo:**\n\nJames Clear apresenta uma abordagem científica para construir hábitos que durem. O livro mostra como pequenas mudanças podem gerar resultados extraordinários.\n\n**Conceitos fundamentais:**\n\n**O Poder dos Hábitos Atômicos**\n- Melhorias de 1% ao dia = 37x melhor em um ano\n- Foque no sistema, não nos objetivos\n- Mudanças pequenas, resultados notáveis\n\n**As 4 Leis dos Hábitos:**\n\n1. **Torne óbvio** - Deixe as deixas visíveis\n2. **Torne atraente** - Use empilhamento de tentações\n3. **Torne fácil** - Diminua a fricção\n4. **Torne satisfatório** - Use recompensas imediatas\n\n**O Loop do Hábito:**\n- Deixa → Desejo → Resposta → Recompensa\n- Entenda este ciclo para modificar comportamentos\n\n**Técnicas práticas:**\n- Empilhamento de hábitos\n- Design do ambiente\n- Regra dos 2 minutos\n- Rastreamento de hábitos\n\n**Citação marcante:**\n"Você não sobe ao nível de seus objetivos. Você cai ao nível de seus sistemas."\n\n**Recomendado para:** Qualquer pessoa que queira criar mudanças positivas duradouras na vida.', '2024-02-22 16:30:00', NOW()),
(2, 2, 'Sapiens - Yuval Noah Harari', 'Uma breve história da humanidade desde a revolução cognitiva até os desafios do século XXI.', '**Autor:** Yuval Noah Harari\n**Ano:** 2011\n**Páginas:** 512\n\n**Resumo:**\n\nHarari conta a história da humanidade através de três grandes revoluções que moldaram nossa espécie e o planeta.\n\n**As Três Revoluções:**\n\n**Revolução Cognitiva (70.000 anos atrás)**\n- Desenvolvimento da linguagem complexa\n- Capacidade de cooperar em grandes números\n- Criação de mitos e ficções compartilhadas\n- Extinção de outras espécies humanas\n\n**Revolução Agrícola (12.000 anos atrás)**\n- Transição de caçadores-coletores para agricultores\n- Aumento populacional massivo\n- Criação de cidades e civilizações\n- Início da desigualdade social\n\n**Revolução Científica (500 anos atrás)**\n- Método científico\n- Expansão global\n- Revolução industrial\n- Era da informação\n\n**Temas centrais:**\n\n**Cooperação em massa**\n- Religião, dinheiro e impérios como "ficções úteis"\n- Capacidade única de acreditar em narrativas comuns\n\n**Consequências do progresso**\n- O trigo "domesticou" os humanos\n- Progresso não significa necessariamente felicidade\n- Impacto ecológico devastador\n\n**Futuro da humanidade**\n- Biotecnologia e IA\n- Possível fim do Homo sapiens\n- Novos desafios éticos\n\n**Recomendado para:** Leitores interessados em história, antropologia e reflexões sobre o futuro humano.', '2024-03-05 11:15:00', NOW()),
(3, 5, 'O Programador Pragmático', 'Guia essencial para se tornar um desenvolvedor mais eficaz e profissional.', '**Autores:** Andy Hunt e Dave Thomas\n**Ano:** 1999 (revisado em 2019)\n**Páginas:** 352\n\n**Resumo:**\n\nUm dos livros mais influentes da programação, apresentando princípios atemporais para se tornar um desenvolvedor melhor.\n\n**Princípios fundamentais:**\n\n**Responsabilidade e Pragmatismo**\n- Assuma responsabilidade pelo seu trabalho\n- Não deixe janelas quebradas no código\n- Seja um catalisador de mudanças\n- Lembre-se do quadro geral\n\n**Ferramentas e Técnicas**\n- Domine seu editor de texto\n- Use controle de versão\n- Automatize tudo que for possível\n- Teste implacavelmente\n\n**Desenvolvimento contínuo**\n- Invista regularmente em seu conhecimento\n- Aprenda pelo menos uma linguagem por ano\n- Leia livros técnicos\n- Participe de grupos de usuários\n\n**Boas práticas:**\n\n- **DRY** (Dont Repeat Yourself)\n- *Ortogonalidade** - componentes independentes\n- **Reversibilidade** - evite decisões irreversíveis\n- **Tracer Bullets** - protótipos funcionais\n\n**Debugging**\n- Não entre em pânico\n- Reproduza o problema\n- Use dados reais\n- Explique o bug para um pato de borracha\n\n**Citação marcante:**\n"O código que você escreve hoje provavelmente ainda estará em funcionamento daqui a cinco anos."\n\n**Recomendado para:** Desenvolvedores que querem elevar seu profissionalismo e eficácia.', '2024-02-28 09:45:00', NOW()),
(3, 4, 'A Startup Enxuta - Eric Ries', 'Metodologia revolucionária para criar empresas inovadoras com menos desperdício.', '**Autor:** Eric Ries\n**Ano:** 2011\n**Páginas:** 336\n\n**Resumo:**\n\nEric Ries apresenta uma nova abordagem para startups baseada em princípios de manufatura enxuta aplicados ao empreendedorismo.\n\n**Conceitos centrais:**\n\n**Ciclo Construir-Medir-Aprender**\n- Construa um MVP (Produto Mínimo Viável)\n- Meça o comportamento dos clientes\n- Aprenda com os dados\n- Itere rapidamente\n\n **Contabilidade para Inovação**\n- Métricas de vaidade vs métricas acionáveis\n- Cohort analysis\n- Testes A/B\n- Métricas divididas em 3: otimização do motor, recursos e crescimento\n\n **Validação de Hipóteses**\n- Teste hipóteses de valor\n- Teste hipóteses de crescimento\n- Use experimentos científicos\n- Falhe rápido e barato\n\n **Os 5 Porquês**\n- Técnica para encontrar causa raiz\n- Evita culpar pessoas\n- Foca em processos\n- Implementa contramedidas proporcionais\n\n **Tipos de crescimento:**\n- **Viral** - clientes trazem outros clientes\n- **Aderente** - baixa taxa de abandono\n- **Pago** - aquisição através de publicidade\n\n**Pivô vs Perseverar**\n- Quando mudar de direção\n- Tipos de pivô\n- Como tomar a decisão\n\n**Lição Principal:**\n"O sucesso de uma startup pode ser projetado seguindo o processo certo, o que significa que pode ser aprendido, o que significa que pode ser ensinado."\n\n**Recomendado para:** Empreendedores, intrapreneurs e executivos interessados em inovação.', '2024-03-12 14:20:00', NOW()),
(4, 9, 'O Poder do Agora - Eckhart Tolle', 'Guia espiritual para viver plenamente no momento presente e encontrar a paz interior.', '**Autor:** Eckhart Tolle\n**Ano:** 1997\n**Páginas:** 236\n\n**Resumo:**\n\nTolle apresenta conceitos espirituais práticos para transcender o sofrimento mental e encontrar a iluminação no momento presente.\n\n**Conceitos fundamentais:**\n\n **O Poder do Momento Presente**\n- O passado já não existe\n- O futuro é apenas imaginação\n- A vida só acontece no AGORA\n- Presença é a chave para a paz\n\n **Identificação com a Mente**\n- Você não é seus pensamentos\n- Observe a mente sem julgamento\n- Crie espaço entre você e seus pensamentos\n- Pare o diálogo interno compulsivo\n\n **O Ego e o Sofrimento**\n- Ego se alimenta do passado e futuro\n- Sofrimento vem da resistência ao que é\n- Aceite o momento presente\n- Transcenda o falso eu\n\n**Práticas espirituais:**\n\n **Estar Presente**\n- Foque nas sensações corporais\n- Observe a respiração\n- Escute os sons ao redor\n- Sinta o espaço interior\n\n **Relacionamentos Conscientes**\n- Esteja presente com outras pessoas\n- Não projete o passado no futuro\n- Aceite os outros como são\n- Ame sem condições\n\n **Observação dos Pensamentos**\n- Torne-se observador da mente\n- Questione: "Qual será meu próximo pensamento?"\n- Crie pausas entre pensamentos\n\n**Citação marcante:**\n"Percebi que o momento presente é tudo que você tem. Torne o AGORA o foco primário de sua vida."\n\n**Recomendado para:** Pessoas buscando paz interior, autoconhecimento e desenvolvimento espiritual.', '2024-04-02 15:30:00', NOW()),
(5, 4, 'Como Fazer Amigos e Influenciar Pessoas', 'Clássico atemporal sobre relacionamentos humanos e influência positiva.', '**Autor:** Dale Carnegie\n**Ano:** 1936\n**Páginas:** 320\n\n**Resumo:**\n\nCarnegie apresenta princípios fundamentais para melhorar relacionamentos pessoais e profissionais através de técnicas testadas pelo tempo.\n\n**Técnicas para lidar com pessoas:**\n\n **Nunca critique, condene ou se queixe**\n- Crítica gera defensividade\n- Foque no comportamento, não na pessoa\n- Use feedback construtivo\n\n **Elogie sinceramente**\n- Reconheça esforços genuinamente\n- Seja específico nos elogios\n- Encontre algo bom em todos\n\n **Desperte interesse genuíno**\n- Foque nos interesses dos outros\n- Faça perguntas sobre suas paixões\n- Escute ativamente\n\n**Como influenciar pessoas:**\n\ **Evite discussões**\n- Não tente "vencer" argumentos\n- Respeite opiniões diferentes\n- Encontre pontos de concordância\n\n **Nunca diga "você está errado"**\n- Admita quando você estiver errado\n- Use frases como "posso estar enganado"\n- Permita que outros salvem a face\n\n **Faça a pessoa sentir-se importante**\n- Use o nome da pessoa frequentemente\n- Peça opiniões e conselhos\n- Reconheça contribuições\n\n**Princípios fundamentais:**\n\n **Seja um bom ouvinte**\n- Encoraje outros a falar sobre si\n- Faça perguntas interessantes\n- Demonstre interesse genuíno\n\n **Sorria**\n- Sorriso genuíno é contagioso\n- Cria atmosfera positiva\n- Faz você parecer mais acessível\n\n**Citação marcante:**\n"Você pode conquistar mais amigos em dois meses se interessando genuinamente por outras pessoas do que em dois anos tentando fazer com que se interessem por você."\n\n**Recomendado para:** Profissionais, líderes e qualquer pessoa que queira melhorar seus relacionamentos.', '2024-03-25 13:45:00', NOW()),
(6, 10, '1984 - George Orwell', 'Distopia clássica sobre totalitarismo, vigilância e controle social.', '**Autor:** George Orwell\n**Ano:** 1949\n**Páginas:** 328\n\n**Resumo:**\n\nOrwell criou uma das distopias mais influentes da literatura, retratando um mundo onde o governo controla todos os aspectos da vida através da vigilância e manipulação.\n\n**Conceitos centrais:**\n\n **Big Brother**\n- Figura onipresente do ditador\n- "Big Brother está te observando"\n- Vigilância constante e total\n- Controle através do medo\n\n **O Partido**\n- Controla verdade, história e linguagem\n- Três ministérios paradoxais:\n  - Ministério da Verdade (propaganda)\n  - Ministério da Paz (guerra)\n  - Ministério do Amor (tortura)\n\ **Novilíngua**\n- Linguagem simplificada para limitar pensamento\n- Reduz vocabulário para controlar ideias\n- "Duplipensar" - aceitar contradições\n\n**Temas principais:**\n\n **Vigilância total**\n- Teletelas em todos os lugares\n- Polícia do Pensamento\n- Controle de informação\n- Manipulação da realidade\n\n **Controle mental**\n- Reescrita constante da história\n- "Quem controla o passado controla o futuro"\n- Destruição da memória individual\n- Guerra perpétua para controle social\n\n **Relacionamentos proibidos**\n- Amor como ato de rebelião\n- Winston e Julia\n- Traição como arma do Estado\n- Destruição da família tradicional\n\n**Conceitos que permaneceram:**\n- "1984" - sinônimo de vigilância estatal\n- "Big Brother" - espionagem governamental\n- "Duplipensar" - hipocrisia política\n- "Novilíngua" - manipulação da linguagem\n\n**Relevância atual:**\n- Vigilância digital\n- Fake news e manipulação\n- Redes sociais e controle\n- Algoritmos e bolhas de filtro\n\n**Citação marcante:**\n"A liberdade é a liberdade de dizer que dois mais dois são quatro. Se isso for concedido, tudo o mais se segue."\n\n**Recomendado para:** Leitores interessados em política, filosofia e reflexões sobre poder e liberdade.', '2024-04-08 16:15:00', NOW()),
(7, 5, 'Padrões de Projetos - Gang of Four', 'O livro fundamental sobre design patterns em programação orientada a objetos.', '**Autores:** Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides\n**Ano:** 1994\n**Páginas:** 395\n\n**Resumo:**\n\nO "Gang of Four" criou o vocabulário comum para design patterns, soluções elegantes para problemas recorrentes em programação.\n\n**Categorias de Padrões:**\n\n **Padrões Criacionais**\n- **Singleton** - Uma única instância\n- **Factory Method** - Criação de objetos\n- **Abstract Factory** - Famílias de objetos\n- **Builder** - Construção complexa\n- **Prototype** - Clonagem de objetos\n\n **Padrões Estruturais**\n- **Adapter** - Interface de compatibilidade\n- **Bridge** - Separação abstração/implementação\n- **Composite** - Árvores de objetos\n- **Decorator** - Adição de funcionalidades\n- **Facade** - Interface simplificada\n- **Proxy** - Representante de objeto\n\n⚡ **Padrões Comportamentais**\n- **Observer** - Notificação de mudanças\n- **Strategy** - Algoritmos intercambiáveis\n- **Command** - Encapsulamento de operações\n- **State** - Mudança de comportamento\n- **Template Method** - Esqueleto de algoritmo\n\n**Princípios fundamentais:**\n\n **Programe para interface, não implementação**\n- Use abstrações\n- Baixo acoplamento\n- Alta coesão\n\n **Favoreça composição sobre herança**\n- Maior flexibilidade\n- Reutilização de código\n- Evita hierarquias complexas\n\n**Exemplo prático - Observer:**\n```java\n// Sujeito notifica observadores sobre mudanças\ninterface Observer {\n    void update(String message);\n}\n\nclass Subject {\n    private List<Observer> observers = new ArrayList<>();\n    \n    void attach(Observer observer) {\n        observers.add(observer);\n    }\n    \n    void notifyObservers(String message) {\n        for (Observer obs : observers) {\n            obs.update(message);\n        }\n    }\n}\n```\n\n**Recomendado para:** Desenvolvedores que querem escrever código mais elegante, reutilizável e maintível.', '2024-04-12 11:30:00', NOW()),
(8, 7, 'Meditações - Marco Aurélio', 'Reflexões estoicas do imperador filósofo sobre vida, virtude e sabedoria.', '**Autor:** Marco Aurélio\n**Ano:** 180 d.C. (escrito entre 161-180 d.C.)\n**Páginas:** 256\n\n**Resumo:**\n\nEscrito como um diário pessoal, "Meditações" contém os pensamentos filosóficos do imperador romano Marco Aurélio, oferecendo insights atemporais sobre como viver uma vida virtuosa.\n\n**Princípios estoicos centrais:**\n\n **Foque no que você pode controlar**\n- Suas ações e reações\n- Seus pensamentos e julgamentos\n- Suas escolhas morais\n- Aceite o que está fora do seu controle\n\n **Viva no presente**\n- O passado já se foi\n- O futuro é incerto\n- Só temos o momento atual\n- "Confine-se ao presente"\n\n **Virtude como único bem verdadeiro**\n- Sabedoria (sophia)\n- Justiça (dikaiosyne)\n- Coragem (andreia)\n- Temperança (sophrosyne)\n\n**Ensinamentos práticos:**\n\n **Sobre adversidades**\n- "O universo é mudança; nossa vida é o que nossos pensamentos fazem dela"\n- Dificuldades são oportunidades de crescimento\n- Aceite contratempos com serenidade\n\n **Sobre mortalidade**\n- Lembre-se diariamente da morte (memento mori)\n- Isso dá perspectiva aos problemas\n- Viva como se fosse seu último dia\n\n **Sobre relacionamentos**\n- Todos somos parte de um todo maior\n- Pratique compaixão mesmo com pessoas difíceis\n- "Somos feitos para trabalhar juntos"\n\n**Citações marcantes:**\n\n "Você tem poder sobre sua mente - não eventos externos. Perceba isso, e você encontrará força."\n\n "Quando você acordar pela manhã, diga a si mesmo: as pessoas com quem vou lidar hoje serão intrusivas, ingratas, arrogantes, desonestas, ciumentas e mal-humoradas."\n\n1 "A melhor vingança é não ser como seu inimigo."\n\n**Relevância moderna:**\n- Base para terapia cognitivo-comportamental\n- Mindfulness e meditação\n- Liderança resiliente\n- Gestão de estresse\n\n**Recomendado para:** Líderes, pessoas buscando sabedoria prática e interessados em filosofia aplicada à vida cotidiana.', '2024-04-18 14:45:00', NOW());

-- Inserir endereços para os usuários
INSERT INTO enderecos (user_id, nome, rua, numero, cidade, bairro, created_at, updated_at) VALUES 
(1, 'Casa', 'Rua das Flores', '123', 'São Paulo', 'Vila Madalena', '2024-01-15 10:30:00', NOW()),
(1, 'Escritório', 'Av. Paulista', '1000', 'São Paulo', 'Bela Vista', '2024-01-15 10:35:00', NOW()),
(2, 'Residência', 'Rua dos Jasmins', '456', 'São Paulo', 'Jardins', '2024-02-20 15:00:00', NOW()),
(3, 'Casa', 'Av. Atlântica', '789', 'Rio de Janeiro', 'Copacabana', '2024-02-25 09:30:00', NOW()),
(3, 'Trabalho', 'Rua do Ouvidor', '321', 'Rio de Janeiro', 'Centro', '2024-02-25 09:35:00', NOW()),
(4, 'Apartamento', 'Rua da Bahia', '654', 'Belo Horizonte', 'Centro', '2024-03-01 17:00:00', NOW()),
(5, 'Casa', 'Rua XV de Novembro', '987', 'Curitiba', 'Centro', '2024-03-05 11:45:00', NOW()),
(5, 'Empresa', 'Av. Cândido de Abreu', '200', 'Curitiba', 'Centro Cívico', '2024-03-05 11:50:00', NOW()),
(6, 'Residência', 'Rua da Praia', '147', 'Porto Alegre', 'Centro Histórico', '2024-03-10 13:30:00', NOW()),
(7, 'Casa', 'SQN 405', 'Bloco A', 'Brasília', 'Asa Norte', '2024-03-15 08:45:00', NOW()),
(8, 'Apartamento', 'Rua Dragão do Mar', '258', 'Fortaleza', 'Praia de Iracema', '2024-03-20 15:30:00', NOW()),
(9, 'Casa', 'Av. Boa Viagem', '369', 'Recife', 'Boa Viagem', '2024-03-25 13:00:00', NOW()),
(10, 'Residência', 'Rua José de Alencar', '741', 'Fortaleza', 'Centro', '2024-04-01 10:45:00', NOW()),
(11, 'Casa', 'Rua das Palmeiras', '852', 'Blumenau', 'Centro', '2024-04-05 14:35:00', NOW()),
(12, 'Apartamento', 'Av. Goiás', '963', 'Campinas', 'Centro', '2024-04-10 10:15:00', NOW()),
(13, 'Casa', 'Rua Jerônimo Monteiro', '159', 'Vitória', 'Centro', '2024-04-15 16:20:00', NOW());

INSERT INTO comentarios (post_id, user_id, comentario, likes, dislikes, created_at, updated_at) VALUES 
(1, 2, 'Ótima iniciativa! Estou ansioso para ler mais resumos.', 5, 0, '2024-01-15 11:00:00', NOW()),
(1, 3, 'Parabéns pela biblioteca! Vou acompanhar sempre.', 8, 0, '2024-01-15 15:30:00', NOW()),
(1, 4, 'Finalmente um lugar com resumos de qualidade!', 12, 1, '2024-01-16 09:15:00', NOW()),

(2, 2, 'Este livro mudou minha carreira! Excelente resumo.', 15, 1, '2024-01-20 16:30:00', NOW()),
(2, 6, 'Código limpo realmente faz diferença na manutenção. Recomendo!', 18, 2, '2024-01-21 13:45:00', NOW()),
(2, 10, 'Como dev iniciante, confirmo: este livro é essencial!', 9, 0, '2024-01-22 19:00:00', NOW()),

(3, 1, 'James Clear é genial! As 4 leis mudaram minha rotina.', 14, 0, '2024-02-22 18:00:00', NOW()),
(3, 7, 'Empilhamento de hábitos salvou minha produtividade!', 20, 0, '2024-02-23 15:15:00', NOW()),
(4, 11, 'A parte sobre a revolução cognitiva é fascinante!', 18, 1, '2024-03-05 16:45:00', NOW()),
(5, 6, 'O conceito de "janelas quebradas" é genial!', 21, 1, '2024-02-29 10:15:00', NOW()),
(5, 8, 'Hunt e Thomas são lendas da programação!', 15, 0, '2024-02-29 16:45:00', NOW()),

(6, 4, 'Eric Ries revolucionou o empreendedorismo! Livro essencial.', 28, 2, '2024-03-12 15:30:00', NOW()),
(6, 7, 'O ciclo construir-medir-aprender salvou minha startup!', 24, 0, '2024-03-12 17:15:00', NOW()),

(7, 2, 'Harari consegue ser profundo e acessível ao mesmo tempo!', 35, 1, '2024-03-18 12:15:00', NOW()),
(7, 5, 'As reflexões sobre IA e futuro do trabalho são essenciais.', 29, 2, '2024-03-18 15:45:00', NOW()),
(7, 10, 'Livro obrigatório para entender nosso tempo!', 18, 0, '2024-03-19 08:30:00', NOW()),
(7, 12, 'A parte sobre fake news é muito atual e necessária.', 26, 4, '2024-03-19 13:20:00', NOW()),

(8, 13, 'Viver no presente é revolucionário nos dias de hoje.', 27, 0, '2024-04-03 10:15:00', NOW()),

(9, 8, 'Os princípios de relacionamento funcionam! Mudou minha carreira.', 38, 1, '2024-03-25 18:30:00', NOW()),
(9, 12, 'Livro que deveria ser leitura obrigatória nas escolas!', 33, 0, '2024-03-26 11:45:00', NOW()),

(10, 7, 'Orwell foi profético! Cada vez mais atual e assustador.', 22, 0, '2024-04-08 17:30:00', NOW()),
(10, 11, 'Distopia que virou manual de instrução. Livro essencial!', 19, 0, '2024-04-09 09:00:00', NOW()),

(11, 1, 'Python realmente é a porta de entrada ideal!', 44, 1, '2024-04-12 13:00:00', NOW()),
(11, 4, 'Data Science com Python é o futuro!', 37, 0, '2024-04-12 16:30:00', NOW()),

(12, 5, 'Inbox zero mudou minha produtividade!', 33, 1, '2024-04-18 18:45:00', NOW()),
(12, 13, 'Um dia sem redes sociais por semana é libertador!', 41, 2, '2024-04-19 11:30:00', NOW());

INSERT INTO enderecos (user_id, nome, rua, numero, cidade, bairro, created_at, updated_at) VALUES 
(2, 'Trabalho', 'Av. Faria Lima', '2500', 'São Paulo', 'Itaim Bibi', '2024-02-20 15:30:00', NOW()),
(4, 'Casa de Praia', 'Rua do Mar', '88', 'Ubatuba', 'Praia Grande', '2024-03-01 17:15:00', NOW()),
(6, 'Escritório', 'Rua dos Andradas', '1234', 'Porto Alegre', 'Centro', '2024-03-10 14:00:00', NOW()),
(8, 'Casa de Campo', 'Estrada Rural', 'KM 15', 'Petrópolis', 'Itaipava', '2024-03-20 16:00:00', NOW()),
(9, 'Coworking', 'Av. Paulista', '2073', 'São Paulo', 'Consolação', '2024-03-25 13:15:00', NOW()),
(10, 'Studio', 'Rua Augusta', '456', 'São Paulo', 'Consolação', '2024-04-01 11:00:00', NOW()),
(11, 'Casa dos Pais', 'Rua das Acácias', '789', 'Florianópolis', 'Trindade', '2024-04-05 15:00:00', NOW()),
(12, 'Loja', 'Av. Getúlio Vargas', '1500', 'Campinas', 'Centro', '2024-04-10 10:45:00', NOW()),
(13, 'Filial', 'Rua da Praia', '300', 'Vila Velha', 'Centro', '2024-04-15 16:45:00', NOW());
