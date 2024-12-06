-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/12/2024 às 04:04
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `sylia` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sylia`;

--
-- Estrutura para tabela `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user_dest` int(11) NOT NULL,
  `id_doc` int(11) NOT NULL,
  `texto` varchar(500) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cmt`
--

CREATE TABLE `cmt` (
  `id_cmt` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `tipo` enum('poste','repositorio','comentario','perfil') NOT NULL,
  `texto` varchar(250) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Estrutura para tabela `comunidade`
--

CREATE TABLE `comunidade` (
  `id_comunidade` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nome` varchar(75) NOT NULL,
  `descricao` varchar(500) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


-- --------------------------------------------------------

--
-- Estrutura para tabela `contacto`
--

CREATE TABLE `contacto` (
  `id_contacto` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_user_dest` int(11) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- --------------------------------------------------------

--
-- Estrutura para tabela `doc`
--

CREATE TABLE `doc` (
  `id_doc` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tipo` enum('poste','mensagen','repositorio','comentario','usuario','comunidade','storie') NOT NULL,
  `indereco` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `noticia`
--

CREATE TABLE `noticia` (
  `id_noticia` int(11) NOT NULL,
  `conteudo` varchar(1500) NOT NULL,
  `fonte` varchar(100) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pbl`
--

CREATE TABLE `pbl` (
  `id_pbl` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `texto` varchar(500) NOT NULL,
  `data` datetime NOT NULL,
  `id_comunidade` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Despejando dados para a tabela `pbl`
--

INSERT INTO `pbl` (`id_pbl`, `id_user`, `texto`, `data`, `id_comunidade`) VALUES
(1, 1, 'oi mundo', '2024-12-06 01:20:50', 0),
(2, 1, '', '2024-12-06 02:55:41', 0),
(3, 1, 'qual, de novo.', '2024-12-06 03:05:50', 1),
(4, 2, 'carregamento de varias imagens', '2024-12-06 03:51:16', 1),
(5, 2, 'imagens, muitas', '2024-12-06 03:52:16', 1),
(6, 2, 'teste', '2024-12-06 03:55:14', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `repositorio`
--

CREATE TABLE `repositorio` (
  `id_reposirotio` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nome` int(11) NOT NULL,
  `data_criacao` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `stories`
--

CREATE TABLE `stories` (
  `id_storie` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `indereco_img` text NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_user` int(11) NOT NULL,
  `nome` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `id_pais` int(11) NOT NULL,
  `senha` text NOT NULL,
  `code_nome` varchar(20) NOT NULL,
  `data` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id_chat`);

--
-- Índices de tabela `cmt`
--
ALTER TABLE `cmt`
  ADD PRIMARY KEY (`id_cmt`);

--
-- Índices de tabela `comunidade`
--
ALTER TABLE `comunidade`
  ADD PRIMARY KEY (`id_comunidade`);

--
-- Índices de tabela `contacto`
--
ALTER TABLE `contacto`
  ADD PRIMARY KEY (`id_contacto`);

--
-- Índices de tabela `doc`
--
ALTER TABLE `doc`
  ADD PRIMARY KEY (`id_doc`);

--
-- Índices de tabela `noticia`
--
ALTER TABLE `noticia`
  ADD PRIMARY KEY (`id_noticia`);

--
-- Índices de tabela `pbl`
--
ALTER TABLE `pbl`
  ADD PRIMARY KEY (`id_pbl`);

--
-- Índices de tabela `repositorio`
--
ALTER TABLE `repositorio`
  ADD PRIMARY KEY (`id_reposirotio`);

--
-- Índices de tabela `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id_storie`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `chat`
--
ALTER TABLE `chat`
  MODIFY `id_chat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cmt`
--
ALTER TABLE `cmt`
  MODIFY `id_cmt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `comunidade`
--
ALTER TABLE `comunidade`
  MODIFY `id_comunidade` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `contacto`
--
ALTER TABLE `contacto`
  MODIFY `id_contacto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `doc`
--
ALTER TABLE `doc`
  MODIFY `id_doc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `noticia`
--
ALTER TABLE `noticia`
  MODIFY `id_noticia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pbl`
--
ALTER TABLE `pbl`
  MODIFY `id_pbl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `repositorio`
--
ALTER TABLE `repositorio`
  MODIFY `id_reposirotio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `stories`
--
ALTER TABLE `stories`
  MODIFY `id_storie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
