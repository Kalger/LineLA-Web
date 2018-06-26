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
    
    <title>圓桌台北拜訪場次登記(七月)</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js" type="text/javascript"></script>
</head>

<body ng-app="module1" ng-controller="ctrl1">
    <div id="a" style="display:block">
        <div id="content" style="margin:20px;">            
            敬愛的學長，我愛您！
            <br>
            2018/07/04，圓桌教育基金會在台北有拜訪活動，敬請您參與。具體如下：
            <br>
            <br>
            一、拜訪地點
            <br>
            <br>
            圓桌台北辦公室 台北市內湖區行愛路69號5樓
            <br>
            <br>
            以下關於您的簡要信息，煩請填報。如有問題，請聯繫&nbsp; 034339199#1002 或 0918957005&nbsp; 李麗雪 敬上
            <br>
            <br>
            <br>
            非常感謝您！
            <br>
            <br>
            圓桌教育基金會 敬邀
        </div>
    </div>

    <div id="b" style="display:block" >
        <div style="margin:20px;">
            <form method="POST" action="processSessionRegister.php" >
                <p>
                    <b>護持者姓名</b><br />
                    <input name="supporterName" id="supporterName" placeholder="姓名" required />
                </p><br />

                <p>
                    <b>能參與拜訪日期及時段</b><br />
                        <fieldset data-role="controlgroup" id="visitdateselectorgroup" data-type="horizontal">
                        <?
                        for($i=0; $i < $numRegister; $i++) {
                            $row = mysqli_fetch_assoc($resultRegister);
                        ?>
                            <input type="checkbox" name="time[]" value="<?echo $row['time']?>"><?echo $row['time']?><br>
                        <?
                        }
                        ?>
                        </fieldset>
                </p><br />
                <input type="submit" value="請按此登記" />
            </form>
        </div> 
    </div>

    

</body>
</html>
