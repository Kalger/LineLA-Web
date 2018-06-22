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
                    </tr>
                        <?php
                    for($i=0; $i < $numRegister; $i++) {
                        $row = mysqli_fetch_assoc($resultRegister);
                        ?>

                        <tr>
                            <td>
                                <?php echo $row['registrarID']?>
                            </td>
                            <td>
                                <?php echo $row['registrarName']?>
                            </td>
                            <td>
                                <?php echo $row['intervieweeName']?>
                            </td>
                            <td>
                                <?php echo $row['time']?>
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
                <table border="1" width="700">
                    <tr>
                        <td>學員ID</td>
                        <td>護持學長</td>
                    </tr>
                        <?php
                    for($i=0; $i < $numSupport; $i++) {
                        $row = mysqli_fetch_assoc($resultSupport);
                        ?>

                        <tr>
                            <td>
                                <?php echo $row['memberID']?>
                            </td>
                            <td>
                                <?php echo $row['name']?>
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


                function inviteSupport(){

                    supportID = document.getElementById("supportID").value ;
                    supportName = document.getElementById("supportName").value;

                    Msg = supportName + "學長您好, 我們誠摯邀請您參與本期拜訪活動，請您於下方連結填寫與會意願及時段";

                    // console.log("supportID" + supportID);
                    // console.log("supportName" + supportName);


                     // publish
                    msg = new Paho.MQTT.Message("ID;name;" + Msg);
                    msg.destinationName = "support/" + supportID;
                    client.send(msg);
                }
                // called when the client connects
                function onConnect() {

                    console.log("onConnect");

                    // subscribe
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
                    console.log("onMessageArrived:"+message.payloadString);
                }
            </script>
    </body>
</html>
    