<?php
class Common{
    public function __construct($id) {
        if($id == 0){
            $this->id = 0;
            return;
        }
        global $con, $entity;
        
        $result = $con->query("select * from {$entity} where id = {$id} ");
        if($result and $result->num_rows > 0)
            foreach ($result->fetch_object() as $key => $val) $this->{$key} = $val;
        else
            die ("Can not create education object..");
    }
    
    static function list(){
        global $con, $entity,$c,$two;
        // echo "welcome to ".$entity;
        $c=$con->query("select count(*) as count from {$entity}")->fetch_object()->count;
        // echo $c; 
        $j=0;

        if(isset($_REQUEST['page'])){
            
                if(($_REQUEST['page'])=='1'){

                        $j=1+(int)$_REQUEST['i'];
                        // echo ' '.$j;
                        $two=2*$j;
                        if(($two+2)>=$c)
                        {
                            // echo "sucess";
                        }
                        $page=" limit ".$two.",2"; 
                }
                else
                {
                    $j=(int)$_REQUEST['i']-1;
                    $two=2*$j;
                    $page=" limit ".$two.",2"; 

                }
        
        }
        else
        {
            
            $page="limit 0,2";
        }


        $subquery = (isset($_REQUEST['search']) and $_REQUEST['search'] !="") ? "and {$entity}.title like '%".$_REQUEST['search']."%'" : '';
        $result = $con->query("select * from $entity where 1 " . $subquery.$page);
        if($result and $result->num_rows > 0){ ?>
            <h3>List of <?=$entity?>s</h3>
            <center>
            <form action="<?=docroot?>" method="POST">
               <input type="hidden" name="entity" value="<?=$entity?>"> 
                <input type="hidden" name="request" value="list"> 
                <input type="text" name="search" placeholder="search name of <?=$entity?>">
                <?php $entity::select()?>
            </form>
            </center>
            <br>
            <br>
            <table>
            <?php
            $i = 0;
            while($row = $result->fetch_assoc()){ ?>
                <tr class="tablerow">
                <tr>
                    <td><?=$row['title']?></td>
                    <td><a href="<?=docroot?>?entity=<?=$entity?>&request=edit&id=<?=$row['id']?>">Edit</a></td>

                    <td>
                        <button class="delete" data-id="<?=$row['id']?>"data-val="<?=$row['title']?>">
                        Delete</button>
                    </td>
                    <!--<td><a href="<?=docroot?>?entity=<?=$entity?>&request=delete&id=<?=$row['id']?>">Delete</a></td>
                    -->
                </tr>
            <?php $i++;} 
            ?>
            </table>

                <script type="text/javascript">
                    $(document).on("click","button.delete",function(event){
                        if(confirm("Do u want to delete "+ $(this).attr("data-id")))
                        {
                            var me = $(this);
                        $.ajax({
                        url: "<?=docroot?>",
                        data: {
                        entity: "<?=$entity?>",
                        request: "delete",
                        id:$(this).attr("data-id"),
                        ajax:true
                        },
                        success: function( result ) {

                        me.parent().parent().remove();
                        }
                        });

                        }
                            
                   
                    });

                </script>
                <center>
                <br /><?php echo "Total ".$entity ."s : " . $c . " | "?><a href="<?=docroot?>?entity=<?=$entity?>&request=edit&id=0">Add new <?=$entity?></a></center>



            <center>
            <div class="row">
            <div class="tile">
                <?php if(((int)$two-2)>=0) { ?>
                <a href="<?=docroot?>?entity=<?=$entity?>&request=list&page=0&i=<?=$j?>">Previous</a>
                <?php 
                } 
                else echo 'Previous';

                ?>
            </div>
            <div class="tile">
                <div class="cities">
                    <?php 
                    echo (($two/2)+1);
                    ?>
                </div>
                
            </div>

            <div class="tile">
                 <?php if((2+(int)$two)<$c) {
                  ?>
                <a href="<?=docroot?>?entity=<?=$entity?>&request=list&page=1&i=<?=$j?>">Next</a>
                <?php } 
                else 
                    {
                        echo 'Next';}

                ?>
            </div>
            
        </div>
        </center>





                <?php     
        }
        else  {  echo "No records found"; }

        ?>
        <a href="<?=docroot?>?entity=<?=$entity?>&request=list">back to list <?=$entity?></a>

        <?php 
       
    }
    
    static function select($selected = 0){
        global $con;
        $child = get_called_class();
        $entityid = strtolower($child) . "id";
        
        if($child == "User")    $subquery = ",name as title ";
        else $subquery = "";
        
        $result = $con->query("select * {$subquery} from $child ");
        if($result and $result->num_rows > 0){ ?>
            <select name="<?=$entityid?>">
                <option value="0" <?=$selected == 0 ? 'selected="selected"' : ''?> >select</option>
                <?php
                while($row = $result->fetch_assoc()){ ?>
                    <option value="<?=$row['id']?>" <?=$selected == $row['id'] ? 'selected="selected"' : ''?> ><?=$row['title']?></option>
                <?php } ?>            
            </select><?php
        }
        else    {echo "No records found";
        }      
    }
    
    function show(){ 
        global $entity; ?>
        <table><?php
        foreach ($this as $key => $val){ 
            if(strpos($key, 'id') === false and strpos($key, 'password') === false) { ?>
            <tr><td><?=ucfirst($key)?></td><td><?=$val?></td></tr>
            <?php } 
        } ?>
        </table>
        <br /><center><a href="<?=docroot?>?entity=<?=$entity?>&request=list">Back To <?=$entity?> List</a> | <a href="<?=docroot?>?entity=app&request=dashboard">Back To Dashboard</a></center>
        <?php
    }
    
    function delete(){
        global $con, $entity;
        echo $this->id;
        
        $result = $con->query("delete from $entity where id = {$this->id}");
        // if($result)
        // {
        //     echo 'records delete';
        // }
        // else
        // {
        //     echo 'not delete success';
        // }
        if(!$ajax) $entity::list();
    }
    
    function edit(){ 
        global $entity;
        ?>
        <form action="<?=docroot?>" method="POST">
            <table>
                <tr><td><?=$entity?></td><td> <input type="text" name="title" value="<?= $this->id != 0 ? $this->title : '' ?>" /></td></tr>
            </table>
            <input type="hidden" name="entity" value="<?=$entity?>">
            <input type="hidden" name="request" value="update">
            <input type="hidden" name="id" value="<?=$this->id?>">
            <input type="submit" value="Update Info" name="update" />
        </form>
    <?php
    }
    
    function update(){
        global $con, $entity;
        
        if($this->id == 0)
            $result = $con->query("insert into $entity(title) values('{$_REQUEST['title']}') ");
        else
            $result = $con->query("update $entity set title = '{$_REQUEST['title']}' where id = {$this->id}");
        
        $entity::list();
    }

    
}

