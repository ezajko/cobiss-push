<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
 
            });
            function sendPushNotification(id){
                var data = $('form#'+id).serialize();
                $('form#'+id).unbind('submit');
                $.ajax({
                    url: "send_message_gcm.php",
                    type: 'GET',
                    data: data,
                    beforeSend: function() {
 
                    },
                    success: function(data, textStatus, xhr) {
                          $('.txt_message').val("");
                    },
                    error: function(xhr, textStatus, errorThrown) {
 
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
                float: left;
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
	 	require_once('../DbFunctionsGCM.php');
        include_once('../DbFunctionsAPN.php');
        include_once('../db_functions_common.php');
        
		$com = new DbFunctionsCommon();
        $gcm = new DbFunctionsGCM();
        //$apn = new DbFunctionsAPN();
		$users = $com->getAllUsers();
        if ($users != false)
            $no_of_users = mysql_num_rows($users);
        else
            $no_of_users = 0;
        ?>
        <div class="container">
            <h1>Android Devices</h1>
            <hr/>
            <ul class="devices">
                <?php
                if ($no_of_users > 0) {
                    ?>
                    <?php
                    while ($row = mysql_fetch_array($users)) {
                        $android=$gcm->getAllRegistrationIds($row["acr"],$row["memid"]);
						if ($android != false)
							$no_of_users = mysql_num_rows($android);
						else
							$no_of_users = 0;
						if ($no_of_users > 0)
						while ($device = mysql_fetch_array($android)) {
						?>
                        <li>
                            <form id="<?php echo $row["id"] ?>" name="" method="post" onsubmit="return sendPushNotification('<?php echo $row["id"] ?>')">
								<label>Acronim: </label> <span><?php echo $row["acr"] ?></span>
                                <div class="clear"></div>
                                <label>member:</label> <span><?php echo $row["memid"] ?></span>
                                <div class="clear"></div>
                                <label>device:</label> <span><?php
									
										echo $device["dev_id"]."<br/>";
									
									?><br/></span>
                                <div class="clear"></div>
                                
                                <div class="clear"></div>
                                <div class="send_container">
									<textarea rows="1" name="title" cols="25" class="txt_message" placeholder="Type title here"></textarea>
									<div class="cb"></div>
                                    <textarea rows="3" name="message" cols="25" class="txt_message" placeholder="Type message here"></textarea>
									<div class="cb"></div>
                                    <input type="hidden" name="dev" value="<?php echo $device["dev_id"] ?>"/>
                                    <input type="hidden" name="acr" value="<?php echo $row["acr"] ?>"/>
                                    <input type="hidden" name="memId" value="<?php echo $row["memid"] ?>"/>
                                    <input type="submit" class="send_btn" value="Send" onclick=""/>
                                </div>
                            </form>
                        </li>
                    <?php }
                }} else { ?>
                    <li>
                        No Users Registered Yet!
                    </li>
                <?php } ?>
            </ul>
        </div>
    </body>
</html>