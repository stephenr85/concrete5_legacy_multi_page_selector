<?php  defined('C5_EXECUTE') or die("Access Denied."); ?> 
<?php


?>

<h1><?php echo t('Select pages') ?></h1>
<p><?php echo t('Click and drag to reoder them, or click the "X" on the right to remove an item from the list.') ?></p>

<?php echo $mps->create('cIDArray[]', $cIDArray); ?>

<br/>
<h2><?php echo t('Label') ?></h2>
<p>
	<?php echo t('Enter a label that describes these references.') ?><br/>
    
    <?php echo $form->text('label', $label) ?> 
    <?php echo $form->select('existingLabels', array_merge(array(''=>t('Choose...')), $existingLabels)) ?>
    
</p>