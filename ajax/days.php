<?php
header("Access-Control-Allow-Origin: *");
$mageFilename = '../app/Mage.php';
require_once $mageFilename;

Mage::app();

$idLocation = $_GET['id'];
$DB = Mage::getSingleton('core/resource')->getConnection('core_write');
?>

<div class="inner">
	<label>
		Days:
	</label>
	<select id="days" name="days" class="days">
		<option value=" ">Selecciona</option>
		<?php
			$sql = "SELECT `id`, `date` FROM ziente_days WHERE `location_id` = '$idLocation' AND `date` > CURDATE() ORDER by `date`";
			
			foreach ($DB->fetchAll($sql) as $rs) { 
				$date = $rs['date'];
				$t = strtotime($date);

				$dias = array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
				$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
				//echo date("l, F j Y", $t); ;
			?>
			    <option value="<?php echo $rs['id'] ?>"><?php echo $dias[date('w', $t)]." ".date('d', $t)." de ".$meses[date('n', $t)-1]. " del ".date('Y', $t); ?></option>
		  <?php } ?>
	</select>
</div>

