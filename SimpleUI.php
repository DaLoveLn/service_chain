<!DOCTYPE html>
<html>
<head>
	<title> Simple Service Chain</title>
</head>
<body>

<?php
	include "GetServers.php";
	$Res =  json_decode (GetServers());
//	echo count($Res->servers);
//	echo $Res->servers[0]->addresses->public[0]->addr;

	if (count($Res->servers)) {
		// Open the table
        	echo "<table border=1> <tr> <td>Instance</td> <td>Image Name</td> <td>status</td> <td>Network</td> </tr>";

		for($i=0 ; $i<count($Res->servers) ; $i++)
		{
			echo "<tr>";
			echo "<td>{$Res->servers[$i]->name}</td>";
			$ImageDetail = GetImage($Res->servers[$i]->image->id);
			
			echo "<td>{$ImageDetail}</td>";
                        echo "<td>{$Res->servers[$i]->status}</td>";
                        echo "<td>{$Res->servers[$i]->addresses->public[0]->addr}</td>";

			echo "</tr>";
		}
	}
	echo "</table>";
		


?>

</body>

</html>
