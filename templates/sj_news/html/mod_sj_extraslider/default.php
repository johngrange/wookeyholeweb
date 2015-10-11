<?php
/**
 * @package Sj News Extra Slider
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
    defined('_JEXEC') or die;
    
    JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_extraslider/css/style.css');
    //JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_extraslider/css/css3.css');
    if( !defined('SMART_JQUERY') && $params->get('include_jquery', 0) == "1" ){
    	JHtml::script('modules/mod_sj_extraslider/assets/js/jquery-1.8.2.min.js');
    	JHtml::script('modules/mod_sj_extraslider/assets/js/jquery-noconflict.js');
    	define('SMART_JQUERY', 1);
    }    
    JHtml::script('modules/mod_sj_extraslider/assets/js/jcarousel.js');
    
    ImageHelper::setDefault($params);
    $options=$params->toObject();
	$count_item = count($items);
	$item_of_page = $options->num_rows * $options->num_cols;
	$suffix = rand().time();
	$tag_id = 'sjextraslider_'.$suffix;	
	$maxcharacs=$params->item_title_max_characs;
	
	
	   
	if(!empty($items)){?>
    <div id="<?php echo $tag_id;?>" class="sj-extraslider <?php if( $options->effect == 'slide' ){ echo $options->effect;}?> preset02-<?php echo $options->num_cols; ?>" data-start-jcarousel='1'>
		<?php if(!empty($options->pretext)) { ?>
			<div class="pre-text"><?php echo $options->pretext; ?></div>
		<?php } ?> 
       	<div class="extraslider-top">	    
	    	<div class="extraslider-control  <?php if( $options->button_page == 'under' ){echo 'button-type2';}?>">
				<a class="button-prev" href="<?php echo '#'.$tag_id;?>" data-jslide="prev"></a>
				<?php if( $options->button_page == 'top' ){?>
				<ul class="nav-page">
				<?php $j = 0;$page = 0;
				    foreach ($items as $item){$j ++;
					    $active_class = $page == 0 ? " active" : "";
					    if( $j%$item_of_page == 1 || $item_of_page == 1 ){$page ++;?>
					    <li class="page">
						    <a class="button-page <?php if( $page==1 ){echo 'sel';}?>" href="<?php echo '#'.$tag_id;?>" data-jslide="<?php echo $page-1;?>"></a>
					    </li>
				    <?php }}?>
				</ul>
				<?php }?>
				<a class="button-next" href="<?php echo '#'.$tag_id;?>" data-jslide="next"></a>
			</div>
		
		 	<?php if($options->title_slider_display == 1){?>
			    <div class="extraslider-heading">
				<h3 class="heading-title google-font"><?php echo $options->title_slider;?></h3><!--end heading-title-->
				<div class="heading-content"><?php echo $options->content_slider;?></div>
			    </div>
	        <?php }?>
		</div>
	<div class="extraslider-inner">
	    <?php $count = 0; $i = 0; 
	    foreach($items as $item){$count ++; $i++;?>
            <?php if($count%$item_of_page == 1 || $item_of_page == 1){?>
            <div class="item <?php if($i==1){echo "active";}?>">
            <?php }?>
                <?php if($count%$options->num_cols == 1 || $options->num_cols == 1 ){?>
				<?php $last_line = ""; 
					if($count%$item_of_page == 0){ $last_line = "last";}
					else{ echo $last_line = "";};
				?>
                <div class="line <?php echo $last_line; ?>">
                <?php }?>  
                
				    <div class="item-wrap <?php echo $options->theme; if($count%$options->num_cols == 0 || $count== $count_item && $options->num_cols !=1){echo " last";}?> ">
				    	<div class="item-image">
                            <?php $img = SjExtrasliderHelper::getImage($item, $params);
	    					echo SjExtrasliderHelper::imageTag($img);?>
				    	</div>
			    	<?php if( $options->item_title_display == 1 || $options->show_introtext == 1 || $options->item_readmore_display == 1 ){?>
				    	<div class="item-info">
				    	<?php if( $options->item_title_display == 1 ){?>
				    		<div class="item-title">
                                <a href="<?php echo $item->link;?>" target = "<?php echo $options->item_link_target;?>">
									<?php //var_dump() ; die('vao day');?>
                                	<?php echo substr($item->title, 0,$options->item_title_max_characs);?>
                                </a>    			     
				    		</div>
			    		<?php }?>
			    		<?php if( ($options->show_introtext == 1 && !empty($item->displayIntrotext)) || $options->item_readmore_display == 1 ){?>
                            <div class="item-content">
                            <?php if( $options->show_introtext == 1 ){?>
                                <div class="item-description">
                                	<?php echo $item->displayIntrotext;?>
                                </div>
                            <?php }?>
                            <?php if( $options->item_readmore_display == 1 ){?>
                                <div class="item-readmore">
			                        <a class="info" href="<?php echo $item->link;?>" target = "<?php echo $options->item_link_target;?>">
		                            	<?php echo $options->item_readmore_text;?>
			                        </a>                                
                                </div> 
                            <?php }?>                               
                            </div>
                        <?php }?>
				    	</div>
			    	<?php }?>
				    </div>                
                 
                <?php if($count%$options->num_cols == 0 || $count== $count_item){?>    
                </div><!--line-->
                <?php } ?>		    		
            <?php if(($count%$item_of_page == 0 || $count== $count_item)){?>    
            </div><!--end item--> 
            <?php }?>
	    <?php }?>
	    </div><!--end extraslider-inner -->
	    <?php if( $options->button_page == 'under' ){?>
	    <ul class="nav-page nav-under">
	    <?php $j = 0;$page = 0;
	    	foreach ($items as $item){$j ++;
			$active_class = $page == 0 ? " active" : "";
	    		if( $j%$item_of_page == 1 || $item_of_page == 1 ){$page ++;?>
	    		<li class="page">
	    			<a class="button-page <?php if( $page==1 ){echo 'sel';}?>" href="<?php echo '#'.$tag_id;?>" data-jslide="<?php echo $page-1;?>"></a>
	    		</li>
    		<?php }}?>
	    </ul>
	    <?php }?>	    
		<?php if(!empty($options->posttext)) {  ?>
			<div class="post-text"><?php echo $options->posttext; ?></div>
		<?php }?>
    </div>
<?php }else{ echo JText::_('Has no item to show!');}?>



