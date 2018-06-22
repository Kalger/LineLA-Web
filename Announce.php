<?php
    $conn = mysqli_connect('localhost','root','LineLA','LineLA');
    mysqli_set_charset($conn, 'utf8');
    $sql = "SELECT memberID FROM members";
    $i = 0;
    $results = array();
    function addi(){
        $i = $i+1;
    }
    //? get data
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0) {
        
	    while($row = mysqli_fetch_assoc($result)) {

            $memberID = $row['memberID'];
            array_push($results,$memberID);
        }
        $json = json_encode($results,JSON_UNESCAPED_UNICODE);
?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        <style>
            #title {
                color:lightslategray;
                font-family: Verdana;
                font-size: 300%;
                background-color: lightskyblue;
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
    </head>
    <body>
        <center>
            <div style="text-align:center; width: 30%; display: inline-block;">
                <h1 id="title" >每月公告</h1>
            </div>
            <br>
            <div style=" width: 30%; max-width:30%; display: inline-block;">
                <fieldset>
                    <legend>編輯公告</legend>
                    <br><br>
                        <textarea id="te" style=" width: 100%; height: 200px; resize:none;">敬邀學長們推薦 7/4 高雄拜訪場次： 140.116.82.34/visit/visitRegister.html</textarea>
                        <br><br>
                        <button onclick="send()">傳送</button>
                </fieldset>
            </div>
        </center>

    <script>
        // /*
        //Create a new Client object with your broker's hostname, port and your own clientId
        client = new Paho.MQTT.Client("140.116.82.34", 9001, "myclientid_" + parseInt(Math.random() * 100, 10));
        
        // set callback handlers
        client.onConnectionLost = onConnectionLost;
        client.onMessageArrived = onMessageArrived;

        // connect the client
        
        

        // called when the client connects
        function onConnect() {
            // Once a connection has been made, make a subscription and send a message.
            console.log("onConnect");
            client.subscribe("recommend/100672000367");
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
        var options = {
        
            //connection attempt timeout in seconds
            timeout: 3,

            userName: "LineLA",
            password: "LineLA",

            //Gets Called if the connection has successfully been established
            onSuccess: function () {
                onConnect();
                //console.log("onSuccess");
            },
        
        
            //Gets Called if the connection could not be established
            onFailure: function (message) {
                alert("Connection failed: " + message.errorMessage);
            }
            
        
        };
        client.connect(options);
        //Attempt to connect
        function send(){
            // alert(document.getElementById("te").value);
            len = "<? echo count($results) ?>";
            results = <? echo $json ?>;
            for(i = 0; i < len;i++){
                // i = "<? echo $i?>"
                memberID = results[i];
                // msg = new Paho.MQTT.Message("Hello;ddd;"+tt);
                msg = new Paho.MQTT.Message("Hello;ddd;"+document.getElementById("te").value);
                //msg.destinationName = "recommend/#";
                msg.destinationName = "recommend/"+memberID;
                client.send(msg);
            }
            
            // alert("ok");
        }
       

    </script>

    </body>
</html>

<?php
    } else {
        echo "0";
    }
    
    mysqli_close($conn);
?>