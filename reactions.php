<?php
class Reactions
{
    static function setReaction($postArray){
        global $con;
        $array = [];
        if (!empty($postArray)) {

            if (isset($postArray['name']) && $postArray['name'] != '') {
                $name = stripslashes(trim($postArray['name']));
            }else{
                $array['error'][] = "Name not set in array";
            }
            if (isset($postArray['email']) && filter_var($postArray['email'], FILTER_VALIDATE_EMAIL)) {
                $email = stripslashes(trim($postArray['email']));
            }else{
                $array['error'][] = "Invalid email format";
            }
            if (isset($postArray['date_added']) && filter_var($postArray['date_added'] != '')) {
                $date_added = stripslashes(trim($postArray['date_added']));
            }else{
                $array['error'][] = "Invalid date_added format";
            }

            if (isset($postArray['message']) && $postArray['message'] != '') {
                $message = stripslashes(trim($postArray['message']));
            }else{
                $array['error'][] = "Message not set in array";
            }

            if (empty($array['error'])) {

                $srqry = $con->prepare("INSERT INTO reactions (name,email,date_added,message) VALUES (?,?,?);");
                if ($srqry === false) {
                    prettyDump( mysqli_error($con) );
                }
                
                $srqry->bind_param('sss',$name,$email,$date_added,$message);
                if ($srqry->execute() === false) {
                    prettyDump( mysqli_error($con) );
                }else{
                    $array['succes'] = "Reaction save succesfully";
                }
            
                $srqry->close();
            }

            return $array;
        }
    }
    
    static function getReactions(){
        global $con;
        $array = [];
        $grqry = $con->prepare("SELECT id,name,email,date_added,message FROM reactions;");
        if($grqry === false) {
            prettyDump( mysqli_error($con) );
        } else{
            $grqry->bind_result($id,$name,$email,$date_added,$message);
            if($grqry->execute()){
                $grqry->store_result();
                while($grqry->fetch()){
                    $array[] = [
                        'id' => $id,
                        'name' => $name,
                        'email'=> $email,
                        'date_added' => $date_added,
                        'message' => $message
                    ];
                }
            }
            $grqry->close();
        }
        return $array;
    }
    static function redirect(){
        header("Location: http://localhost/Weekopdracht%20inlog/web-weekopdracht-2-comment-video-MarcMaassen/");
        die();
    }

}

