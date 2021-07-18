<?php
class Common1{

	 static function select($selected = 0){
        global $con;
        $child = get_called_class();
        $entity = strtolower($child);
        
        if($child == "Student")    
            $chile="user";
        else $subquery = "";

        $result = $con->query("select * {$subquery} from $child ");

        if($result and $result->num_rows > 0){  ?>
            <select name="<?=$entity?>">
                <option value="0" <?= $selected == 0 ? 'selected="selected"' : ''?> >select <?= $selected ?></option>
                <?php
                while($row = $result->fetch_assoc()){ 
                     ?>
                    <option value="<?=$row['id']?>" <?=$selected == $row['title'] ? 'selected="selected"' : ''?> ><?=$row['title']?></option>
                <?php } ?>            
            </select><?php
        }
        else    {echo "No records found";
        }      
    }

  
    
}

?>