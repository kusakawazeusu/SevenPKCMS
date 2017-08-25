DROP TABLE IF EXISTS `Player`;
CREATE TABLE `Player` (
`ID` int(255) AUTO_INCREMENT NOT NULL ,
`Name` varchar(255) NOT NULL DEFAULT '0',
`CardNumber` varchar(255) ,
`Balance` int(11) NOT NULL DEFAULT '0',
`CardType` varchar(255) ,
`IDCardNumber` varchar(255) NOT NULL DEFAULT '0',
`Cellphone` varchar(255) ,
`IntroducerID` int(11) NOT NULL DEFAULT '0',
`DocumentFront` blob(0) ,
`DocumentBack` blob(0) ,
`Photo` blob(0) ,
`Gender` int(1) NOT NULL DEFAULT '2',
`NickName` varchar(255) ,
`Career` varchar(255) ,
`Coming` int(1) ,
`ReceiveAd` int(1) ,
`Telephone` varchar(255) ,
PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;