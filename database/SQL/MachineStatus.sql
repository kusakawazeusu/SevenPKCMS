DROP TABLE IF EXISTS `MachineStatus`;
CREATE TABLE `MachineStatus` (
`MachineID` int(11) NOT NULL DEFAULT '0',
`CurCredit` int(11) NOT NULL DEFAULT '0',
`CurCoinIn` int(11) NOT NULL DEFAULT '0',
`CurPlayer` int(11) NOT NULL DEFAULT '0',
`CurBet` int(11) NOT NULL DEFAULT '0',
`CurDealID` int(11) NOT NULL DEFAULT '0',
`Status` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`MachineID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;