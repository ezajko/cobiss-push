<!DOCTYPE html>
<html>
    <head>
        <title>iosDevices</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
 
            });
            function sendPushNotification(id){
                var data = $('form#'+id).serialize();
                $('form#'+id).unbind('submit');
                $.ajax({
                    url: "../front/send_count_apn.php",
                    type: 'GET',
                    data: data,
                    beforeSend: function() {
                    	window.alert("before ");
                    },
                    success: function(data, textStatus, xhr) {
                          $('.txt_message').val("");
                          window.alert("ok "+data);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                    	window.alert("failed "+id);
                    }
                });
                return false;
            }
        </script>
        <style type="text/css">
            .container{
                width: 950px;
                margin: 0 auto;
                padding: 0;
            }
            h1{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 24px;
                color: #777;
            }
            div.clear{
                clear: both;
            }
            ul.devices{
                margin: 0;
                padding: 0;
            }
            ul.devices li{
                float: left;
                list-style: none;
                border: 1px solid #dedede;
                padding: 10px;
                margin: 0 15px 25px 0;
                border-radius: 3px;
                -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                -moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                color: #555;
            }
            ul.devices li label, ul.devices li span{
                font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 12px;
                font-style: normal;
                font-variant: normal;
                font-weight: bold;
                color: #393939;
                display: block;
                float: left;
            }
            ul.devices li label{
                height: 25px;
                width: 70px;
            }
            ul.devices li textarea{
                resize: none;
				display: block;
            }
            ul.devices li .send_btn{
                background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -webkit-linear-gradient(0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
                background: -moz-linear-gradient(center top, #0096FF, #005DFF);
                background: linear-gradient(#0096FF, #005DFF);
                text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
                border-radius: 3px;
                color: #fff;
            }

			.cb { clear: both; }
        </style>
    </head>
    <body>
        <?php
	 	
        include_once('../lib/DbFunctionsCommon.php');
        include_once('../lib/DbFunctionsAPN.php');
        
		//$apn = new DbFunctionsGCM();
        $apn = new DbFunctionsAPN();
        
        $tokens=$apn->getDistinctTokens();
		if ($tokens != false)
            $no_of_users = mysql_num_rows($tokens);
        else
            $no_of_users = 0;
        ?>
        <div class="container">
            <h1>iOS Devices</h1>
            <hr/>
            <ul class="devices">
                <?php
                if ($no_of_users > 0) {
                    ?>
                    <?php
                    while ($row = mysql_fetch_array($tokens)) {
                    	$tid=$row["tid"];
                        $t=$row["token"];
                        $b=$apn->getUnreadCount($t); ?>
                        <li>
                            <form id="<?php echo $tid ?>" name="" method="post" onsubmit="return sendPushNotification('<?php echo $tid ?>')">
                            	<label>token: </label> 
                            		<span><?php	echo $t."<br/>";?></span>
                                <div class="clear"></div>
                                <label>badge:</label> 
                                	<span><textarea rows="1" name="badge" cols="2" ><?php echo $b?></textarea></span>
                                	<input type="hidden" name="token" value="<?php echo $t ?>"/>
                                    <input type="submit" class="send_btn" value="Send now" onclick=""/>
                                    </span>
                            </form>
                        </li>
                    <?php }
                  }else { ?>
                    <li>
                        No Users Registered Yet!
                    </li>
                <?php } ?>
            </ul>
        </div>
    </body>
</html>