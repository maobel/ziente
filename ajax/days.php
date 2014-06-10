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
		<option value=" ">Seleccione</option>
		<?php
			$sql = "SELECT `id`, `date` FROM ziente_days WHERE `location_id` = '$idLocation' AND `date` > CURDATE() ORDER by `date`";
			
			foreach ($DB->fetchAll($sql) as $rs) { ?>
			    <option value="<?php echo $rs['id'] ?>"><?php echo $rs['date'] ?></option>
		  <?php } ?>
	</select>
</div>

