<?php
class App{
    static function showlogin(){ ?>
        <div style="background-color: lightsteelblue; padding: 1em; margin: auto; margin-top: 10%; font-size: 120%; border-radius: 20px; text-align: center; width: 50%;">
            <form action="<?=docroot?>" method="POST">
                Username <input type="text" name="username" value="" /><hr />
                Password <input type="text" name="password" value="" /><hr />
                <input type="submit" value="Login" name="validate" />
            </form>
        </div>
    <?php }

    static function dashboard(){

        global $con,$r;
       
        $r = $_SESSION['user']->role;
        echo $r;
        
       
       
        if($r =='A'){
     ?>
        <div class="row">
            <div class="tile"><a href="<?=docroot?>?entity=student&request=list&role=S">student</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=user&request=list&role={T}">teacher</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=class1&request=list">class</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=subjects&request=list">subjects</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=exam&request=list">exam</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=sem&request=list">semister</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=Department&request=list">Department</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=examtype&request=list">exam type</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=result&request=list">Department</a></div>

        </div>

    <?php }
    elseif($r=='T')
    {?>
        <div class="row">
            <div class="tile"><a href="<?=docroot?>?entity=student&request=list">Students</a></div>
            
            <div class="tile"><a href="<?=docroot?>?entity=exam&request=list">exam</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=mark&request=list">mark</a></div>
            
        </div>




   <?php }

   else{
    ?>
        <div class="row">
            
            
            <div class="tile"><a href="<?=docroot?>?entity=exam&request=list">exam</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=mark&request=list">mark</a></div>
            <div class="tile"><a href="<?=docroot?>?entity=result&request=list">result</a></div>
        </div>

    <?php

   }


}
    
    static function login($username){
        $_SESSION['user'] = new User($username);
    }
    
    static function logout(){
        session_unset ();
    }
}