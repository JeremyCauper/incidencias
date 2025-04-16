/*
 Navicat Premium Data Transfer

 Source Server         : Mysql_local
 Source Server Type    : MySQL
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : incidencias_prueba

 Target Server Type    : MySQL
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 16/04/2025 16:58:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for contactos_empresas
-- ----------------------------
DROP TABLE IF EXISTS `contactos_empresas`;
CREATE TABLE `contactos_empresas`  (
  `id_contact` int(11) NOT NULL AUTO_INCREMENT,
  `nro_doc` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nombres` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cargo` int(11) NOT NULL,
  `correo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_contact`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of contactos_empresas
-- ----------------------------
INSERT INTO `contactos_empresas` VALUES (10, '00098358', 'MARIBET VALLES VARGAS', '923456871', 1, 'erlinda@gmail.com', 1, '2025-04-12 19:34:22', '2025-04-10 09:28:31');

-- ----------------------------
-- Table structure for tb_contac_ordens
-- ----------------------------
DROP TABLE IF EXISTS `tb_contac_ordens`;
CREATE TABLE `tb_contac_ordens`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nro_doc` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nombre_cliente` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `firma_digital` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_contac_ordens
-- ----------------------------

-- ----------------------------
-- Table structure for tb_cronograma_turno
-- ----------------------------
DROP TABLE IF EXISTS `tb_cronograma_turno`;
CREATE TABLE `tb_cronograma_turno`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha_ini_s` date NOT NULL,
  `hora_ini_s` time(0) NOT NULL,
  `fecha_fin_s` date NOT NULL,
  `hora_fin_s` time(0) NOT NULL,
  `personal_s` int(11) NOT NULL,
  `fecha_ini_a` date NOT NULL,
  `hora_ini_a` time(0) NOT NULL,
  `fecha_fin_a` date NOT NULL,
  `hora_fin_a` time(0) NOT NULL,
  `personal_a` int(11) NOT NULL,
  `creador` int(11) NOT NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `personal_s`(`personal_s`) USING BTREE,
  INDEX `personal_a`(`personal_a`) USING BTREE,
  CONSTRAINT `tb_cronograma_turno_ibfk_1` FOREIGN KEY (`personal_s`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_cronograma_turno_ibfk_2` FOREIGN KEY (`personal_a`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_cronograma_turno
-- ----------------------------
INSERT INTO `tb_cronograma_turno` VALUES (3, '2025-03-24', '18:00:00', '2025-03-31', '08:20:00', 14, '2025-03-29', '13:00:00', '2025-03-31', '08:20:00', 19, 6, 0, 1, NULL, '2025-03-25 10:25:55');

-- ----------------------------
-- Table structure for tb_empresas
-- ----------------------------
DROP TABLE IF EXISTS `tb_empresas`;
CREATE TABLE `tb_empresas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruc` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `razon_social` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `contrato` tinyint(1) NOT NULL,
  `id_nube` int(11) NULL DEFAULT NULL,
  `id_grupo` int(11) NOT NULL,
  `direccion` varchar(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ubigeo` varbinary(6) NOT NULL,
  `facturacion` int(11) NOT NULL DEFAULT 0,
  `prico` int(11) NOT NULL,
  `encargado` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `cargo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `correo` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eds` int(11) NULL DEFAULT NULL,
  `visitas` int(11) NOT NULL DEFAULT 0,
  `mantenimientos` int(11) NOT NULL DEFAULT 0,
  `dias_visita` int(11) NOT NULL DEFAULT 0,
  `codigo_aviso` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ruc`(`ruc`) USING BTREE,
  UNIQUE INDEX `razon_social`(`razon_social`) USING BTREE,
  INDEX `FK_ID_GRUPO`(`id_grupo`) USING BTREE,
  CONSTRAINT `tb_empresas_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `tb_grupos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 203 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_empresas
-- ----------------------------
INSERT INTO `tb_empresas` VALUES (1, '20345774042', 'SERVICENTRO AGUKI S.A.', 0, 115, 3, 'AV. ELMER FAUCETT 5482', 0x313530313430, 1, 1, 'Freddy Taira', NULL, '946501508', 'ftaira@aguki.com', 1, 0, 0, 0, 0, 1, '2025-03-03 00:15:56', '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (2, '20517103633', 'AJ GROUP INVERGAS', 0, 31, 4, 'AV. SANTIAGO DE CHUCO NRO. 501 COO. UNIVERSAL (ESTACION DE SERVICIO)', 0x313530313430, 1, 0, 'CAROLINA ALIAGA', 'GERENTE GENERAL', '998878575', '', 1, 0, 0, 0, 0, 1, '2025-04-13 01:43:42', '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (3, '20603850913', 'A.J.C. NEGOCIOS E.I.R.L.', 0, 100, 5, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', 0x313530313430, 1, 0, 'Juan Salazar', '', '933737615', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (4, '20603906790', 'TRANSPORTES VALL E.I.R.L.', 0, 99, 5, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', 0x313530313430, 1, 0, 'Juan Salazar', '', '933737615', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (5, '20513567139', 'ALTA VIDDA GAS S.A.C.', 0, 14, 6, 'CAL.FELIPE SANTIAGO SALAVERRY NRO. 341 (ALT CDRA 18 DE AV CIRCUNVALACION)', 0x313530313430, 1, 1, 'KARLA VITTOR', 'GERENTE COMERCIAL', '995867602', 'kvittor@altaviddagas.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (6, '20518664019', 'AMEL PHARMA E.I.R.L.', 0, 148, 7, 'JR. PARURO NRO. 926 INT. 381', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (7, '20604857105', 'CORPORACION AXELL S.A.C.', 0, 130, 8, 'JR. HUANTA NRO. 944 INT. B URB. BARRIOS ALTOS', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (8, '20600251873', 'BALIAN S.A.C.', 0, 108, 9, 'AV. LAS PRADERAS MZA. U LOTE. 29 URB. PRADERA DE STA ANITA II E', 0x313530313430, 1, 1, 'Angel Morocco', '', '992210914', 'amorocco@balian.com.pe', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (9, '20546454224', 'CORPORACION BOTIFARMA E.I.R.L', 0, 9, 10, 'JR. PARURO NRO. 926 INT. 2076', 0x313530313430, 1, 0, 'Wilmer Reyna', 'Administrador', '997364151', 'wilmer_reyna25@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (10, '20156278751', 'J.R. TELECOM S.R.LTDA.', 0, 84, 11, 'Jr. Conray Grande Nro. 4909 - Urb. Parque El Naranjal', 0x313530313430, 1, 0, 'beatriz Crisostomo', 'Contadora', '965395755', 'bcrisostomo@cableperu.net', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (11, '20501688593', 'CABLE VIDEO PERU S.A.C.', 0, 78, 11, 'Jr. Conray Grande Nro. 4901 - Urb. Parque Naranjal - Cdra.13 Av. Naranjal', 0x313530313430, 1, 1, 'Maricela Cordova', 'Contadora', '966370237', 'mcordova@cableperu.pe', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (12, '20548480214', 'CATAFARMA S.A.C.', 0, 141, 12, 'AV. JOSE SANTOS CHOCANO NRO. 104 P.J. VEINTIDOS DE OCTUBRE (AL COSTADO DEL HOSP. SAN JOSE)', 0x313530313430, 1, 0, 'Edwin Carlos Diaz', 'Administrador', '918106426', 'boticainkaperu@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (13, '20499102071', 'ALIMENTOS SELECTOS CAZTELLANI S.A.', 0, 6, 13, 'AV. ARAVICUS NRO. 228 URB. TAHUANTISUYO (KM.5 AV.TUPAC AMARU C/CARLOS IZAS)', 0x313530313430, 1, 0, 'Roberto Aguilar', 'Gerente General', '996394345', 'cafecaztellani@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (14, '20605002448', 'SERVICENTROS CELESTE 3 SAC', 0, 144, 14, 'AV. QUILCA MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', 0x313530313430, 1, 0, ' Lizette Silva', ' Administracion', '987264002 ', 'servicentrocelestesa@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (15, '20168217723', 'COMERCIALIZADORA INDUSTRIAL LA MOLINA S.A.C.', 0, 92, 15, 'AV. LA MOLINA NRO. 448 URB. EL ARTESANO (BAJAR OVALO STA ANIT)', 0x313530313430, 1, 0, 'Carlos Briceño', '', '910736075', 'carlos.briceno@distribuidoraessa.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (16, '20491287544', 'CLINICA CORAZON DE JESUS SAC.', 0, 97, 16, 'AV. MARISCAL BENAVIDES NRO. 565', 0x313530313430, 1, 0, 'Isabut', 'Administradora', '925213430', 'clinicacorazondejesus@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (17, '20562897128', 'CORPORACION FARMACEUTICA SALVADOR S.A.C - COFARSA S.A.C.', 0, 74, 17, 'AV. GNRAL MIGUEL IGLESIAS NRO. 947 INT. A-B ZONA D (FRENTE AL HOSPITAL MARIA AUX)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (18, '20548279039', 'CORGAS S.A.C.', 1, 82, 18, 'LAS TORRES NRO. 497 URB. LOS SAUCES', 0x313530313430, 1, 1, 'GUSTAVO CHAVEZ', '', '937504719', 'gchavez36@gmail.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (19, '10199887608', 'QUISPE YUPANQUI SILVIO', 0, 63, 19, 'MZ D27 LOTE 13 AA.HH BOCANEGRA ZONA 3 AV. QUILCA FRENTE TOTUS DE AV. QUILCA', 0x313530313430, 1, 0, 'Henry Quispe', 'Administrador', '983213615', 'henryqc2016@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (20, '20524016070', 'DELTA COMBUSTIBLES E.I.R.L.', 0, 61, 20, 'AV. ALFREDO MENDIOLA NRO. 700 URB. INGENIERIA (ALT. CRUCE AV. HABICH E INGENIERIA A 2 C)', 0x313530313430, 1, 1, 'DARWIN ATACHAGUA', '', '957292531', 'darwin.atachagua@deltaate.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (21, '20555690534', 'DELTA ATE E.I.R.L', 0, 64, 20, 'AV. NICOLAS AYLLON NRO. 3620 A.H. SANTA ILUMINATA (CARRETERA CENTRAL CRUCE AV ATE)', 0x313530313430, 1, 1, 'DARWIN ATACHAGUA', '', '957292531', 'darwin.atachagua@deltaate.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (22, '20511172633', 'ESTACION DE SERVICIOS GRIFO DENVER S.R.L.', 1, 0, 21, 'Av. Canta Callao Numero 59', 0x313530313430, 0, 0, 'JULIO HUERTA', '', '957992031', '', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (23, '20530743919', 'DIESEL MAX SRL', 0, 50, 22, 'AV. CRUZ BLANCA NRO. 996 (CHIRI GRIFO)', 0x313530313430, 1, 1, 'WALTER GARCIA', '', '983489932', 'dieselmax21@yahoo.es', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (24, '20603821247', 'CORPORACION DISFARMED SAC', 0, 142, 23, 'PJ. OCHO MZA. D LOTE. 11 A.H. ALTO EL ROSAL', 0x313530313430, 1, 0, 'Miguel Cañahuaray', 'Administrador', '992514229', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (25, '20603635711', 'A & B DROFAR INVERSIONES S.A.C.', 0, 146, 24, 'JR. CUSCO NRO. 811 INT. 207 URB. BARRIOS ALTOS (ESPALDA DE RENIEC)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (26, '20538108295', 'DUOGAS S.A.', 0, 0, 25, 'Ca. Las Garzas Nro. 328', 0x313530313430, 0, 0, 'EDWIN ARANGO', '', '952675018', 'erango@duogas.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (27, '20517855252', 'INVERSIONES DUVAL SAC', 1, 58, 26, 'AV. ALFREDO MENDIOLA NRO. 6200 INT. 101 URB. MOLITALIA (CRUCE CON AVENIDA MEXICO)', 0x313530313430, 1, 0, ' Jorge Vargas', ' Administracion', ' 945628525', ' jorgev524@yahoo.com', 1, 2, 0, 5, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (28, '20492314154', 'INVERSIONES EBENEZER S.R.L', 0, 65, 27, 'AV. 4 MZA. B4 LOTE. 03 P.J. NESTOR GAMBETTA BAJA ESTE (COSTADO DEL MERCADO ROJO GAMBETTA)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (29, '20521431955', 'ECO TRADING S.A.C', 1, 36, 28, 'REPUBLICA ARGENTINA NRO. 798 URB. ZONA INDUSTRIAL (ALT. DE C.C. LA CACHINA)', 0x313530313430, 1, 0, ' Jose Borda', ' Administrador', '971150078', ' ecogas.ar@hotmail.com', 1, 2, 1, 5, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (30, '20478967111', 'ESTACION DE SERVICIO LURIN', 0, 119, 29, 'MZ. C. LT 2 URB. LOS HUERTOS DE VILENA', 0x313530313430, 1, 0, 'RAUL SOLIER REYNOSO', '', '998322921', 'eesslurinsac@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (31, '20343883936', 'ESTACION DE SERVICIO NIAGARA S.R.L', 0, 35, 30, 'JR. ELVIRA GARCIA Y GARCIA NRO. 2790 (ALT.COLONIAL Y UNIVERSITARIA)', 0x313530313430, 1, 0, ' Ricardo Yep', ' Gerente', ' 994219434', ' ricardoyep@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (32, '10043210675', 'JAPA BORONDA MARIO RODOLFO', 0, 37, 31, 'CAR.MARGINAL NRO. SN C.P. MENOR HUANCABAMBA', 0x313530313430, 1, 0, 'RULO', '', '941165440', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (33, '20512220836', 'FARMA SAN AGUSTIN S.R.L.', 0, 94, 32, 'CAL.JOSE TORRES PAZ NRO. 110 URB. CIUDAD DE DIOS ZONA A (FRENTE A LA POSTA CIUDAD DE DIOS)', 0x313530313430, 1, 0, 'Augusto Castillo', 'Gerente General', '981578374', 'castilloaugusto18@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (34, '20448556663', 'FARMACIA SAN FRANCISCO S.A.C.', 0, 137, 33, 'JR. HUANCANE 721 - PUNO - SAN ROMAN - JULIACA', 0x313530313430, 1, 0, 'Margarita Sucari', 'Gerente General', '962656246', 'msucari41@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (35, '20600534883', 'CORPORACION GANAJUR S.A.C.', 0, 72, 34, 'JR. ANCASH 14142 BARRIOS ALTOS', 0x313530313430, 1, 0, 'Juan Carlos Ortiz', '', '945363393', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (36, '20601790484', 'INVERSIONES LUMIPHARMA E.I.R.L.', 0, 71, 34, 'JR. ANTONIO MIROQUESADA NRO. 806 INT. 403 (ESQ.JR PARURO909 EDF COM MRQS 4PISO 403A)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (37, '20425788192', 'CENTRO GAS DIEGO EIRL', 0, 60, 35, 'AV. LA MOLINA NRO. 401 URB. VULCANO (LETRERO MGAS - FRENTE GRIFO MOBIL)', 0x313530313430, 1, 0, 'GENOVEVA ESPIRITU', '', '989003565', 'iquitos7@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (38, '20502716761', 'GAS ESCORPIO S.R.L.', 0, 102, 35, 'AV. LA MOLINA NRO. 401 URB. VULCANO (ALT. OVALO SANTA ANITA) LIMA - LIMA - ATE', 0x313530313430, 1, 0, 'GENOVEVA ESPIRITU', '', '989003565', 'iquitos7@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (39, '20492920666', 'GASBEL EQUIPOS & ASESORIA S.A.C.', 1, 93, 36, 'JR. HUARAZ NRO. 1134 URB. BREÑA', 0x313530313430, 1, 0, 'MILAGROS ALMANDROS', '', '996890808', 'malmandros@jevaro.com.pe', 1, 2, 0, 9, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (40, '20520786873', 'GASOCENTRO LIMA SUR S.A.C.', 1, 22, 37, 'AV. JUAN DE ALIAGA NRO. 278 INT. 503 (EX JOSE COSSIO)', 0x313530313430, 1, 0, ' Alex Naters', ' Gerente', ' 987937575', ' naters.alex@gmail.com', 1, 2, 0, 9, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (41, '20546818102', 'GRUPO AVTEC CONTENIDOS SAC', 0, 27, 38, 'AV. DEL PINAR NRO. 110 DPTO. 204 URB. CHACARILLA DEL ESTANQUE', 0x313530313430, 1, 0, 'FERNANDO BAZAN', 'GERENTE OPERACIONES', '989054789', 'fernando.bazan@gasored.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (42, '20600658311', 'ESTACION TRAPICHE S.A.C.', 0, 5, 38, 'AV. DEL PINAR NRO. 110 DPTO. 204 URB. CHACARILLA DEL ESTANQUE', 0x313530313430, 1, 0, 'FERNANDO BAZAN', 'GERENTE OPERACIONES', '989054789', 'fernando.bazan@gasored.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (43, '20100111838', 'GRIFOS ESPINOZA S A', 0, 104, 39, 'AV. JAVIER PRADO ESTE NRO. 6519 URB. PABLO CANEPA (ENTRE CRUCE AV. INGENIEROS, GESA MARKETS)', 0x313530313430, 1, 1, ' Francisco ponte', ' Gerente Com.', ' 998115473', 'fponte@grupogesa.com ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (44, '20138645607', 'GRIFO SANTO DOMINGO DE GUZMAN SRLTDA', 0, 140, 40, 'AV. RAMIRO PRIALE LOTE. 23A ASC. DIGNIDAD NACIONAL (PARCELA L)', 0x313530313430, 1, 1, 'SUSAN', 'ADMINISTRADORA', '976363362', 'adm.santodomingo.srl@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (46, '20514304921', 'GRUPO INTIFARMA S.A.C.', 0, 96, 42, 'AV. INSURGENTES NRO. 711 INT. 4 SAN MIGUEL (MINISTERIO MARINA)', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (47, '20602712363', 'CONSORCIO GAS DEL SUR', 0, 20, 43, 'CAL. PANAMERICANA SUR KM. 33.5 URB. PREDIO LAS SALINAS', 0x313530313430, 1, 0, ' Luis Berrios Tosi', 'Luis Berrios', '', 'contabilidad3@copepdelperu.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (48, '20538807037', 'HEVALFAR SRL', 0, 91, 44, 'MZA. D-1 LOTE 26 2DO. PISO INT. 202 URB. LAS PRADERAS DE STA. ANITA', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (49, '20521111888', 'INVERSIONES Y REPRESENTACIONES EGAR S.A.C.', 0, 89, 45, 'AV. ALFREDO MENDIOLA NRO. 3973 INT. 201 URB. MICAELA BASTIDAS - Lima - Lima - Los Olivos', 0x313530313430, 1, 0, 'ORLANDO QUISPE', 'DUEÑO', '920659824', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (50, '20493089570', 'ESTACION DE SERVICIOS H & A S.A.C.', 0, 48, 46, 'AV. UNIVERSITARIA NRO. S/N (CDRA 51 ESQUINA CON LA CALLE A)', 0x313530313430, 1, 1, 'FELICIANO AZAÑERO', 'GERENTE GENERAL', '963760146', 'estacion.hasac@gmail.com>', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (51, '20556597376', 'ESTACION DE SERVICIOS ANDAHUASI S.A.C.', 0, 45, 46, 'AV. UNIVERSITARIA MZA. A LOTE. 06 (CDRA. 51 FRENTE PARQUE NARANJAL)', 0x313530313430, 1, 0, ' Maribel Bernabé', ' Administración', '963760146 ', 'estacion.hasac@gmail.com ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (52, '20551615856', 'INVERSIONES JIARA S.A.C.', 0, 138, 47, 'AV. ESTEBAN CAMPODONICO NRO. 262 URB. SANTA CATALINA', 0x313530313430, 1, 1, 'CARMEN ARAMAYO', 'GERENTE GENERAL', '959373867', 'alkohler_eirl@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (53, '20371975561', 'REPRESENTACIONES JEMMS S.A.C.', 0, 136, 48, 'AV. ALFREDO MENDIOLA NRO. 1085 URB. PALAO 2DA ETAPA', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (54, '20492197417', 'JE OPERADORES SAC', 0, 86, 48, 'Av. Nestor Gambeta Km. 7.10 Mz.B-6 Lt.4 Coop. Vivienda de Trab. ENAPU  - Callao - Callao - Callao ', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (55, '20512853529', 'ATS AMERICA SAC', 0, 87, 48, 'AV. LIMA SUR NRO. 895 CHOSICA LIMA - LIMA - LURIGANCHO', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (56, '20535614548', 'OPERADORES DE ESTACIONES SAC', 0, 85, 48, 'AV. CIRCUNVALACION NRO. 1386 (ALT MERCADO DE FRUTAS) LIMA - LIMA - LA VICTORIA', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (57, '20551112781', 'GRUVENI SRL', 0, 150, 48, 'JR. LOS ANTROPOLOGOS MZA. D LOTE. 4 COO. LA UNION (MODULO DE PODER JUDICIAL DE PROCERES)', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (58, '20551297978', 'GRANDINO S.A.C.', 0, 135, 48, 'CAL.LOS CEREZOS NRO. 291 URB. DE LA LOTIZ. CHILLON', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (59, '20566149151', 'GASNOR S.A.C.', 0, 76, 48, 'AV. ENCALADA 232 - SANTIAGO DE SURCO', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (60, '20566149401', 'ESTACIONES DEL NORTE SAC', 0, 70, 48, 'CAR.PANAN NORTE KM. 1168 P.J. BARRIO LETICIA', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (61, '20600868862', 'PETRO CALLAO SAC', 0, 88, 48, 'AV. ARGENTINA NRO. 498 URB. CHACARITAS - Callao - Callao - Callao', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (62, '20600908627', '524 CONSULTING S.A.C.', 0, 134, 48, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (63, '20602003427', 'PETRO NAZCA S.A.C.', 0, 101, 48, 'AV. PANAMERICANA NRO. 891 URB. VISTA ALEGRE (GRIFO PECSA)', 0x313530313430, 1, 1, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (64, '20604631379', 'MOVI PETROL S.A.C.', 0, 131, 48, 'LA ENCALADA NRO. 232', 0x313530313430, 1, 0, 'RAFAEL ORTIZ', 'GERENTEFINANZAS', '966799508', 'rortiz@jemms.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (65, '20404000447', 'SERVICENTRO ESPINOZA NORTE S.A', 0, 149, 49, 'CAR.PAN. NORTE NRO. K191 (LA PALMA PTO. SUPE)', 0x313530313430, 1, 0, 'Manuel Menacho', 'Administrador', '992018928', 'manuluismd1999@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (66, '20605427147', 'INVERSIONES KATIMILA E.I.R.L.', 0, 147, 49, 'AV. CENTENARIO KM. 4.100 KM. REF (AV. CENTENARIO KM. 4.100)', 0x313530313430, 1, 0, 'Manuel Menacho', 'Administrador', '992018928', 'manuluismd1999@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (67, '20283756115', 'SERVICENTRO UCAYALI S.A.C', 0, 17, 50, 'AV. CENTENARIO NRO.4100', 0x313530313430, 1, 1, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (68, '20404883918', 'GRIFOS ESPINOZA DE TINGO MARIA S.A.', 0, 24, 50, 'AV ENRIQUE PIMENTEL NRO 116', 0x313530313430, 1, 1, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (69, '20508196475', 'PETROCENTRO YULIA S.A.C.', 0, 49, 50, 'AV. DE LA MARINA NRO. 2789 URB. MARANGA 1RA ET. (CRUCE CON AV.ESCARDO)', 0x313530313430, 1, 0, 'Fiorella Elias', 'Contadora', '970428573', 'f.elias@grespinoza.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (70, '20605129154', 'GRIFOS GES S.A.C.', 0, 133, 51, 'AV. ISABEL LA CATOLICA NRO. S/N URB. MATUTE (ESQUINA CON JR ANDAHUAYLAS)', 0x313530313430, 1, 0, 'Anyeli Macalupu', 'Contadora', '939375251', 'amacalupu@grifosges.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (71, '20605450475', 'SERVICENTRO JAQUELINE DE PUCALLPA S.A.C.', 0, 145, 51, 'AV. CARRETERA FEDERICO BASADRE KM. 9.00 (LATERAL DERECHO)', 0x313530313430, 1, 0, 'Anyeli Macalupu', 'Contadora', '939375251', 'amacalupu@grifosges.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (72, '20203530073', 'SERVICENTRO SAN HILARION S.A.', 0, 69, 52, 'AV. FLORES DE PRIMAVERA NRO. 1988 URB. SAN HILARION (MZ B - LT.03 / CRUCE CON AV.CTO.GRANDE)', 0x313530313430, 1, 0, 'Tomas Zavala', 'Gerente General', '998196242', 'huancayo18@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (73, '20506467854', 'CORPORACION JULCAN S.A.', 0, 13, 52, 'AV. PROCERES DE LA INDEPENDEN NRO. 2556 URB. LOS ANGELES (ALTURA DEL PARADERO 20)', 0x313530313430, 1, 1, 'TOMAS ZAVALA', '', '998196242', 'huancayo18@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (74, '20347869849', 'SERVIC. Y AFINES LAS AMERICAS EIRL', 1, 40, 53, 'AV. DE LAS AMERICAS NRO. 1259 URB. BALCONCILLO (GRIFO PETROPERU)', 0x313530313430, 1, 1, ' Luisa Manco', ' Administracion', ' 987817538', ' servicentroyafineslasamericas@hotmail.com', 1, 2, 1, 8, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (75, '20459020137', 'MARKET LAS BELENES S.A.C', 0, 77, 1, 'JR. EL POLO 493 - URB EL DERBY DE MONTERRICO', 0x313530313430, 1, 0, 'Paty Medina', 'Gerente General', '966833946', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (76, '20101312519', 'LIMABANDA S.A.C.', 0, 59, 55, 'AV. MARISCAL ORBEGOSO NRO. 120 URB. EL PINO', 0x313530313430, 1, 1, 'LUIS NUÑEZ', '', '989005187', 'lnudarco@limabandasac.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (77, '20124367850', 'INVERSIONES TRANSP. Y SERV. CINCO S.A.C.', 0, 23, 55, 'AV. JAVIER PRADO ESTE NRO. 1059 URB. SANTA CATALINA (FRENTE AL COLG.SAN AGUSTIN)', 0x313530313430, 1, 0, 'LUIS NUÑEZ', 'Gerente General', '989005187', 'lnudarco@limabandasac.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (78, '20492727661', 'LIVORNO OIL TRADING S.A.C.', 0, 103, 56, 'JR. ABTAO NRO. 784 (ESQ. HIPOLITO UNANUE)', 0x313530313430, 1, 0, 'MAGNOLIA', '', '946594870', 'administracion.control@merrillperu.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (79, '20137926742', 'SERVICENTRO LOS ROSALES S.A.', 0, 124, 57, 'AV. AYACUCHO NRO 140', 0x313530313430, 1, 0, 'Maricell Guillen', 'Administradora', '998450561', 'mguillen@servicentrolosrosales.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (80, '20110623420', 'INVERSIONES LUMARCO SA', 1, 122, 1, 'CAR.CENTRAL KM. 11.2 A.H. LA ESTRELLA (GRIFO PECSA COSTADO PLAZA REAL STA CLARA)', 0x313530313430, 1, 1, 'Luciano Marching', 'Gerente General', '967778888', '', 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (81, '20517735605', 'LUXOR PHARMACEUTICAL SAC', 0, 90, 59, 'AV. CESAR VALLEJO 895.', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (82, '20524359601', 'MABA FARMA S.A.C.', 0, 11, 60, 'MZA. C LOTE. 4 JAZMIN DE OQUENDO (COSTADO MERCADO LA ALBORADA)', 0x313530313430, 1, 0, 'Miguel Balvin', 'Administrador', '975547119', 'mabafarmasac-2013@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (83, '20161800920', 'LUBRIGAS S.R.LTDA.', 0, 80, 61, 'Av. Nicolas Ayllon Nro. 3562 Fnd. Mayorazgo (Frente a Planta Qui lomica Suiza)', 0x313530313430, 1, 1, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (84, '20516035758', 'GASNORTE S.A.C', 0, 79, 61, 'Av. Gerardo Unger Nro. 3301 - Urbanización: Habilit.Indust.Pan.Norte', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (85, '20524249848', 'CENTROGAS VISTA ALEGRE S.A.C.', 0, 83, 61, 'Av. Nicolas Ayllon Nro. 4706 Fnd. Vista Alegre', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (86, '20547011954', 'CENTROGAS IQUITOS S.A.C.', 0, 67, 61, 'AV. IQUITOS NRO. 983 (CRUCE CON AV CATOLICA)', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (87, '20557618920', 'CENTRAL PARIACHI S.A.C.', 0, 57, 61, 'AV. NICOLAS AYLLON NRO. S/N SEMI RUSTICO PARIACHI PARCELA 10906', 0x313530313430, 1, 0, 'Alejandro Mandujano', '', '980090788', 'amandujano@grupomandujano.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (88, '20517053351', 'MASGAS PERU S.A.C.', 1, 0, 63, 'Av. Tupac Amaru Nro. 3685 - Urb. Carabayllo ', 0x313530313430, 0, 0, 'Victor Naranjo', 'Gerente General', '994271052', 'administracionperu@masgasperu.com', 1, 2, 0, 5, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (89, '20371826727', 'ESTACION DE SERVICIOS GRIFO MASTER SRL', 1, 0, 64, 'Av. Alfredo Mendiola Mza. E Lote. 16 - Asoc. Rio Santa', 0x313530313430, 0, 0, '', '', '', '', 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (90, '20603673485', 'MATIAS & ALEXA E.I.R.L.', 0, 75, 65, 'JR. PARURO NRO. 926 INT. 345B GALERIA CENTRO COMERCIAL CAPON CENTER', 0x313530313430, 1, 0, 'Jerson', 'Administrador', '983465591', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (91, '20304887762', 'MIDAS GAS S.A', 0, 44, 66, 'AV. NICOLAS ARRIOLA NRO. 3191 .', 0x313530313430, 1, 1, 'Walter Meza', 'Administrador', '987518298', 'walter.meza@midasgas.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (92, '10090647879', 'SALCEDO GUEVARA NESTOR', 0, 127, 67, 'CAR. CENTRAL NRO 16.5 URB. HUAYCAN', 0x313530313430, 1, 1, ' Alexandra', ' Administradora', ' 920660239', ' Inandisa01@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (93, '20566091306', 'NG FARMA S.A.C.', 0, 132, 68, 'AV. SANTA ROSA 1044 APV. LOS CHASQUIS', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (94, '20177941591', 'ORGANIZACION FUTURO SAC', 1, 121, 69, 'AV. JAVIER PRADO ESTE 6651', 0x313530313430, 1, 1, 'Gunther Paucar', 'Gerente General', '977810907', 'gpaucar@orfusac.com', 1, 2, 0, 6, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (95, '20517231631', 'PANAMERICAN GAS TRADING S.A.C.', 0, 0, 70, 'Av. Republica De Panama Nro. 4120 ', 0x313530313430, 0, 0, '', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (96, '20452799368', 'ESTACION FINLANDIA E.I.R.L.', 0, 109, 71, 'AV. SIETE MZA. 9 LOTE. 02-A (ESQUINA DE AV. SIETE Y FINLANDIA)', 0x313530313430, 1, 1, 'Miriam Ocaña', 'Administradora', '956406088', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (97, '20494793521', 'ESTACION EL OVALO E.I.R.L.', 1, 114, 71, 'AV. F. LEON DE VIVEIRO', 0x313530313430, 1, 1, 'Nola Cordova', 'Administradora', '924770964', '', 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (98, '20601709148', 'PETRO LUMARA S.A.C.', 1, 106, 72, 'Ca. Montegrande Nro. 109 Int. 301 - Urb. Chacarilla Del Estanque', 0x313530313430, 1, 0, ' Fernando Camacho', ' Administrador', ' 975802575', '  fcamacho@cocsaperusa.com', 1, 4, 0, 4, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (99, '20511193045', 'ESTACION DE SERVICIOS MONTE EVEREST SAC', 0, 125, 73, 'AV. AVIACION NRO. 4285 (ALT.CDRA 42 AV.AVIACION)', 0x313530313430, 1, 1, 'x', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (100, '20514636843', 'ESTACIONES DE SERVICIOS PETRO WORLD SAC', 0, 126, 73, 'AV. VENEZUELA ESQUINA CON AV. RIVA AGUERO', 0x313530313430, 1, 1, 'x', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (101, '20505133430', 'PETROCARGO S.A.C', 1, 68, 74, 'AV. ELMER FAUCCETT NRO. 6000', 0x313530313430, 1, 0, 'WILBER LEON', 'CONTADOR', '994271076', 'wilbertleon@petrocorpsa.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (102, '10009635128', 'TEODORA DOMINGUEZ', 0, 33, 75, 'AV. NICOLAS DE AYLLON N 441 CHACLACAYO - LIMA - LIMA', 0x313530313430, 1, 0, 'Teodora', 'Gerente General', '960919061', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (103, '20127765279', 'COESTI S.A.', 1, 66, 76, 'Av. Circunvalación del Club de Golf Los Incas N° 134 Urb. Club de Golf Los Incas - Lima - Lima - Santiago de Surco', 0x313530313430, 1, 1, 'Claudio Aramburu', NULL, '947640511', 'CAramburuL@primax.com.pe', 1, 2, 2, 1, 1, 1, '2024-12-30 17:05:54', '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (104, '20330033313', 'PERUANA DE ESTACIONES SERVICIOS SAC', 0, 117, 76, 'Av. Circunvalación del Club de Golf Los Incas N° 134 Urb. Club de Golf Los Incas - Lima - Lima - Santiago de Surco', 0x313530313430, 1, 1, 'Claudio Aramburu', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (105, '20603822359', 'DROGUERIA DISTRIBUIDORA PRIMED S.A.C.', 0, 105, 77, 'Av. Gral. Miguel Iglesias Mz.g Lt.30 - AA.HH. Javier Heraud', 0x313530313430, 1, 0, '', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (106, '20603012268', 'INVERSIONES RAMSAN E.I.R.L.', 0, 16, 78, 'Jr. Pedro Garenzon Nº 500, Urb. Miguel Grau', 0x313530313430, 1, 0, 'Flor Roxana Sanchez', '', '', 'consor.norteno17@gmail.com / anconero.201802@gmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (107, '20503840121', 'REPSOL COMERCIAL SAC', 1, 0, 79, 'Av. Victor Andres Belaunde Nro. 147 ', 0x313530313430, 0, 0, 'Juan Carlos Evangelista', 'Jefe de Tecnologia', '996412738', 'JEVANGELISTAR@repsol.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (108, '20325753821', 'RED INTERNACIONAL DE COMBUSTIBLE Y SERVICIO AUTOMOTRIZ S.R.L.', 0, 51, 80, 'AV. NICOLAS ARRIOLA NRO. 1003 URB. LA POLVORA', 0x313530313430, 1, 0, ' Catherine Solano', 'Administracion ', ' 992298539', '  jefeope@ricsa.com.pe', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (109, '20486255171', 'CORPORACION RIO BRANCO S A', 1, 143, 81, 'CAR.PANAMERICANA NORTE KM. 92.5 C.P. CHANCAYLLO (BARRIO SAN JUAN PASANDO EL PUENTE)', 0x313530313430, 1, 1, 'Ralpy Hinostroza', 'Administrador', '964004784', 'riobrancoralpy@hotmail.com', 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (110, '10091479791', 'CARLOS ALFREDO IBAÑEZ MANCHEGO', 0, 118, 82, 'AV. DE LOS HEROES 1187-1189', 0x313530313430, 1, 0, 'Sandra', '', '948639772', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (111, '20493091396', 'GASOCENTRO PUENTE NUEVO S.A.C.', 0, 112, 82, 'MZA. G LOTE. 1 ASOCIACION DE VIVIENDA ANCIETA', 0x313530313430, 1, 0, 'Gina Mispireta', 'Gerente General', '989265054', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (112, '20502825624', 'ESTACION DE SERVICIOS SAN JUANITO S.A.C.', 0, 116, 82, 'AV. HEROES NRO. 1109 (ALT.HOSPITAL MARIA AUXILIADORA)', 0x313530313430, 1, 0, 'Nelly', 'Administradora', '989265061', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (113, '20511053031', 'ESTACION DE SERVICIO GIO SAC', 0, 123, 82, 'AV. PACHACUTEC NRO. 3859 P.J. CESAR VALLEJO', 0x313530313430, 1, 0, 'Jose Ibañez', 'Gerente General', '989119054', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (114, '20402786729', 'INVERSIONES SANTA ROSA E.I.R.L', 0, 12, 83, 'JR. MOQUEGUA NRO. 398 INT. 7 P.J. FLORIDA BAJA', 0x313530313430, 1, 1, 'Felipe Chu', 'Gerente General', '981444073', 'grifosantarosa1998@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (115, '20373831124', 'ESTACION DE SERVICIOS SCHOII S.R.L.', 0, 29, 84, 'AV. MARIANO CORNEJO NRO .1508(POR LA PLAZA DE LA BANDERA)', 0x313530313430, 1, 0, '', '', '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (116, '20518960688', 'PITS GNV SAC', 0, 34, 84, 'AV. NICOLAS DE PIEROLA NRO. 800 (MZ.H1 LT.16, ESQUINA CON AV. VILLA MARIA)', 0x313530313430, 1, 0, 'SUSY LAO', '', '994077048', 'pitsgnvsac@yahoo.es', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (117, '20602772935', 'DISTRIBUCIONES SELMAC S.A.C.', 0, 98, 85, 'JR. PARURO NRO. 926 INT. 212 URB. BARRIOS ALTOS', 0x313530313430, 1, 0, 'Sabi', 'Administrador', '955106629', 'contabilidadselmacsac@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (118, '20210975862', 'OPERACIONES Y SERVICIOS GENERALES S A', 0, 38, 86, 'AV. CAMINOS DEL INCA MZA. N LOTE. 19 URB. SAN JUAN BAUTISTA DE V. (URB.SAN JUAN BAUTISTA DE VILLA)', 0x313530313430, 1, 1, 'Eliana Rafael ', ' Administracion', ' 946433001', 'asistenteosg@operadoresmr.com.pe ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (119, '20334129595', 'GRIFO SERVITOR S.A', 0, 0, 87, 'Av. Alfredo Mendiola - Urb. Industrial La Milla ', 0x313530313430, 0, 0, 'Christian Higa', 'Gerente General', '998228493', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (120, '20517117421', 'SHICHI - FUKU CORPORATION S.A.C.', 0, 56, 88, 'AV. CANADA NRO. 298 URB. SANTA CATALINA', 0x313530313430, 1, 0, ' Jose Nakada', ' Administracion', '', ' estacioncanadagnv@hotmail.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (121, '20517700640', 'SIROCO HOLDINGS S.A.C', 0, 0, 89, 'Av.Elmer Faucett #735 Callao', 0x313530313430, 0, 0, 'RICARDO HIDALGO', '', '994219434', 'ricardo.hidalgo@hesperservices.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (122, '20377674686', 'SERVICENTRO SMILE S.A.', 1, 110, 90, 'CAL.LOS ORFEBREROS NRO. 129 URB. IND EL ARTESANO', 0x313530313430, 1, 1, 'ELVIS MARLON', 'ADMINISTRADOR', '999086928', 'e.napravnick@servicentrosmile.com', 1, 4, 2, 3, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (123, '20534525070', 'COMERCIALIZADORA DE COMBUSTIBLES TRIVEÑO S.A.C.', 0, 113, 91, 'AV. MATIAS MANZANILLA-2DO PIS NRO. 625 INT. 04 (FRENTE AL HOSPITAL DEL SEGURO SOCIAL)', 0x313530313430, 1, 0, 'ROMULO TRIVEÑO', '', '956725267', 'trivenog@comtrisac.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (124, '20298736820', 'INVERSIONES UCHIYAMA SRL', 0, 2, 92, 'AV. LA MAR NRO. 2382', 0x313530313430, 1, 0, 'Lizbeth Castro', 'Contadora', '975363363', 'lizbeth@estacionlamar.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (125, '20514326496', 'CORPORACION UNO S.A.', 0, 107, 93, 'AV. VICTOR ANDRÉS BELAUNDE NRO. 214 INT. 303 (ESQUINA CON CALLE LOS PINOS)', 0x313530313430, 1, 1, 'LEANDRA BENDITA', '', '985054954', 'leandra.bendita@senergyc.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (126, '20565949731', 'CONSORCIO VITAFARMA S.A.C.', 0, 129, 94, 'JR. PARURO NRO. 775 INT. 307 URB. BARRIOS ALTOS', 0x313530313430, 1, 0, '', '', '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (127, '20020020000', 'DEMO GS', 0, 15, 1, 'Condevilla', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (128, '12345678901', 'DEMO', 0, 19, 1, 'AV. LT PLAZA NORTE', 0x313530313430, 1, 0, '', NULL, '', 'demo@demo.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (129, '20101127614', 'MANUEL IGREDA Y JULIO RIOS S.R.L', 0, 47, 62, 'CAL.MONTERREY NRO. 341 INT. 502', 0x313530313430, 1, 0, '', NULL, '', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (130, '20519069262', 'RICARDO CALDERON INGENIEROS SAC', 0, 52, 99, 'AV. AUGUSTO B LEGUIA NRO. 307 COO. POLICIAL (ACONT DE AV. PERU -ANTES DE ZARUMILLA)', 0x313530313430, 1, 0, '', NULL, '9999999', 'ventas@rcingenieros.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (131, '20348303636', 'ESTACION DE SERVICIOS HERCO S.A.C.', 1, 55, 43, 'CAR.PANAMERICANA SUR NUEVA KM. 33.5 MZA. C LOTE. 14 SECTOR LAS SALINAS (GRIFO HERCO) ', 0x313530313430, 1, 1, '', NULL, '', 'herco@ventas.com', 1, 2, 0, 8, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (132, '20601351944', 'NEGOCIACIONES VALERIA & CHRIS S.A.C', 0, 73, 1, 'AV. LIMA SUR NRO. S/N CHOSICA (LT A2-1 A1-9) ', 0x313530313430, 1, 0, '', NULL, '', 'ventas@valeria.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (133, '20392479687', 'INVERSIONES CORONACION S.R.L.', 0, 95, 1, 'MZ E LT 10 SECTOR CENTRAL HUERTOS DE MANCHAY.', 0x313530313430, 1, 0, 'Samuel.', NULL, '942416053 - 6624797', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (134, '20515789961', 'GAMA INVERSIONES GENERALES S.A.C.', 0, 111, 1, 'AV. QUILCA CUADRA 11 S/N MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', 0x313530313430, 1, 0, ' ', NULL, '  ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (135, '20602359981', 'PUNTO GAS S.A.C.', 0, 139, 1, 'AV. MARISCAL OSCAR T. BENAVIDES NRO. 1657 URB. LA TRINIDAD (ALTURA CDRA. 16 EX COLONIAL) ', 0x313530313430, 1, 0, '', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (136, '20604271089', 'ESTACION DE SERVICIOS VICTORIA L & K HNAS. S.A.C.', 0, 151, 31, 'JR. LAS ACACIAS NRO. SN (1 CDRA DEL GRIFO HUANCABAMBA)', 0x313530313430, 1, 0, ' ', NULL, ' ', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (137, '20522168182', 'FARMACIAS INTEGRALES DE LA SOLIDARIDAD S.A.C.', 0, 152, 1, 'AV. ANGAMOS ESTE NRO. 716 (HOSPITAL DE SOLIDARIDAD DE SURQUILLO)', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (138, '20600765044', 'EUCEL S.R.L.', 0, 153, 1, 'PJ. SAN MARTIN MZA. L LOTE. 3 CANTO CHICO', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (139, '20605395482', 'DROGUERIA LMG FARMA PERU S.A.C.', 0, 154, 1, 'UPIS SAN JOSE MZA. G-1 LOTE. 4 INT. 2 ', 0x313530313430, 1, 0, ' ', NULL, ' (01) 4045448 / 961 ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (140, '20514721280', 'GRUPO INTI S.A.C', 0, 155, 1, 'Jr. Dante 893 - 899', 0x313530313430, 1, 0, ' ', NULL, '447-3684', 'grupointisac@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (141, '20338926830', 'GRIFO VALERIA VICTORIA S.A.C.', 0, 156, 1, 'AV. RIVA AGUERO NRO. 411', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (142, '20484056227', 'OLEOCENTRO Y SERVICIOS SAN PEDRO EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', 0, 157, 1, 'MZA. 30 LOTE. 01 A.H. SAN PEDRO', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (143, '20546828671', 'DRUGSTORE SOL FARMA CORP. E.I.R.L.', 0, 158, 1, 'CAL.CORACEROS NRO. 158', 0x313530313430, 1, 0, ' ', NULL, '4270669 / 979462433', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (144, '20605344896', 'DROGUERIA DISTRIBUIDORA E IMPORTADORA VILLALEON E.I.R.L', 0, 159, 8, 'JR. HUANTA NRO. 944 INT. B URB.  BARRIOS ALTOS', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (145, '10214719717', 'HILARIO CHAUCA GILMAR MARCIANO', 0, 160, 1, 'AV. AVIACION S/N ', 0x313530313430, 1, 0, ' ', NULL, ' ', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (146, '20605224505', 'BOTICA NUEVO PERU E.I.R.L.', 0, 161, 1, 'AV. LOS PINOS NRO. 1414 URB. EL PINAR', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (147, '20604193711', 'INVERSIONES P & M FARMA E.I.R.L.', 0, 162, 1, 'JR. MERCURIO NRO. 172 URB. SAN CARLOS II ETP', 0x313530313430, 1, 0, '', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (148, '10702311191', 'ALIAGA PEREZ LEONARDO CARLOS', 0, 163, 1, 'S/N', 0x313530313430, 1, 0, '', NULL, ' 932 475 889', 'boticasdelahorro2019@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (149, '20605809686', 'GRIFOS ESSA PUCALLPA S.A.C.', 0, 164, 1, 'AV. FEDERICO BASADRE NRO. 298', 0x313530313430, 1, 0, ' ', NULL, ' ', '', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (150, '20546740154', 'PRECIO S.A.C.', 0, 165, 1, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO (A 2 CUADRAS DE VIVANDA)', 0x313530313430, 1, 0, 'CHRISTIAN GONZALES', NULL, '5342336', 'atencionalcliente@estacion715.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (151, '20605764712', 'DROGUERIA JJC S.A.C.', 0, 166, 1, 'CAL.LAS ANTILLAS NRO. 150 URB. ISLA VERDE', 0x313530313430, 1, 0, '', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (152, '20603587481', 'CONSORCIO EFE S.A.C.', 0, 167, 1, 'AV. JOSE SANTOS CHOCANO NRO. 128 P.J. VEINTIDOS DE OCTUBRE ', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (153, '20557398628', 'DISTRIBUIDORA V & G FARMA S.R.L.', 0, 168, 1, 'CAL.LOS PETALOS NRO. 189 URB. LA ACHIRANA', 0x313530313430, 1, 0, '', NULL, ' 987047349 / 9867456', 'distribuidoravgfarma@gmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (154, '20553772436', 'GASOCENTRO ICA S.A.', 0, 0, 95, 'Av. Mexico # 295', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (155, '20100079179', 'ESTACION DE SERV BOLIVAR S A', 0, 0, 95, 'SAN ROQUE - SANDIEGO DE SURCO ', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (156, '20605955704', 'PETRO LAS LOMAS S.A.C.', 0, 169, 1, 'CAL.LOS PROCERES NRO. S/N PBLO. LAS LOMAS PIURA - PIURA - LAS LOMAS', 0x313530313430, 1, 0, 'CHRISTIAN GONZALES', NULL, '5342336', 'atencionalcliente@estacion715.com', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (157, '20605720847', 'GRIFOS ESSA DE TINGO MARIA S.A.C', 0, 170, 1, 'CAR.TINGO MARIA A HUANUCO NRO. 2.5 CAS. AFILADOR HUANUCO', 0x313530313430, 1, 0, ' ', NULL, ' ', ' ', 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (158, '20565966589', 'CORPORACION VICTORIA PERUANA S.A.C', 0, 171, 1, 'Calle Los Detectives Mz. F2 Lt. L1 Urb. Honor y Lealtad ', 0x313530313430, 1, 0, '', NULL, '(056) 283040', 'drogueria-america@outlook.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (159, '10463388089', 'CAHUAPAZA APAZA FREDY EDWIN', 0, 172, 1, 'Jr. Bolivar 153', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (160, '10411640855', 'AGUIRRE ZURITA CARLOS JAVIER', 0, 173, 1, 'JR LEONCIO PRADO 1008', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (161, '10458612256', 'CORZO LAYME MARIA LORENA', 0, 174, 1, 'Mz.188 Lt.10 AA.HH. Huascar', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (162, '10060507291', 'CORZO OCANA EFRAIN', 0, 175, 1, '', 0x313530313430, 1, 0, '', NULL, '', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (163, '20549666362', 'CONSORCIO MEDICORP & SALUD S.A.C.', 0, 176, 1, 'AV. LAS FLORES DE PRIMAVERA NRO. 1045 URB. LAS FLORES', 0x313530313430, 1, 0, '', NULL, '950279527', 'medicorp_i_salud@hotmail.com', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (164, '20606082917', 'INVERSIONES FARMACEUTICA DIAZ S.A.C', 0, 178, 1, '	JR. LAS CALEZAS NRO. 131 (ALTURA DE PLAZA VEA RIMAC)', 0x313530313430, 1, 0, '', NULL, '01-7151990', ' ', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (165, '20601136059', 'FARMA SOLUTIONS E.I.R.L.', 0, 179, 1, 'Calle Loma Los Crisantemos 117', 0x313530313430, 1, 0, '', NULL, '960207974', '', 0, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (166, '20335757697', 'WO SOCIEDAD ANONIMA', 0, NULL, 96, 'AV. GUILLERMO PRESCOTT NRO. 202 URB.  RISSO', 0x313530313430, 0, 0, NULL, NULL, '4419938', NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (167, '20402173476', 'CARRION INVERSIONES S.A.', 0, NULL, 1, 'JR. ANTONIO LOBATO NRO. 651', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (168, '20602629750', 'RAINFOREST DC S.A.C.', 0, NULL, 39, 'AV. JAVIER PRADO ESTE NRO. 6519 URB.  PABLO CANEPA  (CRUCE ENTRE INGENIEROS Y JAVIER PRADO OE)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (169, '20451706323', 'ESTACION DE SERVICIOS VAMA SAC', 0, NULL, 82, 'AV. DE LOS HEROES NRO. 1187 URB.  SAN JUANITO  (AL FRENTE HOSP. MARIA AUXILIADORA)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (170, '20118180306', 'ESTACION CORMAR S.A.', 1, NULL, 108, 'AV. NICOLAS AYLLON NRO. 3456 URB.  VILLA SANTA ANITA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 8, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (171, '20487514749', 'GNV DEL NORTE S.A.C', 0, NULL, 97, 'AV. FELIPE SALAVERRY NRO. 930 URB.  PATAZCA  (CERCA A GRIFO)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (172, '20457948060', 'XIN XING S.A.', 0, NULL, 1, 'JR. MIRO QUESADA NRO. 1308 LIMA LIMA LIMA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (173, '20600465237', 'EXPERIENCIA PERUANA SOCIEDAD ANONIMA CERRADA', 0, NULL, 100, 'Jr. Huaraz Nº 1484  Lima Lima Breña', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (174, '20517453618', 'GLMAR SOCIEDAD ANONIMA CERRADA', 0, NULL, 100, 'Av El Triunfo # 210 VMT', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (175, '20517767396', 'ESCOH SOCIEDAD ANONIMA CERRADA - ESCOH SAC', 0, NULL, 100, 'Av. Fernando Leon de Vivero s/n ICA - ICA  ', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (176, '20101285449', 'TRANSPORTES Y SERVICIOS SANTA CRUZ S A', 0, NULL, 101, 'AV. NARANJAL 299 NRO. C INT. 15 URB. NARANJAL-INDUSTRIAL LIMA - LIMA - INDEPENDENCIA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (177, '20515657119', 'ADMINISTRADORA DE SERVICIOS Y ASOCIADOS S.A.C.', 0, NULL, 102, 'AV. JAIME BAUZATE Y MEZA NRO. 1050 LIMA LIMA LA VICTORIA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (178, '20604303029', 'ADMINISTRACION DE GRIFOS L&L ONE S.A.C.', 0, NULL, 104, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (179, '20604302863', 'ADMINISTRACION DE GRIFOS LEP S.A.C.', 0, NULL, 105, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (180, '10704012964', 'POLO GOMEZ BRYAN MARTIN', 0, NULL, 1, 'PRUEBA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (181, '20385649194', 'SERVICENTRO TITI S.A.C.', 0, NULL, 106, 'AV. PABLO PATRON NRO. 120 URB. SAN PABLO LIMA LIMA LA VICTORIA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (182, '20601697531', 'CORPORACION HA SOCIEDAD ANONIMA CERRADA ', 0, NULL, 46, 'AV. ALFREDO MENDIOLA NRO 6810 – SMP ', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (183, '20553368902', 'COMBUSTIBLES LIMPIOS PERUANOS SOCIEDAD ANONIMA CERRADA COLPE S.A.C.', 0, NULL, 109, 'CAL. REAL NRO. 588 CERCADO DE EL TAMBO JUNIN HUANCAYO EL TAMBO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (184, '11111111111', 'RC DEMO', 1, 0, 1, 'RCI', 0x313530313430, 1, 1, 'RC DEMO', '', '55555555', '', 1, 4, 0, 2, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (185, '20549745076', 'GASOCENTRO SANTA ANA S.A.C', 1, NULL, 34, 'AV. LOS PROCERES MZA. D-2 LOTE. 41 URB. SANTA ANA LIMA LIMA LOS OLIVOS', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 9, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (186, '20537901277', 'CORPORACION PYX S.A.C. - CORP PYX S.A.C.', 0, NULL, 110, 'AV. ANGELICA GAMARRA NRO. 1361 INT. 00 LIMA LIMA LOS OLIVOS', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (187, '20547799845', 'CONSORCIO GRIFOS DEL PERU SOCIEDAD ANONIMA CERRADA', 0, NULL, 111, 'AV. EL DERBY NRO. 254 DPTO. 704 LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (188, '20565643496', 'GLOBAL FUEL SOCIEDAD ANONIMA', 1, NULL, 112, 'AV. REPUBLICA DE PANAMA NRO. 3591 INT. 401 URB. LIMATAMBO LIMA LIMA SAN ISIDRO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (189, '2051199502', 'TERPEL PERU S.A.C.', 0, NULL, 113, 'AV. JORGE BASADRE GROHMANN NRO. 347 INT. 1001 (EDIFICIO PATIO CENTRIC)', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (190, '20566238927', 'PERU BUS INTERNACIONAL S.A.', 0, NULL, 114, 'AV. CANTA CALLAO MZA. D LOTE. 11 URB. HUERTOS DEL NARANJAL', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (191, '20492841014', 'GANAGAS S.A.C.', 0, NULL, 115, 'Av.Los Proceres 655 SANTIAGO DE SURCO LIMA', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (192, '20507248676', 'VIJOGAS S.A.C.', 0, NULL, 116, 'AV. SANTA ROSA NRO. 610 URB. LOS SAUCES LIMA LIMA ATE', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (193, '20538289656', 'CONSORCIO MICE - JOCEGAS', 0, NULL, 1, 'AV. MARIA REICHE NRO. S/N URB. PACHACAMAC LIMA LIMA VILLA EL SALVADOR', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (194, '20493143612', 'ESTACION DE SERVICIOS MASUR S.A.C.', 0, NULL, 118, 'AV. REPUBLICA DE PANAMA N° 4361', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (195, '20510954999', 'GASOCENTRO EL SOL S.A.C.', 0, NULL, 118, 'AV.EL SOL EQ.AV.GUARDIA C NRO. S/N LIMA LIMA CHORRILLOS', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (196, '20510957581', 'SERVICENTRO SHALOM SAC', 0, NULL, 119, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (197, '20519251656', 'INVERSIONES GASSURCO S.A.C', 0, NULL, 118, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (198, '20605899715', 'GO ORUE SAC', 0, NULL, 70, 'Av. El Derby # 254 int 704 Lima - Lima - SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (199, '20517710955', 'SERVICIOS MULTIPLES SANTA CECILIA S.A.C.  SERMUSCE S.A.C.', 0, NULL, 118, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (200, '20606092998', 'CORPORACION JUDY S.A.C.', 1, NULL, 121, 'Nro. . Ex Fundo Naranjal Parcela', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 2, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (201, '20593472244', 'ALLIN GROUP - JAVIER PRADO S.A.', 1, NULL, 1, 'JIRON PINOS 308', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 2, 0, 10, 0, 1, NULL, '2024-12-30 11:22:03');
INSERT INTO `tb_empresas` VALUES (202, '20492898717', 'ECOMOVIL SOCIEDAD ANONIMA CERRADA', 1, NULL, 120, 'AV. PROLONGACION PRIMAVERA NRO. 120 INT. A316 LIMA LIMA SANTIAGO DE SURCO', 0x313530313430, 0, 0, NULL, NULL, NULL, NULL, 1, 0, 0, 0, 0, 1, NULL, '2024-12-30 11:22:03');

-- ----------------------------
-- Table structure for tb_grupos
-- ----------------------------
DROP TABLE IF EXISTS `tb_grupos`;
CREATE TABLE `tb_grupos`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `Nombre`(`nombre`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 122 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_grupos
-- ----------------------------
INSERT INTO `tb_grupos` VALUES (1, 'SIN GRUPO', 1, '2024-12-06 16:34:03', '2024-11-20 18:05:26');
INSERT INTO `tb_grupos` VALUES (2, 'BOTICAS', 1, '2024-12-06 16:34:12', '2024-11-20 18:05:26');
INSERT INTO `tb_grupos` VALUES (3, 'AGUKI', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (4, 'AJ GROUP', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (5, 'AJC', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (6, 'ALTA VIDDA', 1, NULL, '2024-11-20 18:05:27');
INSERT INTO `tb_grupos` VALUES (7, 'AMEL', 1, NULL, '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (8, 'AXELL', 1, NULL, '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (9, 'BALIAN', 1, '2025-01-31 15:39:36', '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (10, 'BOTIFARMA', 1, NULL, '2024-11-20 18:05:28');
INSERT INTO `tb_grupos` VALUES (11, 'CABLE PERU', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (12, 'CATAFARMA', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (13, 'CAZTELLANI', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (14, 'CELESTE', 1, NULL, '2024-11-20 18:05:29');
INSERT INTO `tb_grupos` VALUES (15, 'CILAMSAC', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (16, 'CLINICA CORAZON DE JESUS', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (17, 'COFARSA', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (18, 'CORGAS', 1, NULL, '2024-11-20 18:05:30');
INSERT INTO `tb_grupos` VALUES (19, 'CRUZ VERDE', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (20, 'DELTA', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (21, 'DENVER', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (22, 'DIESEL MAX', 1, NULL, '2024-11-20 18:05:31');
INSERT INTO `tb_grupos` VALUES (23, 'DISFARMED', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (24, 'DROFAR', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (25, 'DUOGAS', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (26, 'DUVAL', 1, NULL, '2024-11-20 18:05:32');
INSERT INTO `tb_grupos` VALUES (27, 'EBENEZER', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (28, 'ECO TRADING', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (29, 'EDS LURIN', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (30, 'EDS NIAGARA', 1, NULL, '2024-11-20 18:05:33');
INSERT INTO `tb_grupos` VALUES (31, 'ESTACION VICTORIA', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (32, 'FARMA SAN AGUSTIN', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (33, 'FARMACIA SAN FRANCISCO', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (34, 'GANAJUR', 1, NULL, '2024-11-20 18:05:34');
INSERT INTO `tb_grupos` VALUES (35, 'GAS DIEGO', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (36, 'GASBEL', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (37, 'GASOCENTRO LIMA SUR', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (38, 'GASORED', 1, NULL, '2024-11-20 18:05:35');
INSERT INTO `tb_grupos` VALUES (39, 'GESA', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (40, 'GRIFO SANTO DOMINGO', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (41, 'GRIFO TRAPICHE', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (42, 'GRUPO INTIFARMA', 1, NULL, '2024-11-20 18:05:36');
INSERT INTO `tb_grupos` VALUES (43, 'HERCO', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (44, 'HEVALFAR', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (45, 'HUARAL GAS', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (46, 'HYA', 1, NULL, '2024-11-20 18:05:37');
INSERT INTO `tb_grupos` VALUES (47, 'INVERSIONES JIARA', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (48, 'JEMMS', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (49, 'JU-EDGAR', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (50, 'JU-ELSA', 1, NULL, '2024-11-20 18:05:38');
INSERT INTO `tb_grupos` VALUES (51, 'JU-JACQUELINE', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (52, 'JULCAN', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (53, 'LAS AMERICAS', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (54, 'LAS BELENES', 1, NULL, '2024-11-20 18:05:39');
INSERT INTO `tb_grupos` VALUES (55, 'LIMABANDA', 1, NULL, '2024-11-20 18:05:40');
INSERT INTO `tb_grupos` VALUES (56, 'LIVORNO', 1, NULL, '2024-11-20 18:05:40');
INSERT INTO `tb_grupos` VALUES (57, 'LOS ROSALES', 1, NULL, '2024-11-20 18:05:40');
INSERT INTO `tb_grupos` VALUES (58, 'LUMARCO', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (59, 'LUXOR', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (60, 'MABAFARMA', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (61, 'MANDUJANO', 1, NULL, '2024-11-20 18:05:41');
INSERT INTO `tb_grupos` VALUES (62, 'MANUEL IGREDA', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (63, 'MASGAS', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (64, 'MASTER', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (65, 'MATIAS Y ALEXA', 1, NULL, '2024-11-20 18:05:42');
INSERT INTO `tb_grupos` VALUES (66, 'MIDAS', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (67, 'NESTOR SALCEDO', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (68, 'NG FARMA', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (69, 'ORFUSAC', 1, NULL, '2024-11-20 18:05:43');
INSERT INTO `tb_grupos` VALUES (70, 'ORUE', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (71, 'OVALO', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (72, 'PETRO LUMARA', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (73, 'PETROAMERICA', 1, NULL, '2024-11-20 18:05:44');
INSERT INTO `tb_grupos` VALUES (74, 'PETROCARGO', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (75, 'PIHUICHO', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (76, 'PRIMAX', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (77, 'PRIMED', 1, NULL, '2024-11-20 18:05:45');
INSERT INTO `tb_grupos` VALUES (78, 'RAMSAN', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (79, 'REPSOL', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (80, 'RICSA', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (81, 'RIO BRANCO', 1, NULL, '2024-11-20 18:05:46');
INSERT INTO `tb_grupos` VALUES (82, 'SAN JUANITO', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (83, 'SANTA ROSA', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (84, 'SCHOII', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (85, 'SELMAC', 1, NULL, '2024-11-20 18:05:47');
INSERT INTO `tb_grupos` VALUES (86, 'SERVIGRIFOS', 1, NULL, '2024-11-20 18:05:48');
INSERT INTO `tb_grupos` VALUES (87, 'SERVITOR', 1, NULL, '2024-11-20 18:05:48');
INSERT INTO `tb_grupos` VALUES (88, 'SHICHI FUKU', 1, NULL, '2024-11-20 18:05:48');
INSERT INTO `tb_grupos` VALUES (89, 'SIROCCO', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (90, 'SMILE', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (91, 'TRIVEÑO', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (92, 'UCHIYAMA', 1, NULL, '2024-11-20 18:05:49');
INSERT INTO `tb_grupos` VALUES (93, 'UNOGAS', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (94, 'VITAFARMA', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (95, 'BOLIVAR S.A.', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (96, 'WO SA', 1, NULL, '2024-11-20 18:05:50');
INSERT INTO `tb_grupos` VALUES (97, 'GASCOP', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (98, ' XIN XONG', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (99, 'OFICINA', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (100, 'COPETROL', 1, NULL, '2024-11-20 18:05:51');
INSERT INTO `tb_grupos` VALUES (101, 'SANTA CRUZ', 1, NULL, '2024-11-20 18:05:52');
INSERT INTO `tb_grupos` VALUES (102, 'ASSA', 1, NULL, '2024-11-20 18:05:52');
INSERT INTO `tb_grupos` VALUES (103, 'INTRASERV', 1, NULL, '2024-11-20 18:05:52');
INSERT INTO `tb_grupos` VALUES (104, 'ADMINISTRACION DE GRIFOS LLONE S.A.C', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (105, 'EESS PICORP', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (106, 'TITI', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (107, 'TRAILER GAS SAC', 1, NULL, '2024-11-20 18:05:53');
INSERT INTO `tb_grupos` VALUES (108, 'ESTACION CORMAR S.A.', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (109, 'COLPE S.A.C', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (110, 'CORPORACION PYX', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (111, 'CONSORCIOGRIFOS DEL PERU S.A.C', 1, NULL, '2024-11-20 18:05:54');
INSERT INTO `tb_grupos` VALUES (112, 'AVA SAC', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (113, 'Terpel Gazel', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (114, 'PERU BUS', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (115, 'GANAGAS SAC', 1, NULL, '2024-11-20 18:05:55');
INSERT INTO `tb_grupos` VALUES (116, 'VijoGas', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (117, 'CONSORCIO MICE - JOSEGAS', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (118, 'GO', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (119, 'SHALOM', 1, NULL, '2024-11-20 18:05:56');
INSERT INTO `tb_grupos` VALUES (120, 'ECOMOVIL', 1, NULL, '2024-12-30 11:48:01');
INSERT INTO `tb_grupos` VALUES (121, 'CORPORACION JUDY SAC', 1, NULL, '2024-12-30 11:48:04');

-- ----------------------------
-- Table structure for tb_inc_asignadas
-- ----------------------------
DROP TABLE IF EXISTS `tb_inc_asignadas`;
CREATE TABLE `tb_inc_asignadas`  (
  `id_asignadas` int(11) NOT NULL AUTO_INCREMENT,
  `cod_incidencia` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `creador` int(11) NOT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_asignadas`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  INDEX `creador`(`creador`) USING BTREE,
  INDEX `cod_incidencia`(`cod_incidencia`) USING BTREE,
  CONSTRAINT `tb_inc_asignadas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_inc_asignadas_ibfk_2` FOREIGN KEY (`creador`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_inc_asignadas_ibfk_3` FOREIGN KEY (`cod_incidencia`) REFERENCES `tb_incidencias` (`cod_incidencia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_inc_asignadas
-- ----------------------------
INSERT INTO `tb_inc_asignadas` VALUES (29, 'INC-00000001', 14, 1, '2025-03-25', '11:22:35', NULL, '2025-03-25 11:22:35');
INSERT INTO `tb_inc_asignadas` VALUES (31, 'INC-00000001', 16, 1, '2025-03-25', '11:23:19', NULL, '2025-03-25 11:23:19');
INSERT INTO `tb_inc_asignadas` VALUES (32, 'INC-00000002', 14, 6, '2025-03-25', '19:11:23', NULL, '2025-03-25 19:11:23');
INSERT INTO `tb_inc_asignadas` VALUES (33, 'INC-00000003', 15, 1, '2025-03-25', '19:22:19', NULL, '2025-03-25 19:22:19');
INSERT INTO `tb_inc_asignadas` VALUES (34, 'INC-00000004', 14, 1, '2025-03-25', '19:35:42', NULL, '2025-03-25 19:35:42');
INSERT INTO `tb_inc_asignadas` VALUES (35, 'INC-00000006', 7, 1, '2025-03-26', '11:49:22', NULL, '2025-03-26 11:49:22');
INSERT INTO `tb_inc_asignadas` VALUES (36, 'INC-00000006', 14, 6, '2025-03-26', '11:49:42', NULL, '2025-03-26 11:49:42');
INSERT INTO `tb_inc_asignadas` VALUES (37, 'INC-00000005', 14, 1, '2025-03-26', '12:37:28', NULL, '2025-03-26 12:37:28');
INSERT INTO `tb_inc_asignadas` VALUES (38, 'INC-00000008', 15, 6, '2025-03-26', '14:05:24', NULL, '2025-03-26 14:05:24');
INSERT INTO `tb_inc_asignadas` VALUES (39, 'INC-00000009', 16, 6, '2025-03-26', '14:05:43', NULL, '2025-03-26 14:05:43');
INSERT INTO `tb_inc_asignadas` VALUES (40, 'INC-00000007', 15, 1, '2025-03-26', '14:07:40', NULL, '2025-03-26 14:07:40');
INSERT INTO `tb_inc_asignadas` VALUES (41, 'INC-00000010', 6, 6, '2025-03-26', '14:31:07', NULL, '2025-03-26 14:31:07');
INSERT INTO `tb_inc_asignadas` VALUES (42, 'INC-00000011', 6, 1, '2025-03-26', '14:46:22', NULL, '2025-03-26 14:46:22');
INSERT INTO `tb_inc_asignadas` VALUES (43, 'INC-00000012', 9, 1, '2025-03-26', '14:47:12', NULL, '2025-03-26 14:47:12');
INSERT INTO `tb_inc_asignadas` VALUES (44, 'INC-00000013', 7, 6, '2025-03-26', '15:02:37', NULL, '2025-03-26 15:02:37');
INSERT INTO `tb_inc_asignadas` VALUES (45, 'INC-00000014', 7, 6, '2025-03-26', '15:40:09', NULL, '2025-03-26 15:40:09');
INSERT INTO `tb_inc_asignadas` VALUES (46, 'INC-00000015', 4, 1, '2025-03-31', '15:25:25', NULL, '2025-03-31 15:25:25');
INSERT INTO `tb_inc_asignadas` VALUES (47, 'INC-00000020', 5, 1, '2025-03-31', '15:32:33', NULL, '2025-03-31 15:32:33');
INSERT INTO `tb_inc_asignadas` VALUES (49, 'INC-00000019', 4, 1, '2025-04-04', '16:26:21', NULL, '2025-04-04 16:26:21');
INSERT INTO `tb_inc_asignadas` VALUES (50, 'INC-00000018', 7, 1, '2025-04-04', '16:28:05', NULL, '2025-04-04 16:28:05');
INSERT INTO `tb_inc_asignadas` VALUES (51, 'INC-00000021', 14, 1, '2025-04-08', '12:03:57', NULL, '2025-04-08 12:03:57');
INSERT INTO `tb_inc_asignadas` VALUES (52, 'INC-00000020', 14, 1, '2025-04-08', '12:04:38', NULL, '2025-04-08 12:04:38');
INSERT INTO `tb_inc_asignadas` VALUES (53, 'INC-00000017', 14, 1, '2025-04-08', '12:08:07', NULL, '2025-04-08 12:08:07');
INSERT INTO `tb_inc_asignadas` VALUES (54, 'INC-00000023', 9, 1, '2025-04-08', '21:55:55', NULL, '2025-04-08 21:55:55');
INSERT INTO `tb_inc_asignadas` VALUES (55, 'INC-00000022', 14, 1, '2025-04-08', '22:25:42', NULL, '2025-04-08 22:25:42');
INSERT INTO `tb_inc_asignadas` VALUES (56, 'INC-00000015', 5, 1, '2025-04-10', '02:10:54', NULL, '2025-04-10 02:10:54');
INSERT INTO `tb_inc_asignadas` VALUES (57, 'INC-00000024', 14, 1, '2025-04-10', '08:32:52', NULL, '2025-04-10 08:32:52');
INSERT INTO `tb_inc_asignadas` VALUES (58, 'INC-00000025', 14, 1, '2025-04-10', '09:28:31', NULL, '2025-04-10 09:28:31');
INSERT INTO `tb_inc_asignadas` VALUES (59, 'INC-00000026', 14, 1, '2025-04-14', '20:58:16', NULL, '2025-04-14 20:58:16');

-- ----------------------------
-- Table structure for tb_inc_seguimiento
-- ----------------------------
DROP TABLE IF EXISTS `tb_inc_seguimiento`;
CREATE TABLE `tb_inc_seguimiento`  (
  `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `cod_incidencia` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(0) NULL DEFAULT NULL,
  `estado` tinyint(1) NULL DEFAULT 0 COMMENT '0: Iniciado / 1: Finalizado',
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_seguimiento`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  INDEX `cod_incidencia`(`cod_incidencia`) USING BTREE,
  INDEX `cod_incidencia_2`(`cod_incidencia`) USING BTREE,
  CONSTRAINT `tb_inc_seguimiento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_inc_seguimiento_ibfk_2` FOREIGN KEY (`cod_incidencia`) REFERENCES `tb_incidencias` (`cod_incidencia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 82 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_inc_seguimiento
-- ----------------------------
INSERT INTO `tb_inc_seguimiento` VALUES (38, 1, 'INC-00000001', '2025-03-25', '11:23:28', 0, NULL, '2025-03-25 11:23:28');
INSERT INTO `tb_inc_seguimiento` VALUES (39, 1, 'INC-00000001', '2025-03-25', '11:23:42', 1, NULL, '2025-03-25 11:23:43');
INSERT INTO `tb_inc_seguimiento` VALUES (40, 6, 'INC-00000002', '2025-03-25', '19:21:25', 0, NULL, '2025-03-25 19:21:25');
INSERT INTO `tb_inc_seguimiento` VALUES (41, 6, 'INC-00000003', '2025-03-25', '19:22:47', 0, NULL, '2025-03-25 19:22:47');
INSERT INTO `tb_inc_seguimiento` VALUES (42, 1, 'INC-00000004', '2025-03-25', '19:36:09', 0, NULL, '2025-03-25 19:36:09');
INSERT INTO `tb_inc_seguimiento` VALUES (43, 1, 'INC-00000002', '2025-03-26', '11:20:57', 1, NULL, '2025-03-26 11:21:00');
INSERT INTO `tb_inc_seguimiento` VALUES (44, 6, 'INC-00000003', '2025-03-26', '12:28:51', 1, NULL, '2025-03-26 12:28:54');
INSERT INTO `tb_inc_seguimiento` VALUES (45, 1, 'INC-00000005', '2025-03-26', '12:48:27', 0, NULL, '2025-03-26 12:48:27');
INSERT INTO `tb_inc_seguimiento` VALUES (46, 1, 'INC-00000004', '2025-03-26', '12:49:30', 1, NULL, '2025-03-26 12:49:33');
INSERT INTO `tb_inc_seguimiento` VALUES (47, 6, 'INC-00000005', '2025-03-26', '12:50:59', 1, NULL, '2025-03-26 12:51:02');
INSERT INTO `tb_inc_seguimiento` VALUES (48, 1, 'INC-00000006', '2025-03-26', '14:06:32', 0, NULL, '2025-03-26 14:06:32');
INSERT INTO `tb_inc_seguimiento` VALUES (49, 1, 'INC-00000007', '2025-03-26', '14:07:50', 0, NULL, '2025-03-26 14:07:50');
INSERT INTO `tb_inc_seguimiento` VALUES (50, 1, 'INC-00000008', '2025-03-26', '14:07:58', 0, NULL, '2025-03-26 14:07:58');
INSERT INTO `tb_inc_seguimiento` VALUES (51, 1, 'INC-00000009', '2025-03-26', '14:08:05', 0, NULL, '2025-03-26 14:08:05');
INSERT INTO `tb_inc_seguimiento` VALUES (52, 1, 'INC-00000006', '2025-03-26', '14:10:11', 1, NULL, '2025-03-26 14:10:14');
INSERT INTO `tb_inc_seguimiento` VALUES (53, 6, 'INC-00000007', '2025-03-26', '14:12:28', 1, NULL, '2025-03-26 14:12:31');
INSERT INTO `tb_inc_seguimiento` VALUES (54, 1, 'INC-00000008', '2025-03-26', '14:19:53', 1, NULL, '2025-03-26 14:19:56');
INSERT INTO `tb_inc_seguimiento` VALUES (55, 6, 'INC-00000010', '2025-03-26', '14:32:47', 0, NULL, '2025-03-26 14:32:47');
INSERT INTO `tb_inc_seguimiento` VALUES (56, 1, 'INC-00000009', '2025-03-26', '14:33:45', 1, NULL, '2025-03-26 14:33:47');
INSERT INTO `tb_inc_seguimiento` VALUES (57, 6, 'INC-00000010', '2025-03-26', '14:34:14', 1, NULL, '2025-03-26 14:34:17');
INSERT INTO `tb_inc_seguimiento` VALUES (58, 1, 'INC-00000012', '2025-03-26', '14:47:58', 0, NULL, '2025-03-26 14:47:58');
INSERT INTO `tb_inc_seguimiento` VALUES (59, 1, 'INC-00000011', '2025-03-26', '14:48:05', 0, NULL, '2025-03-26 14:48:05');
INSERT INTO `tb_inc_seguimiento` VALUES (60, 1, 'INC-00000011', '2025-03-26', '14:59:39', 1, NULL, '2025-03-26 14:59:42');
INSERT INTO `tb_inc_seguimiento` VALUES (61, 6, 'INC-00000012', '2025-03-26', '15:00:16', 1, NULL, '2025-03-26 15:00:19');
INSERT INTO `tb_inc_seguimiento` VALUES (62, 6, 'INC-00000013', '2025-03-26', '15:02:45', 0, NULL, '2025-03-26 15:02:45');
INSERT INTO `tb_inc_seguimiento` VALUES (63, 6, 'INC-00000013', '2025-03-26', '15:07:00', 1, NULL, '2025-03-26 15:07:02');
INSERT INTO `tb_inc_seguimiento` VALUES (64, 6, 'INC-00000014', '2025-03-26', '15:40:17', 0, NULL, '2025-03-26 15:40:17');
INSERT INTO `tb_inc_seguimiento` VALUES (65, 1, 'INC-00000019', '2025-04-04', '16:28:22', 0, NULL, '2025-04-04 16:28:22');
INSERT INTO `tb_inc_seguimiento` VALUES (66, 1, 'INC-00000018', '2025-04-04', '16:28:35', 0, NULL, '2025-04-04 16:28:35');
INSERT INTO `tb_inc_seguimiento` VALUES (67, 1, 'INC-00000018', '2025-04-04', '16:28:54', 1, NULL, '2025-04-04 16:28:57');
INSERT INTO `tb_inc_seguimiento` VALUES (68, 1, 'INC-00000020', '2025-04-04', '16:29:28', 0, NULL, '2025-04-04 16:29:28');
INSERT INTO `tb_inc_seguimiento` VALUES (69, 1, 'INC-00000019', '2025-04-04', '16:32:11', 1, NULL, '2025-04-04 16:32:14');
INSERT INTO `tb_inc_seguimiento` VALUES (70, 14, 'INC-00000021', '2025-04-08', '12:21:04', 0, NULL, '2025-04-08 12:21:04');
INSERT INTO `tb_inc_seguimiento` VALUES (71, 14, 'INC-00000021', '2025-04-08', '16:01:55', 1, NULL, '2025-04-08 16:01:56');
INSERT INTO `tb_inc_seguimiento` VALUES (72, 14, 'INC-00000020', '2025-04-08', '16:13:24', 1, NULL, '2025-04-08 16:13:24');
INSERT INTO `tb_inc_seguimiento` VALUES (73, 14, 'INC-00000017', '2025-04-08', '19:37:51', 0, NULL, '2025-04-08 19:37:51');
INSERT INTO `tb_inc_seguimiento` VALUES (74, 14, 'INC-00000017', '2025-04-08', '19:38:10', 1, NULL, '2025-04-08 19:38:11');
INSERT INTO `tb_inc_seguimiento` VALUES (75, 1, 'INC-00000023', '2025-04-08', '21:56:01', 0, NULL, '2025-04-08 21:56:01');
INSERT INTO `tb_inc_seguimiento` VALUES (76, 1, 'INC-00000023', '2025-04-08', '21:56:19', 1, NULL, '2025-04-08 21:56:20');
INSERT INTO `tb_inc_seguimiento` VALUES (77, 1, 'INC-00000022', '2025-04-08', '22:25:56', 0, NULL, '2025-04-08 22:25:56');
INSERT INTO `tb_inc_seguimiento` VALUES (78, 1, 'INC-00000022', '2025-04-08', '22:26:12', 1, NULL, '2025-04-08 22:26:12');
INSERT INTO `tb_inc_seguimiento` VALUES (79, 14, 'INC-00000025', '2025-04-11', '13:58:25', 0, NULL, '2025-04-11 13:58:25');
INSERT INTO `tb_inc_seguimiento` VALUES (80, 14, 'INC-00000025', '2025-04-11', '14:06:12', 1, NULL, '2025-04-11 14:06:13');
INSERT INTO `tb_inc_seguimiento` VALUES (81, 1, 'INC-00000014', '2025-04-11', '14:14:56', 1, NULL, '2025-04-11 14:14:56');

-- ----------------------------
-- Table structure for tb_inc_tipo
-- ----------------------------
DROP TABLE IF EXISTS `tb_inc_tipo`;
CREATE TABLE `tb_inc_tipo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_incidencia` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_tipo_inc` int(11) NOT NULL,
  `creador` int(11) NOT NULL,
  `fecha` date NULL DEFAULT NULL,
  `hora` time(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cod_incidencia`(`cod_incidencia`) USING BTREE,
  CONSTRAINT `tb_inc_tipo_ibfk_1` FOREIGN KEY (`cod_incidencia`) REFERENCES `tb_incidencias` (`cod_incidencia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_inc_tipo
-- ----------------------------
INSERT INTO `tb_inc_tipo` VALUES (1, 'INC-00000026', 1, 1, '2025-04-16', '01:09:28', NULL, '2025-04-16 01:09:32');
INSERT INTO `tb_inc_tipo` VALUES (2, 'INC-00000026', 2, 1, '2025-04-16', '01:10:01', NULL, '2025-04-16 01:10:06');
INSERT INTO `tb_inc_tipo` VALUES (3, 'INC-00000001', 1, 1, '2025-04-16', '11:43:47', NULL, '2025-04-16 11:43:47');
INSERT INTO `tb_inc_tipo` VALUES (4, 'INC-00000002', 1, 1, '2025-04-16', '11:43:49', NULL, '2025-04-16 11:43:49');
INSERT INTO `tb_inc_tipo` VALUES (5, 'INC-00000003', 1, 1, '2025-04-16', '11:43:51', NULL, '2025-04-16 11:43:51');
INSERT INTO `tb_inc_tipo` VALUES (6, 'INC-00000004', 1, 1, '2025-04-16', '11:43:53', NULL, '2025-04-16 11:43:53');
INSERT INTO `tb_inc_tipo` VALUES (7, 'INC-00000005', 1, 1, '2025-04-16', '11:43:55', NULL, '2025-04-16 11:43:55');
INSERT INTO `tb_inc_tipo` VALUES (8, 'INC-00000006', 1, 1, '2025-04-16', '11:43:57', NULL, '2025-04-16 11:43:57');
INSERT INTO `tb_inc_tipo` VALUES (9, 'INC-00000007', 1, 1, '2025-04-16', '11:44:01', NULL, '2025-04-16 11:44:01');
INSERT INTO `tb_inc_tipo` VALUES (10, 'INC-00000008', 1, 1, '2025-04-16', '11:44:03', NULL, '2025-04-16 11:44:03');
INSERT INTO `tb_inc_tipo` VALUES (11, 'INC-00000009', 1, 1, '2025-04-16', '11:44:05', NULL, '2025-04-16 11:44:05');
INSERT INTO `tb_inc_tipo` VALUES (12, 'INC-00000010', 1, 1, '2025-04-16', '11:44:07', NULL, '2025-04-16 11:44:07');
INSERT INTO `tb_inc_tipo` VALUES (13, 'INC-00000011', 1, 1, '2025-04-16', '11:44:09', NULL, '2025-04-16 11:44:09');
INSERT INTO `tb_inc_tipo` VALUES (14, 'INC-00000012', 1, 1, '2025-04-16', '11:44:11', NULL, '2025-04-16 11:44:11');
INSERT INTO `tb_inc_tipo` VALUES (15, 'INC-00000013', 1, 1, '2025-04-16', '11:44:12', NULL, '2025-04-16 11:44:12');
INSERT INTO `tb_inc_tipo` VALUES (16, 'INC-00000014', 1, 1, '2025-04-16', '11:44:14', NULL, '2025-04-16 11:44:14');
INSERT INTO `tb_inc_tipo` VALUES (17, 'INC-00000015', 1, 1, '2025-04-16', '11:44:16', NULL, '2025-04-16 11:44:16');
INSERT INTO `tb_inc_tipo` VALUES (18, 'INC-00000016', 1, 1, '2025-04-16', '11:44:18', NULL, '2025-04-16 11:44:18');
INSERT INTO `tb_inc_tipo` VALUES (19, 'INC-00000017', 1, 1, '2025-04-16', '11:44:20', NULL, '2025-04-16 11:44:20');
INSERT INTO `tb_inc_tipo` VALUES (20, 'INC-00000018', 1, 1, '2025-04-16', '11:44:22', NULL, '2025-04-16 11:44:22');
INSERT INTO `tb_inc_tipo` VALUES (21, 'INC-00000019', 1, 1, '2025-04-16', '11:44:24', NULL, '2025-04-16 11:44:24');
INSERT INTO `tb_inc_tipo` VALUES (22, 'INC-00000020', 1, 1, '2025-04-16', '11:44:26', NULL, '2025-04-16 11:44:26');
INSERT INTO `tb_inc_tipo` VALUES (23, 'INC-00000021', 1, 1, '2025-04-16', '11:44:27', NULL, '2025-04-16 11:44:27');
INSERT INTO `tb_inc_tipo` VALUES (24, 'INC-00000022', 1, 1, '2025-04-16', '11:44:30', NULL, '2025-04-16 11:44:30');
INSERT INTO `tb_inc_tipo` VALUES (25, 'INC-00000023', 1, 1, '2025-04-16', '11:44:32', NULL, '2025-04-16 11:44:32');
INSERT INTO `tb_inc_tipo` VALUES (26, 'INC-00000024', 1, 1, '2025-04-16', '11:44:34', NULL, '2025-04-16 11:44:34');
INSERT INTO `tb_inc_tipo` VALUES (27, 'INC-00000025', 1, 1, '2025-04-16', '11:44:35', NULL, '2025-04-16 11:44:35');
INSERT INTO `tb_inc_tipo` VALUES (28, 'INC-00000015', 2, 1, '2025-04-16', '16:40:12', NULL, '2025-04-16 16:40:12');
INSERT INTO `tb_inc_tipo` VALUES (29, 'INC-00000016', 3, 1, '2025-04-16', '16:57:13', NULL, '2025-04-16 16:57:13');

-- ----------------------------
-- Table structure for tb_incidencias
-- ----------------------------
DROP TABLE IF EXISTS `tb_incidencias`;
CREATE TABLE `tb_incidencias`  (
  `id_incidencia` int(11) NOT NULL AUTO_INCREMENT,
  `cod_incidencia` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ruc_empresa` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_sucursal` int(11) NOT NULL,
  `id_tipo_estacion` int(11) NOT NULL,
  `id_tipo_soporte` int(11) NOT NULL,
  `id_problema` int(11) NOT NULL,
  `id_subproblema` int(11) NOT NULL,
  `id_contacto` int(11) NULL DEFAULT NULL,
  `observacion` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `fecha_informe` date NOT NULL,
  `hora_informe` time(0) NOT NULL,
  `estado_informe` int(11) NULL DEFAULT 0,
  `id_usuario` int(11) NOT NULL,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_incidencia`) USING BTREE,
  UNIQUE INDEX `cod_incidencia`(`cod_incidencia`) USING BTREE COMMENT 'El codigo debe ser unico',
  INDEX `FK_Tipo_Estacion`(`id_tipo_estacion`) USING BTREE,
  INDEX `FK_Tipo_Soporte`(`id_tipo_soporte`) USING BTREE,
  INDEX `id_contacto`(`id_contacto`) USING BTREE,
  INDEX `FK_Sucursal`(`id_sucursal`) USING BTREE,
  INDEX `ruc_empresa`(`ruc_empresa`) USING BTREE,
  CONSTRAINT `FK_Sucursal` FOREIGN KEY (`id_sucursal`) REFERENCES `tb_sucursales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_incidencias_ibfk_1` FOREIGN KEY (`id_contacto`) REFERENCES `contactos_empresas` (`id_contact`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_incidencias_ibfk_5` FOREIGN KEY (`ruc_empresa`) REFERENCES `tb_empresas` (`ruc`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 60 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_incidencias
-- ----------------------------
INSERT INTO `tb_incidencias` VALUES (28, 'INC-00000001', '20345774042', 132, 1, 1, 1, 1, NULL, 'aud', '2025-03-25', '11:14:41', 3, 1, 1, NULL, '2025-03-25 11:19:36');
INSERT INTO `tb_incidencias` VALUES (29, 'INC-00000002', '20345774042', 132, 1, 1, 1, 1, NULL, 'urgente', '2025-03-25', '17:18:45', 3, 6, 1, NULL, '2025-03-25 17:26:43');
INSERT INTO `tb_incidencias` VALUES (36, 'INC-00000003', '20345774042', 132, 1, 1, 1, 1, NULL, 'dsafrsdf', '2025-03-25', '18:26:53', 3, 1, 1, NULL, '2025-03-25 18:37:36');
INSERT INTO `tb_incidencias` VALUES (37, 'INC-00000004', '20603850913', 209, 1, 1, 1, 1, NULL, 'ewrt', '2025-03-25', '18:44:54', 3, 1, 1, NULL, '2025-03-25 18:45:11');
INSERT INTO `tb_incidencias` VALUES (38, 'INC-00000005', '20345774042', 132, 1, 1, 1, 1, NULL, '234345', '2025-03-25', '18:44:46', 3, 6, 1, NULL, '2025-03-25 18:45:33');
INSERT INTO `tb_incidencias` VALUES (39, 'INC-00000006', '20517103633', 163, 1, 1, 1, 1, NULL, 'terrter', '2025-03-26', '11:48:51', 3, 1, 1, NULL, '2025-03-26 11:49:22');
INSERT INTO `tb_incidencias` VALUES (40, 'INC-00000007', '20517103633', 163, 1, 1, 1, 1, NULL, 'wertwr', '2025-03-26', '11:48:57', 3, 6, 1, NULL, '2025-03-26 11:49:42');
INSERT INTO `tb_incidencias` VALUES (41, 'INC-00000008', '20603906790', 210, 1, 1, 1, 1, NULL, 'gfjhk', '2025-03-26', '14:03:33', 3, 1, 1, NULL, '2025-03-26 14:03:48');
INSERT INTO `tb_incidencias` VALUES (42, 'INC-00000009', '20345774042', 132, 1, 1, 1, 1, NULL, '123456', '2025-03-26', '14:03:59', 3, 6, 1, NULL, '2025-03-26 14:04:16');
INSERT INTO `tb_incidencias` VALUES (43, 'INC-00000010', '20345774042', 132, 1, 1, 1, 1, NULL, '4yutyfug', '2025-03-26', '14:30:50', 3, 6, 1, NULL, '2025-03-26 14:31:07');
INSERT INTO `tb_incidencias` VALUES (44, 'INC-00000011', '20345774042', 132, 1, 1, 1, 1, NULL, 'hkj,n', '2025-03-26', '14:46:07', 3, 1, 1, NULL, '2025-03-26 14:46:22');
INSERT INTO `tb_incidencias` VALUES (45, 'INC-00000012', '20345774042', 132, 1, 2, 1, 1, NULL, '2134', '2025-03-26', '14:46:29', 3, 1, 1, NULL, '2025-03-26 14:47:12');
INSERT INTO `tb_incidencias` VALUES (46, 'INC-00000013', '20345774042', 132, 1, 2, 1, 1, NULL, 'dasdgf', '2025-03-26', '15:02:19', 3, 6, 1, NULL, '2025-03-26 15:02:37');
INSERT INTO `tb_incidencias` VALUES (47, 'INC-00000014', '20603850913', 209, 1, 2, 1, 1, NULL, '4356iyukj', '2025-03-26', '15:39:51', 3, 6, 1, NULL, '2025-03-26 15:40:09');
INSERT INTO `tb_incidencias` VALUES (48, 'INC-00000015', '20345774042', 132, 1, 1, 1, 2, NULL, 'ewrtery', '2025-04-16', '16:40:08', 1, 1, 1, '2025-04-16 16:40:12', '2025-03-31 15:25:25');
INSERT INTO `tb_incidencias` VALUES (49, 'INC-00000016', '20517103633', 163, 1, 2, 10, 199, NULL, NULL, '2025-04-16', '16:57:10', 0, 1, 1, '2025-04-16 16:57:13', '2025-03-31 15:25:43');
INSERT INTO `tb_incidencias` VALUES (50, 'INC-00000017', '20127765279', 18, 1, 2, 1, 1, NULL, NULL, '2025-03-31', '15:29:57', 3, 1, 1, NULL, '2025-03-31 15:30:12');
INSERT INTO `tb_incidencias` VALUES (51, 'INC-00000018', '20127765279', 18, 1, 2, 1, 1, NULL, NULL, '2025-03-31', '15:30:15', 4, 1, 1, NULL, '2025-03-31 15:30:30');
INSERT INTO `tb_incidencias` VALUES (52, 'INC-00000019', '20127765279', 19, 1, 3, 1, 1, NULL, NULL, '2025-03-31', '15:31:42', 3, 1, 1, NULL, '2025-03-31 15:31:55');
INSERT INTO `tb_incidencias` VALUES (53, 'INC-00000020', '20127765279', 21, 1, 2, 1, 1, NULL, NULL, '2025-03-31', '15:31:58', 3, 1, 1, NULL, '2025-03-31 15:32:33');
INSERT INTO `tb_incidencias` VALUES (54, 'INC-00000021', '20127765279', 22, 1, 1, 1, 1, NULL, NULL, '2025-03-31', '15:35:22', 3, 1, 1, NULL, '2025-03-31 15:35:43');
INSERT INTO `tb_incidencias` VALUES (55, 'INC-00000022', '20127765279', 17, 1, 2, 1, 1, NULL, 'ewaesdrhj', '2025-04-04', '15:49:37', 3, 1, 1, NULL, '2025-04-04 15:49:55');
INSERT INTO `tb_incidencias` VALUES (56, 'INC-00000023', '20345774042', 132, 1, 2, 1, 1, NULL, '43w5rtuhj', '2025-04-08', '21:55:34', 3, 1, 1, NULL, '2025-04-08 21:55:55');
INSERT INTO `tb_incidencias` VALUES (57, 'INC-00000024', '20517103633', 163, 1, 1, 1, 1, NULL, 'rtytuy', '2025-04-10', '08:27:54', 1, 1, 1, NULL, '2025-04-10 08:32:52');
INSERT INTO `tb_incidencias` VALUES (58, 'INC-00000025', '20127765279', 18, 1, 1, 1, 1, 10, 'dsfsdfgsdfg', '2025-04-10', '09:19:51', 3, 1, 1, NULL, '2025-04-10 09:28:31');
INSERT INTO `tb_incidencias` VALUES (59, 'INC-00000026', '20127765279', 18, 1, 1, 1, 60, NULL, 'fgzhghjf', '2025-04-14', '20:57:52', 1, 1, 1, NULL, '2025-04-14 20:58:16');

-- ----------------------------
-- Table structure for tb_materiales
-- ----------------------------
DROP TABLE IF EXISTS `tb_materiales`;
CREATE TABLE `tb_materiales`  (
  `id_materiales` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `producto` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `cantidad` int(11) NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_materiales`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_materiales
-- ----------------------------
INSERT INTO `tb_materiales` VALUES (1, NULL, 'Jack Tool', NULL, 1, NULL, '2024-08-12 14:47:29');
INSERT INTO `tb_materiales` VALUES (2, NULL, 'Protector de Manguera Data (05 Mtrs)', NULL, 1, NULL, '2024-08-12 14:47:33');
INSERT INTO `tb_materiales` VALUES (3, NULL, 'Cable Telefonico (07 Mtrs)', NULL, 1, NULL, '2024-08-12 14:47:38');
INSERT INTO `tb_materiales` VALUES (4, NULL, 'RJ 45', NULL, 1, NULL, '2024-08-12 14:47:43');
INSERT INTO `tb_materiales` VALUES (5, NULL, 'RJ 12', NULL, 1, NULL, '2024-08-12 14:47:51');
INSERT INTO `tb_materiales` VALUES (6, NULL, 'RJ 11', NULL, 1, NULL, '2024-08-12 14:47:53');
INSERT INTO `tb_materiales` VALUES (7, NULL, 'RJ 9', NULL, 1, NULL, '2024-08-12 14:47:56');
INSERT INTO `tb_materiales` VALUES (8, NULL, 'Patch cord de Red (1M)', NULL, 1, NULL, '2024-08-12 14:48:00');
INSERT INTO `tb_materiales` VALUES (9, NULL, 'Patch cord de Red (2M)', NULL, 1, NULL, '2024-08-12 14:48:04');
INSERT INTO `tb_materiales` VALUES (10, NULL, 'Patch cord de Red (3M)', NULL, 1, NULL, '2024-08-12 14:48:06');
INSERT INTO `tb_materiales` VALUES (11, NULL, 'Fuente de 12v.', NULL, 1, NULL, '2024-08-12 14:48:09');
INSERT INTO `tb_materiales` VALUES (12, NULL, 'Fuente de 5v.', NULL, 1, NULL, '2024-08-12 14:48:11');
INSERT INTO `tb_materiales` VALUES (13, NULL, 'Paq. de Precintos', NULL, 1, NULL, '2024-08-12 14:48:14');
INSERT INTO `tb_materiales` VALUES (14, NULL, 'Cinta aislante', NULL, 1, NULL, '2024-08-12 14:48:18');
INSERT INTO `tb_materiales` VALUES (15, NULL, 'USB Serial', NULL, 1, NULL, '2024-08-12 14:48:20');
INSERT INTO `tb_materiales` VALUES (16, NULL, 'Precinto', NULL, 1, NULL, '2024-08-12 14:48:23');
INSERT INTO `tb_materiales` VALUES (17, NULL, 'Cinta aislante', NULL, 1, NULL, '2024-08-12 14:48:26');
INSERT INTO `tb_materiales` VALUES (18, NULL, 'Cable de red Cat5e mts', NULL, 1, NULL, '2024-08-12 14:48:28');
INSERT INTO `tb_materiales` VALUES (19, NULL, 'Cable vulcanizado Nro14 x  mts', NULL, 1, NULL, '2024-08-12 14:48:31');
INSERT INTO `tb_materiales` VALUES (20, NULL, 'Fuente impresora Tysso(24v)', NULL, 1, NULL, '2024-08-12 14:48:34');
INSERT INTO `tb_materiales` VALUES (21, NULL, 'Toma electrica externa', NULL, 1, NULL, '2024-08-12 14:48:36');
INSERT INTO `tb_materiales` VALUES (22, NULL, 'Cable vulcanizado nro 16', NULL, 1, NULL, '2024-08-12 14:48:39');
INSERT INTO `tb_materiales` VALUES (23, NULL, 'Bornera', NULL, 1, NULL, '2024-08-12 14:48:41');
INSERT INTO `tb_materiales` VALUES (24, NULL, 'Compuesto Sellante', NULL, 1, NULL, '2024-08-12 14:48:44');

-- ----------------------------
-- Table structure for tb_materiales_usados
-- ----------------------------
DROP TABLE IF EXISTS `tb_materiales_usados`;
CREATE TABLE `tb_materiales_usados`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_ordens` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_material` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cod_ordens`(`cod_ordens`) USING BTREE,
  INDEX `id_material`(`id_material`) USING BTREE,
  CONSTRAINT `tb_materiales_usados_ibfk_2` FOREIGN KEY (`id_material`) REFERENCES `tb_materiales` (`id_materiales`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_materiales_usados_ibfk_3` FOREIGN KEY (`cod_ordens`) REFERENCES `tb_orden_servicio` (`cod_ordens`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_materiales_usados
-- ----------------------------
INSERT INTO `tb_materiales_usados` VALUES (19, 'ST25-00000001', 2, 1, NULL, '2025-03-25 11:23:43');
INSERT INTO `tb_materiales_usados` VALUES (20, 'ST25-00000002', 3, 1, NULL, '2025-03-26 11:20:59');
INSERT INTO `tb_materiales_usados` VALUES (21, 'ST25-00000003', 3, 1, NULL, '2025-03-26 12:28:54');
INSERT INTO `tb_materiales_usados` VALUES (22, 'ST25-00000004', 4, 1, NULL, '2025-03-26 12:49:33');
INSERT INTO `tb_materiales_usados` VALUES (23, 'ST25-00000005', 2, 1, NULL, '2025-03-26 12:51:02');
INSERT INTO `tb_materiales_usados` VALUES (24, 'ST25-00000006', 3, 1, NULL, '2025-03-26 14:10:14');
INSERT INTO `tb_materiales_usados` VALUES (25, 'ST25-00000007', 2, 1, NULL, '2025-03-26 14:12:31');
INSERT INTO `tb_materiales_usados` VALUES (26, 'ST25-00000008', 2, 1, NULL, '2025-03-26 14:19:56');
INSERT INTO `tb_materiales_usados` VALUES (27, 'ST25-00000009', 5, 1, NULL, '2025-03-26 14:33:47');
INSERT INTO `tb_materiales_usados` VALUES (28, 'ST25-00000010', 1, 1, NULL, '2025-03-26 14:34:17');
INSERT INTO `tb_materiales_usados` VALUES (29, 'ST25-00000011', 1, 1, NULL, '2025-03-26 14:59:42');
INSERT INTO `tb_materiales_usados` VALUES (30, 'ST25-00000012', 3, 1, NULL, '2025-03-26 15:00:19');
INSERT INTO `tb_materiales_usados` VALUES (31, 'ST25-00000014', 3, 1, NULL, '2025-04-04 16:28:57');
INSERT INTO `tb_materiales_usados` VALUES (32, 'ST25-00000015', 1, 2, NULL, '2025-04-04 16:32:14');
INSERT INTO `tb_materiales_usados` VALUES (33, 'ST25-00000016', 2, 2, NULL, '2025-04-08 16:01:56');
INSERT INTO `tb_materiales_usados` VALUES (34, 'ST25-00000017', 5, 2, NULL, '2025-04-08 16:13:24');
INSERT INTO `tb_materiales_usados` VALUES (35, 'ST25-00000018', 2, 2, NULL, '2025-04-08 19:38:11');
INSERT INTO `tb_materiales_usados` VALUES (36, 'ST25-00000019', 4, 2, NULL, '2025-04-08 21:56:20');
INSERT INTO `tb_materiales_usados` VALUES (37, 'ST25-00000020', 2, 2, NULL, '2025-04-08 22:26:12');
INSERT INTO `tb_materiales_usados` VALUES (38, 'OS24-015481', 1, 2, NULL, '2025-04-11 14:06:13');
INSERT INTO `tb_materiales_usados` VALUES (39, 'ST25-00000021', 2, 1, NULL, '2025-04-11 14:14:56');

-- ----------------------------
-- Table structure for tb_orden_correlativo
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_correlativo`;
CREATE TABLE `tb_orden_correlativo`  (
  `id_cor` int(11) NOT NULL AUTO_INCREMENT,
  `num_orden` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_cor`) USING BTREE,
  UNIQUE INDEX `num_orden`(`num_orden`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_correlativo
-- ----------------------------
INSERT INTO `tb_orden_correlativo` VALUES (1, 'ST25-00000001', NULL, '2025-03-25 11:23:43');
INSERT INTO `tb_orden_correlativo` VALUES (3, 'ST25-00000002', NULL, '2025-03-26 11:20:59');
INSERT INTO `tb_orden_correlativo` VALUES (4, 'ST25-00000003', NULL, '2025-03-26 12:28:54');
INSERT INTO `tb_orden_correlativo` VALUES (5, 'ST25-00000004', NULL, '2025-03-26 12:49:33');
INSERT INTO `tb_orden_correlativo` VALUES (6, 'ST25-00000005', NULL, '2025-03-26 12:51:02');
INSERT INTO `tb_orden_correlativo` VALUES (7, 'ST25-00000006', NULL, '2025-03-26 14:10:14');
INSERT INTO `tb_orden_correlativo` VALUES (8, 'ST25-00000007', NULL, '2025-03-26 14:12:31');
INSERT INTO `tb_orden_correlativo` VALUES (9, 'ST25-00000008', NULL, '2025-03-26 14:19:56');
INSERT INTO `tb_orden_correlativo` VALUES (10, 'ST25-00000009', NULL, '2025-03-26 14:33:47');
INSERT INTO `tb_orden_correlativo` VALUES (11, 'ST25-00000010', NULL, '2025-03-26 14:34:17');
INSERT INTO `tb_orden_correlativo` VALUES (12, 'ST25-00000011', NULL, '2025-03-26 14:59:42');
INSERT INTO `tb_orden_correlativo` VALUES (13, 'ST25-00000012', NULL, '2025-03-26 15:00:19');
INSERT INTO `tb_orden_correlativo` VALUES (14, 'ST25-00000013', NULL, '2025-03-26 15:07:02');
INSERT INTO `tb_orden_correlativo` VALUES (15, 'ST25-00000014', NULL, '2025-04-04 16:28:57');
INSERT INTO `tb_orden_correlativo` VALUES (16, 'ST25-00000015', NULL, '2025-04-04 16:32:14');
INSERT INTO `tb_orden_correlativo` VALUES (17, 'ST25-00000016', NULL, '2025-04-08 16:01:56');
INSERT INTO `tb_orden_correlativo` VALUES (18, 'ST25-00000017', NULL, '2025-04-08 16:13:24');
INSERT INTO `tb_orden_correlativo` VALUES (19, 'ST25-00000018', NULL, '2025-04-08 19:38:11');
INSERT INTO `tb_orden_correlativo` VALUES (20, 'ST25-00000019', NULL, '2025-04-08 21:56:20');
INSERT INTO `tb_orden_correlativo` VALUES (21, 'ST25-00000020', NULL, '2025-04-08 22:26:12');
INSERT INTO `tb_orden_correlativo` VALUES (22, 'ST25-00000021', NULL, '2025-04-11 14:14:56');

-- ----------------------------
-- Table structure for tb_orden_servicio
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_servicio`;
CREATE TABLE `tb_orden_servicio`  (
  `id_ordens` int(11) NOT NULL AUTO_INCREMENT,
  `cod_ordens` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cod_incidencia` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `observaciones` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `recomendaciones` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_contacto` int(11) NULL DEFAULT NULL,
  `fecha_f` date NOT NULL,
  `hora_f` time(0) NOT NULL,
  `codigo_aviso` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `estatus` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_ordens`) USING BTREE,
  UNIQUE INDEX `cod_ordens`(`cod_ordens`) USING BTREE,
  INDEX `cod_incidencia`(`cod_incidencia`) USING BTREE,
  INDEX `id_contacto`(`id_contacto`) USING BTREE,
  CONSTRAINT `tb_orden_servicio_ibfk_1` FOREIGN KEY (`cod_incidencia`) REFERENCES `tb_incidencias` (`cod_incidencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_orden_servicio_ibfk_2` FOREIGN KEY (`id_contacto`) REFERENCES `tb_contac_ordens` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_servicio
-- ----------------------------
INSERT INTO `tb_orden_servicio` VALUES (25, 'ST25-00000001', 'INC-00000001', 'dfs', 'fasdfg', NULL, '2025-03-25', '11:23:42', '3', 1, NULL, '2025-03-25 11:23:43');
INSERT INTO `tb_orden_servicio` VALUES (26, 'ST25-00000002', 'INC-00000002', 'rer24354', '324345', NULL, '2025-03-26', '11:20:57', '3', 1, NULL, '2025-03-26 11:20:59');
INSERT INTO `tb_orden_servicio` VALUES (27, 'ST25-00000003', 'INC-00000003', '34324', '436554', NULL, '2025-03-26', '12:28:51', '3', 1, NULL, '2025-03-26 12:28:54');
INSERT INTO `tb_orden_servicio` VALUES (28, 'ST25-00000004', 'INC-00000004', 'ewertr', 'ertwetr', NULL, '2025-03-26', '12:49:30', '3', 1, NULL, '2025-03-26 12:49:33');
INSERT INTO `tb_orden_servicio` VALUES (29, 'ST25-00000005', 'INC-00000005', '342435', '6545436543', NULL, '2025-03-26', '12:50:59', '3', 1, NULL, '2025-03-26 12:51:02');
INSERT INTO `tb_orden_servicio` VALUES (30, 'ST25-00000006', 'INC-00000006', 'errgfh', 'gfhgfh', NULL, '2025-03-26', '14:10:11', '3', 1, NULL, '2025-03-26 14:10:14');
INSERT INTO `tb_orden_servicio` VALUES (31, 'ST25-00000007', 'INC-00000007', 'sadgfdg', 'dfgsfdg', NULL, '2025-03-26', '14:12:28', '3', 1, NULL, '2025-03-26 14:12:31');
INSERT INTO `tb_orden_servicio` VALUES (32, 'ST25-00000008', 'INC-00000008', 'eert', 'reterwt', NULL, '2025-03-26', '14:19:53', '3', 1, NULL, '2025-03-26 14:19:56');
INSERT INTO `tb_orden_servicio` VALUES (33, 'ST25-00000009', 'INC-00000009', 'dsfdsaf', 'dsfdsf', NULL, '2025-03-26', '14:33:45', '3', 1, NULL, '2025-03-26 14:33:47');
INSERT INTO `tb_orden_servicio` VALUES (34, 'ST25-00000010', 'INC-00000010', 'erewrt', 'rwetrwtw', NULL, '2025-03-26', '14:34:14', '3', 1, NULL, '2025-03-26 14:34:17');
INSERT INTO `tb_orden_servicio` VALUES (35, 'ST25-00000011', 'INC-00000011', 'fdsagfdg', 'dfgfdsgs', NULL, '2025-03-26', '14:59:39', '3', 1, NULL, '2025-03-26 14:59:42');
INSERT INTO `tb_orden_servicio` VALUES (36, 'ST25-00000012', 'INC-00000012', 'esfraedfz', 'dfdsgfs', NULL, '2025-03-26', '15:00:16', '3', 1, NULL, '2025-03-26 15:00:19');
INSERT INTO `tb_orden_servicio` VALUES (37, 'ST25-00000013', 'INC-00000013', 'esfraedfz', 'dfdsgfs', NULL, '2025-03-26', '15:07:00', '3', 1, NULL, '2025-03-26 15:07:02');
INSERT INTO `tb_orden_servicio` VALUES (38, 'ST25-00000014', 'INC-00000018', 'ewrrgr', 'aerwerg', NULL, '2025-04-04', '16:28:54', NULL, 1, NULL, '2025-04-04 16:28:57');
INSERT INTO `tb_orden_servicio` VALUES (39, 'ST25-00000015', 'INC-00000019', 'asdzfg', 'dsgf', NULL, '2025-04-04', '16:32:11', 'dsfsdf', 1, NULL, '2025-04-04 16:32:14');
INSERT INTO `tb_orden_servicio` VALUES (40, 'ST25-00000016', 'INC-00000021', 'sdfg', 'srthd', NULL, '2025-04-08', '16:01:55', 'qwerty', 1, '2025-04-08 16:09:22', '2025-04-08 16:01:56');
INSERT INTO `tb_orden_servicio` VALUES (41, 'ST25-00000017', 'INC-00000020', 'dfh', 'fghdfh', NULL, '2025-04-08', '16:13:24', 'qwerty', 1, '2025-04-08 16:15:09', '2025-04-08 16:13:24');
INSERT INTO `tb_orden_servicio` VALUES (42, 'ST25-00000018', 'INC-00000017', 'ewtyryst', 'yrtyrty', NULL, '2025-04-08', '19:38:10', 'rtgsdgdfgrte', 1, '2025-04-08 19:38:33', '2025-04-08 19:38:11');
INSERT INTO `tb_orden_servicio` VALUES (43, 'ST25-00000019', 'INC-00000023', 'wregds', 'sdfgf', NULL, '2025-04-08', '21:56:19', '3', 1, NULL, '2025-04-08 21:56:20');
INSERT INTO `tb_orden_servicio` VALUES (44, 'ST25-00000020', 'INC-00000022', 'ersydg', 'hsdfh', NULL, '2025-04-08', '22:26:12', 'sdtgfdhfg', 1, NULL, '2025-04-08 22:26:12');
INSERT INTO `tb_orden_servicio` VALUES (47, 'OS24-015481', 'INC-00000025', 'fdgsdfgsdf', 'gsdfgsdfgsdfg', NULL, '2025-04-11', '14:06:12', 'qwerty', 1, '2025-04-11 14:10:38', '2025-04-11 14:06:13');
INSERT INTO `tb_orden_servicio` VALUES (48, 'ST25-00000021', 'INC-00000014', 'redgdf', 'hfghdfdfh', NULL, '2025-04-11', '14:14:56', '3', 1, NULL, '2025-04-11 14:14:56');

-- ----------------------------
-- Table structure for tb_orden_visita
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita`;
CREATE TABLE `tb_orden_visita`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id_visita` int(11) NULL DEFAULT NULL,
  `fecha_visita` date NULL DEFAULT NULL,
  `hora_inicio` time(0) NULL DEFAULT NULL,
  `hora_fin` time(0) NULL DEFAULT NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cod_orden_visita`(`cod_orden_visita`) USING BTREE,
  INDEX `id_visita`(`id_visita`) USING BTREE,
  CONSTRAINT `tb_orden_visita_ibfk_1` FOREIGN KEY (`id_visita`) REFERENCES `tb_visitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_orden_visita_ibfk_2` FOREIGN KEY (`cod_orden_visita`) REFERENCES `tb_orden_visita_correlativo` (`cod_orden_visita`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita
-- ----------------------------
INSERT INTO `tb_orden_visita` VALUES (9, 'VT25-00000001', 22, '2025-03-26', '17:27:16', '17:27:16', 0, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita` VALUES (10, 'VT25-00000002', 23, '2025-03-26', '17:38:54', '17:38:54', 0, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita` VALUES (11, 'VT25-00000003', 24, '2025-03-26', '18:00:22', '18:00:22', 0, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita` VALUES (12, 'VT25-00000004', 25, '2025-04-01', '11:46:31', '11:46:31', 0, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita` VALUES (13, 'VT25-00000005', 27, '2025-04-08', '20:35:27', '20:35:27', 0, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita` VALUES (14, 'VT25-00000006', 26, '2025-04-10', '04:26:25', '04:26:25', 0, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita` VALUES (15, 'VT25-00000007', 29, '2025-04-11', '13:58:04', '13:58:04', 0, '2025-04-11 13:58:04');

-- ----------------------------
-- Table structure for tb_orden_visita_correlativo
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita_correlativo`;
CREATE TABLE `tb_orden_visita_correlativo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `cod_orden_visita`(`cod_orden_visita`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita_correlativo
-- ----------------------------
INSERT INTO `tb_orden_visita_correlativo` VALUES (1, 'VT25-00000001', '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_correlativo` VALUES (2, 'VT25-00000002', '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_correlativo` VALUES (3, 'VT25-00000003', '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_correlativo` VALUES (4, 'VT25-00000004', '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_correlativo` VALUES (5, 'VT25-00000005', '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_correlativo` VALUES (6, 'VT25-00000006', '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_correlativo` VALUES (7, 'VT25-00000007', '2025-04-11 13:58:04');

-- ----------------------------
-- Table structure for tb_orden_visita_filas
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita_filas`;
CREATE TABLE `tb_orden_visita_filas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `posicion` int(11) NULL DEFAULT NULL,
  `checked` int(11) NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cod_orden_visita`(`cod_orden_visita`) USING BTREE,
  CONSTRAINT `tb_orden_visita_filas_ibfk_1` FOREIGN KEY (`cod_orden_visita`) REFERENCES `tb_orden_visita` (`cod_orden_visita`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 211 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita_filas
-- ----------------------------
INSERT INTO `tb_orden_visita_filas` VALUES (113, 'VT25-00000001', 1, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (114, 'VT25-00000001', 2, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (115, 'VT25-00000001', 3, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (116, 'VT25-00000001', 4, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (117, 'VT25-00000001', 5, 1, 'wertyjhg', '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (118, 'VT25-00000001', 6, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (119, 'VT25-00000001', 7, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (120, 'VT25-00000001', 8, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (121, 'VT25-00000001', 9, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (122, 'VT25-00000001', 10, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (123, 'VT25-00000001', 11, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (124, 'VT25-00000001', 12, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (125, 'VT25-00000001', 13, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (126, 'VT25-00000001', 14, 0, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_filas` VALUES (127, 'VT25-00000002', 1, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (128, 'VT25-00000002', 2, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (129, 'VT25-00000002', 3, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (130, 'VT25-00000002', 4, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (131, 'VT25-00000002', 5, 1, 'dsafg', '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (132, 'VT25-00000002', 6, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (133, 'VT25-00000002', 7, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (134, 'VT25-00000002', 8, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (135, 'VT25-00000002', 9, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (136, 'VT25-00000002', 10, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (137, 'VT25-00000002', 11, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (138, 'VT25-00000002', 12, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (139, 'VT25-00000002', 13, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (140, 'VT25-00000002', 14, 0, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_filas` VALUES (141, 'VT25-00000003', 1, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (142, 'VT25-00000003', 2, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (143, 'VT25-00000003', 3, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (144, 'VT25-00000003', 4, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (145, 'VT25-00000003', 5, 1, '435', '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (146, 'VT25-00000003', 6, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (147, 'VT25-00000003', 7, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (148, 'VT25-00000003', 8, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (149, 'VT25-00000003', 9, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (150, 'VT25-00000003', 10, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (151, 'VT25-00000003', 11, 1, '43256', '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (152, 'VT25-00000003', 12, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (153, 'VT25-00000003', 13, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (154, 'VT25-00000003', 14, 0, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_filas` VALUES (155, 'VT25-00000004', 1, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (156, 'VT25-00000004', 2, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (157, 'VT25-00000004', 3, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (158, 'VT25-00000004', 4, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (159, 'VT25-00000004', 5, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (160, 'VT25-00000004', 6, 1, 'ERTY', '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (161, 'VT25-00000004', 7, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (162, 'VT25-00000004', 8, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (163, 'VT25-00000004', 9, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (164, 'VT25-00000004', 10, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (165, 'VT25-00000004', 11, 1, 'TRYERT', '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (166, 'VT25-00000004', 12, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (167, 'VT25-00000004', 13, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (168, 'VT25-00000004', 14, 0, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_filas` VALUES (169, 'VT25-00000005', 1, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (170, 'VT25-00000005', 2, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (171, 'VT25-00000005', 3, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (172, 'VT25-00000005', 4, 1, 'dsf', '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (173, 'VT25-00000005', 5, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (174, 'VT25-00000005', 6, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (175, 'VT25-00000005', 7, 1, 'hdfhfghgh', '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (176, 'VT25-00000005', 8, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (177, 'VT25-00000005', 9, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (178, 'VT25-00000005', 10, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (179, 'VT25-00000005', 11, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (180, 'VT25-00000005', 12, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (181, 'VT25-00000005', 13, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (182, 'VT25-00000005', 14, 0, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_filas` VALUES (183, 'VT25-00000006', 1, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (184, 'VT25-00000006', 2, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (185, 'VT25-00000006', 3, 1, 'erdfg', '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (186, 'VT25-00000006', 4, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (187, 'VT25-00000006', 5, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (188, 'VT25-00000006', 6, 1, 'dfgdfh', '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (189, 'VT25-00000006', 7, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (190, 'VT25-00000006', 8, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (191, 'VT25-00000006', 9, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (192, 'VT25-00000006', 10, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (193, 'VT25-00000006', 11, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (194, 'VT25-00000006', 12, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (195, 'VT25-00000006', 13, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (196, 'VT25-00000006', 14, 0, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_filas` VALUES (197, 'VT25-00000007', 1, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (198, 'VT25-00000007', 2, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (199, 'VT25-00000007', 3, 1, 'dfgdffghfg', '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (200, 'VT25-00000007', 4, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (201, 'VT25-00000007', 5, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (202, 'VT25-00000007', 6, 1, 'hfghfghfshfgh', '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (203, 'VT25-00000007', 7, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (204, 'VT25-00000007', 8, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (205, 'VT25-00000007', 9, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (206, 'VT25-00000007', 10, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (207, 'VT25-00000007', 11, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (208, 'VT25-00000007', 12, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (209, 'VT25-00000007', 13, 0, NULL, '2025-04-11 13:58:04');
INSERT INTO `tb_orden_visita_filas` VALUES (210, 'VT25-00000007', 14, 0, NULL, '2025-04-11 13:58:04');

-- ----------------------------
-- Table structure for tb_orden_visita_islas
-- ----------------------------
DROP TABLE IF EXISTS `tb_orden_visita_islas`;
CREATE TABLE `tb_orden_visita_islas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_orden_visita` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `isla` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `pos` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `impresoras` int(11) NULL DEFAULT NULL,
  `des_impresoras` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `lectores` int(11) NULL DEFAULT NULL,
  `des_lector` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jack` int(11) NULL DEFAULT NULL,
  `des_jack` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `voltaje` int(11) NULL DEFAULT NULL,
  `des_voltaje` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `caucho` int(11) NULL DEFAULT NULL,
  `des_caucho` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mueblepos` int(11) NULL DEFAULT NULL,
  `des_mueblepos` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `mr350` int(11) NULL DEFAULT NULL,
  `des_mr350` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `switch` int(11) NULL DEFAULT NULL,
  `des_switch` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `cod_orden_visita`(`cod_orden_visita`) USING BTREE,
  CONSTRAINT `tb_orden_visita_islas_ibfk_1` FOREIGN KEY (`cod_orden_visita`) REFERENCES `tb_orden_visita` (`cod_orden_visita`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_orden_visita_islas
-- ----------------------------
INSERT INTO `tb_orden_visita_islas` VALUES (13, 'VT25-00000001', 'gdr', 'gdr', 0, NULL, 0, NULL, 1, 'sdfhgj', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-03-26 17:27:16');
INSERT INTO `tb_orden_visita_islas` VALUES (14, 'VT25-00000002', '1', '2', 0, NULL, 0, NULL, 1, 'afdg', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-03-26 17:38:54');
INSERT INTO `tb_orden_visita_islas` VALUES (15, 'VT25-00000003', '3', '4', 0, NULL, 0, NULL, 1, '23456', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-03-26 18:00:22');
INSERT INTO `tb_orden_visita_islas` VALUES (16, 'VT25-00000004', '2', '3', 0, NULL, 0, NULL, 1, 'TEREYER', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-04-01 11:46:31');
INSERT INTO `tb_orden_visita_islas` VALUES (17, 'VT25-00000005', '12', '23', 0, NULL, 0, NULL, 1, 'erewafgf', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-04-08 20:35:27');
INSERT INTO `tb_orden_visita_islas` VALUES (18, 'VT25-00000006', '2', '4', 0, NULL, 0, NULL, 1, 'ertwert', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-04-10 04:26:25');
INSERT INTO `tb_orden_visita_islas` VALUES (19, 'VT25-00000007', '22', '4', 0, NULL, 0, NULL, 1, 'dfsfdhdsfh', 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, '2025-04-11 13:58:04');

-- ----------------------------
-- Table structure for tb_personal
-- ----------------------------
DROP TABLE IF EXISTS `tb_personal`;
CREATE TABLE `tb_personal`  (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `ndoc_usuario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nombres` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email_personal` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email_corporativo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email_verified_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `fecha_nacimiento` date NULL DEFAULT NULL,
  `tel_personal` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tel_corporativo` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usuario` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass_view` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_perfil` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `firma_digital` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `tipo_acceso` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `menu_usuario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_usuario`) USING BTREE,
  UNIQUE INDEX `U_ndoc_usuario`(`ndoc_usuario`) USING BTREE,
  UNIQUE INDEX `U_usuario`(`usuario`) USING BTREE,
  UNIQUE INDEX `U_contrasena`(`contrasena`) USING BTREE,
  INDEX `U_email_personal`(`email_personal`) USING BTREE,
  INDEX `U_email_corporativo`(`email_corporativo`) USING BTREE,
  INDEX `U_tel_personal`(`tel_personal`) USING BTREE,
  INDEX `U_tel_corporativo`(`tel_corporativo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_personal
-- ----------------------------
INSERT INTO `tb_personal` VALUES (1, '61505130', 'JEREMY PATRICK', 'CAUPER SILVANO', 'jcauper@gmail.com', 'jcauper@email.com', '2025-03-25 11:11:19', '2003-07-14', '974562354', '954213548', 'jcauper', '$2y$12$5uZJUCoBitJY01nivL.Fy.f22TsGLJrfNggAi49bexW04zNGSIq9u', '123456', 'user_auth.jpg', 'fd_jcauper.png', 5, 5, 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiNCI6WyIzIiwiNCIsIjUiXSwiNSI6WyI2Il0sIjYiOlsiNyIsIjgiXSwiNyI6WyI5IiwiMTAiXSwiOCI6WyIxMSIsIjEyIl0sIjkiOltdfQ==', 0, 1, '2025-03-10 11:41:00', '2024-07-09 23:00:19');
INSERT INTO `tb_personal` VALUES (3, '12345678', 'Pedro', 'Suarez', 'psuarez@gmail.com', 'psuarez@email.com', '2025-03-25 15:58:40', '2003-01-14', '935423118', '952332137', 'psuarez', '$2y$12$3CmRGy97YD3R0M5j19rrRO.G6AbM6n26v8y3CPEJI8ca2.bsRSiLC', '123789', 'fp_psuarez.png', 'fd_psuarez.png', 3, 3, 'eyI4IjpbIjExIiwiMTIiXX0=', 1, 0, '2025-02-10 16:31:18', '2024-07-13 02:41:10');
INSERT INTO `tb_personal` VALUES (4, '74716278', 'JOSTHEIN JOSEPH', 'MAYORCA BELLEZA', 'jmayorca@gmail.com', 'jmayorca@email.com', '2025-03-25 15:58:41', '1997-06-11', '978456123', '985267341', 'jmayorca', '$2y$12$CAclmFJJoM2plUl48iJsgeRbm8WrDbu8jynetkGuWVVBxGTONEm9C', '147852', 'user_auth.jpg', 'fd_jmayorca.png', 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 1, 0, '2025-02-10 15:28:24', '2024-07-15 22:18:33');
INSERT INTO `tb_personal` VALUES (5, '70401296', 'BRYAN MARTIN', 'POLO GOMEZ', 'talvan@gmail.com', 'talvan@email.com', '2025-04-13 01:39:43', '2001-07-02', '987564123', '948741236', 'talvan', '$2y$12$6oyxU4QP06ERy7uIw4t6yeJuW1s6bmft/lUWc9SMosYlyZrHPbwN.', '987654', 'user_auth.jpg', 'fd_talvan.png', 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 0, '2025-02-10 15:28:09', '2024-07-22 02:16:27');
INSERT INTO `tb_personal` VALUES (6, '72878242', 'RENZO GRACIANI', 'VIGO MALLQUI', NULL, NULL, '2025-03-21 17:07:30', '2000-01-04', NULL, NULL, 'rvigo', '$2y$12$r952GLMGgMBwZ/G6GRsNDushp5D2AyKzesrHwD0bhuZj4Bgqy/r.G', '123456', 'fp_rvigo.webp', 'fd_rvigo.png', 2, 1, 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiNCI6WyIzIiwiNCIsIjUiXSwiNSI6WyI2Il0sIjYiOlsiNyIsIjgiXSwiOSI6W119', 0, 1, '2025-03-21 17:07:30', '2025-03-07 15:26:21');
INSERT INTO `tb_personal` VALUES (7, '00000001', 'Soporte01', 'Tecnico', NULL, 'soporte01@rcingenieros.com', '2025-03-26 16:09:36', '2025-03-24', NULL, NULL, 'soporte01', '$2y$12$d4Y0KoSryXMoM8NGETl.EeHPbgxTjemoVFQGF9x6Twk8uKFgJI.vi', '123456', 'user_auth.jpg', '', 4, 1, 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiOCI6WyIxMSIsIjEyIl0sIjkiOltdfQ==', 0, 1, NULL, '2025-03-24 15:55:31');
INSERT INTO `tb_personal` VALUES (9, '00000002', 'Soporte02', 'Tecnico', NULL, 'soporte02@rcingenieros.com', '2025-03-24 15:57:50', '2025-03-24', NULL, NULL, 'soporte02', '$2y$12$.qREeZOuT0PQk4pkH1En.uue/O1UnSXAIH.XjIgMveHHOCWivCt0K', '123456', 'user_auth.jpg', '', 4, 1, 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiOCI6WyIxMSIsIjEyIl0sIjkiOltdfQ==', 0, 1, '2025-03-24 15:57:48', NULL);
INSERT INTO `tb_personal` VALUES (10, '10392834', 'MARLON RAUL', 'RAMOS SAJAMI', NULL, 'mramos@rcingenieros.com', '2025-03-24 16:02:10', '2025-03-24', NULL, '994092153', 'mramos', '$2y$12$z7IC//k6SaVHxfwpCORU4.oS5R1dMAqmgIpmnA8zE.UVg/qeW2416', '912345', 'user_auth.jpg', '', 2, 3, 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiNCI6WyIzIiwiNCIsIjUiXSwiNSI6WyI2Il0sIjYiOlsiNyIsIjgiXSwiOSI6W119', 0, 1, NULL, '2025-03-24 16:02:10');
INSERT INTO `tb_personal` VALUES (14, '40778797', 'OMAR', 'SAENZ', NULL, NULL, '2025-03-25 15:53:14', NULL, NULL, '995910053', 'osaenz', '$2y$12$4eGytdvfCg32riFOKNd2leR35NUczurLWBLJaUsVw1kdXrt4VZ5We', '40778797', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 10:53:14', NULL);
INSERT INTO `tb_personal` VALUES (15, '72159292', 'ALVARO', 'HUERTA', NULL, NULL, '2025-03-25 15:59:41', NULL, NULL, '995910188', 'ahuerta', '$2y$12$fL6kB56dYKI5Ad3yiVrX/u48enV9vUaFl0JPuYwdJJ/rOmqXGDAuS', '72159292', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 10:59:41', NULL);
INSERT INTO `tb_personal` VALUES (16, '77043291', 'JHERSON', 'VILCAPOMA', NULL, NULL, '2025-03-25 16:00:00', NULL, NULL, '995920028', 'jvilcapoma', '$2y$12$PjNa/xSE6pj3OGpMDzesuORdywFrIZH/XaN/dlj/f6FLPhlzGxbjK', '77043291', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 11:00:00', NULL);
INSERT INTO `tb_personal` VALUES (17, '47833900', 'GIANFRANCO', 'ESTEBAN', NULL, NULL, '2025-03-25 15:54:53', NULL, NULL, '970445543', 'gesteban', '$2y$12$odWi2NSGV1ORf6eafi.d/uoLcTlKN85ocD1F9F5gldxkPQJ7SaE2e', '47833900', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 10:54:53', NULL);
INSERT INTO `tb_personal` VALUES (18, '75530490', 'KHESNIL', 'CANCHARI', NULL, NULL, '2025-03-25 16:00:29', NULL, NULL, '995910174', 'kcanchari', '$2y$12$RGp9KxzNPBCOU0d6lCJeKO1qAIZTUeh8E0B6IlLnHR9MsTm0NO/a2', '75530490', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 11:00:29', NULL);
INSERT INTO `tb_personal` VALUES (19, '75005472', 'DAYSI', 'MENDOZA', NULL, NULL, '2025-03-25 16:00:39', NULL, NULL, '995910195', 'dmendoza', '$2y$12$y5SWOx7B0kWpkWDdkZilxe7SjuKFxGVo8XhtyIoQJRqAOwafK6i/.', '75005472', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 11:00:39', NULL);
INSERT INTO `tb_personal` VALUES (20, '45458303', 'SAMUEL', 'VELARDE', NULL, NULL, '2025-03-25 15:53:32', NULL, NULL, NULL, 'svelarde', '$2y$12$.MwN669r2TPvboqFuvCw8egcgMkMCgZ.nZdtPfRng1lE.qVMaqNYO', '45458303', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 10:53:32', NULL);
INSERT INTO `tb_personal` VALUES (23, '73042819', 'RODRIGO', 'ALVAREZ', NULL, NULL, '2025-03-25 16:01:02', NULL, NULL, NULL, 'ralvarez', '$2y$12$PXa.dloB1DNQQShJbb93wegyWpELGuEPDQ9ouhDgZyiX4clh07T3K', '73042819', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 11:01:02', NULL);
INSERT INTO `tb_personal` VALUES (24, '71545548', 'OWEN', 'TRUJILLO', NULL, NULL, '2025-03-25 15:55:16', NULL, NULL, NULL, 'otrujillo', '$2y$12$sVTlO37Y.vwp./nILMNAZe278Q7w0AMaq.XSaXWA7j/.hClHF2nxS', '71545548', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 10:55:16', NULL);
INSERT INTO `tb_personal` VALUES (25, '73206022', 'SEBASTIAN', 'INCIO', NULL, NULL, '2025-03-25 16:01:15', NULL, NULL, NULL, 'sincio', '$2y$12$mGj8JNmSoaaqbeaiA7EmC.aZAgbILcQ6kTg6mJUfBa4aZKURwurz.', '73206022', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 11:01:15', NULL);
INSERT INTO `tb_personal` VALUES (26, '71618287', 'DEBORA', 'CALDERON', NULL, NULL, '2025-03-25 16:07:05', NULL, NULL, '994291205', 'dcalderon', '$2y$12$NwEg6uTUAHneOjsZdQ/f5ex/wrMZBzZhTXdJcL7sinvH9xDdSBCCO', '71618287', NULL, NULL, 2, 3, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 11:07:05', NULL);
INSERT INTO `tb_personal` VALUES (27, '09610245', 'RICARDO', 'CALDERON', NULL, NULL, '2025-03-25 16:06:40', NULL, NULL, '994287333', 'rcalderon', '$2y$12$w7PluJ/ykCfLsCgG9BFy6.WRw0pcznX3Oydepivff/iXT80C9Y51C', '09610245', NULL, NULL, 1, 3, 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiNCI6WyIzIiwiNCIsIjUiXSwiNSI6WyI2Il0sIjYiOlsiNyIsIjgiXX0=', 0, 1, '2025-03-25 11:06:40', NULL);
INSERT INTO `tb_personal` VALUES (28, '45716026', 'EDUARDO', 'ESCOBAR', NULL, NULL, '2025-03-25 15:54:08', '2025-03-25', NULL, '994037476', 'eescobar', '$2y$12$gVFiXJmXFJhGkJH.df26M..JLrJqQkwoky.gnvQO9Z3WQwJdZLdOq', '45716026', NULL, NULL, 3, 1, 'eyI4IjpbIjExIiwiMTIiXX0=', 0, 1, '2025-03-25 10:54:08', NULL);

-- ----------------------------
-- Table structure for tb_problema
-- ----------------------------
DROP TABLE IF EXISTS `tb_problema`;
CREATE TABLE `tb_problema`  (
  `id_problema` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tipo_incidencia` int(11) NULL DEFAULT NULL COMMENT 'TIPO 1 : REMOTO , TIPO 2 : PRESENCIAL',
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_problema`) USING BTREE,
  UNIQUE INDEX `codigo`(`codigo`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_problema
-- ----------------------------
INSERT INTO `tb_problema` VALUES (1, 'L-PRE', 'PROBLEMA DE LECTURA', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (2, 'I-PRE', 'PROBLEMA DE IMPRESORAS', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (3, 'T-PRE', 'PROBLEMA DE TERMINAL TOUCH / MR350', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (4, 'C-PRE', 'PROBLEMA DE COMUNICACION', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (5, 'L-REM', 'PROBLEMA DE LECTURA', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (6, 'I-REM', 'PROBLEMA DE IMPRESORA', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (7, 'T-REM', 'PROBLEMA DE TERMINAL TOUCH', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (8, 'C-REM', 'PROBLEMA DE COMUNICACIÓN', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (9, 'ACT-PRE', 'ACTUALIZACION DE SISTEMA', 2, 0, 1, '2025-03-10 11:45:53', '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (10, 'PSIS-REM', 'PROBLEMAS DE SISTEMA', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (11, 'MANT', 'MANTENIMIENTO GENERAL', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (12, 'PRU-PRE', 'PRUEBA', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (13, 'SPA-PRE', 'SOPORTES ADICIONALES', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (14, 'SPA-REM', 'SOPORTES ADICIONALES', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (15, 'PSCE-REM', 'PROBLEMAS EN SISTEMA SAUCE', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (17, 'CSV-PRE', 'CAMBIO DE SERVIDOR', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (18, 'CSV-REM', 'CAMBIO DE SERVIDOR', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (19, 'CDD-PRE', 'CAMBIO DE DISCO DURO', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (20, 'CDD-REM', 'CAMBIO DE DISCO DURO', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (21, 'PSIS-PRE', 'PROBLEMAS DE SISTEMA', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (22, 'PRE-PNM', 'PUESTA EN MARCHA/ INICIO DE VENTAS', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (23, 'R-PRE', 'REUBICACIÓN DE EQUIPOS', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (24, 'REM-PNM', 'SEGUIMIENTO PUESTA EN MARCHA/ INICIO DE VENTAS', 1, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (25, 'S-PRE', 'LEVANTAMIENTO DE INFORMACION', 2, 0, 1, NULL, '2024-07-27 18:49:13');
INSERT INTO `tb_problema` VALUES (26, 'ACT-PRUEBA', 'Problema prueba', 1, 0, 1, NULL, '2025-01-31 13:23:47');

-- ----------------------------
-- Table structure for tb_subproblema
-- ----------------------------
DROP TABLE IF EXISTS `tb_subproblema`;
CREATE TABLE `tb_subproblema`  (
  `id_subproblema` int(11) NOT NULL AUTO_INCREMENT,
  `id_problema` int(11) NULL DEFAULT NULL,
  `codigo_sub` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `descripcion` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_subproblema`) USING BTREE,
  UNIQUE INDEX `codigo_sub`(`codigo_sub`) USING BTREE,
  INDEX `id_problema`(`id_problema`) USING BTREE,
  CONSTRAINT `id_problema` FOREIGN KEY (`id_problema`) REFERENCES `tb_problema` (`id_problema`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 255 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_subproblema
-- ----------------------------
INSERT INTO `tb_subproblema` VALUES (1, 1, 'L-PRE_01', 'VALIDACION DE JACKTOOL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (2, 1, 'L-PRE_02', 'RESTAURACION PROBLEMA JACKTOOL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (3, 1, 'L-PRE_03', 'CAMBIO DE JACKTOOL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (4, 1, 'L-PRE_04', 'VALIDACION DE MANGUERA TELEFONICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (5, 1, 'L-PRE_05', 'RESTAURACION PROBLEMA MANGUERA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (6, 1, 'L-PRE_06', 'CAMBIO DE MANGUERA TELEFONICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (7, 1, 'L-PRE_07', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (8, 1, 'L-PRE_08', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (9, 1, 'L-PRE_09', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (10, 1, 'L-PRE_10', 'VALIDACION DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (11, 1, 'L-PRE_11', 'RESTAURACION PROBLEMA DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (12, 1, 'L-PRE_12', 'CAMBIO DE DISPOTIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (13, 1, 'L-PRE_13', 'INSTALACION DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (14, 1, 'L-PRE_14', 'LECTOR DE CHIP DAÑADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (15, 1, 'L-PRE_15', 'VALIDACION RJ45', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (16, 1, 'L-PRE_16', 'CAMBIO DE RJ45', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (17, 1, 'L-PRE_17', 'VALIDACION FUENTE DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (18, 1, 'L-PRE_18', 'CAMBIO FUENTE DE DISPOSITIVO LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (19, 2, 'I-PRE_01', 'VALIDACION DE IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (20, 2, 'I-PRE_02', 'RESTAURACION PROBLEMA IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (21, 2, 'I-PRE_03', 'INSTALACION DEIMPRESORA BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (22, 2, 'I-PRE_04', 'RECOJO DE IMPRESORA BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (23, 2, 'I-PRE_05', 'RECOJO DE IMPRESORA EDS PARA REPARACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (24, 2, 'I-PRE_06', 'SALIDA DE IMPRESORA EDS REPARADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (25, 2, 'I-PRE_07', 'INSTALACION IMPRESORA NUEVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (26, 2, 'I-PRE_08', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (27, 2, 'I-PRE_09', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (28, 2, 'I-PRE_10', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (29, 2, 'I-PRE_11', 'CONFIGURACION DE IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (30, 10, 'PSIS-REM_45', 'ANULACION DE DOCUMENTO BOLETA A CALIBRACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (31, 2, 'I-PRE_13', 'ATASCO DE PAPEL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (32, 2, 'I-PRE_14', 'ATASCO DE CUCHILLA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (33, 2, 'I-PRE_15', 'FUENTE DESCONECTADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (34, 3, 'T-PRE_01', 'VALIDACION DE DISPOSITIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (35, 3, 'T-PRE_02', 'RESTAURACION PROBLEMA ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (36, 3, 'T-PRE_03', 'INSTALACION DE TERMINAL', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (37, 3, 'T-PRE_04', 'CONFIGURACION DE TERMINAL ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (38, 3, 'T-PRE_05', 'CONFIGURACION DE APLICATIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (39, 3, 'T-PRE_06', 'DESCALIBRACION DE PANTALLA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (40, 3, 'T-PRE_07', 'PANTALLA CONGELADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (41, 3, 'T-PRE_08', 'FUENTE DESCONECTADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (42, 3, 'T-PRE_09', 'CAMBIO DE FUENTE 12V ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (43, 3, 'T-PRE_10', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (44, 3, 'T-PRE_11', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (45, 3, 'T-PRE_12', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (46, 4, 'C-PRE_01', 'VALIDACION DEPERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (47, 4, 'C-PRE_02', 'RESTAURACION PERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (48, 4, 'C-PRE_03', 'INSTALACION DEINTERFAZ DE BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (49, 4, 'C-PRE_04', 'RECOJO DE INTERFAZ BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (50, 4, 'C-PRE_05', 'RECOJO DE INTERFAZ EDS PARA REPARACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (51, 4, 'C-PRE_06', 'SALIDA DE INTERFAZ EDS REPARADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (52, 4, 'C-PRE_07', 'INSTALACION INTERFAZ NUEVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (53, 4, 'C-PRE_08', 'VALIDACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (54, 4, 'C-PRE_09', 'RESTAURACION DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (55, 4, 'C-PRE_10', 'CAMBIO DE LINEA DATA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (56, 4, 'C-PRE_11', 'CONFIGURACION DE INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (57, 4, 'C-PRE_12', 'FUENTE DESCONECTADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (58, 4, 'C-PRE_13', 'INDEPENDIZACION DE SURTIDORES CON INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (59, 5, 'L-REM_01', 'LENTITUD EN LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (60, 5, 'L-REM_02', 'REINICIO DE SERVICIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (61, 5, 'L-REM_03', 'REINYECCION DE SISTEMA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (62, 5, 'L-REM_04', 'MANTENIMIENTO DE SISTEMA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (63, 5, 'L-REM_05', 'DISPOSITIVO BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (64, 5, 'L-REM_06', 'CONFIGURACION DISPOSITIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (65, 5, 'L-REM_07', 'CARA DESPACHO BLOQUEDA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (66, 5, 'L-REM_08', 'INDICE FUERA DE LA MATRIZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (68, 6, 'I-REM_01', 'LENTITUD EN IMPRESIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (69, 6, 'I-REM_02', 'DOCUMENTOS EN COLA DE IMPRESIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (70, 6, 'I-REM_03', 'DOCUMENTO PENDIENTE POR ENVIAR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (71, 6, 'I-REM_04', 'CONFLICTO DE IP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (72, 6, 'I-REM_05', 'CONFIGURACION DE IMPRESORA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (73, 6, 'I-REM_06', 'DERIVACION DE IMPRESIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (74, 7, 'T-REM_01', 'VALIDACION DE DISPOSITIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (75, 7, 'T-REM_02', 'CONFIGURACION DE APLICATIVO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (76, 7, 'T-REM_03', 'DESCALIBRACION DE PANTALLA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (77, 7, 'T-REM_04', 'PANTALLA CONGELADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (78, 7, 'T-REM_05', 'APP GST NO FIDELIZA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (79, 7, 'T-REM_06', 'SERVICIO AUTORIZADOR NO ENCONTRADO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (80, 7, 'T-REM_07', 'PERDIDA DE COMUNICACIÓN ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (81, 8, 'C-REM_01', 'VALIDACION DEPERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (82, 8, 'C-REM_02', 'RESTAURACION PERDIDA DE COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (83, 8, 'C-REM_03', 'CONFIGURACION DE INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (84, 8, 'C-REM_04', 'CONFLICTO DE PUERTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (85, 8, 'C-REM_05', 'PUERTO FANTASMAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (86, 8, 'C-REM_06', 'CONFIGURACION PUERTO INTERFAZ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (87, 8, 'C-REM_07', 'CAMBIO DE PUERTO COMUNICACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (88, 9, 'ACT-PRE_01', 'ACTUALIZACION DE NUEVA VERSION', 0, 1, '2025-01-31 16:38:14', '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (89, 9, 'ACT-PRE_02', 'ACTUALIZACION PRUEBAS PILOTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (90, 9, 'ACT-PRE_03', 'ACTUALIZACION O.S', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (91, 9, 'ACT-PRE_04', 'ACTUALIZACION DE CAMBIO DE FACTURADOR ELECTRONICO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (92, 9, 'ACT-PRE_05', 'ACTUALIZACION CAMBIO DE SERIES / PREFIJOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (93, 10, 'PSIS-REM_01', 'REINICIO DE SERVICIO COMPLETO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (94, 10, 'PSIS-REM_02', 'CIERRE/ APERTURA DE TURNO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (95, 10, 'PSIS-REM_03', 'CAMBIO DE PRECIO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (96, 10, 'PSIS-REM_04', 'INGRESAR NUEVO EMPLEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (97, 10, 'PSIS-REM_05', 'INGRESAR NUEVO CLIENTE CRÉDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (98, 10, 'PSIS-REM_06', 'INGRESAR NUEVO CLIENTE CON DESCUENTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (99, 10, 'PSIS-REM_07', 'INGRESAR A GENERADOR DE REPORTES', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (100, 10, 'PSIS-REM_08', 'INGRESAR A REPORTES GASOLUTIONS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (101, 10, 'PSIS-REM_09', 'DEJAR BASE DE DATOS COMO HISTORICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (102, 10, 'PSIS-REM_10', 'USUARIO BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (103, 10, 'PSIS-REM_11', 'RETRANSMISION DE VENTAS CLUB PGN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (104, 10, 'PSIS-REM_12', 'ANULACIÓN DE DOCUMENTOS BOLETA A VALE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (105, 10, 'PSIS-REM_13', 'ANULACIÓN DE DOCUMENTOS BOLETA A FACTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (106, 10, 'PSIS-REM_14', 'ACTUALIZACION DE DATOS DE CLIENTE SUNAT', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (107, 10, 'PSIS-REM_15', 'PROBLEMAS CON BASE DE DATOS GASOLUTIONS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (108, 10, 'PSIS-REM_16', 'PROBLEMAS CON SISTEMA OPERATIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (109, 10, 'PSIS-REM_17', 'CAMBIO DE DISCO DURO CON SISTEMA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (110, 10, 'PSIS-REM_18', 'COPIA POR NUMERO DE DOCUMENTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (111, 10, 'PSIS-REM_19', 'COPIA POR NUMERO DE DOCUMENTO CLIENTE CREDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (112, 11, 'MANT_01', 'MANTENMIENTO GENERAL DE EESS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (113, 2, 'I-PRE_16', 'SOBRE ESCRITURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (114, 7, 'T-REM_08', 'APLICATIVO CERRADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (115, 10, 'PSIS-REM_20', 'INGRESO AL DATA ADMIN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (116, 10, 'PSIS-REM_21', 'INGRESO AL CONFIGURADOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (117, 10, 'PSIS-REM_22', 'REGISTRO DE PLACAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (118, 10, 'PSIS-REM_23', 'EMISION DE BOLETA CON NUMERO DE DNI', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (119, 10, 'PSIS-REM_24', 'VENTA PERDIDA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (120, 10, 'PSIS-REM_25', 'ACTUALIZACION DE DATA CLIENTE RUC', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (121, 10, 'PSIS-REM_26', 'ANULACIÓN DE DOCUMENTO DE FACTURA A FACTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (122, 10, 'PSIS-REM_27', 'ANULACIÓN DE DOCUMENTO POR VENTA MILLONARIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (123, 3, 'T-PRE_13', 'ACTUALIZACION DE APLICATIVO GST', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (124, 3, 'T-PRE_14', 'SALIDA DE EQUIPO/ TERMINAL EDS REPARADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (125, 17, 'CSV-PRE_01', 'CAMBIO DE SERVIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (126, 13, 'SPA-PRE_01', 'INSTALACION DE SWICH', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (127, 13, 'SPA-PRE_02', 'ENTREGA DE LECTORES', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (128, 13, 'SPA-PRE_03', 'INSTALACION DE ANTIVIRUS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (129, 13, 'SPA-PRE_04', 'APOYO ÁREA DE PROYECTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (130, 5, 'L-REM_09', 'VALIDACION DE VENTAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (131, 1, 'L-PRE_19', 'PRUEBAS DE VENTA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (132, 14, 'SPA-REM_01', 'APERTURA DE USER EN SERVER', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (133, 14, 'SPA-REM_02', 'VALIDACIÓN EN SISTEMAS CARGA EN GNC', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (134, 13, 'SPA-PRE_05', 'VALIDACIÓN DE CARGA EN GNC', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (135, 15, 'PSCE-REM_01', 'SISTEMA NO ACTIVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (136, 15, 'PSCE-REM_02', 'SISTEMA BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (137, 13, 'SPA-PRE_06', 'VALIDACIÓN DE UPS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (138, 13, 'SPA-PRE_07', 'REVISIÓN TÉCNICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (139, 10, 'PSIS-REM_28', 'EXCEPSIÓN DEL CDC ( CLUB PGN)', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (140, 10, 'PSIS-REM_29', 'APLIAR TIEMPO DE ANULACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (141, 14, 'SPA-REM_03', 'CARAS BLOQUEADAS POR VENTAS PUNTOS BONUS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (142, 3, 'T-PRE_15', 'INSTALACION DE EQUIPO BACKUP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (143, 13, 'SPA-PRE_08', ' IMPLEMENTACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (144, 10, 'PSIS-REM_30', 'ANULACIÓN DE DOCUMENTO VALE A BOLETA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (145, 10, 'PSIS-REM_31', 'CONVERSION DE VENTA EFECTIVO A TARJETA CORPORATIVA ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (146, 13, 'SPA-PRE_09', 'PRUEBAS DE VENTAS CON TARJETA CORPORATIVA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (147, 14, 'SPA-REM_04', 'APERTURA DE REPORTES', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (148, 14, 'SPA-REM_05', 'LA OPERACION SE COMPLETO CORRECTRAMENTE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (149, 10, 'PSIS-REM_32', 'ANULACIÓN DE DOCUMENTO SIN EMITIR OTRO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (150, 10, 'PSIS-REM_33', 'ANULACIÓN DE DOCUMENTO POR UNA NOTA DE CRÉDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (151, 10, 'PSIS-REM_34', 'SACAR COPIA DE TURNOS / AUDITORIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (152, 10, 'PSIS-REM_35', 'REIMPRESIÓN COPIA DE DOCUMENTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (153, 13, 'SPA-PRE_11', 'INSTALACION DE BANDA MAGNETICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (155, 13, 'SPA-PRE_12', 'VALIDACIÓN DE PANATALLA SERVER', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (156, 14, 'SPA-REM_06', 'APERTURA DEL MAI STATION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (158, 13, 'SPA-PRE_13', 'RECOJO DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (159, 13, 'SPA-PRE_14', 'INSTALACION DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (160, 13, 'SPA-PRE_15', 'PRUEBAS SISTEMAS SAUCE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (161, 10, 'PSIS-REM_36', 'ANULACION DE DOCUMENTO VALA A FACTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (164, 6, 'I-REM_07', 'IMPRESIONES BASURAS/ DATOS INCORRECTO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (165, 14, 'SPA-REM_07', 'INVENTARIO PRIMAX', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (166, 13, 'SPA-PRE_16', 'DEVOLUCIÓN DE EQUIPO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (167, 13, 'SPA-PRE_17', 'CAMBIO DE IP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (168, 10, 'PSIS-REM_37', 'DESACTIVAR PLACA DE CLIENTE CREDITO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (169, 5, 'L-REM_10', 'CHIP BLOQUEADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (170, 10, 'PSIS-REM_38', 'VENTA CON INCONSISTENCIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (171, 13, 'SPA-PRE_18', 'CORTO DE FLUIDO ELECTRICO / VALIDACION DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (172, 14, 'SPA-REM_08', 'DIFERENCIA EN PARTE DIARO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (173, 19, 'CDD-PRE_01', 'CAMBIO DE DISCO DURO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (175, 6, 'I-REM_08', 'NO IMPRIMEN DOCUMENTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (176, 10, 'PSIS-REM_39', 'VALIDACIONES DE SISTEMA OPERATIVO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (177, 10, 'PSIS-REM_40', 'MANTENIMIENTO DE SISTEMAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (184, 4, 'C-PRE_14', 'CONFIGURACION DE PUERTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (185, 18, 'CSV-REM_01', 'CAMBIO DE SERVIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (186, 14, 'SPA-REM_09', 'PROBLEMAS - ERROR DEL SURTIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (187, 14, 'SPA-REM_10', 'APERTURA DEL GENERADOR DE ARCHIVOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (189, 13, 'SPA-PRE_19', 'INVENTARIO/ LEVANTAMIENTO DE INFO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (190, 14, 'SPA-REM_11', 'PRUEBAS SISTEMAS SAUCE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (191, 10, 'PSIS-REM_41', 'PROBLEMAS CON TARJETA CORPORATIVA / BONUS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (192, 10, 'PSIS-REM_42', 'ANULACION DE DOCUMENTO FACTURA A VALE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (193, 10, 'PSIS-REM_43', 'VENTA EN CURSO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (194, 20, 'CDD-REM_01', 'CAMBIO DE DISCO DURO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (196, 6, 'I-REM_09', 'IMPRESION CON LINEAS EN BLANCO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (197, 14, 'SPA-REM_12', 'REVISION DE UPS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (198, 14, 'SPA-REM_13', 'PROBLEMAS CON FLUIDO ELÉCTRICO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (199, 21, 'PSIS-PRE_01', 'PROBLEMAS VARIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (202, 13, 'SPA-PRE_20', 'VALIDACIONES SISTEMAS SAUCE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (203, 13, 'SPA-PRE_21', 'LEVANTAMIENTO DE INFORMACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (204, 15, 'PSCE-REM_03', 'CARA BLOQUEADA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (205, 14, 'SPA-REM_14', 'RESTABLECIMIENTO DE SISTEMAS POR PARADA DE EMERGENCIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (208, 13, 'SPA-PRE_22', 'VALIDACIÓN DE EQUIPOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (209, 10, 'PSIS-REM_44', 'PROBLEMAS EN CONSULTA SUNAT', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (210, 12, 'PRU-PRE_01', 'PRIMAX GO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (211, 13, 'SPA-PRE_23', 'CAMBIO DE SERVIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (212, 15, 'PSCE-REM_04', 'EXCEPCION SALTOS DE LECTURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (213, 2, 'I-PRE_17', 'NO IMPRIMEN DOCUMENTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (214, 14, 'SPA-REM_15', 'INCONVENIENTE CON GNV POR MANTENIMIENTE DE TABLERO ELÉCTRICO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (216, 13, 'SPA-PRE_24', 'ENTREGA DE MANGUERAS TELEFÓNICAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (217, 23, 'R-PRE_01', 'IMPRESORAS TYSSO/ EPSON', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (218, 23, 'R-PRE_02', 'GABINETE', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (219, 23, 'R-PRE_03', 'EQUIPO DE LECTURA ISR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (220, 23, 'R-PRE_04', 'EQUIPO TERMINAL TOUCH', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (221, 10, 'PSIS-REM_46', 'DESACTIVAR DESCUENTO DE CLIENTE / CREDITO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (223, 6, 'I-REM_10', 'IMPRESIONES ENTRE CORTADAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (224, 8, 'C-REM_08', 'CONFIGURACION DE IP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (225, 6, 'I-REM_11', 'SOBRE ESCRITURA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (226, 13, 'SPA-PRE_25', 'SOPORTES VARIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (227, 10, 'PSIS-REM_47', 'CONFIGURACION DE HORARIOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (228, 14, 'SPA-REM_16', 'REVISIÓN DE IP SERVER/ IP PUBLICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (229, 14, 'SPA-REM_17', 'CONSULTAS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (231, 13, 'SPA-PRE_26', 'ACTIVACIÓN DE LICENCIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (232, 14, 'SPA-REM_18', 'AGREGAR PRODUCTOS', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (236, 2, 'I-PRE_18', 'IMPRESIÓN BORROSA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (237, 14, 'SPA-REM_20', 'VALIDACIÓN DE CHIP', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (238, 10, 'PSIS-REM_48', 'ANULACIÒN DE FACTURA A BOLETA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (239, 14, 'SPA-REM_21', 'SEGUIMEINTO PRUEBAS DE VENTA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (240, 12, 'PRU-PRE_02', 'PRUEBAS DE VENTA POR DONACIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (241, 10, 'PSIS-REM_49', 'EMITIR DOCUMENTO POR VENTA MILLONARIA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (242, 13, 'SPA-PRE_27', 'VALIDACIÓN - ERROR DEL SURTIDOR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (244, 14, 'SPA-REM_22', 'CAPACITACIÓN / ESTACIÓN DE SERVICIO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (245, 14, 'SPA-REM_23', 'GENERACIÓN DE CLAVE DINÁMICA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (246, 14, 'SPA-REM_24', 'SEGUIMIENTO DE ACTIVAVIÓN CREDITO ', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (247, 14, 'SPA-REM_25', 'CONFIGURACIONES DATA ADMIN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (248, 14, 'SPA-REM_26', 'VALIDACIONES CON EQUIPO MR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (249, 13, 'SPA-PRE_28', 'PROBLEMA CON EQUIPO MR', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (250, 2, 'I-PRE_19', 'IMPRESORA DE BAJA', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (251, 2, 'I-PRE_20', 'CAMBIO DE IMPRESORA POR REPOSICIÓN', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (252, 10, 'PSIS-REM_50', 'EXCEPCIÓN EL RECIBO NO TIENE UN DOCUMENTO ASOCIADO', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (253, 25, 'S-PRE_01', 'LEVANTAMIENTO DE INFORMACION', 0, 1, NULL, '2024-07-27 19:31:21');
INSERT INTO `tb_subproblema` VALUES (254, 1, 'ACT-PRE_PRUEBA', 'Problema de lectura prueba', 0, 1, NULL, '2025-01-31 16:39:31');

-- ----------------------------
-- Table structure for tb_sucursales
-- ----------------------------
DROP TABLE IF EXISTS `tb_sucursales`;
CREATE TABLE `tb_sucursales`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruc` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `nombre` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `cofide` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `direccion` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ubigeo` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(14) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `correo` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `v_visitas` tinyint(1) NOT NULL DEFAULT 0,
  `v_mantenimientos` tinyint(1) NOT NULL DEFAULT 0,
  `url_mapa` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ruc`(`ruc`) USING BTREE,
  CONSTRAINT `tb_sucursales_ibfk_1` FOREIGN KEY (`ruc`) REFERENCES `tb_empresas` (`ruc`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 355 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_sucursales
-- ----------------------------
INSERT INTO `tb_sucursales` VALUES (1, '20100111838', 'GESA CENTRAL', NULL, 'AV. VICTOR R HAYA DE LA TORRE 1949', '010101', ' ', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (2, '20100111838', 'GESA AREQUIPA', NULL, 'AV. AREQUIPA Nro. 900', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (3, '20100111838', 'GESA TABLADA', NULL, 'AV. PACHACUTEC 5295', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (4, '20100111838', 'GESA ULTRAGRIFOS', NULL, 'CAR.PANAMERICANA SUR NRO. 18.5', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (5, '20100111838', 'GESA VILLA', NULL, 'AV. MICAELA BASTIDAS # 1848', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (6, '20100111838', 'GESA HUACHO', NULL, 'CAR.PANAMERICANA NORTE NRO. 148', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (7, '20100111838', 'GESA BARRANCA', NULL, 'ANTIGUA CAR.PANAMERICANA NORTE NRO. 196 URB LAS PALMERAS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (8, '20100111838', 'GESA ICA', NULL, 'CAR.PANAMERICANA SUR KM. 299 (ALT. DEL KM 299 PANAMERICANA SUR)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (9, '20100111838', 'GESA ASIA', NULL, 'AV. PANAMERICANA SUR KM. 97.5 URB. ASIA (BOULEVARD ASIA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (10, '20100111838', 'GESA CHIMBOTE', NULL, 'PANAMERICANA NORTE KM 429.5', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (11, '20100111838', 'GESA PISCO', NULL, 'AV. FERMIN TANGUI # 220', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (12, '20100111838', 'GESA PUENTE PIEDRA', NULL, 'PANAMERICANA NORTE KM 25 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (13, '20100111838', 'GESA LURIN', NULL, 'AV. LIMA NRO. 2205 (ALT. POSTES 22 Y 23)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (14, '20101312519', 'LIMABANDA S.A.C.', NULL, 'AV. MARISCAL ORBEGOSO NRO. 120 URB. EL PINO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (15, '20101312519', 'LIMABANDA S.A.C.', NULL, 'AV. CARLOS IZAGUIRRE 220 INDEPENDENCIA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (16, '20101312519', 'LIMABANDA S.A.C.', NULL, 'AV VICTOR RAUL HAYA DE LA TORRE 250 URB SAN EDUARDO ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (17, '20127765279', 'E/S JAVIER PRADO', NULL, ' AV. JAVIER PRADO NRO. 1059 LIMA - LIMA - LA VICTORIA', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (18, '20127765279', 'E/S TAVIRSA', '', 'Av. Prol. Huaylas 600, Lima', '010101', '999999', NULL, 1, 1, NULL, 1, '2025-04-14 21:52:14', '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (19, '20127765279', 'E/S HIPODROMO', NULL, 'AV. JAVIER PRADO ESTE 4400', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (20, '20127765279', 'E/S ZARATE', NULL, ' AV. MALECON CHECA 1. ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (21, '20127765279', 'E/S RISSO', NULL, 'AV. ARENALES N°  2100', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (22, '20127765279', 'E/S LA MARINA', NULL, 'AV. LA MARINA 2185', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (23, '20127765279', 'E/S BAHIA', NULL, 'AV. AVIACION 1500 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (24, '20127765279', 'E/S CASTAÑOS', '', 'AV. JAVIER PRADO OESTE 1895', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (25, '20127765279', 'E/S BENAVIDES', NULL, 'AV. REPUBLICA DE PANAMA Y BENAVIDES S/N MIRAFLORES', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (26, '20127765279', 'E/S ESCOSA', NULL, 'AV. GUARDIA CIVIL 333 LA CAMPIÑA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (27, '20127765279', 'E/S HUIRACOCHA', NULL, 'AV. GREGORIO ECOBEDO 410 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (28, '20127765279', 'E/S MONTERRICO', NULL, 'AV. LA MOLINA 580 URB. RESIDENCIAL MONTERRICO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (29, '20127765279', 'E/S ARRIOLA', '', 'AV. NICOLAS ARRIOLA 710 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (30, '20127765279', 'E/S FELVERANA', NULL, 'AV. NICOLAS DE AYLLON 2162', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (31, '20127765279', 'E/S 28 DE JULIO', NULL, 'AV. 28 DE JULIO 220', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (32, '20127765279', 'E/S CANADA', NULL, 'AV. CANADA CDRA. 11 ESQ. VICTOR ALZAMORA ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (33, '20127765279', 'E/S BRASIL', '', 'AV. EL EJERCITO #110', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (34, '20127765279', 'E/S SUDAMERICANO', NULL, 'AV. COLONIAL N° 817', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (35, '20127765279', 'E/S FRUTALES', '', 'AV. LOS FRUTALES 994 URB. CAMINO REAL', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (36, '20127765279', 'E/S QUILCA', NULL, 'URB SAN ALONSO STA IRENE AV QUILCA / CALLE 10', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (37, '20127765279', 'E/S BREÑA', '', 'AV. TINGO MARIA 1711', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (38, '20127765279', 'E/S MAGDALENA', NULL, 'AV. DEL EJERCITO 101', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (39, '20127765279', 'E/S CASTILLA LIMA', '', 'AV. NICOLAS ARRIOLA 295', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (40, '20127765279', 'E/S LA PERLA', '', 'AV LA PAZ N° 2326', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (41, '20127765279', 'E/S TINGO MARIA', NULL, 'AV. TINGO MARIA 1194', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (42, '20127765279', 'E/S IGARZA', NULL, 'AV. TOMAS VALLE 1981', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (43, '20127765279', 'E/S AREQUIPA I', '', 'AV. AREQUIPA 3325', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (44, '20127765279', 'E/S CARMELO', '', 'AV. LA MARINA 3112 URB. MARANGA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (45, '20127765279', 'E/S ORRANTIA', NULL, 'AV. DEL EJERCITO 965', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (46, '20127765279', 'E/S SUCRE', NULL, 'AV. SUCRE 1070 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (47, '20127765279', 'E/S AULY ', NULL, 'AV. EL SOL KM 1 NRO. 33 URB. SAN CARLOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (48, '20127765279', 'E/S SAN LUIS', NULL, 'AV. NICOLAS AYLLON 1340', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (49, '20127765279', 'E/S SAN ISIDRO', NULL, 'AV. REPUBLICA DE PANAMA 3690-3696', '010101', ' 221-1340', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (50, '20127765279', 'E/S PIURA II', NULL, 'MZ A SUBLOTE A-1 SECTOR PARCELA J-ZONA INDSUTRIAL', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (51, '20127765279', 'E/S PERSHING', NULL, 'AV. FAUSTINO SANCHEZ 471', '010101', '463-0707', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (52, '20127765279', 'E/S SAN JUAN DE LURIGANCHO', NULL, 'AV. LURIGANCHO S/N MZA. B LOTE 10 (LOTE A-B-C-D-E Y F-1)', '010101', '7172549', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (53, '20127765279', 'E/S FAUCETT', NULL, 'AV ELMER FAUCETT 384', '010101', '4512408', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (54, '20127765279', 'E/S CHICLAYO', NULL, 'FND. LA ESPERANZA AV. EVITAMIENTO S/N', '010101', '612068', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (55, '20127765279', 'E/S ROSARIO', NULL, 'AV. TOMAS MARSANO 1008 ', '010101', '4452786', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (56, '20127765279', 'E/S ÑAÑA', NULL, 'AV NICOLAS AYLLON #1500', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (57, '20127765279', 'E/S FERRARI', NULL, 'AV. TOMAS MARSANO NRO 5010', '010101', '274-3090', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (58, '20127765279', 'E/S MONTREAL', NULL, 'AV. CAMINOS DEL INCA 2017', '010101', '275-0382', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (59, '20127765279', 'E/S GRAU', NULL, 'AV. GRAU NRO 1308 - ESQU JT HUANUCO 1101', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (60, '20127765279', 'E/S ARICA', '', 'AV. ARICA NRO. 499 URB. GARDEN CITY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (61, '20127765279', 'E/S SALAVERRY', '', 'JR. SALAVERRY 480 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (62, '20127765279', 'E/S ATE', '', 'AV. NICOLAS AYLLON MZ. A Lt. 1- CARRET. CTRAL KM. 16', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (63, '20127765279', 'E/S AREQUIPA II', '', 'AV. AREQUIPA NRO. 1890', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (64, '20127765279', 'E/S ANGAMOS', '', 'AV. ANGAMOS ESTE NRO 1401', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (65, '20127765279', 'E/S COLOMA', '', 'AV. DOMINGO COLOMA NRO 152', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (66, '20127765279', 'E/S PECSA COLONIAL III', '', 'AV, OSCAR R BENAVIDES NRO S/N (ESQUINA JR. PRESBITERO)', '010101', ' ', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (67, '20127765279', 'E/S PROCERES', '', 'AV. PROCERES DE INDEPENDENCIA NRO 701 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (68, '20127765279', 'E/S GARDENIAS', '', 'JR.BATALLA DE SAN JUAN 112 URB SANTA TERESA (ESQ. CON AV. MORRO SOLAR) ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (69, '20127765279', 'E/S COLONIAL II', '', 'AV MCAL OSCAR R. BENAVIDES NRO 300', '010101', '4237562', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (70, '20127765279', 'E/S MEXICO', '', 'AV NICOLAS AYLLON MZA 306 LOTE 1-2 URB SAN PABLO (LOTE 1 - 2 - 3)', '010101', '6602466', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (71, '20127765279', 'E/S PASEO LA REPUBLICA', '', 'AV. PASEO DE LA REPUBLICA NRO 5545 URB SAN ANTONIO', '010101', '2431334', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (72, '20127765279', 'E/S TOMAS VALLE', '', 'AV. TOMAS VALLE ESQ. AV BETA NRO SN(FUNDO GARAGAY BAJO)', '010101', '5338042', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (73, '20127765279', 'E/S WIESSE', '', 'MZA B LOTE 06 URB CANTO GRANDE ESQ. PROCERES DE LA INDEPENDENCIA', '010101', '3889296', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (74, '20127765279', 'E/S CALERA', '', 'AV. AVIACION NRO 4524 (ESQUINA CON AV. VILLARAN URB LOS SAUCES )', '010101', '2608688', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (75, '20127765279', 'E/S TOMAS MARZANO', '', 'AV. TOMAS MARSANO NRO. 4070 URB. 18 DE NOVIEMBRE', '010101', '448-2770', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (76, '20127765279', 'E/S CIRCUNVALACION', '', 'AV. CIRCUNVALACION 1411', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (77, '20127765279', 'E/S LA PAZ', '', 'AV. LA PAZ NRO 1200 URB. MIRAMAR', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (78, '20127765279', 'E/S BOLIVAR', '', 'AV BOLIVAR NRO 1020', '010101', '4602870', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (79, '20156278751', 'OFICINA BARRANCA', NULL, 'JR GALVEZ 587 BARRANCA', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (80, '20156278751', 'OFICINA CHANCAY', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (81, '20156278751', 'OFICINA PATIVILCA', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (82, '20156278751', 'OFICINA HUARAL', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (83, '20156278751', 'OFICINA HUACHO', NULL, 'CALLE BOLIVAR 222 CHANCAY', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (84, '20168217723', 'CILIAMSAC ATE', NULL, 'AV. LA MOLINA NRO. 448 URB. EL ARTESANO (BAJAR OVALO STA ANIT)', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (85, '20168217723', 'CILAMSAC SJM', NULL, ' Panamericana Sur KM 17.5 San Juan De Miraflores', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (86, '20210975862', 'OPERACIONES Y SERVICIOS SJM', NULL, 'CARRETERA PANAMERICANA SUR KM. 14 (ENTRE PTE ALIPO Y PTE HUMAMARCA)', '010101', '3231654789', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (87, '20210975862', 'OPERACIONES Y SERVICIOS CHORRILLOS', NULL, 'AV. CAMINOS DEL INCA MZA. N LOTE. 19 URB. SAN JUAN BAUTISTA DE V. (URB.SAN JUAN BAUTISTA DE VILLA)', '010101', '3231654789', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (88, '20325753821', 'RICSA ARRIOLA', NULL, 'AV. NICOLAS ARRIOLA NRO. 1003 URB. LA POLVORA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (89, '20325753821', 'RICSA CHACLACAYO', NULL, 'Km 22.7 URB LAS CASUARINAS DE CHACLACAYO - CHACLACAYO - LIMA', '010101', '3804764', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (90, '20492727661', 'EDS LIVORNO BAUZATE', NULL, 'AV. BAUZATE Y MEZA 568.ESQ RENOVACION', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (91, '20492727661', 'EDS LIVORNO ABTAO', NULL, 'JR. ABTAO NRO. 784 (ESQ. HIPOLITO UNANUE)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (92, '20512220836', 'FARMA SAN AGUSTIN SJM', NULL, 'CAL.JOSE TORRES PAZ NRO. 110 URB. CIUDAD DE DIOS ZONA A (FRENTE A LA POSTA CIUDAD DE DIOS)', '010101', '981578374', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (93, '20512220836', 'FARMA SAN AGUSTIN VMT', NULL, 'AV. LIMA 852 P.J. JOSE GALVEZ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (94, '20513567139', 'ALTA VIDDA SAN LUIS', NULL, 'CAL.FELIPE SANTIAGO SALAVERRY NRO. 341 (ALT CDRA 18 DE AV CIRCUNVALACION)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (95, '20513567139', 'ALTA VIDDA LOS OLIVOS', NULL, 'CALLE LOS HORNOS N# 149 URB. INDUSTRIAL NARANJAL', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (96, '20513567139', 'ALTA VIDDA SJL', NULL, 'AV. SANTA ROSA N# 942 URB. CANTO GRANDE 2DA ETAPA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (97, '20514304921', 'INTIFARMA MIRAFLORES', NULL, 'AV. ANGAMOS ESTE NRO. 252', '010101', '995 555 655', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (98, '20514304921', 'INTIFARMA LA PERLA', NULL, 'AV. LOS INSURGENTES NRO. 709 INT. 3 URB. CAP LUIS GERMAN ASTETE (ALT MINISTERIO DE MARINA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (99, '20514304921', 'INTIFARMA LOS OLIVOS', NULL, '	AV. NARANJAL NRO. 1486', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (100, '20514326496', 'UNO GAS  ICA ', NULL, 'Calle Fernando Leon Arechua Nro.281 -  URB. San Miguel ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (101, '20514326496', 'UNO GAS CHINCHA BAJA', NULL, 'Urb Predio Cerco La Viña Azabache - Lote 54 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (102, '20520786873', 'GASOCENTRO LIMA SUR  - Lurin', NULL, 'Av Lima 2100', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (103, '20520786873', 'GASOCENTRO LIMA SUR - Huaral', '', 'CAR. CHANCAY-HUARAL KM.7', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (104, '20605450475', 'EDS KM.9', NULL, 'AV. CARRETERA FEDERICO BASADRE KM. 9.00 (LATERAL DERECHO)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (105, '20605450475', 'EDS PAMPAYURAC', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (106, '20605129154', 'EDS GES', NULL, 'AV. ISABEL LA CATOLICA NRO. S/N URB. MATUTE (ESQUINA CON JR ANDAHUAYLAS)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (107, '20605427147', 'EDS KM. 4', NULL, 'AV. CENTENARIO KM. 4.100 KM. REF (AV. CENTENARIO KM. 4.100)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (108, '20404000447', 'EDS MONTECARLO', NULL, 'AV. SANTA ROSA MZA. A LOTE. 11 URB. COOPIP (LOTE 11 Y 12 ALT. AV. ALCIDES VIGO)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (109, '20508196475', 'PETROCENTRO YULIA S.A.C', NULL, 'AV. DE LA MARINA NRO. 2789 URB. MARANGA 1RA ET. (CRUCE CON AV.ESCARDO)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (110, '20508196475', 'PETROCENTRO YULIA S.A.C', NULL, 'AV. JAVIER PRADO OESTE NRO. 900 (ALT. DE SUPERMERCADO STA ISABEL)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (111, '20283756115', 'EDS AGUAYTIA', NULL, 'AV. Centenario Nro. 4.100 Coronel Portillo', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (112, '20404883918', 'GETIMSA PIMENTEL', NULL, 'AV. ENRIQUE PIMENTEL NRO. 116', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (113, '20404883918', 'GETIMSA RAYMONDI', NULL, 'AV. RAYMONDI NRO 1001.', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (114, '20404883918', 'GETIMSA AFILADOR', NULL, 'CAS. AFILADOR CAR. TINGO MARIA A HUANUCO 2.5', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (115, '10009635128', 'POLLERIA PIUHICHO', NULL, 'AV. NICOLAS DE AYLLON N 441 CHACLACAYO - LIMA - LIMA', '010101', '960948488', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (116, '10043210675', 'ESTACION DE SERVICIOS VICTORIA', NULL, 'CAR. MARGINAL S-D CPME', '010101', '987654321', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (117, '10090647879', 'SALCEDO GUEVARA NESTOR', NULL, 'CAR. CENTRAL NRO 16.5 URB. HUAYCAN', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (118, '10091479791', 'EDS EX SAN JUANITO', '', 'AV. DE LOS HEROES 1187-1189', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (119, '10199887608', 'QUISPE YUPANQUI SILVIO', NULL, 'MZ D27 LOTE 13 AA.HH BOCANEGRA ZONA 3 AV. QUILCA FRENTE TOTUS DE  AV. QUILCA', '010101', '13621677', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (120, '20101127614', 'MANUEL IGREDA Y JULIO RIOS S.R.L.', NULL, 'CAL.MONTERREY NRO. 341 INT. 502 LIMA - LIMA - SANTIAGO DE SURCO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (121, '20110623420', 'E/S INVERSIONES LUMARCO', NULL, 'CARRETERA CENTRAL KM 11.2 SANTA CLARA', '010101', '356-3047', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (122, '20124367850', 'INVERSIONES TRANSP. Y SERV. CINCO S.A.C.', NULL, 'AV. JAVIER PRADO ESTE NRO. 1059 URB. SANTA CATALINA (FRENTE AL COLG. SAN AGUSTIN)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (123, '20137926742', 'SERVICENTRO LOS ROSALES SA', NULL, 'AV. AYACUCHO NRO 140', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (124, '20138645607', 'GRIFO SANTO DOMINGO DE GUZMAN SRLTDA', NULL, 'AV. RAMIRO PRIALE LOTE. 23A ASC. DIGNIDAD NACIONAL (PARCELA L)', '010101', '3710079', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (125, '20161800920', 'LUBRIGAS S.R.LTDA.', NULL, 'Av. Nicolas Ayllon Nro. 3562 Fnd. Mayorazgo (Frente a Planta Qui lomica Suiza)', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (126, '20177941591', 'EDS ORFUSAC', '9139', 'AV. JAVIER PRADO ESTE 6651 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (127, '20127765279', 'E/S TRAPICHE', 'E159309', 'AV. HEROES DEL ALTO CENEPA NRO. 697', '010101', '5373121 / 7150', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (128, '20203530073', 'SERVICENTRO SAN HILARION S.A.', NULL, 'AV. FLORES DE PRIMAVERA NRO. 1988 URB. SAN HILARION (MZ B - LT.03 / CRUCE CON AV.CTO.GRANDE)', '010101', '13893224', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (129, '20298736820', 'INVERSIONES UCHIYAMA SRL', NULL, 'CAL.CAHUIDE NRO. 160 P.J. J C MARIATEGUI SECT SAN GABRIEL B', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (130, '20348303636', 'Herco San Luis', '9050', 'AV. NICOLAS ARRIOLA NRO. 3191 ', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (131, '20343883936', 'ESTACION DE SERVICIO NIAGARA SRL.', NULL, 'JR. ELVIRA GARCIA Y GARCIA NRO. 2790 (ALT.COLONIAL Y UNIVERSITARIA)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (132, '20345774042', 'SERVICENTRO AGUKI S.A.', NULL, 'AV. ELMER FAUCETT 5482', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (133, '20347869849', 'LAS AMERICAS EIRL', 'E159286', 'AV. DE LAS AMERICAS NRO. 1259 URB. BALCONCILLO (GRIFO PETROPERU)', '010101', '987817538', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (134, '20348303636', 'HERCO GAMBETA ', 'E159247', 'AV. NESTOR GAMBETA NRO. S/N EX FUNDO MARQUEZ (SUBLOTE 1 1-A CARRET.VENTANILLA KM 14.5) PROV. CONST. ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (135, '20371975561', 'REPRESENTACIONES JEMMS S.A.C.', NULL, 'AV. ALFREDO MENDIOLA NRO. 1085 URB. PALAO 2DA ETAPA', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (136, '20373831124', 'PITS II GNV', '', 'AV. MARIANO CORNEJO NRO. 1508 (POR LA PLAZA DE LA BANDERA)', '010101', '994077048', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (137, '20377674686', 'SERVICENTRO SMILE S.A.', NULL, 'AV. SEPARADORA INDUSTRIAL/ORFEBREROS 129', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (138, '20402786729', 'INVERSIONES SANTA ROSA E.I.R.L', NULL, 'JR. MOQUEGUA NRO. 398 INT. 7 P.J. FLORIDA BAJA', '010101', '994893599', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (139, '20425788192', 'CENTRO GAS DIEGO EIRL', NULL, ' AV. LA MOLINA NRO. 401 URB. VULCANO (LETRERO MGAS - FRENTE GRIFO MOBIL)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (140, '20448556663', 'FARMACIA SAN FRANCISCO S.A.C.', NULL, 'JR. HUANCANE 721 ', '010101', '051-336596', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (141, '20452799368', 'ESTACION FINLANDIA E.I.R.L.', NULL, 'AV. SIETE MZA. 9 LOTE. 02-A (ESQUINA DE AV. SIETE Y FINLANDIA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (142, '20459020137', 'MARKET LAS BELENES S.A.C', NULL, 'JR. EL POLO 493 URB EL DERBY DE MONTERRICO', '010101', '966833946', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (143, '20478967111', 'ESTACION DE SERVICIO LURIN', NULL, 'MZ. C. LT 2 URB. LOS HUERTOS DE VILENA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (144, '20486255171', 'E/S CORPORACION RIO BRANCO', NULL, 'CAR.PANAMERICANA NORTE KM. 92.5 C.P. CHANCAYLLO (BARRIO SAN JUAN PASANDO EL PUENTE)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (145, '20491287544', 'CLINICA CORAZON DE JESUS SAC', NULL, '	AV. MARISCAL BENAVIDES NRO. 565', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (146, '20492197417', 'JE OPERADORES SAC', '', 'AV. NESTOR GAMBETA KM. 7.10 MZA. B-6 LOTE. 4 COO. VIVIENDA DE TRAB. ENAPU - CALLAO - CALLAO', '010101', '5342336', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (147, '20492314154', 'INVERSIONES EBENEZER S.R.L', NULL, 'AV. 4 MZA. B4 LOTE. 03 P.J. NESTOR GAMBETTA BAJA ESTE (COSTADO DEL MERCADO ROJO GAMBETTA)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (148, '20492920666', 'GASBEL', NULL, 'Av Micaela Bastidas cruce con 200 millas', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (149, '20493089570', 'ESTACION DE SERVICIOS H & A', NULL, ' AV. UNIVERSITARIA NRO. S/N (CDRA 51 ESQUINA CON LA CALLE A), LOS OLIVOS, LIMA.', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (150, '20493091396', 'GASOCENTRO PUENTE NUEVO S.A.C.', NULL, 'AV. JOSE CARLOS MARIATEGUI N° 2397', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (151, '20494793521', 'ESTACION EL OVALO E.I.R.L.', '', 'AV. F. LEON DE VIVEIRO - Carr. Panamericana Sur 311', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (152, '20499102071', 'ALIMENTOS SELECTOS CAZTELLANI S.A.', NULL, 'AV. ARAVICUS NRO. 228 URB. TAHUANTISUYO (KM.5 AV.TUPAC AMARU C/CARLOS IZAS)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (153, '20501688593', 'CABLE VIDEO PERU S.A.C.', NULL, 'Jr. CONRAY GRANDE Nro. 4901 - Urb. PARQUE NARANJAL - Cdra.13 Av. NARANJAL', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (154, '20502716761', 'GAS ESCORPIO S.R.L.', NULL, 'AV. LA MOLINA NRO. 401 URB. VULCANO (ALT. OVALO SANTA ANITA) LIMA - LIMA - ATE', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (155, '20502825624', 'ESTACION DE SERVICIOS SAN JUANITO S.A.C.', NULL, 'AV. HEROES NRO. 1109 (ALT.HOSPITAL MARIA AUXILIADORA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (156, '20505133430', 'PETROCARGO S.A.C', NULL, 'AV.GERARDO UNGER MZ. D LT. 26 INDEPENDENCIA ', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (157, '20506467854', 'CORPORACION JULCAN S.A.', NULL, 'AV. PROCERES DE LA INDEPENDEN NRO. 2556 URB. LOS ANGELES', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (158, '20511053031', 'ESTACION DE SERVICIO  GIO SAC', NULL, 'AV. PACHACUTEC NRO. 3859 P.J. CESAR VALLEJO ', '010101', '7190235', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (159, '20511193045', 'EDS EVEREST', NULL, 'AV. AVIACION NRO. 4285 (ALT.CDRA 42 AV.AVIACION)', '010101', '2718701', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (160, '20512853529', 'ATS AMERICA SAC', NULL, 'AV. LIMA SUR NRO. 895 CHOSICA LIMA - LIMA â€“ LURIGANCHO', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (161, '20514636843', 'ESTACIONES DE SERVICIOS PETRO WORLD', NULL, 'AV. VENEZUELA ESQUINA CON AV. RIVA AGUERO ', '010101', '464-1114', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (162, '20516035758', 'GASOCENTRO NORTE SOCIEDAD ANONIMA CERRADA', NULL, 'AV. GERARDO UNGER NRO. 3301 URB. HABILIT.INDUST.PAN.NORTE (Fte.aq Comisaria Indep./ex GrifoMosquito)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (163, '20517103633', 'AJ GROUP INVERGAS SAC', NULL, 'AV. SANTIAGO DE CHUCO NRO. 501 COO. UNIVERSAL (ESTACION DE SERVICIO)', '010101', '993059907', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (164, '20517117421', 'SHICHI-FUKU CORPORATION', NULL, 'AV. CANADA NRO. 298 URB. SANTA CATALINA', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (165, '20517735605', 'LUXOR PHARMACEUTICAL', NULL, 'AV. CESAR VALLEJO 895. ', '010101', '  ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (166, '20517855252', 'INVERSIONES DUVAL', NULL, ' AV. ALFREDO MENDIOLA NRO. 6200 INT. 101 URB. MOLITALIA (CRUCE CON AVENIDA MEXICO)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (167, '20518664019', 'AMEL PHARMA', NULL, 'JR. PARURO NRO. 926 INT. 381 (GALERIA CAPON CENTER) ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (168, '20518960688', 'PITS II GNVsss', '', ' AV. NICOLAS DE PIEROLA NRO. 800 (MZ.H1 LT.16, ESQUINA CON AV. VILLA MARIA)', '010101', '994077048', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (169, '20521111888', 'EDS EGAR S.A.C.', NULL, 'AV. ALFREDO MENDIOLA NRO. 3973 INT. 201 URB. MICAELA BASTIDAS - LIMA - LIMA - LOS OLIVOS', '010101', '01 2502164', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (170, '20521431955', 'ECO TRADING S.A.C', NULL, 'AV. REPUBLICA ARGENTINA NRO. 798 URB. ZONA INDUSTRIAL (ALT. DE C.C. LA CACHINA) ', '010101', '971150078', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (171, '20524016070', 'DELTA  San Martin', '', 'AV. ALFREDO MENDIOLA NRO. 700 URB. INGENIERIA (ALT. CRUCE AV. HABICH E INGENIERIA A 2 C)', '010101', '965461201', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (172, '20524249848', 'CENTROGAS VISTA ALEGRE', NULL, 'Av. Nicolas Ayllon Nro. 4706 Fnd. Vista Alegre', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (173, '20524359601', 'MABA FARMA', NULL, 'MZA. C LOTE. 4 JAZMIN DE OQUENDO (COSTADO MERCADO LA ALBORADA)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (174, '20530743919', 'DIESEL MAX', NULL, ' CAR.MAZO-VEGUETA NRO. SN C.P. LA PERLITA (GRIFO DIESEL MAX) ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (175, '20534525070', 'EDS TRIVEÑO', NULL, 'AV. MATIAS MANZANILLA-2DO PIS NRO. 625 INT. 04 (FRENTE AL HOSPITAL DEL SEGURO SOCIAL)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (176, '20535614548', 'OPERADORES DE ESTACIONES', NULL, 'CIRCUNVALACION NRO. 1386 (ALT MERCADO DE FRUTAS) LIMA - LIMA - LA VICTORIA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (177, '20538807037', 'HEVALFAR SRL', NULL, 'JR. PARURO NRO. 926 INT. 2072 GALERIA CAPON CENTER', '010101', '0', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (178, '20546454224', 'BOTIFARMA', NULL, 'JR. PARURO NRO. 926 INT. 2076', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (179, '20546818102', 'GRUPO AVTEC', NULL, 'CALLE MONTE GRANDE N° 109 OFICINA 307,  URB. CHACARILLA DEL ESTANQUE', '010101', '989054789', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (180, '20547011954', 'CENTROGAS IQUITOS', NULL, 'AV. IQUITOS NRO. 983 (CRUCE CON AV CATOLICA)', '010101', '949800092', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (181, '20548279039', 'CORGAS', NULL, 'LAS TORRES NRO. 497 URB. LOS SAUCES', '010101', '937504719', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (182, '20548480214', 'CATAFARMA ', NULL, 'AV. JOSE SANTOS CHOCANO NRO. 104 P.J. VEINTIDOS DE OCTUBRE (AL COSTADO DEL HOSP. SAN JOSE)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (183, '20551112781', 'GRUVENI', NULL, '	JR. LOS ANTROPOLOGOS MZA. D LOTE. 4 COO. LA UNION (MODULO DE PODER JUDICIAL DE PROCERES)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (184, '20551297978', 'GRANDINO', NULL, 'CAL.LOS CEREZOS NRO. 291 URB. DE LA LOTIZ. CHILLON', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (185, '20551615856', 'INVERSIONES JIARA', NULL, 'AV. ESTEBAN CAMPODONICO NRO. 262 URB. SANTA CATALINA', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (186, '20555690534', 'DELTA ATE E', NULL, 'AV. NICOLAS AYLLON NRO. 3620 A.H. SANTA ILUMINATA (CARRETERA CENTRAL CRUCE AV ATE', '010101', '965451201', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (187, '20556597376', 'ESTACION DE SERVICIOS ANDAHUASI', NULL, 'AV. HUAURA - SAYAN LOTE. 100 (SECT. SANJUAN DE CAÃƒâ€˜AS.ESQUINA AV. RIO)', '010101', '974628921', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (188, '20557618920', 'CENTRAL PARIACHI', NULL, 'AV. NICOLAS AYLLON NRO. S/N SEMI RUSTICO PARIACHI PARCELA 10906 ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (189, '20562897128', 'COFARSA', NULL, 'AV. GNRAL MIGUEL IGLESIAS NRO. 947 INT. A-B ZONA D (FRENTE AL HOSPITAL MARIA AUX)', '010101', '987946819', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (190, '20565949731', 'CONSORCIO VITAFARMA', NULL, 'JR. PARURO NRO. 775 INT. 307 URB. BARRIOS ALTOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (191, '20566091306', 'NG FARMA', NULL, 'AV. SANTA ROSA NRO. 1046 APV. LOS CHASQUIS (2DO.PISO.)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (192, '20566149151', 'GASNOR', NULL, 'AV. ENCALADA 232 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (193, '20566149401', 'ESTACIONES DEL NORTE', NULL, 'CAR.PANAN NORTE KM. 1168 P.J. BARRIO LETICIA', '010101', '13600374', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (194, '20600251873', 'BALIAN', NULL, 'AV. LAS PRADERAS MZA. U LOTE. 29 URB. PRADERA DE STA ANITA II E', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (195, '20600534883', 'CORPORACION GANAJUR', NULL, 'JR. ANCASH 14142 BARRIOS ALTOS', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (196, '20600658311', 'ESTACION TRAPICHE ', NULL, 'CAL.MONTE GRANDE NRO. 109 DPTO. 307 URB. CHACARILLA DEL ESTANQUE LIMA - LIMA - SANTIAGO DE SURCO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (197, '20600868862', 'PETRO CALLAO', NULL, 'AV. ARGENTINA NRO. 498 URB. CHACARITAS PROV. CONST. DEL CALLAO - PROV. CONST. DEL CALLAO â€“ CALLAO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (198, '20600908627', '524 CONSULTING', NULL, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (199, '20601709148', 'EESS PETRO LUMARA I', '', 'AV.LAS LOMAS Mz.Q LT1A URB ZARATE SJL.', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (200, '20601790484', 'INVERSIONES LUMIPHARMA', NULL, 'JR. ANTONIO MIROQUESADA NRO. 806 INT. 403 (ESQ.JR PARURO909 EDF COM MRQS 4PISO 403A)', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (201, '20602003427', 'PETRO NAZCA', NULL, 'AV. PANAMERICANA NRO. 891 URB. VISTA ALEGRE (GRIFO PECSA)', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (202, '20602712363', 'CONSORCIO GAS DEL SUR', '', 'CAL. PANAMERICANA SUR KM. 33.5 URB. PREDIO LAS SALINAS', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (203, '20602772935', 'DISTRIBUCIONES SELMAC', NULL, 'JR. PARURO NRO. 926 INT. 212 URB. BARRIOS ALTOS ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (204, '20603012268', 'INVERSIONES RAMSAN', NULL, 'JR. PEDRO GARENZON NRO. 500 URB. MIGUEL GRAU (FRENTE AL CLUB DE TIRO-ANCON)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (205, '20603635711', 'A & B DROFAR INVERSIONES', NULL, 'JR. CUSCO NRO. 811 INT. 207 URB. BARRIOS ALTOS (ESPALDA DE RENIEC)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (206, '20603673485', 'MATIAS & ALEXA', NULL, 'JR. PARURO NRO. 926 INT. 345B GALERIA CENTRO COMERCIAL CAPON CENTER', '010101', '999999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (207, '20603821247', 'CORPORACION DISFARMED', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (208, '20603822359', 'DROGUERIA DISTRIBUIDORA PRIMED', NULL, 'Av. Gral. Miguel Iglesias Mz.g Lt.30 - AA.HH. Javier Heraud ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (209, '20603850913', 'A.J.C. NEGOCIOS', NULL, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (210, '20603906790', 'TRANSPORTES VALL E', NULL, 'CAL.ALVARADO NRO. 701 (KM. 11 DE LA AV. TUPAC AMARU)', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (211, '20604631379', 'MOVI PETROL ', NULL, 'LA ENCALADA NRO. 232 ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (212, '20604857105', 'CORPORACION AXELL', NULL, 'JR. HUANTA NRO. 944 INT. B URB. BARRIOS ALTOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (213, '20605002448', 'SERVICENTROS CELESTE 3', NULL, 'AV. QUILCA MZA. E LOTE. 29 URB.  AEROPUERTO  PROV. CONST. DEL CALLAO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (214, '20334129595', 'GRIFO SERVITOR', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (215, '20371826727', 'GRIFO MASTER', '', 'AV. ALFREDO MENDIOLA ESQ. CALLE 12', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (216, '20511172633', 'GRIFO DENVER', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (217, '20517053351', 'MASGAS', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (218, '20517231631', 'PANAMERICAN GAS TRADING', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (219, '20517700640', 'SIROCO MANCO CAPAC', '9125', 'Av.Manco Capac 301', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (220, '20538108295', 'DUOGAS', NULL, '-', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (221, '20020020000', 'DEMO GS', NULL, 'Condevilla', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (222, '12345678901', 'DEMO', NULL, 'AV. LT PLAZA NORTE', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (223, '20101127614', 'GRIFOSA LA MARINA', '', ' Av. La Marina 1305 - San Miguel  - Lima. / Dom. Fiscal : CAL.MONTERREY NRO. 373 INT. 701 URB. CHACARILLA DEL ESTANQUE LIMA - LIMA - SANTIAGO DE SURCO', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (224, '20519069262', 'OFICINA', '', 'AV. AUGUSTO B LEGUIA NRO. 307 COO. POLICIAL (ACONT DE AV. PERU -ANTES DE ZARUMILLA)', '010101', '9999999', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (225, '20348303636', 'ESTACION DE SERVICIOS HERCO S.A.C.', '', 'CAR.PANAMERICANA SUR NUEVA KM. 33.5 MZA. C LOTE. 14 SECTOR LAS SALINAS (GRIFO HERCO) ', '010101', '', NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (226, '20601351944', 'NEGOCIACIONES VALERIA & CHRIS S.A.C', NULL, 'AV. LIMA SUR NRO. S/N CHOSICA (LT A2-1 A1-9) ', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (227, '20392479687', 'INVERSIONES CORONACION S.R.L.', NULL, 'MZ E LT 10 SECTOR CENTRAL HUERTOS DE MANCHAY.', '010101', '942416053 - 66', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (228, '20515789961', 'GAMA INVERSIONES GENERALES S.A.C.', NULL, 'AV. QUILCA CUADRA 11 S/N MZA. E LOTE. 29 URB. AEROPUERTO PROV. CONST. DEL CALLAO', '010101', '  ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (229, '20602359981', 'PUNTO GAS S.A.C.', NULL, 'AV. MARISCAL OSCAR T. BENAVIDES NRO. 1657 URB. LA TRINIDAD (ALTURA CDRA. 16 EX COLONIAL) ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:58');
INSERT INTO `tb_sucursales` VALUES (230, '20604271089', 'ESTACION DE SERVICIOS VICTORIA L & K HNAS. S.A.C.', NULL, 'JR. LAS ACACIAS NRO. SN (1 CDRA DEL GRIFO HUANCABAMBA)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (231, '20522168182', 'FARMACIAS INTEGRALES DE LA SOLIDARIDAD S.A.C.', NULL, 'AV. ANGAMOS ESTE NRO. 716 (HOSPITAL DE SOLIDARIDAD DE SURQUILLO)', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (232, '20600765044', 'EUCEL S.R.L.', NULL, 'PJ. SAN MARTIN MZA. L LOTE. 3 CANTO CHICO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (233, '20605395482', 'DROGUERIA LMG FARMA PERU S.A.C.', NULL, 'UPIS SAN JOSE MZA. G-1 LOTE. 4 INT. 2 ', '010101', ' (01) 4045448 ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (234, '20514721280', 'GRUPO INTI S.A.C', NULL, 'Jr. Dante 893 - 899', '010101', '447-3684', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (235, '20338926830', 'GRIFO VALERIA VICTORIA S.A.C.', NULL, 'AV. RIVA AGUERO NRO. 411', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (236, '20484056227', 'OLEOCENTRO Y SERVICIOS SAN PEDRO EMPRESA INDIVIDUAL DE RESPONSABILIDAD LIMITADA', NULL, 'MZA. 30 LOTE. 01 A.H. SAN PEDRO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (237, '20546828671', 'DRUGSTORE SOL FARMA CORP. E.I.R.L.', NULL, 'CAL.CORACEROS NRO. 158', '010101', '4270669 / 9794', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (238, '20605344896', 'DROGUERIA DISTRIBUIDORA E IMPORTADORA VILLALEON E.I.R.L', NULL, 'JR. HUANTA NRO. 944 INT. B URB.  BARRIOS ALTOS', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (239, '10214719717', 'HILARIO CHAUCA GILMAR MARCIANO', NULL, 'AV. AVIACION S/N ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (240, '20605224505', 'BOTICA NUEVO PERU E.I.R.L.', NULL, 'AV. LOS PINOS NRO. 1414 URB. EL PINAR', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (241, '20604193711', 'INVERSIONES P & M FARMA E.I.R.L.', NULL, 'JR. MERCURIO NRO. 172 URB. SAN CARLOS II ETP', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (242, '10702311191', 'ALIAGA PEREZ LEONARDO CARLOS', NULL, 'S/N', '010101', ' 932 475 889', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (243, '20605809686', 'GRIFOS ESSA PUCALLPA S.A.C.', NULL, 'AV. FEDERICO BASADRE NRO. 298', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (244, '20100111838', 'GESA SOL DE ORO', NULL, 'AV. ALFREDO MENDIOLA 3550', '010101', ' ', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (245, '20503840121', 'EESS ARRIOLA', NULL, 'Av.Nicolas Arriola 2140', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (246, '20503840121', 'EESS IQUITOS', NULL, 'AV.IQUITOS 1100', '010101', '', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (247, '20503840121', 'EESS REVOREDO', NULL, 'Av. Elmer Faucett # 2900.', '010101', '', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (248, '20503840121', 'EESS TRIESTAR', NULL, 'Av. Proceres de la Independencia 1487 Urb. Los Jardines ', '010101', '', '', 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (249, '20503840121', 'EESS ANGAMOS', NULL, 'Av.Angamos Este  1715', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (250, '20503840121', 'EESS CANTOLAO', '', 'Av. Elmer Faucett 6000', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (251, '20503840121', 'EESS DUEÑAS\r\n', NULL, 'Av. Nicolas Dueñas # 606', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (252, '20503840121', 'EESS CHACARILLA', NULL, 'AV. PRIMAVERA  N° 1212', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (253, '20503840121', 'EESS LAS TIENDAS\r\n', NULL, 'Av. Andres Aramburu 904-908', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (254, '20503840121', 'EESS GRACCO\r\n', NULL, 'AV. LOS INGENIEROS MZA. E. LOTE. 14-2 (con Av. Separadora Industrial 2503)', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (255, '20546740154', 'PRECIO S.A.C.', NULL, 'AV. LA ENCALADA NRO. 232 URB. CENTRO COMERCIAL MONTERRICO (A 2 CUADRAS DE VIVANDA)', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (256, '20605764712', 'DROGUERIA JJC S.A.C.', NULL, 'CAL.LAS ANTILLAS NRO. 150 URB. ISLA VERDE', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (257, '20603587481', 'CONSORCIO EFE S.A.C.', NULL, 'AV. JOSE SANTOS CHOCANO NRO. 128 P.J. VEINTIDOS DE OCTUBRE ', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (258, '20557398628', 'DISTRIBUIDORA V & G FARMA S.R.L.', NULL, 'CAL.LOS PETALOS NRO. 189 URB. LA ACHIRANA', '010101', ' 987047349 / 9', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (259, '20553772436', 'GASOCENTRO ICA S.A.', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (260, '20100079179', 'ESTACION DE SERV BOLIVAR S A', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (261, '20605955704', 'PETRO LAS LOMAS S.A.C.', NULL, 'CAL.LOS PROCERES NRO. S/N PBLO. LAS LOMAS PIURA - PIURA - LAS LOMAS', '010101', '5342336', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (262, '20605720847', 'GRIFOS ESSA DE TINGO MARIA S.A.C', NULL, 'CAR.TINGO MARIA A HUANUCO NRO. 2.5 CAS. AFILADOR HUANUCO', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (263, '20565966589', 'CORPORACION VICTORIA PERUANA S.A.C', NULL, 'Calle Los Detectives Mz. F2 Lt. L1 Urb. Honor y Lealtad ', '010101', '(056) 283040', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (264, '10463388089', 'CAHUAPAZA APAZA FREDY EDWIN', NULL, 'Jr. Bolivar 153', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (265, '10411640855', 'AGUIRRE ZURITA CARLOS JAVIER', NULL, 'JR LEONCIO PRADO 1008', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (266, '10458612256', 'CORZO LAYME MARIA LORENA', NULL, 'Mz.188 Lt.10 AA.HH. Huascar', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (267, '10060507291', 'CORZO OCANA EFRAIN', NULL, '', '010101', '', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (268, '20549666362', 'CONSORCIO MEDICORP & SALUD S.A.C.', NULL, 'AV. LAS FLORES DE PRIMAVERA NRO. 1045 URB. LAS FLORES', '010101', '950279527', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (269, '20606082917', 'INVERSIONES FARMACEUTICA DIAZ S.A.C', NULL, '	JR. LAS CALEZAS NRO. 131 (ALTURA DE PLAZA VEA RIMAC)', '010101', '01-7151990', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (270, '20601136059', 'FARMA SOLUTIONS E.I.R.L.', NULL, 'Calle Loma Los Crisantemos 117', '010101', '960207974', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (271, '20100111838', 'GESA CAÑETE', NULL, 'Antigua Panamericana Sur m. 143.5', '010101', ' ', NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (272, '20100111838', 'GESA LA MOLINA', NULL, 'Av. La Molina Este 1596', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (273, '20127765279', 'E/S CHORRILLOS', '', 'Av.  Defensores del Morro 1391', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (274, '20335757697', 'WO SOCIEDAD ANONIMA', NULL, 'AV. GUILLERMO PRESCOTT NRO. 202 URB.  RISSO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (275, '20402173476', 'EESS SAN LUIS', NULL, 'Av. Nicolas Arriola Nº 2400', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (276, '20127765279', 'E/S SURCO', '9002', 'Av. Circunvalación 377 Urb. San Ignacio de Loyola Monterrico', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (277, '20127765279', 'E/S LA VICTORIA', '9081', 'Av. Isabel La Catolica 077', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (278, '20127765279', 'E/S CASTILLA PIURA I', '9127', 'Av. Luis Moreno S/N Miraflores I Etapa Castilla', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (279, '20602629750', 'E/S BELA', '', 'AV. PETIT THOUARS N° 3929 ESQUINA  CON CALLE CHACARILLA ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (280, '20451706323', 'ESTACION DE SERVICIOS VAMA SAC', NULL, 'AV. DE LOS HEROES NRO. 1187 URB.  SAN JUANITO  (AL FRENTE HOSP. MARIA AUXILIADORA)', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (281, '20451706323', 'ESTACION DE SERVICIOS VAMA SAC', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (282, '20127765279', 'E/S SAN BORJA', '9080', 'AV.AGUSTIN DE LA ROSA TORO N° 1312-1358 URB.JACARANDA II SECTOR 5 SAN BORJA LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (283, '20118180306', 'ESTACION CORMAR S.A.', '', 'AV. NICOLAS AYLLON NRO. 3456 URB.  VILLA SANTA ANITA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (284, '20118180306', 'E/S CORMAR', '9031', 'Carretera Central km 2.5', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (285, '20600658311', 'GASORED', 'E159311', 'Urb. Tungasuca II Mz. A Lt. 65 y 66 CARABAYLLO - LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (286, '20517700640', 'SIROCO FAUCETT', '9149', 'Av.Elmer Faucett 735 Callao', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (287, '20601709148', 'EESS PETRO LUMARA II', '9045', 'AV. CHINCHASUYO Nº 710 SJL - LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (288, '20487514749', 'E/S CHICLAYO', '', 'AV. FELIPE SALAVERRY NRO. 930 URB.  PATAZCA  (CERCA A GRIFO)', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (289, '20100111838', 'BELA', NULL, '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (290, '20457948060', 'XIN XING S.A.', NULL, 'JR. MIRO QUESADA NRO. 1308 LIMA LIMA LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (291, '20457948060', 'EESS XIN XONG ARGENTINA', '9041', 'Av. Argentina 898', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (292, '20127765279', 'OFIC. JAVIER PRADO', '', 'JAVIER PRADO S/N', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (293, '20100111838', 'GESA SANTA MARIA HUACHO', '', 'Panam. Norte Km 147.5', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (294, '20600465237', 'ES BREÑA', '9044', 'Jr. Huaraz Nº 1484  Lima Lima Breña', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (296, '20517453618', 'ES VMT', '9082', 'Av El Triunfo # 210 VMT', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (297, '20517767396', 'EDS / ESCOH ICA', 'E119215', 'Av. Fernando Leon de Vivero s/n ICA - ICA  ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (298, '20101285449', 'EDS SANTA CRUZ', '', 'AV. NARANJAL 299 NRO. C INT. 15 URB. NARANJAL-INDUSTRIAL LIMA - LIMA - INDEPENDENCIA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (299, '20515657119', 'EESS ASSA LA VICTORIA', '', 'AV. JAIME BAUZATE Y MEZA NRO. 1050 LIMA LIMA LA VICTORIA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (300, '20100111838', 'GESA TOMAS VALLE', '20100111838', 'Av. Tomas Valle Parcela F Sección C ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (301, '20348303636', 'HERCO LURIN', 'E159317', 'Panamericana Sur Km. 33.5. Urb. Predio Las Salinas - Lima', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (302, '20604303029', 'ADMINISTRACION DE GRIFOS L&L ONE S.A.C.', NULL, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (303, '20604303029', 'GRIFOS LLONE', 'E159336', 'Av. Universitaria Mz. B  Lt. Ref. Programa de vivienda santa Rosa de Carabayllo', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (304, '20604302863', 'ADMINISTRACION DE GRIFOS LEP S.A.C.', NULL, 'JR. MONTE ROSA NRO. 256 INT. 902 URB. CHACARILLA DEL ESTANQUE LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (305, '20604302863', 'EDS Picorp José Granda', '9016', 'Av. José Granda 3210 ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (306, '10704012964', 'POLO GOMEZ BRYAN MARTIN', '', 'PRUEBA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (307, '20127765279', 'E/S CUSCO', 'E159347', 'AV. LA CULTURA Nª 1506 SAN SEBASTIAN ', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (308, '20385649194', 'SERVICENTRO TITI S.A.C.', NULL, 'AV. PABLO PATRON NRO. 120 URB. SAN PABLO LIMA LIMA LA VICTORIA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (309, '20605899715', 'Go Orue', '9029', 'Av. El Derby # 254 int 704 Lima - Lima - SANTIAGO DE SURCO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (311, '20348303636', 'HERCO GNC', '', 'AV. NESTOR GAMBETA NRO. S/N EX FUNDO MARQUEZ (SUBLOTE 1 1-A CARRET.VENTANILLA KM 14.5) PROV. CONST.', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (312, '20601697531', 'EESS HA', '9193', 'AV. ALFREDO MENDIOLA NRO 6810 - SMP', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (313, '20127765279', 'E/S NAZCA', 'E159350', 'CARRETERA PANAMERICANA SUR KM. 446 SECTOR CURVE S/N NAZCA ICA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (314, '20553368902', 'EESS COLPE S.A.C.', 'E129318', 'CAL. REAL NRO. 588 CERCADO DE EL TAMBO JUNIN HUANCAYO EL TAMBO', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (315, '11111111111', 'Sucursal DEMO RC', '', 'demo', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (316, '20549745076', 'GASOCENTRO SANTA ANA S.A.C', 'E159293', 'AV. LOS PROCERES MZA. D-2 LOTE. 41 URB. SANTA ANA LIMA LIMA LOS OLIVOS', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (317, '20537901277', 'E/S PYX Gamarra', NULL, 'Av. Angélica Gamarra 1436', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (318, '20547799845', 'E/S Gas Surco', '9089', 'Av. Guardia civil Mz B Lote 1-2-3 con Calle Doña Mercedes', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (319, '20547799845', 'E/S GO MANCO CAPAC', '9089', 'AV. MANCO CAPAC NRO. 693 LIMA', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (320, '20565643496', 'EESS AVA SAN LUIS', '', 'Av. Nicolás Ayllón 1711', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (321, '10704012964', 'PRUEBA', '', 'DADASD', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (322, '2051199502', 'Gazel Juanes', '', 'Carretera panamerica sur, km 307', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (323, '20566238927', 'E/S CANTA CALLAO', 'E159351', ' AV. CANTA CALLAO MZA. D LOTE. 11 URB. HUERTOS DEL NARANJAL (A MEDIA CDRA. DEL CIRCUITO DE MANEJO)', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (324, '20492841014', 'GANAGAS S.A.C.', NULL, 'Av.Los Proceres 655 SANTIAGO DE SURCO LIMA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (325, '20507248676', 'VIJOGAS S.A.C.', NULL, 'AV. SANTA ROSA NRO. 610 URB. LOS SAUCES LIMA LIMA ATE', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (326, '20507248676', 'E/S Vijogas Ate', '', 'AV. SANTA ROSA NRO. 610 URB. LOS SAUCES', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (327, '20538289656', 'EESS CONSORCIO MICE', 'E159218', 'AV.MARIA REICHE N° S/N URB PACHACAMAC (ESQ. CON AV. SEPARADORA INDUSTRIAL) VILLA EL SALVADOR - LIMA', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (328, '20493143612', 'EESS MASUR', NULL, 'AV. DERBY NRO. 254 INT. 704 LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (329, '20510954999', 'EESS EL SOL', NULL, 'AV.EL SOL EQ.AV.GUARDIA C NRO. S/N LIMA LIMA CHORRILLOS', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (330, '20510957581', 'SERVICENTRO SHALOM SAC', NULL, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (331, '20510957581', 'EDS Shalom', '9056', 'Av. Naciones Unidas 1222 Cercado de Lima ', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (332, '20127765279', 'E/S GAMARRA II', '', 'Av. La Paz 1480  Esq. Con Agustin Gamarra', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (333, '20519251656', 'INVERSIONES GASSURCO S.A.C', NULL, 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (334, '20519251656', 'GASSURCO', '', 'AV. GUARDIA CIVIL MZ B LOTE 1-2-3 CON CALLE DOÑA MERCEDES', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (335, '20503840121', 'EESS CHUBUT', 'E159310', 'AV. VENEZUELA 4647', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (336, '20503840121', 'EESS COLONIAL A', '', 'Av. Óscar R. Benavides 930, Lima 15081', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (337, '20517710955', 'EESS GO PRIALE / SANTA CESILIA', '', 'AV. EL DERBY NRO. 254 INT. 704 URB. EL DERBY DE MONTERRICO LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (338, '20606092998', 'EESS JUDY', '', 'EXFUNDO NARANJAL PARCELA 59 ', '010101', NULL, NULL, 0, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (339, '20127765279', 'E/S PUENTE PIEDRA', '', 'Av. San Juan de Dios, Puente Piedra', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (340, '20593472244', 'ALLIN GROUP - JAVIER PRADO S.A.', '', 'JIRON PINOS 308', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (341, '20100111838', 'GESA BRASIL', '', 'Magdalena', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (342, '20565643496', 'AVA PETIT THOUARS', '', 'AV Arequipa', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (343, '20565643496', 'AVA BOLIVAR', '', 'Parque de las aguas', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (344, '20127765279', 'ALTOMOCHE', '00000', 'PAN. NORTE SUB LOTE 1B GRUPO A SECTOR V TRUJILLO LA LIBERTAD', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (345, '20492898717', 'ECOMOVIL SOCIEDAD ANONIMA CERRADA', NULL, 'AV. PROLONGACION PRIMAVERA NRO. 120 INT. A316 LIMA LIMA SANTIAGO DE SURCO', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (346, '20492898717', 'ECOMOVIL', '', 'SMP', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (347, '20492898717', 'ECOMOVIL', '', 'SMP', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (350, '20168217723', 'EESS MOLINA', '', 'Molina', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (351, '20168217723', 'EESS LURIN', '', 'MolinaLURIN', '010101', NULL, NULL, 0, 0, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (352, '20565643496', 'AVA PRIMAVERA', '', '-', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (353, '20565643496', 'Ava Finlandia ', '', 'Av. Finlandia MZ A- D urb San idelfonso', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');
INSERT INTO `tb_sucursales` VALUES (354, '20606092998', 'CORPORACION JUDY SAC', '', 'EX FUNDO NARANJAL PARCELA 59', '010101', NULL, NULL, 1, 1, NULL, 1, NULL, '2024-12-30 14:36:59');

-- ----------------------------
-- Table structure for tb_usuario_empresa
-- ----------------------------
DROP TABLE IF EXISTS `tb_usuario_empresa`;
CREATE TABLE `tb_usuario_empresa`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ruc_empresa` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `email_usuario` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `usuario` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contrasena` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pass_view` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `menu_usuario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `eliminado` tinyint(1) NULL DEFAULT 0,
  `estatus` tinyint(1) NULL DEFAULT 1,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ruc_empresa`(`ruc_empresa`) USING BTREE,
  CONSTRAINT `tb_usuario_empresa_ibfk_1` FOREIGN KEY (`ruc_empresa`) REFERENCES `tb_empresas` (`ruc`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_usuario_empresa
-- ----------------------------
INSERT INTO `tb_usuario_empresa` VALUES (1, '20127765279', NULL, '20127765279', '$2y$12$5uZJUCoBitJY01nivL.Fy.f22TsGLJrfNggAi49bexW04zNGSIq9u', '123456', NULL, 0, 1, NULL, NULL);

-- ----------------------------
-- Table structure for tb_vis_asignadas
-- ----------------------------
DROP TABLE IF EXISTS `tb_vis_asignadas`;
CREATE TABLE `tb_vis_asignadas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_visitas` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `creador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_visitas`(`id_visitas`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  CONSTRAINT `tb_vis_asignadas_ibfk_1` FOREIGN KEY (`id_visitas`) REFERENCES `tb_visitas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_vis_asignadas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_vis_asignadas
-- ----------------------------
INSERT INTO `tb_vis_asignadas` VALUES (1, 21, 20, 6, '2025-03-25', '10:22:30', '2025-03-25 10:22:30');
INSERT INTO `tb_vis_asignadas` VALUES (2, 22, 7, 6, '2025-03-26', '15:53:36', '2025-03-26 15:53:36');
INSERT INTO `tb_vis_asignadas` VALUES (3, 23, 15, 6, '2025-03-26', '16:02:24', '2025-03-26 16:02:24');
INSERT INTO `tb_vis_asignadas` VALUES (4, 24, 17, 6, '2025-03-26', '16:02:43', '2025-03-26 16:02:43');
INSERT INTO `tb_vis_asignadas` VALUES (5, 25, 16, 6, '2025-03-26', '16:03:07', '2025-03-26 16:03:07');
INSERT INTO `tb_vis_asignadas` VALUES (8, 28, 16, 1, '2025-03-26', '17:47:20', '2025-03-26 17:47:20');
INSERT INTO `tb_vis_asignadas` VALUES (9, 26, 14, 1, '2025-04-08', '12:08:27', '2025-04-08 12:08:27');
INSERT INTO `tb_vis_asignadas` VALUES (10, 27, 14, 1, '2025-04-08', '12:08:50', '2025-04-08 12:08:50');
INSERT INTO `tb_vis_asignadas` VALUES (11, 29, 14, 1, '2025-04-08', '18:04:47', '2025-04-08 18:04:47');

-- ----------------------------
-- Table structure for tb_vis_seguimiento
-- ----------------------------
DROP TABLE IF EXISTS `tb_vis_seguimiento`;
CREATE TABLE `tb_vis_seguimiento`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_visitas` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: Iniciado / 1: Finalizado',
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_visitas`(`id_visitas`) USING BTREE,
  INDEX `id_usuario`(`id_usuario`) USING BTREE,
  CONSTRAINT `tb_vis_seguimiento_ibfk_1` FOREIGN KEY (`id_visitas`) REFERENCES `tb_visitas` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tb_vis_seguimiento_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_vis_seguimiento_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_vis_seguimiento
-- ----------------------------
INSERT INTO `tb_vis_seguimiento` VALUES (22, 22, 1, '2025-03-26', '17:02:34', 0, '2025-03-26 17:02:34');
INSERT INTO `tb_vis_seguimiento` VALUES (23, 23, 1, '2025-03-26', '17:05:40', 0, '2025-03-26 17:05:40');
INSERT INTO `tb_vis_seguimiento` VALUES (24, 22, 1, '2025-03-26', '17:27:16', 1, '2025-03-26 17:27:16');
INSERT INTO `tb_vis_seguimiento` VALUES (25, 23, 1, '2025-03-26', '17:38:54', 1, '2025-03-26 17:38:54');
INSERT INTO `tb_vis_seguimiento` VALUES (26, 24, 1, '2025-03-26', '17:47:29', 0, '2025-03-26 17:47:29');
INSERT INTO `tb_vis_seguimiento` VALUES (27, 24, 1, '2025-03-26', '18:00:22', 1, '2025-03-26 18:00:22');
INSERT INTO `tb_vis_seguimiento` VALUES (28, 25, 1, '2025-04-01', '11:46:04', 0, '2025-04-01 11:46:04');
INSERT INTO `tb_vis_seguimiento` VALUES (29, 25, 1, '2025-04-01', '11:46:31', 1, '2025-04-01 11:46:31');
INSERT INTO `tb_vis_seguimiento` VALUES (30, 27, 1, '2025-04-08', '12:09:30', 0, '2025-04-08 12:09:30');
INSERT INTO `tb_vis_seguimiento` VALUES (31, 26, 1, '2025-04-08', '19:12:12', 0, '2025-04-08 19:12:12');
INSERT INTO `tb_vis_seguimiento` VALUES (32, 27, 14, '2025-04-08', '20:35:27', 1, '2025-04-08 20:35:27');
INSERT INTO `tb_vis_seguimiento` VALUES (33, 26, 14, '2025-04-10', '04:26:25', 1, '2025-04-10 04:26:25');
INSERT INTO `tb_vis_seguimiento` VALUES (34, 29, 14, '2025-04-11', '13:56:15', 0, '2025-04-11 13:56:15');
INSERT INTO `tb_vis_seguimiento` VALUES (35, 29, 14, '2025-04-11', '13:58:04', 1, '2025-04-11 13:58:04');

-- ----------------------------
-- Table structure for tb_visitas
-- ----------------------------
DROP TABLE IF EXISTS `tb_visitas`;
CREATE TABLE `tb_visitas`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sucursal` int(11) NOT NULL,
  `id_creador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time(0) NOT NULL,
  `estado` int(11) NULL DEFAULT 0,
  `contingencia` tinyint(1) NULL DEFAULT 0,
  `eliminado` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_sucursal`(`id_sucursal`) USING BTREE,
  INDEX `id_creador`(`id_creador`) USING BTREE,
  CONSTRAINT `tb_visitas_ibfk_1` FOREIGN KEY (`id_sucursal`) REFERENCES `tb_sucursales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_visitas_ibfk_2` FOREIGN KEY (`id_creador`) REFERENCES `tb_personal` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_visitas
-- ----------------------------
INSERT INTO `tb_visitas` VALUES (21, 46, 6, '2025-03-25', '10:22:30', 0, 0, 1, '2025-03-25 10:22:30');
INSERT INTO `tb_visitas` VALUES (22, 121, 6, '2025-03-27', '15:53:36', 2, 0, 0, '2025-03-26 15:53:36');
INSERT INTO `tb_visitas` VALUES (23, 284, 6, '2025-03-26', '16:02:24', 2, 0, 0, '2025-03-26 16:02:24');
INSERT INTO `tb_visitas` VALUES (24, 17, 6, '2025-03-26', '16:02:43', 2, 0, 0, '2025-03-26 16:02:43');
INSERT INTO `tb_visitas` VALUES (25, 18, 6, '2025-03-26', '16:03:07', 2, 0, 0, '2025-03-26 16:03:07');
INSERT INTO `tb_visitas` VALUES (26, 19, 6, '2025-03-26', '16:03:19', 2, 0, 0, '2025-03-26 16:03:19');
INSERT INTO `tb_visitas` VALUES (27, 121, 1, '2025-03-26', '17:47:08', 2, 0, 0, '2025-03-26 17:47:08');
INSERT INTO `tb_visitas` VALUES (28, 284, 1, '2025-03-26', '17:47:20', 0, 0, 0, '2025-03-26 17:47:20');
INSERT INTO `tb_visitas` VALUES (29, 121, 1, '2025-04-08', '18:04:47', 2, 0, 0, '2025-04-08 18:04:47');

-- ----------------------------
-- Procedure structure for GetCodeInc
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetCodeInc`;
delimiter ;;
CREATE PROCEDURE `GetCodeInc`()
BEGIN
    DECLARE last_cod VARCHAR(20);
    DECLARE nuevo_num INT;
    DECLARE nuevo_cod VARCHAR(20);

    -- Obtener el último código generado
		SELECT cod_incidencia INTO last_cod FROM tb_incidencias ORDER BY id_incidencia DESC LIMIT 1;

    -- Si no hay ningún código, inicializar con "INC-00000001"
    IF last_cod IS NULL THEN
        SET nuevo_cod = 'INC-00000001';
    ELSE
        -- Extraer el número del último código
        SET nuevo_num = CAST(SUBSTRING(last_cod, 5) AS UNSIGNED) + 1;
        -- Generar el nuevo código
        SET nuevo_cod = CONCAT('INC-', LPAD(nuevo_num, 8, '0'));
    END IF;

    -- Seleccionar y devolver el nuevo código generado
    SELECT nuevo_cod AS cod_incidencia;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for GetCodeOrds
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetCodeOrds`;
delimiter ;;
CREATE PROCEDURE `GetCodeOrds`(IN _serie CHAR(2))
BEGIN
    DECLARE last_cod VARCHAR(20);
    DECLARE nuevo_num INT UNSIGNED;
    DECLARE nuevo_cod VARCHAR(20);
    DECLARE num_str VARCHAR(20);

    -- Obtener el último código generado
    SELECT num_orden INTO last_cod FROM tb_orden_correlativo ORDER BY id_cor DESC LIMIT 1;

    -- Si no hay ningún código, inicializar con "STXX-00000001"
    IF last_cod IS NULL THEN
        SET nuevo_cod = CONCAT('ST', _serie, '-00000001');
    ELSE
        -- Extraer la parte numérica del código
        SET num_str = SUBSTRING_INDEX(last_cod, '-', -1);

        -- Manejar posibles valores inválidos
        IF num_str REGEXP '^[0-9]+$' THEN
            SET nuevo_num = CAST(num_str AS UNSIGNED) + 1;
        ELSE
            SET nuevo_num = 1; -- Si el formato no es válido, empezar desde 1
        END IF;

        -- Generar el nuevo código
        SET nuevo_cod = CONCAT('ST', _serie, '-', LPAD(nuevo_num, 8, '0'));
    END IF;

    -- Seleccionar y devolver el nuevo código generado
    SELECT nuevo_cod AS num_orden;
END
;;
delimiter ;

-- ----------------------------
-- Procedure structure for GetCodeOrdVis
-- ----------------------------
DROP PROCEDURE IF EXISTS `GetCodeOrdVis`;
delimiter ;;
CREATE PROCEDURE `GetCodeOrdVis`(IN _serie CHAR(2))
BEGIN
    DECLARE last_cod VARCHAR(20);
    DECLARE last_num INT;
    DECLARE nuevo_num INT;
    DECLARE nuevo_cod VARCHAR(20);

    -- Obtener el último código generado
    SELECT cod_orden_visita INTO last_cod 
    FROM tb_orden_visita_correlativo 
    ORDER BY cod_orden_visita DESC 
    LIMIT 1;

    -- Si no hay ningún código, inicializar con "VT-00000001"
    IF last_cod IS NULL THEN
        ALTER TABLE tb_orden_visita_correlativo AUTO_INCREMENT = 1;
        SET nuevo_cod = CONCAT('VT', _serie, '-00000001');
    ELSE
        -- Extraer solo la parte numérica del código (asumiendo que el formato es 'VT25-XXXXXXXX')
        SET last_num = CAST(SUBSTRING_INDEX(last_cod, '-', -1) AS UNSIGNED);
        
        -- Incrementar en 1
        SET nuevo_num = last_num + 1;
        
        -- Generar el nuevo código con formato adecuado
        SET nuevo_cod = CONCAT('VT', _serie, '-', LPAD(nuevo_num, 8, '0'));
    END IF;

    -- Devolver el nuevo código generado
    SELECT nuevo_cod AS cod_orden;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
