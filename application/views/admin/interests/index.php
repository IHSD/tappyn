<?php defined("BASEPATH") or exit('No direct script access allowed');


?>

<form method='post' action="<?php echo base_url().'admin/interests/create'; ?>">
    <input type='text' name='name' placeholder="Name">
    <input type='text' name='display_name' placeholder="Display Name">
    <input type='text' name='parent_id' placeholder="Parent ID">
    <button type='submit'>Submit</button>
</form>

<div class='col-sm-10 col-sm-offset-1 content'>
    <div class='custom-table-wrapper'>
        <?php _display($interests); ?>
    </div>
</div>


<?php

$level = 0;

function _display($result)
{
    global $level;

    echo "<div class='col-sm-12 interest-card'>";
        echo "<h4>".str_repeat('----------', $level).$result->display_name."|".($result->id)."</h4>";
    echo "</div>";
    if(isset($result->children) && !empty($result->children))
    {
        $level++;
        foreach($result->children as $child)
        {
            _display($child);
        }
        $level--;
    }
}
?>
