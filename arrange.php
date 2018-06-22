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
    </body>
</html>
    