<?php
  foreach($alertas as $key => $alerta): //iterar sobre las alertas, ver si es error, y mostrar mensaje
    foreach($alerta as $mensaje): //iterar que mensaje es
?>        
  <div class="alerta <?php echo $key; ?>">
     <?php echo $mensaje; ?>
  </div>

<?php
    endforeach;
  endforeach;
?>