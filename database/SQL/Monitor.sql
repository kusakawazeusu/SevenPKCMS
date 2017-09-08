SELECT
machine.ID,
machine.AgentID,
machine.MachineName,
machine.SectionID,
machinestatus.CurPlayer,
machinestatus.CurCredit,
machinestatus.CurCoinIn,
machinestatus.CurBet,
machinestatus.CurDealID,
machinestatus.`Status`,
player.`Name`,
player.Cellphone
FROM
machine JOIN (player Right OUTER JOIN machinestatus on machinestatus.CurPlayer = player.ID) on machinestatus.MachineID = machine.ID
ORDER BY
machine.ID ASC 