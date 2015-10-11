<?php
/**
 * @package Sj News Ajax Tabs
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;

if((int)$params->get('nb_column', 3) >6){
	$params->set('nb_column',6);
}

$instance	= rand().time();
$nb_column	= (int)$params->get('nb_column', 3);
$nb_row 	= (int)$params->get('nb_row', 1);
$nb_items	= count($category_items);
$nb_pages	= $nb_items*1.0 / ($nb_column*$nb_row);
if (intval($nb_pages)<$nb_pages){
	$nb_pages = intval($nb_pages) + 1;
} else {
	$nb_pages = intval($nb_pages);
}
$i = 0;

if ($nb_items>0){

	if ((int)$params->get('pager_display', 1)){
		@ob_start(); ?>
		<div class="pager-container">
			<ul class="pages">
				<li><div class="page page-previous" href="<?php echo '#items_container_'.$module->id.$instance;?>" data-jslide="prev"><</div></li>
			<?php for ($j=0; $j<$nb_pages; $j++) {?>
				<li><div class="page number-page <?php if( $j == 0 ){echo ' sel';}?>" href="<?php echo '#items_container_'.$module->id.$instance;?>" data-jslide="<?php echo $j;?>"><?php echo ($j+1); ?></div></li>
			<?php } ?>
				<li><div class="page page-next" href="<?php echo '#items_container_'.$module->id.$instance;?>" data-jslide="next">></div></li>
			</ul>
		</div>
		<?php
		$pages_markup = @ob_get_contents();
		@ob_end_clean();
	} else {
		$pages_markup = '';
	}

	// show tabs here if (bottom, left);
	if (in_array($params->get('position'), array('bottom'))){
		echo $pages_markup;
	}?>
	<div class="items-container slide" id="items_container_<?php echo $module->id.$instance;?>" data-interval="0">
		<div class="items-container-inner <?php echo $nb_pages ?>" style="width:<?php //echo 100*$nb_pages ?>%;">
			<?php $qu = 0; foreach ($category_items as $key=> $item) {
				// index item.
				$classCurr = ($key==0)?'curr':'';
				$i++;
				if($nb_column*$nb_row>1) {$qu ++;
					if ($i % ($nb_column*$nb_row) == 1){ ?>
						<div class="items-grid <?php echo $classCurr?> <?php if($qu == 1){echo "active";}?> item ajaxtabs01-<?php echo  $nb_column?> ajaxtabs02-<?php echo  $nb_column?><?php //echo ( $nb_column == 6 || $nb_column == 5)?($nb_column - 2):($nb_column - 1)  ?> ajaxtabs03-<?php echo  $nb_column?><?php //echo ( $nb_column == 6 || $nb_column == 5)?($nb_column - 3):($nb_column - 1) ?> ajaxtabs04-1" style="width:<?php //echo round(100/$nb_pages,4)?>%">
						 
					<?php }
				}else{ $qu ++;?>
						<div class="items-grid <?php echo $classCurr?> <?php echo $classCurr?> <?php if($qu == 1){echo "active";}?> item ajaxtabs01-<?php echo  $nb_column?> ajaxtabs02-<?php echo ( $nb_column == 6 || $nb_column == 5)?($nb_column - 2):($nb_column - 1)  ?> ajaxtabs03-<?php echo ( $nb_column == 6 || $nb_column == 5)?($nb_column - 3):($nb_column - 1) ?> ajaxtabs04-1" style="width:<?php //echo round(100/$nb_pages,4)?>%">
				<?php }
				if ($i % $nb_column == 0){
					$item_last_css = ' last';
				} else {
					$item_last_css = '';
				}
				include JModuleHelper::getLayoutPath($module->module, 'item');;
				if ($i % $nb_column == 0 && $i < $nb_items && $i % ($nb_column*$nb_row) > 0){
					echo '<div class="item-separator"></div>';
				}
				if ($i % ($nb_column*$nb_row) == 0 || $i == $nb_items){
					// close item-grid
					echo "</div>";
				}
			}?>
		</div>
	</div>
	
	<?php
	// show tabs here if (bottom, left);
	if (in_array($params->get('position'), array('top', 'right', 'left'))){
		echo $pages_markup;
	}
		 
} else {?>
	<div class="noitem"><?php echo JText::_('There are no items matching the selection.'); ?></div>
<?php }?>

<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function($){//console.log("acbbc");
		$('#items_container_<?php echo $module->id.$instance;?>').each(function(){
			var $this = $(this), options = options = !$this.data('modal') && $.extend({}, $this.data());
			$this.jcarousel(options);
			$this.bind('jslide', function(e){
				var index = $(this).find(e.relatedTarget).index();
				// process for nav
				$('[data-jslide]').each(function(){
					var $nav = $(this), $navData = $nav.data(), href, $target = $($nav.attr('data-target') || (href = $nav.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, ''));
					if ( !$target.is($this) ) return;
					if (typeof $navData.jslide == 'number' && $navData.jslide==index){
						$nav.addClass('sel');
					} else {
						$nav.removeClass('sel');
					}
				});
	
			});
		});
		return;
	});
	//]]>
</script>

