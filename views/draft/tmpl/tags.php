<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

// Get block properties
$complete = $this->pub->curation('blocks', $this->step, 'complete');
$props    = $this->pub->curation('blocks', $this->step, 'props');
$required = $this->pub->curation('blocks', $this->step, 'required');

$elName = "keywordsPick";

// Get curator status
$curatorStatus = $this->pub->_curationModel->getCurationStatus($this->pub, $this->step, 0, 'author');

$this->css('tags.css')
     ->js('tags.js');
?>

<?php echo $this->elHtml; ?>

<!-- Load content selection browser //-->
<div id="<?php echo $elName; ?>" class="blockelement<?php
	echo $required ? ' el-required' : ' el-optional';
	echo $complete ? ' el-complete' : ' el-incomplete';
	echo $curatorStatus->status == 1 ? ' el-passed' : '';
	echo $curatorStatus->status == 0 ? ' el-failed' : '';
	echo $curatorStatus->updated && $curatorStatus->status != 2 ? ' el-updated' : '';
	?>">
	<div class="element_editing">
		<div class="pane-wrapper">
			<span class="checker">&nbsp;</span>
			<label id="<?php echo $elName; ?>-lbl">
				<?php if ($required) { ?><span class="required"><?php echo Lang::txt('PLG_PROJECTS_PUBLICATIONS_REQUIRED'); ?></span>
				<?php } else { ?><span class="optional"><?php echo Lang::txt('PLG_PROJECTS_PUBLICATIONS_OPTIONAL'); ?></span><?php } ?>
				<?php echo ucfirst(Lang::txt('PLG_PROJECTS_PUBLICATIONS_PUBLICATION_TAGS')); ?>
			</label>
		  <?php echo $this->pub->_curationModel->drawCurationNotice($curatorStatus, $props, 'author', $elName); ?>
			<?php
			$tf = Event::trigger( 'hubzero.onGetMultiEntry', array(array('tags', 'tags', 'actags', '', $this->keywords)) );

			echo 'Enter your own tags below:';
			if (count($tf) > 0) {
				echo $tf[0];
			} else {
				echo '<textarea name="tags" id="tags" rows="6" cols="35">' . $this->keywords . '</textarea>' . "\n";
			}
			?>
		</div>
	</div>
</div>
