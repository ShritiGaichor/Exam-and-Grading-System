<?php
class User{
    public function __construct($username) {
        if($username === 0) {
            $this->id=0;
            return;
            }        
            global $con;

            $result = $con->query("select * from user where (name = '{$username}' or id = '{$username}' ) ");
            if($result and $result->num_rows > 0)
                {
                foreach ($result->fetch_object() as $key => $val) $this->{$key} = $val;
            }
            else
                die ("Can not create user object..");
            }
    
    static function validate(){
        global $con;

        $result = $con->query("select id from user where name = '{$_REQUEST['username']}' and password = '{$_REQUEST['password']}'");
       
        if($result and $result->num_rows > 0)
            {return true;
                echo "sxdgfgsg";}
        else
            return false;
    }
        
    static function list($role){
        global $con;
        // $role=$_SESSION['user']->role;
        $role=$_REQUEST['role'];


        $result = $con->query("select user.id,name,class1.title as title,department.title as dept from user inner join relation on user.relationid=relation.id inner join class1 on relation.classid=class1.id inner join department on relation.departmentid=department.id where role = '{$role}'");

        $result1= $con->query("select name,department.title as dept from user inner join relation on user.relationid=relation.id inner join department on relation.departmentid=department.id where role = '{$role}'");



        if($result and $result->num_rows > 0){ ?>
            <h3>List of Users</h3>
            <table>
            <?php
            $i = 0;

            while($row = $result->fetch_assoc()){ ?>
                <tr class="tablerow">
                <tr>
                    <td><a href="<?=docroot?>?entity=user&request=show&id=<?=$row['id']?>"><?=$row['name']?></a></td>
                    <td><?=$row['title']?></td>
                    <td><?=$row['dept']?></td>
                    <td><a href="<?=docroot?>?entity=user&request=edit&id=<?=$row['id']?>&role='S'">Edit</a></td>
                    <td><a href="<?=docroot?>?entity=user&request=delete&id=<?=$row['id']?>&role='S'">Delete</a></td>
                </tr>
            <?php $i++;} 
            ?>
            </table><center><br /><?php echo "Total users : " . $i . " | "?><a href="<?=docroot?>?entity=user&request=edit&id=0">Add new user</a></center><?php     
        }

        else
        {?>

             <h3>List of Users</h3>
            <table>
            <?php
            $i = 0;

            while($row1 = $result1->fetch_assoc()){ 
                echo $row['id'];
                ?>
                <tr class="tablerow">
                <tr>
                    <td><a href="<?=docroot?>?entity=user&request=show&id=<?=$row1['id']?>"><?=$row1['name']?></a></td>
                    
                    <td><?=$row1['dept']?></td>
                    <td><a href="<?=docroot?>?entity=user&request=edit&id=<?=$row1['id']?>&role='T'">Edit</a></td>
                    <td><a href="<?=docroot?>?entity=user&request=delete&id=<?=$row1['id']?>&role='T'">Delete</a></td>
                </tr>
            <?php $i++;} 
            ?>
            </table><center><br /><?php echo "Total users : " . $i . " | "?><a href="<?=docroot?>?entity=user&request=edit&id=0">Add new user</a></center>
            <?php


        }
    }
    
    function delete(){
        global $con, $entity;
        echo $this->id;
        
        $result = $con->query("delete from user where id = {$this->id}");
      
        User::list($_REQUEST['role']);
    }


    function disable(){
        global $con;
        
        $result = $con->query("update users set status = 'n' where id = {$this->id}");
        
        User::list();
    }
    
    function enable(){
        global $con;
        
        $result = $con->query("update users set status = 'y' where id = {$this->id}");
        
        User::list();
    }
    
    function edit(){
        ?>
        <form action="<?=docroot?>" method="POST">
            <table>
        <?php


    if($role='S'){ ?>
        
                <tr><td>Student name</td><td> <input type="text" name="username" value="<?= $this->id != 0 ? $this->name : '' ?>"/></td></tr>
                <tr><td>Department name</td><td> <input type="text" name="department" value="<?=$this->id != 0 ? $this->dept : '' ?>" /></td></tr>
                <tr><td>class</td><td> <input type="text" name="title" value="<?=$this->id != 0 ? $this->title : '' ?>" /></td></tr>                
                <tr><td>Subject1</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
                <tr><td>Subject2</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
                <tr><td>Subject3</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
                <tr><td>Subject4</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
                <tr><td>Subject5</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
                <tr><td>Subject6</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
                <tr><td>Subject7</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
            </table>
            <input type="hidden" name="entity" value="user">
            <input type="hidden" name="request" value="update">
            <input type="hidden" name="id" value="<?=$this->id?>">
            <input type="submit" value="Update Info" name="update" />
        </form>
    <?php }
    else
    {




    }
}
    
    function update(){
        global $con;
        
        if(strpos($_REQUEST['username'], " "))  {
            echo "Spaces not allowed in username";
            $this->edit();
            return;
        }
        
        if($this->id == 0)
            $result = $con->query("insert into users(username, displayname, email, educationid, occupationid, designationid) "
                . "values('{$_REQUEST['username']}', '{$_REQUEST['displayname']}', '{$_REQUEST['email']}', "
                . "{$_REQUEST['educationid']}, {$_REQUEST['occupationid']}, {$_REQUEST['designationid']}) ");
        else
            $result = $con->query("update users set displayname = '{$_REQUEST['displayname']}',  "
            . "email = '{$_REQUEST['email']}', educationid = '{$_REQUEST['educationid']}', "
            . "occupationid = '{$_REQUEST['occupationid']}', designationid = '{$_REQUEST['designationid']}' "
            . "where id = {$this->id}");
            
        if($con->error) echo $con->error;
        else    echo "Records updated successfully";
        User::list();        
    }
}