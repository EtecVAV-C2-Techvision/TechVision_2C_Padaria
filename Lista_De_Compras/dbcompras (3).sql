-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/11/2025 às 01:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `dbcompras`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbcategorias`
--

CREATE TABLE `tbcategorias` (
  `idCat` int(11) NOT NULL,
  `nomeCat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbcategorias`
--

INSERT INTO `tbcategorias` (`idCat`, `nomeCat`) VALUES
(1, 'Bebidas'),
(12, 'Farmácia'),
(13, 'Shopping'),
(14, 'Papelaria'),
(15, 'Padaria');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbcompras`
--

CREATE TABLE `tbcompras` (
  `idItem` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `quantidade` int(10) NOT NULL,
  `idCat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tbcompras`
--

INSERT INTO `tbcompras` (`idItem`, `nome`, `quantidade`, `idCat`) VALUES
(108, 'Dipirona ', 90, 12),
(110, 'Chá ', 4, 1),
(111, 'Coca', 10, 1),
(112, 'Suco de Laranja', 25, 1),
(113, 'Água Mineral 500ml', 80, 1),
(114, 'Guaraná Antártica', 40, 1),
(115, 'Energético RedBull', 30, 1),
(116, 'Leite Integral', 60, 1),
(117, 'Café em Pó Tradicional', 50, 1),
(118, 'Paracetamol', 100, 12),
(119, 'Ibuprofeno', 90, 12),
(120, 'Soro Fisiológico 500ml', 45, 12),
(121, 'Vitamina C', 70, 12),
(122, 'Curativo Band-Aid', 200, 12),
(123, 'Camiseta Básica', 40, 13),
(124, 'Calça Jeans', 25, 13),
(125, 'Tênis Casual', 15, 13),
(126, 'Mochila Escolar', 20, 13),
(127, 'Boné Aba Curva', 35, 13),
(128, 'Caderno 10 matérias', 50, 14),
(129, 'Lápis HB', 200, 14),
(130, 'Caneta Azul', 300, 14),
(131, 'Borracha Branca', 150, 14),
(132, 'Tesoura Escolar', 55, 14),
(133, 'Pão Francês', 300, 15),
(134, 'Sonho', 40, 15),
(135, 'Bolo de Chocolate', 12, 15),
(136, 'Rosquinha Açucarada', 60, 15),
(137, 'Croissant', 30, 15);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `token`) VALUES
(4, 'gustavo', 'gustavo', '$2y$10$zvRRIM1zNRWN.PMcpP5nuOIyNktifdKUYKPQzdBEkQLzFlieC9neC', '');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `tbcategorias`
--
ALTER TABLE `tbcategorias`
  ADD PRIMARY KEY (`idCat`);

--
-- Índices de tabela `tbcompras`
--
ALTER TABLE `tbcompras`
  ADD PRIMARY KEY (`idItem`),
  ADD KEY `fk_categoria` (`idCat`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbcategorias`
--
ALTER TABLE `tbcategorias`
  MODIFY `idCat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `tbcompras`
--
ALTER TABLE `tbcompras`
  MODIFY `idItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `tbcompras`
--
ALTER TABLE `tbcompras`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`idCat`) REFERENCES `tbcategorias` (`idCat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
