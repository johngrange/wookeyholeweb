<?php
/**
 * @package Sj Article Slider
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;
JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_article_slider/css/slider.css');

if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_JQUERY', 1);
}
JHtml::script('modules/'.$module->module.'/assets/js/slider.js');

ImageHelper::setDefault($params);
$options = $params->toObject();
$uniqued ='articles_slider_'.rand().time();

if(!empty($list)){?>
	<?php if(!empty($options->pretext)) { ?>
		<div class="pre-text"><?php echo $options->pretext; ?></div>
	<?php } ?>
	<div id="<?php echo $uniqued; ?>" class="container-slider" style="<?php if( $options->anchor == "bottom" ){ echo "margin-bottom:40px;"; }?>">
			<div class="page-title"><?php echo $options->slider_title_text;?></div>
			<?php if($options->anchor =="top"){?>
			<?php if($options->button_display == 1){?>
			<div class="page-button <?php echo $options->anchor;?> <?php echo $options->control;?>">
				<ul class="control-button">
					<li class="preview">Prev</li>
					<li class="next">Next</li>
				</ul>		
			</div>
			<?php }}?>
	
		<div class="slider not-js cols-6 <?php echo $options->deviceclass_sfx; ?>">
			<div class="vpo-wrap">
				<div class="vp">
					<div class="vpi-wrap">
					<?php foreach($list as $item){?>
						<div class="item">
							<div class="item-wrap">
								<div class="item-img item-height">
									<div class="item-img-info">
										<a href="<?php echo $item->link;?>"  <?php echo  ArticlesSliderHelper::parseTarget($options->item_link_target);?>>
											<?php $img = ArticlesSliderHelper::getAImage($item, $params);
	    										echo ArticlesSliderHelper::imageTag($img);?>
										</a>
									</div>
								</div>
								<div class="item-info <?php if( $options->theme == "theme2" ){ echo "item-spotlight"; }?> ">
									<div class="item-inner">
									<?php if( $options->item_title_display == 1 ){?>
										<div class="item-title  google-font">
											<a href="<?php echo $item->link;?>" title="<?php echo $item->title; ?>"  <?php echo  ArticlesSliderHelper::parseTarget($options->item_link_target);?>>
												<?php echo str_replace("...","",$item->title);?>
											</a>
										</div>
									<?php }?>
										<div class="item-content">
										<?php if( $options->show_introtext == 1 ){?>
											<div class="item-des">
												<?php echo $item->displayIntrotext;?>									
											</div>
										<?php }?>
											<?php if( $options->item_readmore_display == 1 ){?>
											<div class="item-read">
												<a href="<?php echo $item->link;?>" <?php echo  ArticlesSliderHelper::parseTarget($options->item_link_target);?>>
													<?php echo $options->item_readmore_text; ?>
												</a>
											</div>	
											<?php }?>							
										</div>	
										<?php if( $options->theme == "theme2" ){
											if( $options->item_title_display == 1 || $options->show_introtext == 1 || $options->item_readmore_display == 1 ){?>
											<div class="item-bg"></div>				
										<?php }}?>		
									</div>
								</div>						
							</div>
						</div>
					<?php }?>
					</div>
				</div>
			</div>
		</div>
		
		<?php if($options->anchor !="top"){?>
			<?php if($options->button_display == 1){?>
			<div class="page-button <?php echo $options->anchor;?> <?php echo $options->control;?>">
				<ul class="control-button">
					<li class="preview">Prev</li>
					<li class="next">Next</li>
				</ul>		
			</div>
		<?php }}?>
		
	</div>
	<?php if(!empty($options->posttext)) {  ?>
		<div class="post-text"><?php echo $options->posttext; ?></div>
	<?php } ?>
<?php }else {echo JText::_('Has no content to show!');}?>

<script type="text/javascript">
//<![CDATA[
    jQuery(document).ready(function($){
		;(function(element){
			var  $element = $(element);
			$('.slider',$element).responsiver({
				interval: <?php echo $options->delay;?>,
				speed: <?php echo $options->duration;?>,
				start: <?php echo $options->start -1;?>,
				step: <?php echo $options->scroll;?>,
				circular: true,
				preload: true,
				fx: 'slide',
				pause: 'hover',
				control:{
					prev: '#<?php echo $uniqued;?> .control-button li[class="preview"]',
					next: '#<?php echo $uniqued;?> .control-button li[class="next"]'
				},
				getColumns: function(el){
					var match = $(el).attr('class').match(/cols-(\d+)/);
					if (match[1]){
						var column = parseInt(match[1]);
					} else {
						var column = 1;
					}
					if (!column) column = 1;
					return column;
				}          
			});
		})('#<?php echo $uniqued ; ?>')	
    });
//]]>
</script>



