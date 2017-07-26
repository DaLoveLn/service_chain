<!DOCTYPE html>
<html>
<head>
	<title> Simple Service Chain</title>

	<style type="text/css">
          .end-element { background-color : #FFCCFF; }
        </style>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.0/raphael-min.js"></script>
        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="http://flowchart.js.org/flowchart-latest.js"></script>
        <!-- <script src="../release/flowchart.min.js"></script> -->
        <script>
            window.onload = function () {
                var btn = document.getElementById("run"),
                    cd = document.getElementById("code"),
                    chart;
                (btn.onclick = function () {
                    var code = cd.value;
		    code = code.replace(/\<br \/>/g,'');
                    if (chart) {
                      chart.clean();
                    }
                    chart = flowchart.parse(code);
                    chart.drawSVG('canvas', {
                      // 'x': 30,
                      // 'y': 50,
                      'line-width': 3,
                      'maxWidth': 3,//ensures the flowcharts fits within a certian width
                      'line-length': 50,
                      'text-margin': 10,
                      'font-size': 14,
                      'font': 'normal',
                      'font-family': 'Helvetica',
                      'font-weight': 'normal',
                      'font-color': 'black',
                      'line-color': 'black',
                      'element-color': 'black',
                      'fill': 'white',
                      'yes-text': 'yes',
                      'no-text': 'no',
                      'arrow-end': 'block',
                      'scale': 1,
                      'symbols': {
                        'start': {
                          'font-color': 'red',
                          'element-color': 'green',
                          'fill': 'yellow'
                        },
                        'end':{
                          'class': 'end-element'
                        }
                      },
                      'flowstate' : {
                        'past' : { 'fill' : '#CCCCCC', 'font-size' : 12},
                        'current' : {'fill' : 'yellow', 'font-color' : 'red', 'font-weight' : 'bold'},
                        'future' : { 'fill' : '#FFFF99'},
                        'request' : { 'fill' : 'blue'},
                        'invalid': {'fill' : '#444444'},
                        'approved' : { 'fill' : '#58C4A3', 'font-size' : 12, 'yes-text' : 'APPROVED', 'no-text' : 'n/a' },
                        'rejected' : { 'fill' : '#C45879', 'font-size' : 12, 'yes-text' : 'n/a', 'no-text' : 'REJECTED' }
                      }
                    });
                    $('[id^=sub1]').click(function(){
                      alert('info here');
                    });
                })();
            };
        </script>



</head>
<body>

<?php
	include "GetServers.php";
	session_start();
	$Res =  json_decode (GetServers());
//	$json_string = json_encode($Res, JSON_PRETTY_PRINT);
//	header('Content-Type: application/json');
//	Flight::json(($json_string);
//	echo $json_string . '<br>';
//	echo count($Res->servers);
//	echo $Res->servers[0]->addresses->public[0]->addr;
	$_SESSION['arrName'] = array();
	if (count($Res->servers)) {
		// Open the table
        	echo "<table border=1> <tr> <td>Instance</td> <td>Image Name</td> <td>status</td> <td>Network</td> </tr>";

		for($i=0 ; $i<count($Res->servers) ; $i++)
		{
			echo "<tr>";
			echo "<td>{$Res->servers[$i]->name}</td>";
			$ImageDetail = GetImage($Res->servers[$i]->image->id);
			array_push($_SESSION['arrName'],$Res->servers[$i]->name.'/'.$ImageDetail);

			echo "<td>{$ImageDetail}</td>";
                        echo "<td>{$Res->servers[$i]->status}</td>";
                        echo "<td>{$Res->servers[$i]->addresses->public[0]->addr}</td>";

			echo "</tr>";
		}
	}
	echo "</table>";
		


?>
<div style="display:none"><textarea id="code" style="width: 100%;" rows="11" >
<?php
session_start();
$count = 1;
//echo count($_SESSION['arrName']);
//echo $_SESSION['arrName'][0];
for($val=0;$val<count( $_SESSION['arrName']) ; $val++)
{
	echo nl2br(sprintf("op%s=>operation: %s|past:>http://www.google.com[blank]\n",$val,$_SESSION['arrName'][$val]));
//	echo nl2br('op'.$val.'=>operation: ' . $_SESSION['arrName'][$val] . '\n');
}
//array_push($_SESSION['arrName'],'CiaB');

for($val=0;$val<count( $_SESSION['arrName'])-1 ; $val++)
{
        echo nl2br(sprintf("op%s(right)->op%s\n",$val,$val+1));
//      echo nl2br('op'.$val.'=>operation: ' . $_SESSION['arrName'][$val] . '\n');
}


?>
</textarea></div>
<div  style="display:none"><button id="run" type="button">Show Graph</button></div>
<div id="canvas"></div>
</body>

</html>
