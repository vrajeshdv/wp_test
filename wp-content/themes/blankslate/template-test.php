<?php /* Template Name: test Template */ ?>
<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
 
<header class="header">
<h1 class="entry-title" itemprop="name"><?php the_title(); ?></h1> 
</header>
<div class="entry-content" itemprop="mainContentOfPage">
<?php 
 if(isset($_REQUEST) && !empty($_REQUEST)){
    echo '<h2>Here is your query string list :'.implode(', ',$_REQUEST).'</h2>';
 }else{
    echo '<h2>Please add query string in url</h2>';
 }
 ?>
<?php the_content(); ?>
 
</div>
 
 
<?php endwhile; endif;
global $wpdb;
$table_name = $wpdb->prefix . 'user_address';
$items = $wpdb->get_results("SELECT * FROM $table_name ", ARRAY_A);
  ?>
  <h2>Table List : </h2>
<table border='1'>
 <tr><td>User</td><td>Note</td></tr>
 <tr>
    <?php foreach($items as $single_item){ ?>
        <tr><td><?php echo $single_item['user_name']; ?></td><td><?php echo $single_item['note']; ?></td></tr>
    <?php } ?>
 </tr>
</table>
 
<?php get_footer(); ?>