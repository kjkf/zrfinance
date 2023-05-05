<?php
/*
Template Name: Страница школы
*/
?>

<?php get_header();?>

<?php
function is_empty($var)
{
    return !($var || (is_scalar($var) && strlen($var)));
}
?>
<?php
    $page_title = get_locale() == 'ru_RU' ?  'Школа' : 'Мектеп';
    $mainPageId = get_page_by_title($page_title); 
  ?>
<?php
$isNewsBlockVisible = get_field('isNewsBlockVisible');
if ($isNewsBlockVisible) : ?>
<section class="bl news bl-info" id="news">
    <div class="container">
        <?php
        $news_title = get_field('news_title');
        if (!is_empty($news_title)):
            ?>
            <h2 class="title"><?php echo $news_title?></h2>
        <div class="hide-text-wrap">
            <div class="bl-in hide-text excerpt">
                <?php
                // параметры по умолчанию
                $args = array(
                    //'numberposts' => 2,
                    'cat'    => '3,-11',
                    'orderby'     => 'date',
                    'order'       => 'DESC',
                    'post_type'   => 'post',
                    //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
                );
                // ваш запрос и код вывода с пагинацией
                //$wp_query = new WP_Query( $args );
                $query = new WP_Query( $args );

                while ( $query->have_posts() ) {
                    $query->the_post();

                    ?>
                    <article class="bl-info-item">
                        <div class="bl-info-img">
                            <a href="<?php the_permalink();?>"><?php the_post_thumbnail('news_thumb')?></a>
                        </div>
                        <div class="bl-info-descr">
                            <h3 class="title"><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>
                            <div class="bl-info-date">
                                <span class="date-label"><?php echo get_field('date_translate', $mainPageId->ID)?>:</span>
                                <span class="date"><?php the_time('d.m.Y'); ?></span>
                            </div>

                            <div class="bl-info-text">
                                <?php the_excerpt();?>
                            </div>
<a href="<?php the_permalink(); ?>" class="bl-info-detail">
  			 	<?php echo get_field('more_translate', $mainPageId->ID)?>
			    </a>
                        </div>
                    </article>
                    <?php
                }
                // вернем global $wp_query
                wp_reset_postdata();
                ?>

            </div>
            <?php
            //global $wp_query;
            //echo '~~~~~~~~~'.$query->max_num_pages;
            if ($query->max_num_pages > 1) : ?>
                <button id="loadmore" type="button" class="btn btn--more"><?php echo get_field('show_more_translate', $mainPageId->ID)?></button>
                <script>
                    var ajaxurl = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';
                    var posts_vars = '<?php echo serialize($query->query_vars); ?>';
                    var current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
                    var max_pages = '<?php echo $query->max_num_pages; ?>';
                </script>
                <!--<button id="loadmore">Показать ещё</button>-->

            <?php endif; ?>

        </div>
        <?endif;?>
    </div>
</section>
<?php endif;?>

<?php
$isAboutBlockVisible = get_field('isAboutBlockVisible');
if ($isAboutBlockVisible) : ?>
<main class="bl bl-sm bl-accent aboutus" id="about">
    <div class="container">
        <?php
        $about_title = get_field('about_title');
        if (!is_empty($about_title)):
            ?>
            <h2 class="title"><?php echo $about_title?></h2>

                <div class="bl-in">


                    <div class="aboutus-item"><?php the_field('about_block_text'); ?></div>
                    <!--<div class="aboutus-item"><?php /*the_field('about2'); */?></div>

                    <?php
/*                    $list=get_field('about_list');
                    if ($list) {
                        $a_list = explode(";", $list);*/?>
                        <ul class="aboutus-item">
                            <?/*
                            foreach ($a_list as $item) {*/?>
                                <li><?/*echo $item;*/?></li>
                            <?/*}*/?>
                        </ul>
                    <?/*}
                    */?>


                    <div class="aboutus-item">
                        <?php /*the_field('about3'); */?>
                    </div>
                    <div class="aboutus-item">
                        <?php /*the_field('about4'); */?>
                    </div>-->
                </div>
            <div class="ball-ic orange"><svg class="ball"><use xlink:href="#ball"/></svg></div>
            <div class="ball-ic aqua"><svg class="ball"><use xlink:href="#ball"/></svg></div>
        <?endif;?>
    </div>
</main>
<?php endif;?>

<?php
$isTeamBlockVisible = get_field('isTeamBlockVisible');
if ($isTeamBlockVisible) : ?>
<section class="bl team bl-info" id="team">
    <div class="container">
        <?php
        $team_title = get_field('team_title');

        if (!is_empty($team_title)):
            ?>
            <h2 class="title"><?echo $team_title?></h2>
            <div class="hide-text-wrap">
                <div class="bl-in hide-text excerpt">
                    <?

                    // параметры по умолчанию
                    $arg = array(
                        //'numberposts' => 0,
                        'cat'    => '10',//'-11',
                        'post_type'   => 'staff',
                        //'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
                    );
                    // ваш запрос и код вывода с пагинацией
                    //$wp_query = new WP_Query( $args );
                    $query1 = new WP_Query( $arg );

                    while ( $query1->have_posts() ) {
                        $query1->the_post();
                        $full_name = get_the_title();
                        $photo_url = get_field('photo');
                        $position = get_field('position');
                        $permalink = get_permalink();
                        ?>
                        <div class="bl-info-item">
                            <div class="bl-info-img">
                                <a href="<?php echo esc_url( $permalink ); ?>">
                                    <img src="<?php echo esc_url($photo_url)?>" alt="">
                                </a>
                            </div>
                            <div class="bl-info-descr">
                                <h4 class="title"><?php echo esc_html($full_name)?></h4>
                                <div class="subtitle"><?php echo get_field('position_translate', $mainPageId->ID)?>: <span class="team-position"><?php echo esc_html($position)?></span></div>
                                <div class="team-info">
                                    <div class="team-info-title"><?php echo get_field('education_translate', $mainPageId->ID)?>:</div>
                                    <?php
                                    $education = get_field('education');
                                    $count=0;
                                    if( $education ):
                                        ?>
                                        <ol>
                                            <?php foreach ( $education as $item ):
                                                $univer = get_the_title($item->ID);
                                                ?>
                                                <li><?php echo esc_html($univer)?></li>
                                                <?php
                                                if ($count == 1) {
                                                    break;
                                                }

                                                $count = $count + 1;
                                            endforeach;?>
                                        </ol>
                                    <?php endif;?>
                                </div>
                                <a href="<?php echo esc_url( $permalink ); ?>" class="bl-info-detail"><?php echo get_field('more_translate', $mainPageId->ID)?>..</a>
                            </div>
                        </div>
                        <?
                    }
                    // вернем global $wp_query
                    wp_reset_postdata();
                    ?>
                </div>
                <?php

                if ($query1->max_num_pages > 1) : ?>
                    <button id="loadmore_staff" type="button" class="btn btn--more"><?php echo get_field('show_more_translate', $mainPageId->ID)?></button>
                    <script>
                        //var ajaxurl1 = '<?php echo site_url(); ?>/wp-admin/admin-ajax.php';
                        var posts_vars_staff = '<?php echo serialize($query1->query_vars); ?>';
                        var current_page_staff = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
                        var max_pages_staff = '<?php echo $query1->max_num_pages; ?>';
                    </script>
                    <!--<button id="loadmore">Показать ещё</button>-->

                <?php endif; ?>
            </div>
        <?endif;?>
    </div>
</section>
<?php endif;?>

<?php
$isMethodsBlockVisible = get_field('isMethodsBlockVisible');
if ($isMethodsBlockVisible) : ?>
<main class="bl bl-sm bl-accent aboutus">
    <div class="container">
        <?php
        $materials = get_field('materials');
        $methods_title = get_field('methods_title');
        if (!is_empty($materials)):
            ?>
            <h2 class="title"><?php echo $methods_title?></h2>

                <div class="bl-in">

                <?php
                if ($materials) :
                        foreach($materials as $material):
                            $material_link = get_field('methods', $material->ID);
                            $material_title = get_the_title($material->ID);
                            
                            ?>
                            <div class="group-item">
                                <a href="<?php echo $material_link?>" download><?php echo $material_title?></a>

                            </div>

                        <?php
                        endforeach;
                    endif?>
                    
                </div>
            <div class="ball-ic orange"><svg class="ball"><use xlink:href="#ball"/></svg></div>
            <div class="ball-ic aqua"><svg class="ball"><use xlink:href="#ball"/></svg></div>
        <?endif;?>
    </div>
</main>
<?php endif;?>

<?php
$isRoomsBlockVisible = get_field('isRoomsBlockVisible');
if ($isRoomsBlockVisible) : ?>
<div class="bl bl-sm bl-slider rooms" id="rooms">
    <div class="container">
        <?php
        $rooms_title = get_field('rooms_block_title');
        if (!is_empty($rooms_title)):
            ?>
            <h2 class="title"><?echo esc_html($rooms_title);?></h2>
            <div class="bl-in">
                <div class="slider">
                    <?
                    $rooms_note = get_field('rooms_note');
                    if (!is_empty($rooms_note)):
                        ?>
                        <div class="attention">
                            <p><?echo $rooms_note?></p>
                        </div>
                    <?
                    endif;?>
                    <div class="slider-wrapper">
                        <div class="slider-items">
                            <?php
                            $rooms_slider = get_field('rooms_slider');

                            echo do_shortcode($rooms_slider);
                            ?>
                            <!--<div class="slider-item">
                                <img src="#" alt="" class="slider-img">
                                <div class="slider-text">
                                    <div class="">
                                        <h3 class="title">Наименование помещения</h3>
                                        <div>Описание: Площадь - 20кв.м.; Расчитано на 15 детей;</div>
                                    </div>
                                    <div class="slider-indic">
                                        <span>1</span>/<span>20</span>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                    <!--<a href="#" class="slider-control slider-control_prev" href="#" role="button"></a>
                    <a href="#" class="slider-control slider-control_next" href="#" role="button"></a>-->
                </div>
            </div>
            <div class="ball-ic red"><svg class="ball"><use xlink:href="#ball"/></svg></div>
            <div class="ball-ic violet"><svg class="ball"><use xlink:href="#ball"/></svg></div>
        <?endif;?>
    </div>
</div>
<?php endif;?>

<?php
$isFoodBlockVisible = get_field('isFoodBlockVisible');
if ($isFoodBlockVisible) : ?>
<div class="bl bl-sm bl-slider food" id="food">
    <div class="container">
        <?php
        $food_title = get_field('food_title');
        if (!is_empty($food_title)):
            ?>
            <h2 class="title"><?echo esc_html($food_title);?></h2>
            <div class="bl-in">
                <div class="slider">
                    <?
                    $food_note = get_field('food_note');
                    if (!is_empty($food_note)):
                    ?>
                    <div class="attention">
                        <p><?echo $food_note?></p>
                    </div>
                    <?
                    endif;?>
                    <div class="slider-wrapper">
                        <div class="slider-items">

                            <?php
                            $food_slider = get_field('food_slider');

                            echo do_shortcode($food_slider);
                            ?>
                        </div>
                    </div>
                    <!--<a href="#" class="slider-control slider-control_prev" href="#" role="button"></a>
                    <a href="#" class="slider-control slider-control_next" href="#" role="button"></a>-->
                </div>
            </div>
            <div class="ball-ic yellow"><svg class="ball "><use xlink:href="#ball"/></svg></div>
            <div class="ball-ic light-blue"><svg class="ball "><use xlink:href="#ball"/></svg></div>
        <?endif;?>
    </div>
</div>
<?php endif;?>

<?php
$isCLassesBlockVisible = get_field('isClassesBlockVisible');
if ($isCLassesBlockVisible) : ?>
    <div class="bl bl-sm bl-slider classes" id="classes">
        <div class="container">
            <?php
            $classes_title = get_field('classes_title');
            if (!is_empty($classes_title)):
                ?>
                <h2 class="title"><?echo esc_html($classes_title);?></h2>
                <div class="bl-in">
                    <div class="slider">
                        <?
                        $classes_note = get_field('classes_note');
                        if (!is_empty($classes_note)):
                            ?>
                            <div class="attention">
                                <p><?echo $classes_note?></p>
                            </div>
                        <?
                        endif;?>
                        <div class="slider-wrapper">
                            <div class="slider-items">
                                <!--<div class="slider-item">
                                    <img src="#" alt="" class="slider-img">
                                    <div class="slider-text">
                                        <div class="">
                                            <h3 class="title">Игровая площадка “Грибок”</h3>
                                            <div>Описание: Площадь - 40кв.м.; Игровой инвентарь: качеля, горка, карусель.</div>
                                        </div>
                                        <div class="slider-indic">
                                            <span>1</span>/<span>20</span>
                                        </div>
                                    </div>
                                </div>-->
                                <?php
                                $classes_slider = get_field('classes_slider');
                                echo do_shortcode($classes_slider);
                                ?>
                            </div>
                        </div>
                        <!--<a href="#" class="slider-control slider-control_prev" href="#" role="button"></a>
                        <a href="#" class="slider-control slider-control_next" href="#" role="button"></a>-->
                    </div>
                </div>
                <div class="ball-ic yellow"><svg class="ball "><use xlink:href="#ball"/></svg></div>
                <div class="ball-ic light-blue"><svg class="ball "><use xlink:href="#ball"/></svg></div>
            <?endif;?>
        </div>
    </div>
<?php endif;?>

<?php
$isGroupsBlockVisible = get_field('isGroupsBlockVisible');
if ($isGroupsBlockVisible) : ?>
<div class="bl bl-sm group bl-accent" id="groups">
    <div class="container">
        <?php
        $group_title= get_field('group_title');
        if (!is_empty($group_title)):
            ?>
            <h2 class="title"><?echo esc_html($group_title);?></h2>
            <div class="bl-in">
                <h4 class="title"><?php the_field('group_text');?></h4>
                <div class="group-wrap">
                    <!--<div>
                        <?/* the_field('groups__1');*/?>
                    </div>-->

                    <?php
                    $groups = get_field('groups');
                    if ($groups) :
                        foreach($groups as $group):
                            $group_name = get_the_title($group->ID);

                            $group_age = get_field('group_age', $group->ID);
                            $group_age_array = explode("-", $group_age);
                            $group_age_min = $group_age_array[0] ? $group_age_array[0] : 1.5;
                            $group_age_max = $group_age_array[1] ? $group_age_array[1] : 6.5;

                            $group_amount = get_field('group_amount', $group->ID);
                            //$group_amount_array = explode("-", $group_amount);
                            //$group_amount_min = $group_amount_array[0] ? $group_amount_array[0] : 10;
                            //$group_amount_max = $group_amount_array[1] ? $group_amount_array[1] : 20;
                            ?>
                            <div class="group-item">
                                <div class="group-item-title">Группа “<?php echo esc_html($group_name)?>”</div>
                                <ul class="group-info">
                                    <li>
                                        <span class="group-label">Возраст:</span>
                                        <span>от <?php echo esc_html($group_age_min)?> до <?php echo esc_html($group_age_max)?> лет</span>
                                    </li>
                                    <li>
                                        <span class="group-label">Количество детей:</span>
                                        <span><?php echo esc_html($group_amount)?></span>
                                    </li>
                                </ul>
                            </div>

                        <?php
                        endforeach;
                    endif?>

                </div>
            </div>
            <div class="ball-ic pink"><svg class="ball "><use xlink:href="#ball"/></svg></div>
            <div class="ball-ic green"><svg class="ball "><use xlink:href="#ball"/></svg></div>
        <?endif;?>
    </div>
</div>
<?php endif;?>

<?php
$isFeedbackBlockVisible = get_field('isFeedbackBlockVisible');
if ($isFeedbackBlockVisible) : ?>
    <div class="bl bl-sm feedback" id="feedback">
        <div class="container">
            <?php
            $form_title= get_field('form_title');
            if (!is_empty($form_title)):
                ?>
                <h2 class="title"><?echo esc_html($form_title);?></h2>
                <div class="bl-in">
                    <?php
                    $contact_form = get_field('contact_form');
                    echo do_shortcode($contact_form);
                    //echo do_shortcode('[contact-form-7 id="137" title="Контактная форма 1"]');
                    ?>

                </div>
                <div class="ball-ic blue"><svg class ="ball"><use xlink:href="#ball"/></svg></div>
                <div class="ball-ic aqua"><svg class="ball"><use xlink:href="#ball"/></svg></div>
            <?php endif;?>
        </div>
    </div>
<?php endif;?>

<?php
$isContactsBlockVisible = get_field('isContactsBlockVisible');
if ($isContactsBlockVisible) : ?>
<div class="bl bl-sm bl-accent contacts" id="contacts">
    <div class="container">
        <?php
        $contact_title= get_field('contact_title');
        if (!is_empty($contact_title)):
            ?>
            <h2 class="title"><?echo esc_html($contact_title);?></h2>
            <div class="contacts-map">
                <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Ab9154135fddcb540a36a5e15f693098675e6158eb26909566cb480a0c8362bac&amp;width=100%25&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
            </div>
            <div class="bl-in">
                <div class="contacts-address">
                    <ul>
                        <li>
                            <h3 class="title"><?php the_field("address_translate", $mainPageId->ID)?>:</h3>
                            <address><?php the_field("address");?></address>
                        </li>
                        <li>
                            <h3 class="title"><?php the_field("phone_translate", $mainPageId->ID)?>:</h3>
                            <a href="tel:<?php echo str_replace(" ","",get_field('phone'));?>" class="phone"><?php the_field("phone");?></a>
                        </li>
                        <li>
                            <h3 class="title">E-mail:</h3>
                            <a href="mailto:<?php the_field("email");?>"><?php the_field("email");?></a>
                        </li>
                        <li>
                            <h3 class="title">Web: </h3>
                            <a href="<?php the_field("web");?>"><?php the_field("web");?></a>
                        </li>
                    </ul>
                </div>
            </div>
        <?endif;?>
        <div class="bl-in br-top w-100">
            <div class="copyright">Copyright © 2021 BLU-GROUP</div>
        </div>
    </div>
</div>
<?php endif;?>


<?php get_footer();?>
