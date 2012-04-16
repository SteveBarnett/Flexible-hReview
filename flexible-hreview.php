<?php
/**
 * @package Flexible_hReview
 * @version 0.5
 */
/*
Plugin Name: Flexible hReview
Description: Adds meta boxes to Posts for adding <a href="http://microformats.org/wiki/hreview">hReview</a> data. Allows for <a href="http://microformats.org/wiki/hreview#Multidimensional_Restaurant_Review">multi-dimensional hReviews</a> (mutiple rating items) and setting of rating range.
Author: Steve Barnett
Version: 0.5
Author URI: http://naga.co.za
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Flexible hReview - fhr


// Set defaults

$options_accepted_fields = array(
	'fhr_categories' => 'Atmosphere, Staff, Service, Food, Wine, Value for money',
	'fhr_rating_max' => 10,
	'fhr_use_average' => 0,
	'fhr_use_average_label' => 'Overall',
	'fhr_use_average_text' => 'Average of scores below',
	'fhr_before_rating_text' => '(',
	'fhr_after_rating_text' => ')',
	'hreview_item_url' => 'http://',

);

// Defaults

foreach($options_accepted_fields as $accepted_field => $default_value) {
	if(get_option($accepted_field)) $$accepted_field = $default_value;
}

$accepted_fields = array();
$accepted_fields[] = 'hreview_summary';
$accepted_fields[] = 'hreview_type';
$accepted_fields[] = 'hreview_item';
$accepted_fields[] = 'hreview_item_url';

$all_categories = get_option('fhr_categories');
$split_categories = explode(',',$all_categories);

foreach($split_categories as $category) {
	$accepted_fields[] = str_replace('-','_',sanitize_title($category));
	$accepted_fields[] = str_replace('-','_',sanitize_title($category) . '_commentary');
}


// Options page

// add menu page
add_action('admin_menu', 'create_fhr_menu');

function create_fhr_menu() {
	add_options_page('Flexible hReview', 'Flexible hReview', 'manage_options', 'fhr-category-options', 'fhr_category_options');
}

function fhr_category_options() {

	// Save data

	global $options_accepted_fields;

	if($_POST && wp_verify_nonce($_POST['fhr_nonce'],'fhr_edit')) {
		foreach($options_accepted_fields as $accepted_field => $default_value) {
			update_option( $accepted_field, $_POST[$accepted_field] );
		}
	}


	?>
	<div class="wrap">

	<form method="post">

	<h2>hReview - options</h2>

	<h3>Ratings</h3>

	<label for="fhr_categories">Fields to use (Comma separated)</label>
	<br />
	<input type="text" class="regular-text" name="fhr_categories" id="fhr_categories" value="<?php echo get_option('fhr_categories'); ?>" />

	<?php
	// echo '<br >';
	// $fhr_categories_array = explode(',',$fhr_categories);	
	// print_r($fhr_categories_array);
	?>

	<br />
	<br />

	<label for="fhr_rating_max">Maximum rating</label>
	<br />
	<input type="text" name="fhr_rating_max" id="fhr_rating_max" value="<?php echo get_option('fhr_rating_max'); ?>" class="small-text" />

	<h3>Average of ratings</h3>

	Generate average rating as first field?<br />
	<label for="fhr_use_average_yes">
		<input type="radio" id="fhr_use_average_yes" name="fhr_use_average" value="0" <?php if (get_option('fhr_use_average') == 0) echo 'checked="checked" '; ?> />
		Yes
	</label>
	&nbsp;
	<label for="fhr_use_average_no">
		<input type="radio" id="nfhr_use_average_no" name="fhr_use_average" value="1" <?php if (get_option('fhr_use_average') == 1) echo 'checked="checked" '; ?> />
		No
	</label>

	<br />
	<br />

	<label for="fhr_use_average_label">If yes, item label to use</label>
	<br />
	<input type="text" id="fhr_use_average_label" name="fhr_use_average_label" value="<?php echo get_option('fhr_use_average_label'); ?>" />

	<br />
	<br />

	<label for="fhr_use_average_text">If yes, rating text to use</label>
	<br />
	<input type="text" id="fhr_use_average_text" name="fhr_use_average_text" value="<?php echo get_option('fhr_use_average_text'); ?>" />


	<h3>Output</h3>

	<input type="text" name="fhr_before_rating_text" id="fhr_before_rating_text" value="<?php echo get_option('fhr_before_rating_text'); ?>" size="1" />
	Rating text example
	<input type="text" name="fhr_after_rating_text" id="fhr_after_rating_text" value="<?php echo get_option('fhr_after_rating_text'); ?>" size="1" />

	<br/>
	<br/>

	<p class="submit" style="text-align: left;">
		<input type="submit" name="submit" value="Save Settings" class="button-primary" />
	</p>

	<?php wp_nonce_field('fhr_edit','fhr_nonce'); ?>

	</form>

	</div>


	<?php
}

// meta boxes

// for entering review

add_action('admin_init','fhr_meta_init');

function fhr_meta_init() {
	add_meta_box(
		'fhr',
		'Flexible hReview',
		'flexible_hreview',
		'post'
	);
}

// for previewing review

add_action('admin_init','fhr_preview_init');

function fhr_preview_init() {
	add_meta_box(
		'fhrp',
		'Flexible hReview preview',
		'flexible_hreview_preview',
		'post'
	);
}

// $categories = array(
//         'atmosphere' => 'Atmosphere',
//         'staff' => 'Staff',
//         'service' => 'Service',
//         'food' => 'Food',
//         'wine' => 'Wine',
//         'value_for_money' => 'Value for money'
// );

// $accepted_fields = array();

// foreach($categories as $sanname => $name) {
//     $accepted_fields[] = $sanname;
//     $accepted_fields[] = $sanname . '_commentary';
// }
// $accepted_fields[] = 'hreview_summary';
// $accepted_fields[] = 'hreview_type';
// $accepted_fields[] = 'hreview_item';
// $accepted_fields[] = 'hreview_item_url';


// collect the hReview data

function flexible_hreview() {

global $post;
// global $categories;

// foreach($categories as $sanname => $name) {
//     $$sanname = get_post_meta($post->ID, $sanname,TRUE);
    
//     $namecom = $sanname . '_commentary';
//     $$namecom = get_post_meta($post->ID, $namecom,TRUE);
// }

// global $accepted_fields;
// echo '::' . print_r($accepted_fields, true) . '::';
?>



<label for="hreview_summary"><code>summary</code> (Optional. Defaults to Excerpt.)</label>
<br />

<textarea id="hreview_summary" name="hreview_summary" rows="5"><?php echo get_post_meta($post->ID, 'hreview_summary',TRUE); ?></textarea>
<br /><br />

<label for="hreview_type"><code>type</code></label>

<select id="hreview_type" name="hreview_type">
<?php
$hreview_types = array('product', 'business', 'event', 'person', 'place', 'website', 'url');
foreach ($hreview_types as $hreview_type) {
	echo '<option';
	if(
		get_post_meta($post->ID, 'hreview_type',TRUE) == $hreview_type ||
		(get_post_meta($post->ID, 'hreview_type',TRUE) == '' && $hreview_type == 'business')

	) {
		echo ' selected="selected"';
	}
	echo '>' . $hreview_type . '</option>';
}
?>
</select>

<br /><br />

<label for="hreview_item"><code>item</code> Name (Defaults to Post Title, without " Review". Used for <code>fn</code> and <code>org</code> in <code>hCard</code>.)</label>
<br />

<input type="text" id="hreview_item" name="hreview_item" value="<?php
echo get_post_meta($post->ID, 'hreview_item',TRUE);
if(get_post_meta($post->ID, 'hreview_item',TRUE) == '') echo get_post_meta($post->ID, 'restaurant_name',TRUE);
?>" />

<br /><br />

<label for="hreview_item_url"><code>item</code> <code>url</code><br /></label>
<input type="text" id="hreview_item_url" name="hreview_item_url" value="<?php
echo get_post_meta($post->ID, 'hreview_item_url',TRUE);
if (get_post_meta($post->ID, 'hreview_item_url',TRUE) == '') echo get_post_meta($post->ID, 'restaurant_url',TRUE);
?>" />


<h4>Ratings</h4>

<table class="widefat">

	<thead>
		<tr>
			<th scope="col">Item</th>
			<th scope="col"><code>rating</code></th>
			<th scope="col">Commentary</th>
		</tr>
	</thead>


	<tbody>

	<?php
	$all_categories = get_option('fhr_categories');
	$split_categories = explode(',',$all_categories);
	// print_r($split_categories);

	foreach($split_categories as $category) {
		$san_cat = str_replace('-','_',sanitize_title($category));
	?>
		<tr>
			<td>
				<?php echo $category; ?>
			</td>
			<td>
				<select id="<?php echo $san_cat; ?>" name="<?php echo $san_cat; ?>">
                                        <option></option>
					<?php 
					for ($i = 1; $i <= get_option('fhr_rating_max'); $i++) {
						echo '<option';
						if(get_post_meta($post->ID, $san_cat, TRUE) == $i) echo ' selected="selected"';
						echo '>' . $i . '</option>';
					}
					?>
				</select> / <?php echo get_option('fhr_rating_max'); ?>
			</td>
			<td class="form-field">
				<textarea id="<?php echo $san_cat; ?>_commentary" name="<?php echo $san_cat; ?>_commentary" rows="2"><?php echo get_post_meta($post->ID, $san_cat . '_commentary',TRUE); ?></textarea>
			</td>
		</tr>
	<?php
	}
	?>

	</tbody>
</table>
<?php
wp_nonce_field('ehr-edit','ehr-nonce');

}


// save the hReview data

add_action( 'save_post', 'ehr_meta_save', 3, 2 );

function ehr_meta_save($post_id, $post) {

	if(!wp_verify_nonce($_POST['ehr-nonce'],'ehr-edit'))
	{
		return $post_id;
	}
	if (!current_user_can('edit_post', $post_id))
	{
		return $post_id;
	}

    global $post;
    // global $categories;
    global $accepted_fields;
 
	foreach($accepted_fields as $key){
		$custom_field = $_POST[$key];
		if(is_null($custom_field))
		{
			delete_post_meta($post_id, $key);
		}
		elseif(isset($custom_field) && !is_null($custom_field))
		{
			update_post_meta($post_id,$key,$custom_field);
		}
		else
		{
			add_post_meta($post_id, $key, $custom_field, TRUE);
		}
	}

return $post_id;
}


function flexible_hreview_html($post_id) {
    $the_post = get_post($post_id);

    // check for review data: any item set?

    $post_has_review = false;

    global $accepted_fields;
 
	foreach($accepted_fields as $key) {
		if(get_post_meta($post_id, $key, TRUE)) $post_has_review = true;
		
	}

	if($post_has_review) {
    $flexible_hreview_html_output = '';
    
    $flexible_hreview_html_output .= '
<div class="hreview">';

	if (strlen(get_post_meta($post_id, 'review_summary',TRUE))>0) {
		$flexible_hreview_html_output .= '
	<div class="summary">
		' . get_post_meta($post_id, 'review_summary',TRUE) . '
	</div>';
	}
	elseif(strlen($the_post->post_excerpt)>0) {
		$flexible_hreview_html_output .=  '
	<div class="summary">
	' . $the_post->post_excerpt . '
	</div>';
	}

	$flexible_hreview_html_output .= '
	<div class="item vcard">';

		if (strlen(get_post_meta($post_id, 'hreview_item_url',TRUE))>0) {
			$flexible_hreview_html_output .= '	<a class="fn org url" href="' . get_post_meta($post_id, 'hreview_item_url',TRUE) . '">';
		}
		else {
			$flexible_hreview_html_output .= '<span class="fn org">';
		}

	if (strlen(get_post_meta($post_id, 'hreview_item',TRUE))>0) {
		$flexible_hreview_html_output .= get_post_meta($post_id, 'hreview_item',TRUE);
	}
	else {
		$flexible_hreview_html_output .= str_ireplace(' review', '', $the_post->post_title);
	}

		if (strlen(get_post_meta($post_id, 'hreview_item_url',TRUE))>0) {
			$flexible_hreview_html_output .= '</a>';
		}
		else {
			$flexible_hreview_html_output .= '</span>';
		}
	
		$flexible_hreview_html_output .= '
		</div>';

		$flexible_hreview_html_output .= '
	<div class="review-meta">
    		<a href="' . get_permalink($post_id) . '" rel="bookmark">Reviewed</a> on <abbr class="dtreviewed" title="' . date('c', strtotime($the_post->post_date)) . '">' . date('d/m/Y', strtotime($the_post->post_date)) . ' </abbr> by <span class="reviewer vcard"><a class="url fn" href="';
    		if(get_the_author_meta('user_url', $the_post->post_author)) {
    			$flexible_hreview_html_output .= get_the_author_meta('user_url', $the_post->post_author);
    		}
    		else {
    			$flexible_hreview_html_output .= get_home_url() . '/author/' . get_the_author_meta( 'user_nicename' , $the_post->post_author );
    		}
    		
    		$flexible_hreview_html_output .= '/">' . get_the_author_meta( 'user_nicename' , $the_post->post_author ) . '</a></span>
    	</div>

    <span class="type">' . get_post_meta($post_id, 'hreview_type', TRUE) . '</span>';

    $flexible_hreview_html_output .= '<ul>';
    $ratings_list = '';
    $total_rating = 0;
    $total_categories = 0;

	$all_categories = get_option('fhr_categories');
	$split_categories = explode(',',$all_categories);

	foreach($split_categories as $category) {

		$san_cat = str_replace('-','_',sanitize_title($category));	

		if(get_post_meta($post_id, $san_cat,TRUE)) {

        $total_rating += get_post_meta($post_id, $san_cat,TRUE);
        $total_categories++;

        $ratings_list .= '
        <li>
            <span class="rating">
            	<strong>' . $category . ':</strong>
           		<span class="value">' . get_post_meta($post_id, $san_cat, TRUE) . '</span> / <span class="best">' . get_option('fhr_rating_max') . '</span>
           	</span>
            <span class="commentary">' . get_option('fhr_before_rating_text') . get_post_meta($post_id, $san_cat . '_commentary',TRUE) . get_option('fhr_after_rating_text') . '</span>
        </li>
            ';

        }
	}

    if($total_categories > 1 && get_option('fhr_use_average') == 0) {
    $flexible_hreview_html_output .= '
        	<li>
            <span class="rating">
            	<strong>' . get_option('fhr_use_average_label') . ':</strong>
           		<span class="value">' . round((($total_rating) / ($total_categories * get_option('fhr_rating_max')))*get_option('fhr_rating_max'),0) . '</span> / <span class="best">' . get_option('fhr_rating_max') . '</span>
           	</span> ' . get_option('fhr_before_rating_text') . get_option('fhr_use_average_text') . get_option('fhr_after_rating_text') . '
            </li>
            ';
    }

    $flexible_hreview_html_output .= $ratings_list;
    
	$flexible_hreview_html_output .=  '</ul>';

	$flexible_hreview_html_output .= '    
    <span class="version">0.4</span>';
    
    $flexible_hreview_html_output .= '
</div><!-- end hreview -->';

	return $flexible_hreview_html_output;

	} // if($post_has_review) 
	else {
		return false;
	}
	
} // function flexible_hreview_html


function flexible_hreview_preview() {
	global $post;
	flexible_hreview_html($_GET['post']);
}

// shortcode

function flexible_hreview_shortcode($atts, $content = null) {

        extract(shortcode_atts(array(
                "id" => ''
        ), $atts));

        if($id) {
        	return flexible_hreview_html($id);	
        }
        else {
        	global $post;
        	return flexible_hreview_html($post->ID);	
        }
}

add_shortcode("flexible_hreview", "flexible_hreview_shortcode");


// let reviews show in sidebar

add_filter('widget_text', 'do_shortcode');