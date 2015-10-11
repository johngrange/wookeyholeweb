
/* ---------------------------------------------------------------------------------------------------------------------
 * Bang2Joom Social Plugin for Joomla! 2.5+
 * ---------------------------------------------------------------------------------------------------------------------
 * Copyright (C) 2011-2012 Bang2Joom. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: Bang2Joom
 * Website: http://www.bang2joom.com
  ----------------------------------------------------------------------------------------------------------------------
 */

jQuery(document).ready(function(){
    // JOOMLA 2.5 and JOOMLA 3.0
	jQuery('#jform_params_t_name-lbl').append('<span style="display:inline; position:relative; padding-left:78px;">@</span>');
	
    parent2 = Array();
    jQuery('.pos_field').each(function(el){
        var id = jQuery(this).attr('id');
        var pos_id = id+'_pos';
        var pos = jQuery('#'+pos_id).val();
        parent2[pos] = Array();
        parent2[pos]['html'] = jQuery(this).parent().html();
        parent2[pos]['html']+= '<span><input type="button" onclick="go_up2(\''+jQuery(this).attr('id')+'\')" value="/\\"/>';
        parent2[pos]['html']+= '<input type="button" onclick="go_down2(\''+jQuery(this).attr('id')+'\')" value="\\/"></span>';
        parent2[pos]['id'] = jQuery(this).attr('id');
    });
	
    refresh_fields2();
    
    if(jQuery('#jform_params_content').val()=='all'){
        jQuery('#jform_params_content').parent().next().show();
        jQuery('#jform_params_content').parent().next().next().show();
        jQuery('#jform_params_content').parent().next().next().next().show();
        jQuery('#jform_params_content').parent().next().next().next().next().show();
        jQuery('#jform_params_content').parent().next().next().next().next().next().show();
    }
    if(jQuery('#jform_params_content').val()=='k2'){
        jQuery('#jform_params_content').parent().next().show();
        jQuery('#jform_params_content').parent().next().next().show();
        jQuery('#jform_params_content').parent().next().next().next().hide();
        jQuery('#jform_params_content').parent().next().next().next().next().hide();
        jQuery('#jform_params_content').parent().next().next().next().next().next().show();
    }
    if(jQuery('#jform_params_content').val()=='article'){
        jQuery('#jform_params_content').parent().next().hide();
        jQuery('#jform_params_content').parent().next().next().hide();
        jQuery('#jform_params_content').parent().next().next().next().show();
        jQuery('#jform_params_content').parent().next().next().next().next().show();
        jQuery('#jform_params_content').parent().next().next().next().next().next().show();
    }
    if(jQuery('#jform_params_content').val()=='rs'){
        jQuery('#jform_params_content').parent().next().hide();
        jQuery('#jform_params_content').parent().next().next().hide();
        jQuery('#jform_params_content').parent().next().next().next().hide();
        jQuery('#jform_params_content').parent().next().next().next().next().hide();
        jQuery('#jform_params_content').parent().next().next().next().next().next().hide();
    }
		
    jQuery('#jform_params_content').change(function(){
        if(jQuery('#jform_params_content').val()=='all'){
            jQuery('#jform_params_content').parent().next().show();
            jQuery('#jform_params_content').parent().next().next().show();
            jQuery('#jform_params_content').parent().next().next().next().show();
            jQuery('#jform_params_content').parent().next().next().next().next().show();
            jQuery('#jform_params_content').parent().next().next().next().next().next().show();
        }
        if(jQuery('#jform_params_content').val()=='k2'){
            jQuery('#jform_params_content').parent().next().show();
            jQuery('#jform_params_content').parent().next().next().show();
            jQuery('#jform_params_content').parent().next().next().next().hide();
            jQuery('#jform_params_content').parent().next().next().next().next().hide();
            jQuery('#jform_params_content').parent().next().next().next().next().next().show();
        }
        if(jQuery('#jform_params_content').val()=='article'){
            jQuery('#jform_params_content').parent().next().hide();
            jQuery('#jform_params_content').parent().next().next().hide();
            jQuery('#jform_params_content').parent().next().next().next().show();
            jQuery('#jform_params_content').parent().next().next().next().next().show();
            jQuery('#jform_params_content').parent().next().next().next().next().next().show();
        }
        if(jQuery('#jform_params_content').val()=='rs'){
            jQuery('#jform_params_content').parent().next().hide();
            jQuery('#jform_params_content').parent().next().next().hide();
            jQuery('#jform_params_content').parent().next().next().next().hide();
            jQuery('#jform_params_content').parent().next().next().next().next().hide();
            jQuery('#jform_params_content').parent().next().next().next().next().next().hide();
        }

        if(jQuery("#k2_installed").val() == "")
        {
            jQuery("#jform_params_k2_categories-lbl").parent().hide();
        }

    });

    if(jQuery("#k2_installed").val() == "")
    {
        jQuery("#jform_params_k2_categories-lbl").parent().hide();
    }
    
    // Select or All
    if(jQuery('#jform_params_article_filter0').attr('checked')=='checked'){
        jQuery('#jform_params_joomla_categories').parent('li').hide();
    } else {
        jQuery('#jform_params_joomla_categories').parent('li').show();
    }
    
    jQuery('#jform_params_article_filter').change(function(){
        if(jQuery('#jform_params_article_filter0').attr('checked')=='checked'){
            jQuery('#jform_params_joomla_categories').parent('li').hide();
        } else {
            jQuery('#jform_params_joomla_categories').parent('li').show();
        }
    });
		
		// Share count on off -> color
    if(jQuery('#jform_params_share_counts1').attr('checked')=='checked'){
        jQuery('#jform_params_count_color').parent('li').hide();
    } else {
        jQuery('#jform_params_count_color').parent('li').show();
    }
    
    jQuery('#jform_params_share_counts').change(function(){
        if(jQuery('#jform_params_share_counts1').attr('checked')=='checked'){
            jQuery('#jform_params_count_color').parent('li').hide();
        } else {
            jQuery('#jform_params_count_color').parent('li').show();
        }
    });
    
    if(jQuery('#jform_params_k2_filter0').attr('checked')=='checked'){
        jQuery('#jformparamsk2_categories').parent('li').hide();
    } else {
        jQuery('#jformparamsk2_categories').parent('li').show();
    }
    
    jQuery('#jform_params_k2_filter').change(function(){
        if(jQuery('#jform_params_k2_filter0').attr('checked')=='checked'){
            jQuery('#jformparamsk2_categories').parent('li').hide();
        } else {
            jQuery('#jformparamsk2_categories').parent('li').show();
        }
    });

    // selected preset social default
    jQuery('#jform_params_preset').change(function(){
        if(jQuery('#jform_params_preset').val() == 7){
            jQuery('#jform_params_buttons').parents('li').hide();
            jQuery('#jform_params_icon_size').parents('li').hide();
						jQuery('#jform_params_include_font').parents('li').hide();
						jQuery('#jform_params_background_color').parents('li').hide();
						jQuery('#jform_params_icon_color').parents('li').hide();
						jQuery('#jform_params_icon_font_size').parents('li').hide();
        } else if (jQuery('#jform_params_preset').val() == 10){
						jQuery('#jform_params_buttons').parents('li').hide();
            jQuery('#jform_params_icon_size').parents('li').hide();
						jQuery('#jform_params_align').parents('li').hide();
						jQuery('#jform_params_include_font').parents('li').show();
						jQuery('#jform_params_background_color').parents('li').show();
						jQuery('#jform_params_icon_color').parents('li').show();
						jQuery('#jform_params_icon_font_size').parents('li').show();
				} else {
            jQuery('#jform_params_buttons').parents('li').show();
            jQuery('#jform_params_icon_size').parents('li').show();
						jQuery('#jform_params_align').parents('li').show();
						jQuery('#jform_params_include_font').parents('li').hide();
						jQuery('#jform_params_background_color').parents('li').hide();
						jQuery('#jform_params_icon_color').parents('li').hide();
						jQuery('#jform_params_icon_font_size').parents('li').hide();
        }
    });

    if(jQuery('#jform_params_preset').val() == 7){
				jQuery('#jform_params_buttons').parents('li').hide();
				jQuery('#jform_params_icon_size').parents('li').hide();
				jQuery('#jform_params_include_font').parents('li').hide();
				jQuery('#jform_params_background_color').parents('li').hide();
				jQuery('#jform_params_icon_color').parents('li').hide();
				jQuery('#jform_params_icon_font_size').parents('li').hide();
		} else if (jQuery('#jform_params_preset').val() == 10){
				jQuery('#jform_params_buttons').parents('li').hide();
				jQuery('#jform_params_icon_size').parents('li').hide();
				jQuery('#jform_params_align').parents('li').hide();
				jQuery('#jform_params_include_font').parents('li').show();
				jQuery('#jform_params_background_color').parents('li').show();
				jQuery('#jform_params_icon_color').parents('li').show();
				jQuery('#jform_params_icon_font_size').parents('li').show();
		} else {
				jQuery('#jform_params_buttons').parents('li').show();
				jQuery('#jform_params_icon_size').parents('li').show();
				jQuery('#jform_params_align').parents('li').show();
				jQuery('#jform_params_include_font').parents('li').hide();
				jQuery('#jform_params_background_color').parents('li').hide();
				jQuery('#jform_params_icon_color').parents('li').hide();
				jQuery('#jform_params_icon_font_size').parents('li').hide();
		}
		
		// Counts
		jQuery('#jform_params_counts0, #jform_params_counts1').change(function(){
        if(jQuery('#jform_params_counts0').is(':checked')){
            jQuery('#jform_params_buttons').parent('li').hide();
        } else {
            jQuery('#jform_params_buttons').parent('li').show();
        }
    });

    if(jQuery('#jform_params_counts0').is(':checked')){
				jQuery('#jform_params_buttons').parent('li').hide();
		} else {
				jQuery('#jform_params_buttons').parent('li').show();
		}
    
    // JOOMLA 3.0
    
    jQuery('.pos_field').parents('.controls').css('float', 'left');
    jQuery('.pos_field').parents('.controls').css('margin', '0 36px');
    jQuery('.pos_field').parents('.control-group').find('.control-label').css('width', '100');
    jQuery('span.spacer').parents('.control-group').height(30);
    jQuery('input[type=hidden]').parents('.control-group').height(14);
    jQuery('input[type=hidden]').parents('.control-group').css('margin', '0');
    jQuery('input[type=hidden]').parents('.control-group').css('padding', '0');
    
    parent3 = Array();
    jQuery('.pos_field').each(function(el){
        var id = jQuery(this).attr('id');
        var pos_id = id+'_pos';
        var pos = jQuery('#'+pos_id).val();
        parent3[pos] = Array();
        parent3[pos]['html'] = jQuery(this).parents('.control-group').html();
        parent3[pos]['html']+= '<span><input style="margin-top:14px" type="button" onclick="go_up3(\''+jQuery(this).attr('id')+'\')" value="/\\"/>';
        parent3[pos]['html']+= '<input  style="margin-top:14px" type="button" onclick="go_down3(\''+jQuery(this).attr('id')+'\')" value="\\/"></span>';
        parent3[pos]['id'] = jQuery(this).attr('id');
    });
    
    refresh_fields3();
    
    if(jQuery('#jform_params_content').val()=='all'){
        jQuery('#jform_params_content').parents('.control-group').next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().show();
    }
    if(jQuery('#jform_params_content').val()=='k2'){
        jQuery('#jform_params_content').parents('.control-group').next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().show();
    }
    if(jQuery('#jform_params_content').val()=='article'){
        jQuery('#jform_params_content').parents('.control-group').next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().show();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().show();
    }
    if(jQuery('#jform_params_content').val()=='rs'){
        jQuery('#jform_params_content').parents('.control-group').next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().hide();
        jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().hide();
    }
		
    jQuery('#jform_params_content').change(function(){
        if(jQuery('#jform_params_content').val()=='all'){
            jQuery('#jform_params_content').parents('.control-group').next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().show();
        }
        if(jQuery('#jform_params_content').val()=='k2'){
            jQuery('#jform_params_content').parents('.control-group').next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().show();
        }
        if(jQuery('#jform_params_content').val()=='article'){
            jQuery('#jform_params_content').parents('.control-group').next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().show();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().show();
        }
        if(jQuery('#jform_params_content').val()=='rs'){
            jQuery('#jform_params_content').parents('.control-group').next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().hide();
            jQuery('#jform_params_content').parents('.control-group').next().next().next().next().next().hide();
        }

        if(jQuery("#k2_installed").val() == "")
        {
            jQuery("#jform_params_k2_categories-lbl").parents('.control-group').hide();
        }

    });

    if(jQuery("#k2_installed").val() == "")
    {
        jQuery("#jform_params_k2_categories-lbl").parents('.control-group').hide();
    }
    
    // Select or All
    if(jQuery('#jform_params_article_filter0').attr('checked')=='checked'){
        jQuery('#jform_params_joomla_categories').parents('.control-group').hide();
    } else {
        jQuery('#jform_params_joomla_categories').parents('.control-group').show();
    }
    
    jQuery('#jform_params_article_filter').change(function(){
        if(jQuery('#jform_params_article_filter0').attr('checked')=='checked'){
            jQuery('#jform_params_joomla_categories').parents('.control-group').hide();
        } else {
            jQuery('#jform_params_joomla_categories').parents('.control-group').show();
        }
    });
		
		// Share count on off -> color
		if(jQuery('#jform_params_share_counts1').attr('checked')=='checked'){
        jQuery('#jform_params_count_color').parents('.control-group').hide();
    } else {
        jQuery('#jform_params_count_color').parents('.control-group').show();
    }
    
    jQuery('#jform_params_share_counts').change(function(){
        if(jQuery('#jform_params_share_counts1').attr('checked')=='checked'){
            jQuery('#jform_params_count_color').parents('.control-group').hide();
        } else {
            jQuery('#jform_params_count_color').parents('.control-group').show();
        }
    });
		
    
    if(jQuery('#jform_params_k2_filter0').attr('checked')=='checked'){
        jQuery('#jformparamsk2_categories').parents('.control-group').hide();
    } else {
        jQuery('#jformparamsk2_categories').parents('.control-group').show();
    }
    
    jQuery('#jform_params_k2_filter').change(function(){
        if(jQuery('#jform_params_k2_filter0').attr('checked')=='checked'){
            jQuery('#jformparamsk2_categories').parents('.control-group').hide();
        } else {
            jQuery('#jformparamsk2_categories').parents('.control-group').show();
        }
    });

    // selected preset social default
    jQuery('#jform_params_preset').change(function(){
        if(jQuery('#jform_params_preset').val() == 7){
            jQuery('#jform_params_buttons').parents('.control-group').hide();
            jQuery('#jform_params_icon_size').parents('.control-group').hide();
						jQuery('#jform_params_include_font').parents('.control-group').hide();
						jQuery('#jform_params_background_color').parents('.control-group').hide();
						jQuery('#jform_params_icon_color').parents('.control-group').hide();
						jQuery('#jform_params_icon_font_size').parents('.control-group').hide();
        } else if (jQuery('#jform_params_preset').val() == 10){
						jQuery('#jform_params_buttons').parents('.control-group').hide();
            jQuery('#jform_params_icon_size').parents('.control-group').hide();
						jQuery('#jform_params_align').parents('.control-group').hide();
						jQuery('#jform_params_include_font').parents('.control-group').show();
						jQuery('#jform_params_background_color').parents('.control-group').show();
						jQuery('#jform_params_icon_color').parents('.control-group').show();
						jQuery('#jform_params_icon_font_size').parents('.control-group').show();
				} else {
            jQuery('#jform_params_buttons').parents('.control-group').show();
            jQuery('#jform_params_icon_size').parents('.control-group').show();
						jQuery('#jform_params_align').parents('.control-group').show();
						jQuery('#jform_params_include_font').parents('.control-group').hide();
						jQuery('#jform_params_background_color').parents('.control-group').hide();
						jQuery('#jform_params_icon_color').parents('.control-group').hide();
						jQuery('#jform_params_icon_font_size').parents('.control-group').hide();
        }
    });

    if(jQuery('#jform_params_preset').val() == 7){
				jQuery('#jform_params_buttons').parents('.control-group').hide();
				jQuery('#jform_params_icon_size').parents('.control-group').hide();
				jQuery('#jform_params_include_font').parents('.control-group').hide();
				jQuery('#jform_params_background_color').parents('.control-group').hide();
				jQuery('#jform_params_icon_color').parents('.control-group').hide();
				jQuery('#jform_params_icon_font_size').parents('.control-group').hide();
		} else if (jQuery('#jform_params_preset').val() == 10){
				jQuery('#jform_params_buttons').parents('.control-group').hide();
				jQuery('#jform_params_icon_size').parents('.control-group').hide();
				jQuery('#jform_params_align').parents('.control-group').hide();
				jQuery('#jform_params_include_font').parents('.control-group').show();
				jQuery('#jform_params_background_color').parents('.control-group').show();
				jQuery('#jform_params_icon_color').parents('.control-group').show();
				jQuery('#jform_params_icon_font_size').parents('.control-group').show();
		} else {
				jQuery('#jform_params_buttons').parents('.control-group').show();
				jQuery('#jform_params_icon_size').parents('.control-group').show();
				jQuery('#jform_params_align').parents('.control-group').show();
				jQuery('#jform_params_include_font').parents('.control-group').hide();
				jQuery('#jform_params_background_color').parents('.control-group').hide();
				jQuery('#jform_params_icon_color').parents('.control-group').hide();
				jQuery('#jform_params_icon_font_size').parents('.control-group').hide();
		}
		
		// Counts
		jQuery('#jform_params_counts0, #jform_params_counts1').change(function(){
        if(jQuery('#jform_params_counts0').is(':checked')){
            jQuery('#jform_params_buttons').parents('.control-group').hide();
        } else {
            jQuery('#jform_params_buttons').parents('.control-group').show();
        }
    });

    if(jQuery('#jform_params_counts0').is(':checked')){
				jQuery('#jform_params_buttons').parents('.control-group').hide();
		} else {
				jQuery('#jform_params_buttons').parents('.control-group').show();
		}
    
});

function refresh_fields2(){
    var i=1;
    jQuery('.pos_field').each(function(el){
        var cur_li = jQuery(this).parent();
        if (cur_li.is('li')){
            cur_li.html(parent2[i]['html']);
            var label = cur_li.find('label:first').html();
            cur_li.find('label:first').html(i+'.'+label);
        }
        i++;
    });
}

function refresh_fields3(){
    var i=1;
    jQuery('.pos_field').each(function(el){
        var cur_parent = jQuery(this).parents('.control-group');
        cur_parent.html(parent3[i]['html']);
        var label = cur_parent.find('.control-label label').html();
        cur_parent.find('.control-label label').html(i+'.'+label);
        i++;
    });
}
function go_up2(id){
    var pos = parseInt(jQuery('#'+id+'_pos').val());
    //alert(pos);
    if(pos>1){
        var current = jQuery('#'+parent2[pos]['id']);
        var next = jQuery('#'+parent2[pos-1]['id']);
        current.parent().css({
            position:'relative'
        });
        next.parent().css({
            position:'relative'
        });
        var top = current.position().top -next.position().top;
        current.parent().animate({
            top: '-='+top+'px'
        },200);
        next.parent().animate({
            top: '+='+top+'px'
        },200,function(){
            current.parent().css({
                position:'',
                top:''
            });
            next.parent().css({
                position:'',
                top:''
            });
            jQuery('#'+parent2[pos-1]['id']+'_pos').val(pos);
            jQuery('#'+parent2[pos]['id']+'_pos').val(pos-1);	
            var temp_html = parent2[pos]['html'], temp_id = parent2[pos]['id'];
            parent2[pos]['html'] = parent2[pos-1]['html'];
            parent2[pos-1]['html'] = temp_html;
            parent2[pos]['id'] = parent2[pos-1]['id'];
            parent2[pos-1]['id'] = temp_id;
            refresh_fields2();
        });		
    }
		
}
function go_down2(id){
    var pos = parseInt(jQuery('#'+id+'_pos').val());
    if(pos<6){
        var current = jQuery('#'+parent2[pos]['id']);
        var next = jQuery('#'+parent2[pos+1]['id']);
        current.parent().css({
            position:'relative'
        });
        next.parent().css({
            position:'relative'
        });
        var top = next.position().top-current.position().top;
        current.parent().animate({
            top: '+='+top+'px'
        },200);
        next.parent().animate({
            top: '-='+top+'px'
        },200,function(){
            current.parent().css({
                position:'',
                top:''
            });
            next.parent().css({
                position:'',
                top:''
            });
            jQuery('#'+parent2[pos+1]['id']+'_pos').val(pos);
            jQuery('#'+parent2[pos]['id']+'_pos').val(pos+1);	
            var temp_html = parent2[pos]['html'], temp_id = parent2[pos]['id'];
            parent2[pos]['html'] = parent2[pos+1]['html'];
            parent2[pos+1]['html'] = temp_html;
            parent2[pos]['id'] = parent2[pos+1]['id'];
            parent2[pos+1]['id'] = temp_id;
            refresh_fields2();
        });
    }
}

    function go_up3(id){
    var pos = parseInt(jQuery('#'+id+'_pos').val());
    if(pos>1){
        var current = jQuery('#'+parent3[pos]['id']);
        var next = jQuery('#'+parent3[pos-1]['id']);
        var top = current.parents('.control-group').offset().top - next.parents('.control-group').offset().top;
        current.parents('.control-group').css({
            position:'relative'
        });
        next.parents('.control-group').css({
            position:'relative'
        });
        
        current.parents('.control-group').animate({
            top: '-='+top+'px'
        },200);
        next.parents('.control-group').animate({
            top: '+='+top+'px'
        },200,function(){
            current.parents('.control-group').css({
                position:'',
                top:''
            });
            next.parents('.control-group').css({
                position:'',
                top:''
            });
            jQuery('#'+parent3[pos-1]['id']+'_pos').val(pos);
            jQuery('#'+parent3[pos]['id']+'_pos').val(pos-1);	
            var temp_html = parent3[pos]['html'], temp_id = parent3[pos]['id'];
            parent3[pos]['html'] = parent3[pos-1]['html'];
            parent3[pos-1]['html'] = temp_html;
            parent3[pos]['id'] = parent3[pos-1]['id'];
            parent3[pos-1]['id'] = temp_id;
            refresh_fields3();
        });		
    }
		
}
function go_down3(id){
    var pos = parseInt(jQuery('#'+id+'_pos').val());
    if(pos<6){
        var current = jQuery('#'+parent3[pos]['id']);
        var next = jQuery('#'+parent3[pos+1]['id']);
        current.parents('.control-group').css({
            position:'relative'
        });
        next.parents('.control-group').css({
            position:'relative'
        });
        var top = next.parents('.control-group').offset().top - current.parents('.control-group').offset().top;
        current.parents('.control-group').animate({
            top: '+='+top+'px'
        },200);
        next.parents('.control-group').animate({
            top: '-='+top+'px'
        },200,function(){
            current.parents('.control-group').css({
                position:'',
                top:''
            });
            next.parents('.control-group').css({
                position:'',
                top:''
            });
            jQuery('#'+parent3[pos+1]['id']+'_pos').val(pos);
            jQuery('#'+parent3[pos]['id']+'_pos').val(pos+1);	
            var temp_html = parent3[pos]['html'], temp_id = parent3[pos]['id'];
            parent3[pos]['html'] = parent3[pos+1]['html'];
            parent3[pos+1]['html'] = temp_html;
            parent3[pos]['id'] = parent3[pos+1]['id'];
            parent3[pos+1]['id'] = temp_id;
            refresh_fields3();
        });
    }
}


