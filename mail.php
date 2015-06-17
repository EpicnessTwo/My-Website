<?php
	header('Content-type: application/json');
    
    function removeJunk($input)
    {
        return htmlentities(trim(strip_tags(stripslashes($input))), ENT_NOQUOTES, "UTF-8");
    }
    
    $whitelist = array(
            "name",
            "number",
            "email",
            "address_1",
            "address_2",
            "address_3",
            "address_4",
            "address_5",
            "message",
        );
        
    foreach ($_POST as $key=>$item)
    {
        if (!in_array($key, $whitelist))
        {
            $status = array(
		        'type'=>'error',
		        'message'=>'One or more of the boxes have been modified maliciously!'
		    );
		    echo json_encode($status);
            die();
        }
    }
    
    $return = array(
            "name" => removeJunk($_POST['name']),
            "ip" => $_SERVER['REMOTE_ADDR'],
            "email" => removeJunk($_POST['email']),
            
            "message" => removeJunk($_POST['message']),
            
            "subject" => "Website Feedback Response",
            "send" => "me@epickitty.uk",
        );
    
    
    $body = "
Name: " . $return['name'] . "
Email Address: " . $return['email'] . "
Message: " . $return['message'] . "

Additional Information:
IP Address: " . $return['ip'] . "

        ";
    if(mail($return['send'], "Website Feedback", $body, 'From: <'.$return['email'].'>'))
    {
        $status = array(
		'type'=>'success',
		'message'=>'Email sent!'
	        );
    } else {
        $status = array(
		'type'=>'fail',
		'message'=>'An Error Occurred!'
	        );
    }
    
    
    echo json_encode($status);
    die();
?>
