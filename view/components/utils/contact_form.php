<div method="post">
  <input type="text" class="form-control">
  <input type="text" class="form-control">
  <input type="text" class="form-control">
  <input type="text" class="form-control">
  <input type="text" class="form-control">
  <button class="btn btn-dark" id="test_button">Guardar</button>
</div>



<script type="text/javascript">
  const button = document.querySelector('#test_button');
  button.addEventListener('click', function(e){
    e.preventDefault();
    alert('CLICKED')
  })
</script>
