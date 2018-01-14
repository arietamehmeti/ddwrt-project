<?php
    $base_path_url = $_SESSION['base_path_url'];
    $current_page_name = basename($_SERVER['PHP_SELF'], ".php");
?>

<nav class="navbar navbar-default navbar-inverse bg-light navbar-static-top">
     <ul class="nav">
      <li class="nav-item">
        <?php 
        
        echo '<a id ="index" class="nav-link nav-brand" href="'.$base_path_url.'index.php">Home</a>';
        ?>
        
      </li>
      <li class="nav-item">

        <?php 
        
        echo '<a id ="edit" class="nav-link nav-brand" href="'.$base_path_url.'routes/edit.php">Edit</a>';
        ?>
      </li>
    </ul>
</nav>

<script type="text/javascript">
    function setActive(id){
      var navClass = $("#" + id).attr('class') + " active ";
      $("#" + id).prop("class", navClass);
    }
    var current_page_name = <?php echo json_encode($current_page_name); ?>;
    setActive(current_page_name);

</script>