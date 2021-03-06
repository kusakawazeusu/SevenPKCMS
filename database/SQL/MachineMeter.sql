DROP TABLE IF EXISTS `MachineMeter`;
CREATE TABLE `MachineMeter` (
`MachineID` int(11) NOT NULL DEFAULT '0',
`Games` int(11) NOT NULL DEFAULT '0',
`DoubleStar` int(11) NOT NULL DEFAULT '0',
`HighCard` int(11) NOT NULL DEFAULT '0',
`TwoPairs` int(11) NOT NULL DEFAULT '0',
`ThreeOfAKind` int(11) NOT NULL DEFAULT '0',
`Straight` int(11) NOT NULL DEFAULT '0',
`Flush` int(11) NOT NULL DEFAULT '0',
`FullHouse` int(11) NOT NULL DEFAULT '0',
`FourOfAKind` int(11) NOT NULL DEFAULT '0',
`RealFourOfAKind` int(11) NOT NULL DEFAULT '0',
`STRFlush` int(11) NOT NULL DEFAULT '0',
`RealSTRFlush` int(11) NOT NULL DEFAULT '0',
`FiveOfAKind` int(11) NOT NULL DEFAULT '0',
`RoyalFlush` int(11) NOT NULL DEFAULT '0',
`RealRoyalFlush` int(11) NOT NULL DEFAULT '0',
`BetCredit` int(11) NOT NULL DEFAULT '0',
`Credit` int(11) NOT NULL DEFAULT '0',
`RTP` int(11) NOT NULL DEFAULT '0',
`TotalCreditIn` int(11) NOT NULL DEFAULT '0',
`TotalCreditOut` int(11) NOT NULL DEFAULT '0',
`Throughput` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`MachineID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;