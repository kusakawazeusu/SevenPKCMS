DROP TABLE IF EXISTS `Machine`;
CREATE TABLE `Machine` (
`ID` int(11) AUTO_INCREMENT NOT NULL ,
`AgentID` int(11) NOT NULL DEFAULT '0',
`MachineName` int(11) NOT NULL DEFAULT '0',
`IPAddress` varchar(15) NOT NULL DEFAULT '0',
`SectionID` int(11) NOT NULL DEFAULT '0',
`MaxDepositCredit` int(11) NOT NULL DEFAULT '0',
`DepositCreditOnce` int(11) NOT NULL DEFAULT '0',
`MinCoinOut` int(11) NOT NULL DEFAULT '0',
`MaxCoinIn` int(11) NOT NULL DEFAULT '0',
`CoinInOnce` int(11) NOT NULL DEFAULT '0',
`CoinInBonus` int(11) NOT NULL DEFAULT '0',
`TwoPairsOdd` int(11) NOT NULL DEFAULT '1',
`ThreeOfAKindOdd` int(11) NOT NULL DEFAULT '2',
`StraightOdd` int(11) NOT NULL DEFAULT '3',
`FlushOdd` int(11) NOT NULL DEFAULT '5',
`FullHouseOdd` int(11) NOT NULL DEFAULT '7',
`FourOfAKindOdd` int(11) NOT NULL DEFAULT '50',
`STRFlushOdd` int(11) NOT NULL DEFAULT '100',
`FiveOfAKindOdd` int(11) NOT NULL DEFAULT '150',
`RoyalFlushOdd` int(11) NOT NULL DEFAULT '200',
PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

















