<?php
/**
 * ACF used in Wordpress Multisite to Create a Post Broadcaster that will broadcast the selected posts 
 * from main site to other sites on the network
**/

/** 
  * Link to check : http://lunarartwork.com/blog/ 
**/

	$original_blog_id = get_current_blog_id(); // get current blog
	$compare_array = array('1' => 'lunarartwork', '2' => 'thelunarark', '4' => 'thelunarrenaissance', '6' => 'ilgy-2017', '7' => 'lunarsigs','8' => 'thelunarincubator', '9' => 'lunarchallenges', '10' => 'cubestothemoon', '11' => 'thelunarfrontier');
	switch_to_blog('1');
	$wp_query = new WP_Query('post_type=post');

	// Start the Loop.
	while ( $wp_query->have_posts() ) : $wp_query->the_post();
		$flag = 0;

		//get selected sites from post broadcaster
		$post_broadcaster = get_field('post-broadcaster');
		if(!in_array($compare_array[$original_blog_id], $post_broadcaster)){
			continue;
		}

		// Include the page content template.			
		get_template_part( 'content', 'blog' );

		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
	endwhile;
	?>

	<!-- ACF used to create additional Title field for Categories -->
	<header class="archive-header">
		<?php 
		$cat_ID = get_query_var('cat');
		$cat_title = get_field('category_title', 'category_'. $cat_ID .'');
		?>
		<h1 class="archive-title"><?php printf( __( '%s', 'twentythirteen' ), $cat_title ); ?></h1>
	</header><!-- .archive-header -->

<?php 
/**
 * ACF used to create a "Hot News Carousel" by adding a Hot News sidebar option for posts to display a post in 
 * Carousel either on Home Page or on category Pages
**/

/** 
  * Link to check : http://www.naturallyfit.com 
**/

// ACF used to create additional Description field for Categories
if(get_field('additional_text', 'category_'.get_query_var('cat'))){
    the_field('additional_text', 'category_'.get_query_var('cat'));
}

/**
  *  Posts Scroller for Categories
**/
function bd_posts_scroller($category_ID)
{
    if($category_ID != ''){
        $args= array('posts_per_page'=> $slider_nub , 'cat' => $category_ID, 'meta_key' => 'featured', 'meta_value' => 1,'orderby' => 'menu_order','order' => 'ASC');
    }
    
    ?>
    <div class="container">
        <div id="breaking-news-in-pic" class="breaking-news-in-pic">
            <div class="home-box-title">
                <h2><b><?php echo 'Hot News'; ?></b>
                    <div class="breaking-news-in-pic-nav box-title-more">
                        <a class="prev" id="breaking-news-in-pic-prev" href="#"><i class="icon-chevron-left"></i></a>
                        <a class="nxt" id="breaking-news-in-pic-nxt" href="#"><i class="icon-chevron-right"></i></a>
                    </div>
                </h2>
            </div><!-- .box-title/-->

            <div class="post-warpper">
            <?php
            $featured_posts_query = new wp_query( $args );
            if( $featured_posts_query->have_posts() ) {
                $i= 0;
                while ( $featured_posts_query->have_posts() ) : $featured_posts_query->the_post(); ?>
                    <div class="post-item">
                    <?php
                        global $post;
                        $img_w      = 300;
                        $img_h      = 210;
                        $thumb      = bd_post_image('full');
                        $image      = aq_resize( $thumb, $img_w, $img_h, true );
                        $url_meta = get_post_meta($post->ID,'cat_hot_url',true);
                        if($url_meta == ''){
                            $url = get_permalink( $post->ID );
                        } else{
                            $url = $url_meta;
                        }

                        if($image =='')
                        {
                            $image = BD_IMG .'default-300-210.png';
                        }
                        $alt        = get_the_title();

                        if (strpos(bd_post_image(), 'youtube'))
                        {
                            echo '<div class="post-image"><a href="'. $url .'" title="'. $alt .'"><img width="'. $img_w .'" height="'. $img_h .'"  src="'. bd_post_image('full').'" alt="'. $alt .'" /></a></div><!-- .post-image/-->' ."\n";
                        }
                        elseif (strpos(bd_post_image(), 'vimeo'))
                        {
                            echo '<div class="post-image"><a href="'. $url .'" title="'. $alt .'"><img width="'. $img_w .'" height="'. $img_h .'"  src="'. bd_post_image('full').'" alt="'. $alt .'" /></a></div><!-- .post-image/-->' ."\n";
                        }
                        elseif (strpos(bd_post_image(), 'dailymotion'))
                        {
                            echo '<div class="post-image"><a href="'. $url .'" title="'. $alt .'"><img width="'. $img_w .'" height="'. $img_h .'"  src="'. bd_post_image('full').'" alt="'. $alt .'" /></a></div><!-- .post-image/-->' ."\n";
                        }
                        else
                        {
                            echo '<div class="post-image"><a href="'. $url .'" title="'. $alt .'"><img width="'. $img_w .'" height="'. $img_h .'" src="'. $image .'" alt="'. $alt .'" /></a></div><!-- .post-image/-->' ."\n";
                        }
                    ?>
                    <div class="post-caption">
                        <div class="post-meta">
                            <a href="<?php echo $url; ?>" title="<?php printf(__( '%s', 'bd' ), the_title_attribute( 'echo=0' )); ?>" rel="bookmark"><?php the_title(); ?></a>
                        </div><!-- .post-meta/-->
                    </div><!-- .post-caption/-->
                </div><!-- article/-->
                <?php endwhile; 
            } wp_reset_query(); ?>
        </div>
<?php } ?>