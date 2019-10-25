<?php

/**
 * Single Leadquiz Template
 */

?>
<!DOCTYPE html>
<html lang="en" class="leadquizzes">
<head>
    <meta charset="UTF-8">
    <title><?php the_title(); ?> | <?php bloginfo('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body>
    <?php while(have_posts()) : the_post(); ?>

        <?php if ( post_password_required() ) : ?>

            <form action="<?php get_option('siteurl'); ?>/wp-login.php?action=postpass" method="post" class="leadquiz-protected-form">
                <h2>This page is password protected. Please enter your quiz password below:</h2>
                <div class="input-field">
                    <input name="post_password" id="<?php $label ?>" type="password" size="20" />
                </div>
                <input type="submit" name="Submit" class="protected-button" value="Submit" />
            </form>
            
        <?php else: ?>

            <?php the_quiz(); ?>

        <?php endif; ?>

    <?php endwhile; ?>
    
    <?php wp_reset_postdata(); ?>
    
    <?php wp_footer(); ?>
</body>
</html>