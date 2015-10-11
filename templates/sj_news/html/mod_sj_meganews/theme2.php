<?php
/**
 * @package Sj Mega News
 * @version 2.5
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 * 
 */
defined('_JEXEC') or die;
JHtml::stylesheet('templates/' . JFactory::getApplication()->getTemplate().'/html/mod_sj_meganews/css/meganews.css');

//JHtml::stylesheet('modules/mod_sj_meganews/assets/css/meganews.css');
ImageHelper::setDefault($params);
$options=$params->toObject();
$uniqued='mgi_wrap_'.rand().time();

if(!empty($items)){?>
    
    <div id="<?php echo $uniqued; ?>" class="mgi-wrap theme2 <?php echo $options->deviceclass_sfx; ?>">
    <!--[if IE 8]> <div class="ie8 presets"> <![endif]-->
        <?php if (!empty($options->pretext)): ?>
        <div class="pretext">
            <?php  echo $options->pretext;?>
        </div>
        <?php endif; ?>
            <?php $j = 0;      
            foreach ( $items as $cat ){        	 
                $j++;?>
                                
                <div class="mgi-box">        
                    <div class="mgi-cat google-font">
                        <a href="<?php echo $cat->link;?>" <?php echo SjMeganewsHelper::parseTarget($options->target);?>><?php echo $cat->title;?></a> 
                    </div><!--end mgi-cat--> 
                      <?php  $articles = SjMeganewsHelper::getArticlesInCategory($cat->id, $params);
                    		$count_item = count($articles);?>
                    <?php if(!empty($articles)){
    			            $items_list = $articles;
    			            $item_first = array_shift($articles); ?>            
                    <div class="mgi-item">                            
                            <div class="item-wrap <?php if($count_item == 1){echo 'only';}?>">
                                <?php if($options->item_title_display == 1){?>                        
                                <div class="item-title google-font"><a href="<?php echo $item_first->link;?>" <?php echo SjMeganewsHelper::parseTarget($options->target);?>><?php echo str_replace("...","",$item_first->title);?></a></div>
                                <?php }if($options->item_image_display == 1){?>
                                <div class="item-image">
                                    <a href="<?php echo $item_first->link;?>" <?php echo SjMeganewsHelper::parseTarget($options->target);?>>
                                    <?php $img = SjMeganewsHelper::getAImage($item_first, $params, 'imgcfg');
    	    							echo SjMeganewsHelper::imageTag($img);?>
                                    </a>
                                </div>
                                <?php }if($options->show_introtext == 1){?>
                                <p class="item-desc"><?php echo str_replace("...","",$item_first->displayIntrotext);?></p>
                                <?php }if($options->item_readmore_display == 1){?>
                                <div class="item-readmore"> | <a href="<?php echo $item_first->link;?>" <?php echo SjMeganewsHelper::parseTarget($options->target);?>><?php echo $options->item_readmore_text;?></a></div>  
                                <?php }?>                                              
                            </div>                                                           
    						<?php if($count_item > 1){?>
    						<div class="title-link-wrap">
    							<!--<div class="more-label">MORE :</div> -->
    							<ul class="other-links">
    							<?php $i=0; foreach($items_list as $item){$i++; if($i!=1){?>
    								<?php $last_item ="";							 
    								 if($i==2){ $last_item = "first";} 
    								 if($i == count($items_list)){$last_item = "last";} ?>
    								<li class="<?php echo $last_item; ?>">
    									<a href="<?php echo $item->link;?>" <?php echo SjMeganewsHelper::parseTarget($options->target);?>><?php echo str_replace("...","",$item->title);?></a>
    									<?php if($options->show_introtext == 1){?>
    									<p class="item-desc"><?php echo $item->displayIntrotext;?></p>
    								<?php } ?>
    								
    								</li>                      
    							<?php }}?>                                                                                          
    							</ul> 
    							<?php if($options->show_all_items == 1){?> 
    							<div class="view-all">                        
    								<a href="<?php echo $cat->link; ?>" <?php echo SjMeganewsHelper::parseTarget($options->target);?>><?php echo $options->view_all_text;?></a>
    							</div> 
    							<?php }?>                                                          
    						</div>
    						<?php }?>          
                    </div>
                    <?php }else{echo "Has no content to show";} ?>
                </div><!--end mgi-box-->  
    	    	<?php
    	    		$clear = 'clr1';
    	    		if ($j % 2 == 0) $clear .= ' clr2';
    	    		if ($j % 3 == 0) $clear .= ' clr3';
    	    		if ($j % 4 == 0) $clear .= ' clr4';
    	    		if ($j % 5 == 0) $clear .= ' clr5';
    	    		if ($j % 6 == 0) $clear .= ' clr6';
    	    	?>
    	    	<div class="<?php echo $clear; ?>"></div>                                                 
            <?php }?>           
        <?php if (!empty($options->posttext)): ?>
        <div class="posttext">
            <?php  echo $options->posttext;?>
        </div>
        <?php endif; ?>              
        <!--[if IE 8]> </div> <![endif]-->                                     
    </div><!--end mgi_wrap-->
     
<?php } else { echo JText::_('Has no content to show!');}?>

