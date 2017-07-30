<!DOCTYPE html>
<html>
<head>
	<title> Simple Service Chain</title>
	<!--<link rel="stylesheet" href="next/dest/css/next.css">
	<script src="next/dest/js/next.js"></script>
	<script src="Data.js"></script> -->

	<!-- <script>
	var topologyData = {                                      
    nodes: [                                              
        {"id": 0, "x": 400, "y": 100, "name": "12K-1"},   
        {"id": 1, "x": 500, "y": 100, "name": "12K-2"},   
        {"id": 2, "x": 600, "y": 100, "name": "Of-9k-03"},
    ],                                                    
    links: [                                              
        {"source": 0, "target": 1},                       
        {"source": 1, "target": 2},                       
        {"source": 2, "target": 3},                       
    ]                                                     
};                                                        

	</script> -->
	 <script src="Shell.js"></script> 

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
	echo nl2br(sprintf("op%s=>operation: %s|approved:>http://www.google.com[blank]\n",$val,$_SESSION['arrName'][$val]));
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

<?php
session_start();

echo <<<EOF

        <link rel="stylesheet" href="next/dest/css/next.css">
        <script src="next/dest/js/next.js"></script>
EOF;
//        <script src="Data.js"></script>


echo '<script>';
echo 'var topologyData = {                                      ';
echo '    nodes: [                                              ';

for($val=0;$val<count( $_SESSION['arrName']) ; $val++)
{
	echo sprintf('{"id": %d, "x": %d, "y": %d, "name": "%s"}',$val,400+$val*100,400+($val%2)*100,$_SESSION['arrName'][$val]);
	if($val+1 <count( $_SESSION['arrName']) )
		echo ',';
	else
		echo '],';
}
echo 'links: [';
for($val=0;$val<count( $_SESSION['arrName'])-1 ; $val++)
{
	echo sprintf('{"source": %d, "target": %d}',$val,$val+1);
	if($val+1 <count( $_SESSION['arrName'])-1 )
                echo ',';
        else
                echo ']';

}
echo '};';



/*
echo '        {"id": 0, "x": 400, "y": 500, "name": "mme"},   ';
echo '        {"id": 1, "x": 500, "y": 400, "name": "spgw"},   ';
echo '        {"id": 2, "x": 600, "y": 500, "name": "hss"}';

echo '    ],                                                    ';
echo '    links: [                                              ';
echo '        {"source": 0, "target": 1},                       ';
echo '        {"source": 1, "target": 2},                       ';
echo '        {"source": 2, "target": 3}                       ';
//echo '        {"source": 3, "target": 4}                       ';
echo '    ]                                                     ';
echo '};                                                        ';
*/
echo '</script>';





?>
<script src="Shell.js"></script>

</body>

</html>
