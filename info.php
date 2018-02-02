<?php
//ini_set("display_errors", 1);
//ini_set("track_errors", 1);
//ini_set("html_errors", 1);
//error_reporting(E_ALL);

//这玩意只能在Minecraft 1.7.X来食用

$SERVER_IP = "35.201.188.40"; //IPIPIPIPIPIPIPIPIPIP
$SERVER_PORT = "25565"; //读取各种东西的关键
$QUERY_PORT = "25560"; //query.port=""在你的server.properties里面

$HEADS = "3D"; //"normal" / "3D"
$show_max = "unlimited"; // how much playerheads should we display? "unlimited" / "10" / "53"/ ...
$SHOW_FAVICON = "on"; //"off" / "on"

$TITLE = "超级无敌服务器信息页";
$TITLE_BLOCK_ONE = "信息";
$TITLE_BLOCK_TWO = "玩家";

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$ping = json_decode(file_get_contents('http://api.minetools.eu/ping/' . $SERVER_IP . '/' . $SERVER_PORT . ''), true);
$query = json_decode(file_get_contents('http://api.minetools.eu/query/' . $SERVER_IP . '/' . $QUERY_PORT . ''), true);


if(empty($ping['error'])) { 
        $version = $ping['version']['name'];
        $online = $ping['players']['online'];
        $max = $ping['players']['max'];
        $motd = $ping['description'];
        $favicon = $ping['favicon'];
}

if(empty($query['error'])) {
        $playerlist = $query['Playerlist'];
}

?>
<!DOCTYPE html>
<html>
        <head>
        <meta charset="utf-8">
        <title><?php echo htmlspecialchars($TITLE); ?></title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
            <link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
            <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
            <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
            <script language="javascript">
                   jQuery(document).ready(function(){
                         $("[rel='tooltip']").tooltip();
             });
                </script>
            <style>
            /*Custom CSS Overrides*/
            body {
                      font-family: 'Lato', sans-serif !important;
            }
            </style>
    </head>
    <body>
        <div class="container">
        <h1><?php echo htmlspecialchars($TITLE); ?></h1><hr>       
                <div class="row">
                        <div class="col-md-4">
                                <h3><?php echo htmlspecialchars($TITLE_BLOCK_ONE); ?></h3>
                                <table class="table table-striped">
                                        <tbody>
                                                <tr>
                                                        <td><b>IP</b></td>
                                                        <td><?php echo $SERVER_IP; ?></td>
                                                </tr>
                                        <?php if(empty($ping['error'])) { ?>
                                                <tr>
                                                        <td><b>版本</b></td>
                                                        <td><?php echo $version; ?></td>
                                                </tr>
                                        <?php } ?>
                                        <?php if(empty($ping['error'])) { ?>
                                                <tr>
                                                        <td><b>玩家</b></td>
                                                        <td><?php echo "".$online." / ".$max."";?></td>
                                                </tr>
                                        <?php } ?>
                                                <tr>
                                                        <td><b>状态</b></td>
                                                        <td><?php if(empty($ping['error'])) { echo "<i class=\"fa fa-check-circle\"></i> 服务器在线"; } else { echo "<i class=\"fa fa-times-circle\"></i> 服务器不在线";}?></td>
                                                </tr>
                                        <?php if(empty($ping['error'])) { ?>
                                        <?php if(!empty($favicon)) { ?>
                                        <?php if ($SHOW_FAVICON == "on") { ?>
                                                <tr>
                                                        <td><b>图标</b></td>
                                                        <td><img src='<?php echo $favicon; ?>' width="64px" height="64px" style="float:left;"/></td>
                                                </tr>
                                        <?php } ?>
                                        <?php } ?>
                                        <?php } ?>
                                        </tbody>
                                </table>
                        </div>
                        <div class="col-md-8" style="font-size:0px;">
                                <h3><?php echo htmlspecialchars($TITLE_BLOCK_TWO); ?></h3>
                                <?php
                                if($HEADS == "3D") {
                                        $url = "https://cravatar.eu/helmhead/";
                                } else {
                                        $url = "https://cravatar.eu/helmavatar/";
                                }

                                if(empty($query['error'])) {
                                        if($playerlist != "null") { //1
                                                $shown = "0";
                                                foreach ($playerlist as $player) {
                                                        $shown++;
                                                        if($shown < $show_max + 1 || $show_max == "unlimited") {
                                                ?>
                                                                <a data-placement="top" rel="tooltip" style="display: inline-block;" title="<?php echo $player;?>">
                                                                <img src="<?php echo $url.$player;?>/50" size="40" width="40" height="40" style="width: 40px; height: 40px; margin-bottom: 5px; margin-right: 5px; border-radius: 3px; "/></a>
                                        <?php         }
                                                }
                                                if($shown > $show_max && $show_max != "unlimited") {
                                                        echo '<div class="col-md-8" style="font-size:16px; margin-left: 0px;">';
                                                        echo "and " . (count($playerlist) - $show_max) . " more ...";
                                                        echo '</div>';
                                                }
                                        } else {
                                                echo "<div class=\"alert alert-info\" style=\"font-size:16px;\"> There are no players online at the moment! <i class=\"fa fa-frown-o\"></i></div>";
                                        }
                                } else {
                                        echo "<div class=\"alert alert-danger\" style=\"font-size:16px;\"> 你的服务器尚未开启query端口，请在server.properties里面开启 <i class=\"fa fa-meh-o\"></i></div>";
                                } ?>
                        </div>
                </div>
        </div>
        </body>
</html>

