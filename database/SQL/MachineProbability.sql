DROP TABLE IF EXISTS `MachineProbability`;
CREATE TABLE `MachineProbability` (
`MachineID` int(11) NOT NULL DEFAULT '0',
`TwoPairs` int(2) NOT NULL DEFAULT '10',
`ThreeOfAKind` int(2) NOT NULL DEFAULT '10',
`Straight` int(2) NOT NULL DEFAULT '10',
`Flush` int(2) NOT NULL DEFAULT '10',
`FullHouse` int(2) NOT NULL DEFAULT '10',
`FourOfAKind` int(2) NOT NULL DEFAULT '10',
`STRFlush` int(2) NOT NULL DEFAULT '10',
`FiveOfAKind` int(2) NOT NULL DEFAULT '10',
`RoyalFlush` int(2) NOT NULL DEFAULT '10',
`RealFourOfAKind` int(2) NOT NULL DEFAULT '10',
`RealSTRFlush` int(2) NOT NULL DEFAULT '10',
`RealFiveOfAKind` int(2) NOT NULL DEFAULT '10',
`RealRoyalFlush` int(2) NOT NULL DEFAULT '10',
`Turtle` int(2) NOT NULL DEFAULT '10',
`DoubleStar` int(2) NOT NULL DEFAULT '10',
`BonusDifficulty` int(3) NOT NULL DEFAULT '10',
`WildCard` int(3) NOT NULL DEFAULT '10',
`Water` int(3) NOT NULL DEFAULT '10',
PRIMARY KEY (`MachineID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



















