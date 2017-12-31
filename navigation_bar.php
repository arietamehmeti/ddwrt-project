<nav class="navbar navbar-default navbar-inverse bg-light navbar-static-top">
  <div class="container">
     <ul class="nav">
      <li class="nav-item">
        <a id ="home" class="nav-link nav-brand" href="">Home</a>
      </li>
      <li class="nav-item">
        <a id="edit" class="nav-link nav-brand" href="">Edit</a>
      </li>
    </ul>     
  </div>

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