DROP TABLE IF EXISTS `PlayerAcc`;
CREATE TABLE `PlayerAcc` (
`PlayerID` int(11) NOT NULL DEFAULT '0',
`Games` int(11) NOT NULL DEFAULT '0',
`DoubleStar` int(11) NOT NULL DEFAULT '0',
`TotalCoinIn` int(11) NOT NULL DEFAULT '0',
`TotalWin` int(11) NOT NULL DEFAULT '0',
`update_at` timestamp(0) NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY (`PlayerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;