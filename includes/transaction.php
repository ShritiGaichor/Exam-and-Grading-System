<?php
class Transaction extends Common{
    public $id = 0;
    
    public function __construct($id) {
        if($id != 0){
        global $con;
        
        $result = $con->query("select * from transactions where id = '{$id}' ");
        if($result and $result->num_rows > 0)
            foreach ($result->fetch_object() as $key => $val) $this->{$key} = $val;
        else
            die ("Can not create transaction object..");
        }
    }
        
    static function list(){
        global $con;
        
        $result = $con->query("select transactions.id, books.title as book, users.displayname as borrower, transactions.date as date, transactions.type, returndate "
                . "from transactions "
                . "join books on transactions.bookid = books.id "
                . "join users on transactions.userid = users.id "
                . "order by transactions.date ");
        echo $con->error;
        
        if($result and $result->num_rows > 0){ ?>
            <h3>List of Transaction</h3>
            <table>
            <?php
            $i = 0;
            while($row = $result->fetch_assoc()){ ?>
                <tr class="tablerow">
                <tr>
                    <td><a href="<?=docroot?>?entity=transaction&request=show&id=<?=$row['id']?>"><?=$row['date']?></a></td>
                    <td><?=$row['book']?></td>
                    <td><?=$row['borrower']?></td>
                    <td><a href="<?=docroot?>?entity=transaction&request=edit&id=<?=$row['id']?>">Edit</a></td>
                    <td><a href="<?=docroot?>?entity=transaction&request=delete&id=<?=$row['id']?>">Delete</a></td>
                    <td><?php if($row['type'] == 'b') { ?>
                        <a href="<?=docroot?>?entity=transaction&request=return&id=<?=$row['id']?>">Return</a>
                    <?php } else { ?>
                        Returned on <?=$row['returndate']?>
                    <?php } ?>
                    </td>
                </tr>
            <?php $i++;} 
            ?>
            </table><center><br /><?php echo "Total transactions : " . $i . " | "?><a href="<?=docroot?>?entity=transaction&request=edit&id=0">Add new transaction</a></center><?php     
        }
        else    echo "No records found";
    }
    
    function edit(){ ?>
        <form action="<?=docroot?>" method="POST">
            <table>
                <tr><td>Date (yyyy/mm/dd) </td><td><input type="text" name="date" value="<?= $this->id != 0 ? $this->date : '' ?>" /></td></tr>
                <tr><td>Book</td><td><?php Book::autocomplete($this->id != 0 ? $this->bookid : 0); ?></td></tr>
                <tr><td>Borrower</td><td><?php User::select($this->id != 0 ? $this->userid : 0); ?></td></tr>                
            </table>
            <input type="hidden" name="entity" value="transaction">
            <input type="hidden" name="request" value="update">
            <input type="hidden" name="id" value="<?=$this->id?>">
            <input type="submit" value="Update Info" name="update" />
        </form>
    <?php }
    
    function update(){
        global $con;
        
        if($this->id == 0)
            $result = $con->query("insert into transactions(date, bookid, userid, type) "
                . "values('{$_REQUEST['date']}', '{$_REQUEST['bookid']}', '{$_REQUEST['userid']}', 'b') ");
        else
            $result = $con->query("update transactions set date = '{$_REQUEST['date']}',  "
            . "bookid = {$_REQUEST['bookid']}, userid = '{$_REQUEST['userid']}' where id = {$this->id}");
            
        if($con->error) echo $con->error;
        else    echo "Records updated successfully";
        Transaction::list();
    }
    
    function return(){
        global $con;
        
        $result = $con->query("update transactions set returndate = curdate(), type = 'r' where id = {$this->id} ");
            
        if($con->error) echo $con->error;
        else    echo "Records updated successfully";
        Transaction::list();
    }
}