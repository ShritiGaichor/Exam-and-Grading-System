<?php
class Book extends Common{
    public $id = 0;
    
    public function __construct($id) {
        if($id != 0){
        global $con;
        
        $result = $con->query("select * from books where id = '{$id}' ");
        if($result and $result->num_rows > 0)
            foreach ($result->fetch_object() as $key => $val) $this->{$key} = $val;
        else
            die ("Can not create book object..");
        }
    }
        
    static function list(){
        global $con;
        
        $subquery = (isset($_REQUEST['search']) and $_REQUEST['search'] != "" ) ? ' and books.title like "%' .$_REQUEST['search']. '%"' : '';
        $subquery .= (isset($_REQUEST['subjectid']) and $_REQUEST['subjectid'] != 0 ) ? ' and books.subjectid = ' .$_REQUEST['subjectid'] : '';
        
        $result = $con->query("select books.id, author, books.title as title, subjects.title as subject  "
                . "from books join subjects on books.subjectid = subjects.id where 1 " . $subquery);
        echo $con->error;
        
        if($result and $result->num_rows > 0){ ?>
            <h3>List of Books <?=(isset($_REQUEST['search']) and $_REQUEST['search'] != "" ) ? ' : searched for - ' . $_REQUEST['search'] : ''?></h3>
            <center>
                <form action="<?=docroot?>" method="POST">
                    <input type="hidden" name="entity" value="book">
                    <input type="hidden" name="request" value="list">
                    <input type="text" placeholder="search book names here.." name="search" >
                    <?php Subject::select(); ?>
                </form><br /> 
            </center>
            <table>
            <?php
            $i = 0;
            while($row = $result->fetch_assoc()){ ?>
                <tr class="tablerow">
                <tr>
                    <td><a href="<?=docroot?>?entity=book&request=show&id=<?=$row['id']?>"><?=$row['title']?></a></td>
                    <td><?=$row['author']?></td>
                    <td><?=$row['subject']?></td>
                    <td><a href="<?=docroot?>?entity=book&request=edit&id=<?=$row['id']?>">Edit</a></td>
                    <td><a href="<?=docroot?>?entity=book&request=delete&id=<?=$row['id']?>">Delete</a></td>
                </tr>
            <?php $i++;} 
            ?>
            </table><center><br /><?php echo "Total books : " . $i . " | "?><a href="<?=docroot?>?entity=book&request=edit&id=0">Add new book</a></center><?php     
        }
        else    {
            echo "No records found. ";?><a href="<?=docroot?>?entity=book&request=list">Back to list..</a><?php
        }
    }
    
    function edit(){ ?>
        <form action="<?=docroot?>" method="POST">
            <table>
                <tr><td>Title</td><td><input type="text" name="title" value="<?= $this->id != 0 ? $this->title : '' ?>" /></td></tr>
                <tr><td>Author</td><td> <input type="text" name="author" value="<?=$this->id != 0 ? $this->author : '' ?>" /></td></tr>
                <tr><td>Publication</td><td> <input type="text" name="publication" value="<?=$this->id != 0 ? $this->publication : '' ?>" /></td></tr>                
                <tr><td>Subject</td><td><?php Subject::select($this->id != 0 ? $this->subjectid : 0); ?></td></tr>
            </table>
            <input type="hidden" name="entity" value="book">
            <input type="hidden" name="request" value="update">
            <input type="hidden" name="id" value="<?=$this->id?>">
            <input type="submit" value="Update Info" name="update" />
        </form>
    <?php }
    
    function update(){
        global $con;
        
        if($this->id == 0)
            $result = $con->query("insert into books(title, author, publication, subjectid) "
                . "values('{$_REQUEST['title']}', '{$_REQUEST['author']}', '{$_REQUEST['publication']}', "
                . "{$_REQUEST['subjectid']} ) ");
        else
            $result = $con->query("update books set title = '{$_REQUEST['title']}',  "
            . "author = '{$_REQUEST['author']}', publication = '{$_REQUEST['publication']}', "
            . "subjectid = '{$_REQUEST['subjectid']}' "
            . "where id = {$this->id}");
            
        if($con->error) echo $con->error;
        else    echo "Records updated successfully";
        Book::list();
    }
    
    static function autocomplete($selected = 0){ ?>
        <input name="book" value="" id="book">
        <input name="bookid" type="hidden" value="" id="bookid">
        <script type="text/javascript">
            $("#book" ).autocomplete({
                source: "<?=docroot?>?entity=book&request=options&ajax",
                minLength: 3,
                select: function( event, ui ) {
                    $("#book").val(ui.item.value);
                    $("#bookid").val(ui.item.id);
                }                
            });
        </script>
        <?php
    }    
    
    static function options(){
        global $con;
        
        $result = $con->query("select id, title as value from books where books.id not in(select bookid from transactions where type = 'b') and title like '%{$_REQUEST['term']}%' limit 10 ");
        if($result and $result->num_rows > 0){ 
            $options = array();
            while($row = $result->fetch_assoc())    $options[] = $row;
            
            echo json_encode($options);
        }
    }    
    
    static function select($selected = 0){
        global $con;
        
        $result = $con->query("select * from books where books.id not in(select bookid from transactions where type = 'b') ");
        if($result and $result->num_rows > 0){ ?>
            <select name="bookid">
                <?php
                while($row = $result->fetch_assoc()){ ?>
                    <option value="<?=$row['id']?>" <?=$selected == $row['id'] ? 'selected="selected"' : ''?> ><?=$row['title']?></option>
                <?php } ?>            
            </select><?php
        }
        else    echo "No records found";//*/
    }
}