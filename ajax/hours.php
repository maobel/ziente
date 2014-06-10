<?php
header("Access-Control-Allow-Origin: *");
$mageFilename = '../app/Mage.php';
require_once $mageFilename;

Mage::app();

$idDay = $_GET['id'];
$DB = Mage::getSingleton('core/resource')->getConnection('core_write');
?>

<div class="inner">
	<label>
		Hours:
	</label>
	<select id="hours" name="hours" class="hours">
		<option value=" ">Seleccione</option>
		<?php
			$sql = "SELECT `id`, `time` FROM ziente_hours WHERE `day_id` = '$idDay' ORDER by `time`";
			
			foreach ($DB->fetchAll($sql) as $rs) { ?>
			    <option value="<?php echo $rs['id'] ?>"><?php echo $rs['time'] ?></option>
		  <?php } ?>
	</select>
</div>

