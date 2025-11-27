-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2025 às 00:46
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_funcionarios`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `idCli` int(11) NOT NULL,
  `nome_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `endereco` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`idCli`, `nome_completo`, `email`, `usuario`, `senha`, `endereco`, `telefone`, `data_cadastro`) VALUES
(1, 'Gustavo', 'gustavo@grano.com', 'GG', '$2y$10$eCrP2yojbGvbgvYfHNbPm.RvAM8BBjeP9GEH2/yzm5nScvLmIxpKi', 'Rua Tutóia', '12112313231331213', '2025-10-29 21:01:32'),
(3, 'cliente um', 'clienteum@grano.com', 'Cliente 1', '$2y$10$yalkpsG748uEElg4FguarOcdg0WzCAgImXyoOUvlIJ0Vlmu8A3YVa', 'Rua Cliente', '324131441', '2025-10-29 21:12:29'),
(4, 'cliente dois', 'clientedois@grano.com', 'cliente dois', '$2y$10$uGVFtPwMYDar/Vrf63OePevH2jCFpV52qbpo33XTbeJWShuZboSp.', 'Rua Cliente', '1241241214214', '2025-11-01 20:33:59');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `idFunc` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(225) NOT NULL,
  `nome_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `funcao` enum('gerente','funcionario','repositor','entregador') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`idFunc`, `usuario`, `senha`, `nome_completo`, `email`, `funcao`) VALUES
(3, 'gerente3', '$2y$10$boxfPvxclZee9DNR9L3js.8WmAwXhqA0UXOzY6B7xLOeSTIJ9oGBa', 'gerentetres', 'gerente.tres@gmail.com', 'gerente'),
(4, 'repositor1', '$2y$10$31ncgb7HtYCG.jyCxvs9Wem2xnVkP7.AhhwVztH8M2EIzR2c.l23G', 'Repositor Um', 'repositorum@empresa.com', 'repositor'),
(5, 'gerente1', '$2y$10$Te4IhAgtlU/KkblWPR1VGOvg2vZMclBRuSI16CqI.0QmmO4s7vPiy', 'Gerente 1', 'gerente1@gmail.com', 'gerente'),
(6, 'funcionario1', '$2y$10$2XIzQ9Vwe1jQqGXFCvx.i.TrYneie1fQhssxMvlJYBxOXm0/0Vw8W', 'Funcionario 1', 'funcionario1@gmail.com', 'funcionario'),
(7, 'repositor2', '$2y$10$UZ6G9OwqzLICb9JEjoFove2/pnSTkiSLhMc3lY.DtyLKw5KyaKoCS', 'Repositor 2', 'repositor2@gmail.com', 'repositor'),
(8, 'gerente2', '$2y$10$TOPsrXMD5kJ3UmKKVJgFcOlZysmYz0sXY0ws99JCU47mW0iJyxTAW', 'Gerente 2', 'gerente2@gmail.com', 'gerente'),
(9, 'entregador1', '$2y$10$ikYeYd3wQr0eiOIZ.o1NwewlK3KXQ2cBED2JyV.u8Hx/ilFVXvEyq', 'Entregador 1', 'entregador1@grano.com', 'entregador');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `idItem` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idProd` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`idItem`, `idPedido`, `idProd`, `quantidade`, `preco_unitario`) VALUES
(1, 3, 43, 3, 5.00),
(2, 3, 45, 1, 9.90),
(5, 6, 45, 1, 9.90),
(6, 7, 1, 3, 10.00),
(7, 7, 44, 1, 0.80),
(8, 7, 55, 1, 5.00),
(9, 8, 59, 1, 4.00),
(10, 9, 45, 1, 9.90),
(11, 10, 59, 2, 4.00),
(12, 11, 43, 4, 5.00),
(13, 14, 45, 4, 9.90),
(14, 15, 51, 1, 25.00),
(15, 16, 58, 1, 6.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_clientes`
--

CREATE TABLE `log_clientes` (
  `idLog` int(11) NOT NULL,
  `idCli` int(11) NOT NULL,
  `idFunc` int(11) NOT NULL,
  `acao` varchar(255) NOT NULL,
  `data_log` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `log_clientes`
--

INSERT INTO `log_clientes` (`idLog`, `idCli`, `idFunc`, `acao`, `data_log`) VALUES
(1, 4, 5, 'Alterou dados do cliente \'cliente dois\' (ID 4)', '2025-11-27 20:03:26');

-- --------------------------------------------------------

--
-- Estrutura para tabela `log_pedidos`
--

CREATE TABLE `log_pedidos` (
  `idLog` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idFunc` int(11) NOT NULL,
  `acao` varchar(255) NOT NULL,
  `data_log` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `log_pedidos`
--

INSERT INTO `log_pedidos` (`idLog`, `idPedido`, `idFunc`, `acao`, `data_log`) VALUES
(1, 7, 5, 'Alterou status do pedido para \'Em processo\'', '2025-11-01 19:44:15'),
(2, 7, 5, 'Alterou status do pedido para \'Pendente\'', '2025-11-01 19:44:18'),
(3, 7, 5, 'Alterou status do pedido para \'Pendente\'', '2025-11-01 19:44:18'),
(4, 7, 5, 'Alterou status do pedido para \'Pendente\'', '2025-11-01 19:48:35'),
(5, 3, 5, 'Alterou status do pedido para \'Em processo\'', '2025-11-01 19:48:41'),
(6, 3, 5, 'Alterou status do pedido para \'Entregue\'', '2025-11-01 19:48:44'),
(7, 5, 5, 'Excluiu o pedido #5', '2025-11-01 19:49:00'),
(8, 5, 5, 'Excluiu o pedido #5', '2025-11-01 19:49:03'),
(9, 5, 5, 'Excluiu o pedido #5', '2025-11-01 19:50:31'),
(10, 7, 5, 'Adicionou o produto #55 (qtd 1) ao pedido', '2025-11-01 19:50:54'),
(11, 4, 5, 'Excluiu o pedido #4', '2025-11-01 20:04:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `idCli` int(11) NOT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idPedido`, `idCli`, `data_pedido`, `status`, `total`) VALUES
(3, 3, '2025-10-29 03:00:00', 'Entregue', 24.90),
(6, 3, '2025-11-01 23:51:42', 'Entregue', 9.90),
(7, 4, '2025-11-02 00:46:47', 'Pendente', 35.80),
(8, 3, '2025-11-28 01:39:04', 'Cancelado', 4.00),
(9, 3, '2025-11-28 01:44:44', 'Cancelado', 9.90),
(10, 3, '2025-11-28 01:46:06', 'Cancelado', 8.00),
(11, 3, '2025-11-28 03:15:03', 'Em processo', 20.00),
(12, 3, '2025-11-27 23:19:05', 'Pendente', 0.00),
(13, 3, '2025-11-27 23:19:11', 'Pendente', 0.00),
(14, 3, '2025-11-28 03:24:45', 'Em processo', 39.60),
(15, 3, '2025-11-28 03:30:00', 'Em processo', 25.00),
(16, 3, '2025-11-28 03:39:13', 'Em processo', 6.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idProd` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `fotos` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idProd`, `nome`, `categoria`, `preco`, `quantidade`, `fotos`) VALUES
(1, 'Brioche', 'Pães Doces', 10.00, 17, 'imagens/brioche.jpg'),
(43, 'Donuts', 'Doces', 5.00, 3, 'imagens/68b18ebe8abeb.jpg'),
(44, 'Pão Francês', 'Pães', 0.80, 499, 'imagens/68d004f4e6bc6.jpg'),
(45, 'Pão de Forma Integral', 'Pães', 9.90, 33, 'imagens/68d007222c3dd.jpg'),
(46, 'Pão de Batata com Recheio de Catupiry', 'Pães', 5.50, 60, 'imagens/68d00749c441a.jpg'),
(47, 'Baguete Tradicional', 'Pães', 7.00, 35, 'imagens/68d0076373c68.jpg'),
(48, 'Pão Doce com Coco', 'Pães Doces', 3.50, 70, 'imagens/68d0077f845a6.jpg'),
(49, 'Bolo de Chocolate (fatia)', 'Bolos', 6.00, 80, 'imagens/68d007cad296a.jpg'),
(50, 'Bolo de Fubá (inteiro)', 'Bolos', 25.00, 15, 'imagens/68d007ecdf3f3.jpg'),
(51, 'Torta de Limão (fatia)', 'Sobremesa', 25.00, 14, 'imagens/68d0080f28bfd.jpg'),
(52, 'Sonho com Creme', 'Doces', 4.50, 100, 'imagens/68d0083c37153.jpg'),
(53, 'Churros de Doce de Leite', 'Doces', 3.00, 120, 'imagens/68d0085380db3.jpg'),
(54, 'Coxinha de Frango', 'Salgados', 4.00, 150, 'imagens/68d0089044d49.jpg'),
(55, 'Pastel de Queijo', 'Salgados', 5.00, 90, 'imagens/68d0096098220.png'),
(56, 'Empadinha de Frango', 'Salgados', 4.50, 70, 'imagens/68d009927d6ae.jpg'),
(57, 'Esfiha de Carne', 'Salgados', 5.50, 110, 'imagens/68d009aa525ae.jpg'),
(58, 'Quiche de Alho-Poró Mini', 'Salgados', 6.00, 29, 'imagens/68d009ddde73c.jpg'),
(59, 'Café Expresso (pequeno)', 'Bebidas', 4.00, 197, 'imagens/68d009fbd39b2.jpeg'),
(60, 'Café com Leite (médio)', 'Bebidas', 5.50, 150, 'imagens/68d00a1b30216.jpg'),
(61, 'Suco Natural de Laranja (300ml)', 'Bebidas', 6.60, 80, 'imagens/68d00a4242891.jpeg'),
(62, 'Capuccino (médio)', 'Bebidas', 7.50, 60, 'imagens/68d00a5e5da27.jpg'),
(63, 'Chá Gelado de Pêssego (500ml)', 'Bebidas', 5.50, 40, 'imagens/68d00a7d14c66.jpg');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCli`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`idFunc`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idProd` (`idProd`);

--
-- Índices de tabela `log_clientes`
--
ALTER TABLE `log_clientes`
  ADD PRIMARY KEY (`idLog`);

--
-- Índices de tabela `log_pedidos`
--
ALTER TABLE `log_pedidos`
  ADD PRIMARY KEY (`idLog`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `idCli` (`idCli`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idProd`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `idFunc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `log_clientes`
--
ALTER TABLE `log_clientes`
  MODIFY `idLog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `log_pedidos`
--
ALTER TABLE `log_pedidos`
  MODIFY `idLog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`idProd`) REFERENCES `produtos` (`idProd`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`idCli`) REFERENCES `clientes` (`idCli`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
