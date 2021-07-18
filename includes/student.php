<?php

/**
 * 
 */
class Student 
{
	public function __construct($id) {

        if($id === 0){

            $this->id = 0;
            return;
        }
        global $con, $entity;
        
        $result = $con->query("select user.id,name,class1.title as title,department.title as dept,user.s1,user.s2,user.s3,user.s4,user.s5,user.s6,user.s7 from user right join relation on user.relationid=relation.id inner join class1 on relation.classid=class1.id inner join department on relation.departmentid=department.id where role = 'S' and user.id=".$id);
        if($result and $result->num_rows > 0)
            foreach ($result->fetch_object() as $key => $val) $this->{$key} = $val;
        else
            die ("Can not create student object..");
    }

	static function list(){
        global $con;
        // $role=$_SESSION['user']->role;
        $result = $con->query("select user.id,name,class1.title as title,department.title as dept from user inner join relation on user.relationid=relation.id inner join class1 on relation.classid=class1.id inner join department on relation.departmentid=department.id where role = 'S'");



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
                    <td><a href="<?=docroot?>?entity=student&request=edit&id=<?=$row['id']?>&role='S'">Edit</a></td>
                    <td><a href="<?=docroot?>?entity=student&request=delete&id=<?=$row['id']?>&role='S'">Delete</a></td>
                </tr>
            <?php $i++;} 
            ?>
            </table><center><br /><?php echo "Total users : " . $i . " | "?><a href="<?=docroot?>?entity=student&request=edit&id=0">Add new user</a></center><?php     
        }

       
    }

     function delete(){
        global $con, $entity;
       
        echo $this->id ."  is ".$_REQUEST['id'];
        
        $result = $con->query("delete from user where id = {$this->id}");
      
        Student::list();
    }


    function edit(){
        ?>
        <form action="<?=docroot?>" method="POST">
            <table>
        <?php

 ?>
                <tr><td>Student name</td><td> <input type="text" name="username" value="<?= $this->id != 0 ? $this->name : '' ?>"/></td></tr>

                <tr><td>Department</td><td><?php Department::select($this->id != 0 ? $this->dept : 0); ?></td></tr>
                <tr><td>Class</td><td><?php Class1::select($this->id  != 0 ? $this->title : 0); ?></td></tr>
                
                <tr><td>Subject1</td><td><?php Student::select($this->id != 0 ? $this->s1 : 0); ?></td></tr>
                <tr><td>Subject2</td><td><?php Student::select($this->id != 0 ? $this->s2 : 0); ?></td></tr>
                <tr><td>Subject3</td><td><?php Student::select($this->id != 0 ? $this->s3 : 0); ?></td></tr>
                <tr><td>Subject4</td><td><?php Student::select($this->id != 0 ? $this->s4 : 0); ?></td></tr>
                <tr><td>Subject5</td><td><?php Student::select($this->id != 0 ? $this->s5 : 0); ?></td></tr>
                <tr><td>Subject6</td><td><?php Student::select($this->id != 0 ? $this->s6 : 0); ?></td></tr>
                <tr><td>Subject7</td><td><?php Student::select($this->id != 0 ? $this->s7 : 0); ?></td></tr>

            </table>
            <input type="hidden" name="entity" value="student">
            <input type="hidden" name="request" value="update">
            <input type="hidden" name="id" value="<?=$this->id?>">

            <input type="submit" value="Update Info" name="update" />
        </form>
    <?php 
}


static function select($selected = 0){
        global $con;
        echo "sgdsgs".$selected;

        $subjectid="s".$selected;
        $result = $con->query("select * from subject");
        if($result and $result->num_rows > 0){ ?>
            <select name="<?=$subjectid?>">
             <option value="0" <?=$selected == 0 ? 'selected="selected"' : ''?> >select</option>
                <?php
                while($row = $result->fetch_assoc()){ ?>
                    <option value="<?=$row['id']?>" <?=$selected == $row['id'] ? 'selected="selected"' : ''?> ><?=$row['title']?></option>
                <?php } ?>            
            </select><?php
        }
        else    echo "No records found";//*/
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
            $result = $con->query("update user set name='{$_REQUEST['username']}',s1='{$_REQUEST['id']}'  "
            . "where id = {$this->id}");
            
        if($con->error) echo $con->error;
        else    echo "Records updated successfully";
        User::list();        
    }

    


   
}



    
