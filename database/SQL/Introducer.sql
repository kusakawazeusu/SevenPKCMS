DROP TABLE IF EXISTS `Introducer`;
CREATE TABLE `Introducer` (
`ID` int(11) AUTO_INCREMENT NOT NULL ,
`Name` varchar(255) NOT NULL DEFAULT '0',
`Gender` int(11) NOT NULL DEFAULT '2',
`Address` varchar(255) ,
`Cellphone` varchar(255) ,
`ReturnThreshold` int(11) ,
`ReturnCreditRate` int(11) ,
`CalcWeeks` int(11) ,
`Memo` varchar(255) ,
`Create_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;