<?php
    //? remember to modify IP 
    $conn = mysqli_connect('localhost','root','LineLA','LineLA');
    mysqli_set_charset($conn, 'utf8');

    $visitRegisterID = time();
    $registrarID = $_POST['memberID'];
    $intervieweeName = $_POST['intervieweeName'];
    $time = $_POST['time'];



    $sql = "INSERT INTO `visitRegister` (`visitRegisterID`, `registrarID`, `intervieweeName`, `time`) 
            VALUES ( '$visitRegisterID', '$registrarID', '$intervieweeName', '$time' );";


    $result = mysqli_query($conn,$sql);

    if($result == true){
        ?>
        <html>
            <head>
                <title>登記完成</title>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <style>
                    #title {
                        color:lightslategray;
                        font-family: Verdana;
                   
                        background-color: lightskyblue;
                    }
                </style>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
            </head>
            <body>
                <center>
                    <div style="text-align:center; display: inline-block;">
                        <h1 id="title" >登記完成</h1>
                    </div>
                    <br>
                    <div style="display: inline-block;">
                        <form>
                            <fieldset>
                                <legend>訊息</legend>
                                <br><br>
                                敬愛的學長，恭喜您成功登記拜訪!
                                <br><br>
                            </fieldset>
                        </form>
                    </div>
                </center>
                <script>
                
                    //Create a new Client object with your broker's hostname, port and your own clientId
                    
                    // var registerName, invitedName, memberID, time;
                    // var replyMsg;
                        

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

                    memberID = "<? echo $_POST['memberID'] ?>" ;
                    registerName = "<? echo $_POST['registrarName'] ?>" ;


                    // console.log("memberID : " + memberID);

                    replyMsg = registerName + "學長您好, 恭喜您成功登記拜訪";
                    client = new Paho.MQTT.Client("140.116.82.34", 9001, "myclientid_" + parseInt(Math.random() * 100, 10));
                        
                    // set callback handlers
                    client.onConnectionLost = onConnectionLost;
                    client.onMessageArrived = onMessageArrived;

                    client.connect(options);
                    // client.connect(options);

                    // called when the client connects
                    function onConnect() {

                        console.log("onConnect");

                        // subscribe
                        client.subscribe("recommend/" + memberID);

                        // publish
                        // msg = new Paho.MQTT.Message("ID;name;aaa");
                        msg = new Paho.MQTT.Message("ID;name;" + replyMsg);
                        msg.destinationName = "recommend/" + memberID;
                        client.send(msg);
                        
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
                    
                    

                    // replyMsg2 = registerName + "學長，您推薦的" + invitedName + "，將於" + time + "受訪，" + "請您與" + invitedName 
                    //             + "提早15分鐘抵達會場。地點：圓桌台北辦公室 台北市內湖區行愛路69號5樓\n" + 
                    //             "若您的形成有異動，請和我們聯絡。感謝您！ \n 圓桌教育基金會 敬上";

                    
            

                </script>
            </body>
        </html>

        <?php
    }else {
        echo "登記好友失敗,請再試一次";
    }   

?>