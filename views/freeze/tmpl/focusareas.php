<?php
/**
 * @package    hubzero-cms
 * @copyright  Copyright (c) 2005-2020 The Regents of the University of California.
 * @license    http://opensource.org/licenses/MIT MIT
 */

// No direct access
defined('_HZEXEC_') or die();

// Get block/element properties
$props    = $this->pub->curation('blocks', $this->master->blockId, 'props') . '-' . $this->elementId;
$complete = $this->pub->curation('blocks', $this->master->blockId, 'elementStatus', $this->elementId);

// Element required status depends on the requirement of each focus area
$required = count(array_filter($this->fas->copy()->rows()->fieldsByKey('mandatory_depth'),
                  function($required) {
                    return $required;
                  }));

$elName   = 'focusareas-element' . $this->elementId;

$aboutTxt = $this->manifest->adminTips
		? $this->manifest->adminTips
		: $this->manifest->about;

$shorten = ($aboutTxt && strlen($aboutTxt) > 200) ? 1 : 0;

if ($shorten)
{
	$about  = \Hubzero\Utility\Str::truncate($aboutTxt, 200);
	$about .= ' <a href="#more-' . $elName . '" class="more-content">' . Lang::txt('COM_PUBLICATIONS_READ_MORE') . '</a>';
	$about .= ' <div class="hidden">';
	$about .= ' 	<div class="full-content" id="more-' . $elName . '">' . $aboutTxt . '</div>';
	$about .= ' </div>';
}
else
{
	$about = $aboutTxt;
}

// Get curator status
if ($this->name == 'curator')
{
	$curatorStatus = $this->pub->_curationModel->getCurationStatus(
		$this->pub,
		$this->master->blockId,
		$this->elementId,
		'curator'
	);
}

?>
<?php if ($this->name == 'curator') { ?>
<div id="<?php echo $elName; ?>" class="blockelement<?php
	echo $required ? ' el-required' : ' el-optional';
	echo $complete ? ' el-complete' : ' el-incomplete';
	echo $curatorStatus->status == 1 ? ' el-passed' : '';
	echo $curatorStatus->status == 0 ? ' el-failed' : '';
	echo $curatorStatus->updated && $curatorStatus->status != 2 ? ' el-updated' : '';
	echo ($curatorStatus->status == 3 && !$complete) ? ' el-skipped' : '';
	?>">
<?php } else { ?>
	<div id="<?php echo $elName; ?>" class="blockelement<?php
		echo $required ? ' el-required' : ' el-optional';
		echo $complete ? ' el-complete' : ' el-incomplete';
		?>">
<?php } ?>
	<!-- Showing status only -->
	<div class="element_overview">
		<?php if ($this->name == 'curator') { ?>
		<div class="block-aside"><div class="block-info"><?php echo $about; ?></div></div>
		<?php echo $this->pub->_curationModel->drawChecker($props, $curatorStatus, Route::url($this->pub->link('edit')), $this->manifest->label); ?>
		<div class="block-subject">
		<?php } ?>
			<h5 class="element-title"><?php echo $this->manifest->label; ?></h5>
			<?php if ($this->name == 'curator') { $this->pub->_curationModel->drawCurationNotice($curatorStatus, $props, 'curator', $elName); } ?>
			<?php if ($this->fas_cloud) {
                echo $this->fas_cloud;
			} else { ?>
				<p class="noresults">No user input</p>
				<?php if (!$this->pub->isPublished() && ($this->status->getError() || ($required && !$complete))) { ?>
					<p class="witherror"><?php echo $this->status->getError() ? $this->status->getError() : Lang::txt('Missing required input'); ?></p>
				<?php } ?>
			<?php } ?>
		<?php if ($this->name == 'curator') { ?>
		</div>
		<?php } ?>
	</div>
</div>
