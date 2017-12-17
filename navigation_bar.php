<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <ul class="nav">
    <li class="nav-item">
      <a id ="home" class="nav-link navbar-brand" href="">Home</a>
    </li>
    <li class="nav-item">
      <a id="edit" class="nav-link navbar-brand" href="">Edit</a>
    </li>
  </ul>
</nav>

<script type="text/javascript">
    function setNavigationPage(id, loc_path){
      $("#" + id).attr("href", loc_path);

    }

    function setActive(id){
      var navClass = $("#" + id).attr('class') + " active ";
      $("#" + id).prop("class", navClass);
    }    
</script>