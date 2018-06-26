<?php
    $conn = mysqli_connect('localhost','root','LineLA','LineLA');
    mysqli_set_charset($conn, 'utf8');

    $sql = "SELECT registrarID, registrarName, intervieweeName, time FROM visitRegister";
    $resultRegister = mysqli_query($conn,$sql);
    $numRegister = mysqli_num_rows($resultRegister);


    
    $sql = "SELECT memberID, name FROM members WHERE memberID IN
            (SELECT memberID FROM groupMemberMap WHERE groupID = 'group_6')";
    $resultSupport = mysqli_query($conn,$sql);
    $numSupport = mysqli_num_rows($resultSupport);



    $sql = "SELECT supporterID,time FROM supportRegister WHERE supporterID IN
            (SELECT memberID FROM groupMemberMap WHERE groupID = 'group_6')";
    $resultSupporttime = mysqli_query($conn,$sql);
    $numSupporttime = mysqli_num_rows($resultSupporttime);
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <style>
            #title {
                color:lightslategray;
                font-family: Verdana;
                
                background-color: lightskyblue;
            }
            .column {
                float: left;
                width: 30%;
                padding: 5px;
            }

            /* Clear floats after image containers */
            .row::after {
                content: "";
                clear: both;
                display: table;
            }
        </style>
        
        <title>拜訪場次安排</title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    </head>
    <body>

        <center>
            <div style="text-align:center; display: inline-block;">
                <h1 id="title" >拜訪場次安排</h1>
            </div>
            <br>
        </center>
        <!-- 登記 -->
        <div>
            <div style= "font-size: 150%; display: inline-block;">
                登記狀況
            </div>
            <br>
            <br>
            <div style="display: inline-block;">

                登記場數 : <? echo $numRegister ?>
            </div>

            <?php
            if( $numRegister > 0 ){
                ?>

                <br>
                <br>
                <table border="1" width="700">
                    <tr>
                        <td>學員ID</td>
                        <td>登記者</td>
                        <td>受訪者</td>
                        <td>受訪時段</td>
                        <td>發送公告</td>
                    </tr>
                        <?php
                        $sessionary = mysqli_fetch_all($resultRegister);
                    for($i=0; $i < $numRegister; $i++) {
                        $row = $sessionary[$i];
                        ?>

                        <tr>
                            <td>
                                <?php echo $row[0]?>
                            </td>
                            <td>
                                <?php echo $row[1]?>
                            </td>
                            <td>
                                <?php echo $row[2]?>
                            </td>
                            <td>
                                <?php echo $row[3]?>
                            </td>
                            <td>
                                <button onclick="sendregistrarannounce('<? echo $row[0] ?>','<? echo $row[1] ?>','<? echo $row[3] ?>')">發送公告</button>
                            </td>
                        </tr>

                        <?php
                    }
                        ?>
                        
                </table>
                

                <?php
            }
                ?>
        </div>
        <br>
        <br>
        <br>
        <!-- 護持學長清單 -->
        <div>
            <div style= "font-size: 150%; display: inline-block;">
                護持學長清單
            </div>
            <br>
            <br>
            <div style="display: inline-block;">

                護持學長總人數 : <? echo $numSupport ?>
            </div>
            <?php
            if( $numSupport > 0 ){
                ?>

                <br>
                <br>
                <table border="1" width="900">
                    <tr>
                        <td>學員ID</td>
                        <td>護持學長</td>
                        <td>邀請護持</td>
                        <td>允許場次</td>
                        <td>發送公告</td>
                    </tr>
                    <?php
                        $index = mysqli_fetch_all($resultSupporttime);
                    for($i=0; $i < $numSupport; $i++) {
                        $row = mysqli_fetch_assoc($resultSupport);
                        $st = 0;
                        for($j=0; $j < $numSupporttime; $j++) {
                            $rowtime = $index[$j];
                            if($row['memberID'] == $rowtime[0]){
                                $st = 1;
                                break;
                            }
                        }
                            // echo $rowtime;
                    ?>

                        <tr>
                            <td>
                                <?php echo $row['memberID']?>
                            </td>
                            <td>
                                <?php echo $row['name']?>
                            </td>
                            <td>
                                <button onclick="inviteSupport('<? echo $row['memberID']?>','<? echo $row['name']?>')">邀請護持</button>
                            </td>
                            <td>
                                <?
                                if($st == 1){
                                    $ary = explode(",",$rowtime[1]);
                                    $len = count($ary);
                                    for($j = 0; $j < $len; $j++){
                                ?>
                                        <input type="checkbox" name="<? echo "time".$i ?>" value="<? echo $ary[$j] ?>"><? echo $ary[$j] ?>
                                <? 
                                    }
                                }
                                else{
                                    echo "尚未填寫";
                                }
                                ?>
                            </td>
                            <td>
                                <button onclick="sendsupportannounce('<? echo $row['memberID']?>','<? echo $row['name']?>','<? echo $i ?>')">發送公告</button>
                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                </table>
                

                <?php
            }
                ?>
        </div>
        
        <br><br>
        <hr size=5>
        <br><br>
        <!-- 訊息發送 -->
<!--        
        <div>
            <div style= "font-size: 150%; display: inline-block;">
                    發送訊息給護持學長
            </div>
            <div class="row">
                <div class="column">
                    <p>
                        <b>學員ID</b><br />
                        <input name="supportID" id="supportID" placeholder="學員ID" required />
                    </p><br />
                </div>
                <div class="column">
                    <p>
                        <b>學員姓名</b><br />
                        <input name="supportName" id="supportName" placeholder="姓名" required />
                    </p><br />
                </div>
                <div class="column">
                    <p>
                        <br />
                        <button onclick="inviteSupport()">邀請護持</button>
                    </p><br />
                </div>
            </div>
        </div>
         -->

        <!-- mqtt -->
        <script>
                
                var options = {
                    
                    //connection attempt timeout in seconds
                    timeout: 3,

                    userName: "LineLA",
                    password: "LineLA",

                    //Gets Called if the connection has successfully been established
                    onSuccess: function () {
                        console.log("ConnectSuccess");
                        onConnect();
                    },
                
                    //Gets Called if the connection could not be established
                    onFailure: function (message) {
                        console.log("Connection failed: " + message.errorMessage);
                    }
                };


                
                client = new Paho.MQTT.Client("140.116.82.34", 9001, "myclientid_" + parseInt(Math.random() * 100, 10));
                    
                // set callback handlers
                client.onConnectionLost = onConnectionLost;
                client.onMessageArrived = onMessageArrived;

                client.connect(options);
                // client.connect(options);


                function inviteSupport(supportID,supportName){
                    // alert(supportID+"\n"+supportName);
                    // supportID = document.getElementById("supportID").value ;
                    // supportName = document.getElementById("supportName").value;

                    Msg = supportName + "學長您好, 我們誠摯邀請您參與本期拜訪活動，請您於下方連結填寫與會意願及時段\n140.116.82.34/visit/sessionRegister.php";

                     // publish
                    msg = new Paho.MQTT.Message("ID;instruction;" + Msg);
                    msg.destinationName = "support/" + supportID;
                    client.send(msg);
                }
                
                function sendregistrarannounce(registrarID,registrarName,time){
                   ary_tp = time.split(" ");
                   //alert(ary_tp[0]+"\n"+ary_tp[1]+"\n"+ary_tp[2]+"\n"+registrarName+"\n"+registrarID);
                   Msg = "敬愛的" + registrarName + "學長，感謝您把愛傳出去！"+ary_tp[0]+"台北拜訪，請於"+ary_tp[2]+"與您邀約的受訪者一起準時抵達圓桌台北辦公室1：台北市內湖區行愛路69號5樓。\n請點擊下方的按鈕回覆ok或是提出異動。";
                   // publish
                   msg = new Paho.MQTT.Message("ID;annouArrange;" + Msg);
                   msg.destinationName = "recommend/" + registrarID;
                   client.send(msg); 
                }
                
                function sendsupportannounce(supportID,supportName,index){
                    var x = document.getElementsByName('time' + index);
                    len = x.length;
                    ary = [];
                    for(i = 0; i < len; i++){
                        if(x[i].checked){
                            ary.push(x[i].value);
                        }
                    }
                    len = ary.length;
                    for(i = 0; i < len; i++){
                        item = ary[i];
                        <?
                        for($i = 0; $i < $numRegister; $i++){
                            $temp = $sessionary[$i];
                            $ary_tp = explode(" ",$temp[3]);
                            $registrarID = $temp[0];
                            $registrarName = $temp[1];
                            $session = $ary_tp[1];
                        ?>
                            if (item == "<? echo $session ?>") {   
                                // support
                                Msg = "敬愛的" + supportName + "學長，感謝您一同參與！"+"<? echo $ary_tp[0] ?>"+"台北拜訪，請於"+"<? echo $ary_tp[2] ?>"+"準時抵達圓桌台北辦公室1：台北市內湖區行愛路69號5樓。\n請點擊下方的按鈕回覆ok或是提出異動。";                                   
                                // publish
                                msg = new Paho.MQTT.Message("ID;annouArrange;" + Msg);
                                msg.destinationName = "support/" + supportID;
                                client.send(msg);
                                
                            }
                        <?
                        }
                        ?>
                    }
                }
                // called when the client connects
                function onConnect() {

                    console.log("onConnect");

                    // subscribe
                    client.subscribe("recommend/+");
                    client.subscribe("support/+"); 
                }

                // called when the client loses its connection
                function onConnectionLost(responseObject) {
                    if (responseObject.errorCode !== 0) {
                        console.log("onConnectionLost:"+responseObject.errorMessage);
                    }
                }

                // called when a message arrives
                function onMessageArrived(message) {
                    for(i = 0; i < 100000000; i++);
                    console.log("onMessageArrived:"+message.payloadString);
                    str = message.payloadString;
                    tp = str.split(";");
                    if(tp[1]!="annouArrange" && tp[1]!="instruction"){ 
                        ary = tp[1].split(" ");
                        ID = tp[0];
                        type = ary[0];
                        topic = ary[1];
                        if(type == "replyModify"){
                            sendto(ID,topic,"已幫您取消，若是要更改時間請點入以下的連結填入新的時間。");
                        }
                    }
                }
                
                function sendto(ID,topic,message){
                    if(topic=="support"){
                        Msg = message + "http://140.116.82.34/visit/sessionRegister.php";
                        msg = new Paho.MQTT.Message("ID;instruction;"+Msg);
                        msg.destinationName = "support/" + ID;
                        client.send(msg);
                    }
                    else{
                        $.ajax({
                            url: 'modifyDB.php',
                            cache: false,
                            dataType: 'html',
                            type:'GET',
                            data: { id: ID},
                            error: function(xhr) {
                                Msg = "不好意思，資料庫連線失敗，請在嘗試一次。";
                                msg = new Paho.MQTT.Message("ID;annouArrange;"+Msg);
                                msg.destinationName = "recommend/" + ID;
                                client.send(msg);
                            },
                            success: function(response) {
                                Msg = message + "http://140.116.82.34/visit/visitRegister.html";
                                msg = new Paho.MQTT.Message("ID;instruction;"+Msg);
                                msg.destinationName = "recommend/" + ID;
                                client.send(msg);
                            }
                        });
                    }
                }
            </script>
    </body>
</html>
    
