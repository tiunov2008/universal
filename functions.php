<?php
//подключение стилей
function enqueue_universal_style() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
    wp_enqueue_style( 'swiper-slider', get_template_directory_uri(  ) . "/assets/css/swiper-bundle.min.css" , "style" );
    wp_enqueue_style( 'universal-theme', get_template_directory_uri(  ) . "/assets/css/universal.css" , "style" );
    wp_enqueue_style( 'Roboto-Slab', 'https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&display=swap');
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', 'https://code.jquery.com/jquery-3.6.0.min.js');
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('swiper', get_template_directory_uri(  ) . "/assets/js/swiper-bundle.min.js", null, null, true);
	wp_enqueue_script('scripts', get_template_directory_uri(  ) . "/assets/js/scripts.js", 'swiper' , null ,true);

}
add_action( 'wp_enqueue_scripts', 'enqueue_universal_style' );
//Добавления расширенных возможностей
if ( ! function_exists( 'universal_theme_setup' ) ) :
    function universal_theme_setup(){
        //добавление title
        add_theme_support('title-tag');
        //добавление минеатюр
        add_theme_support( 'post-thumbnails', array( 'post' ) );   
        //добавление logo
        add_theme_support( 'custom-logo', [
            'width'       => 163,
            'flex-height' => true,
            'header-text' => 'Universal',
            'unlink-homepage-logo' => false,
        ] );
        //Регистрация меню
        register_nav_menus( [
            'header_menu' => 'Меню в шапке',
            'footer_menu' => 'Меню в подвале'
        ] ); 

}
endif;
add_action( 'after_setup_theme', 'universal_theme_setup' );


add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type( 'lesson', [
		'label'  => null,
		'labels' => [
			'name'               => 'Видеоуроки', // основное название для типа записи
			'singular_name'      => 'Видеоурок', // название для одной записи этого типа
			'add_new'            => 'Добавить видеоурок', // для добавления новой записи
			'add_new_item'       => 'Добавление видеоурока', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование видеоурока', // для редактирования типа записи
			'new_item'           => 'Новый видеоурок', // текст новой записи
			'view_item'          => 'Смотреть видеоурок', // для просмотра записи этого типа.
			'search_items'       => 'Искать видеоуроки', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Видеоуроки', // название меню
		],
		'description'         => 'Раздел с видеоуроками',
		'public'              => true,
		// 'publicly_queryable'  => null, // зависит от public
		// 'exclude_from_search' => null, // зависит от public
		// 'show_ui'             => null, // зависит от public
		// 'show_in_nav_menus'   => null, // зависит от public
		'show_in_menu'        => true, // показывать ли в меню адмнки
		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
		'show_in_rest'        => null, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-welcome-learn-more',
		'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => [ 'title', 'editor' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => [],
		'has_archive'         => false,
		'rewrite'             => true,
		'query_var'           => true,
	] );
	}	
	// хук, через который подключается функция
	// регистрирующая новые таксономии (create_lesson_taxonomies)
	add_action( 'init', 'create_lesson_taxonomies' );

	// функция, создающая 2 новые таксономии "genres" и "writers" для постов типа "lesson"
	function create_lesson_taxonomies(){

		// Добавляем древовидную таксономию 'genre' (как категории)
		register_taxonomy('genre', array('lesson'), array(
			'hierarchical'  => true,
			'labels'        => array(
				'name'              => _x( 'Genres', 'taxonomy general name' ),
				'singular_name'     => _x( 'Genre', 'taxonomy singular name' ),
				'search_items'      =>  __( 'Search Genres' ),
				'all_items'         => __( 'All Genres' ),
				'parent_item'       => __( 'Parent Genre' ),
				'parent_item_colon' => __( 'Parent Genre:' ),
				'edit_item'         => __( 'Edit Genre' ),
				'update_item'       => __( 'Update Genre' ),
				'add_new_item'      => __( 'Add New Genre' ),
				'new_item_name'     => __( 'New Genre Name' ),
				'menu_name'         => __( 'Genre' ),
			),
			'show_ui'       => true,
			'query_var'     => true,
			'rewrite'       => array( 'slug' => 'the_genre' ),
		));

		// Добавляем НЕ древовидную таксономию 'Teacher' (как метки)
		register_taxonomy('Teacher', 'lesson',array(
			'hierarchical'  => false,
			'labels'        => array(
				'name'                        => _x( 'Teachers', 'taxonomy general name' ),
				'singular_name'               => _x( 'Teacher', 'taxonomy singular name' ),
				'search_items'                =>  __( 'Search Teachers' ),
				'popular_items'               => __( 'Popular Teachers' ),
				'all_items'                   => __( 'All Teachers' ),
				'parent_item'                 => null,
				'parent_item_colon'           => null,
				'edit_item'                   => __( 'Edit Teacher' ),
				'update_item'                 => __( 'Update Teacher' ),
				'add_new_item'                => __( 'Add New Teacher' ),
				'new_item_name'               => __( 'New Teacher Name' ),
				'separate_items_with_commas'  => __( 'Separate Teachers with commas' ),
				'add_or_remove_items'         => __( 'Add or remove Teachers' ),
				'choose_from_most_used'       => __( 'Choose from the most used teachers' ),
				'menu_name'                   => __( 'teachers' ),
			),
			'show_ui'       => true,
			'query_var'     => true,
			//'rewrite'       => array( 'slug' => 'the_writer' ), // свой слаг в URL
		));
	}


add_action( 'widgets_init', 'register_my_widgets' );
function register_my_widgets(){

	register_sidebar( 
    array(
		'name'          => sprintf(__('Main Sidebar Top'), $i ),
		'id'            => "main-sidebar-top",
		'description'   => 'Добавте виджеты сюда',
		'class'         => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => "</section>\n",
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => "</h2>\n",
	) );
	
	register_sidebar( 
		array(
			'name'          => sprintf(__('Main Sidebar Bottom'), $i ),
			'id'            => "main-sidebar-bottom",
			'description'   => 'Добавте виджеты сюда',
			'class'         => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => "</section>\n",
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => "</h2>\n",
		) );
	
	register_sidebar( 
		array(
			'name'          => sprintf(__('Footer Menu'), $i ),
			'id'            => "sidebar-footer",
			'description'   => 'Добавте меню сюда',
			'class'         => '',
			'before_widget' => '<section id="%1$s" class="footer-menu %2$s">',
			'after_widget'  => "</section>\n",
			'before_title'  => '<h2 class="footer-menu-title">',
			'after_title'   => "</h2>\n",
		) );
	register_sidebar( 
		array(
			'name'          => sprintf(__('Footer Text'), $i ),
			'id'            => "sidebar-footer-text",
			'description'   => 'Добавте текст сюда',
			'class'         => '',
			'before_widget' => '<section id="%1$s" class="footer-text %2$s">',
			'after_widget'  => "</section>\n",
			'before_title'  => '',
			'after_title'   => "",
		) );
				
}

add_filter( 'widget_tag_cloud_args', 'edit_widget_tag_cloud_args');

function edit_widget_tag_cloud_args($args){
	$args['unit'] = 'px';
	$args['smallest'] = '14';
	$args['largest'] = '14';
	$args['number'] = '16';
	$args['orderby'] = 'count';
	return $args;
}


/**
 * Добавление нового виджета Downloader_Widget.
 */
class Downloader_Widget extends WP_Widget {

	// Регистрация виджета используя основной класс
	function __construct() {
		// вызов конструктора выглядит так:
		// __construct( $id_base, $name, $widget_options = array(), $control_options = array() )
		parent::__construct(
			'downloader_widget', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: Downloader_Widget
			'Полезные файлы ',
			array( 'description' => 'Файлы для скачивания', 'classname' => 'widget-downloader', )
		);

		// скрипты/стили виджета, только если он активен
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action('wp_enqueue_scripts', array( $this, 'add_downloader_widget_scripts' ));
			add_action('wp_head', array( $this, 'add_downloader_widget_style' ) );
		}
	}

	/**
	 * Вывод виджета во Фронт-энде
	 *
	 * @param array $args     аргументы виджета.
	 * @param array $instance сохраненные данные из настроек
	 */
	function widget( $args, $instance ) {
		$title = $instance['title'];
        $description = $instance['description'];
        $link = $instance['link'];

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
        if ( ! empty( $description ) ) {
			echo '<p>' . $description . '</p>';
		}
        if ( ! empty( $link ) ) {
			echo '<a class="widget-link" href="' . $link . '">
            <img class="widget-link-icon" src="' . get_template_directory_uri(  ) . '/assets/img/download.svg">
            Скачать</a>';
		}
		echo $args['after_widget'];
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	function form( $instance ) {
		$title = @ $instance['title'] ?: 'Полезные файлы';
        $description = @ $instance['description'] ?: 'Описание';
        $link = @ $instance['link'] ?: 'http://example.com';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Описание:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo esc_attr( $description ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Ссылка на файл:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>">
		</p>
		<?php 
	}

	/**
	 * Сохранение настроек виджета. Здесь данные должны быть очищены и возвращены для сохранения их в базу данных.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance новые настройки
	 * @param array $old_instance предыдущие настройки
	 *
	 * @return array данные которые будут сохранены
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';
        $instance['link'] = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';
		return $instance;
	}

	// скрипт виджета
	function add_downloader_widget_scripts() {
		// фильтр чтобы можно было отключить скрипты
		if( ! apply_filters( 'show_downloader_widget_script', true, $this->id_base ) )
			return;

		$theme_url = get_stylesheet_directory_uri();

		wp_enqueue_script('downloader_widget_script', $theme_url .'/downloader_widget_script.js' );
	}

	// стили виджета
	function add_downloader_widget_style() {
		// фильтр чтобы можно было отключить стили
		if( ! apply_filters( 'show_downloader_widget_style', true, $this->id_base ) )
			return;
		?>
		<style type="text/css">
			.downloader_widget a{ display:inline; }
		</style>
		<?php
	}

} 
// конец класса Downloader_Widget

// регистрация Downloader_Widget в WordPress
function register_Downloader_Widget() {
	register_widget( 'Downloader_Widget' );
}
add_action( 'widgets_init', 'register_Downloader_Widget' );

/**
 * Добавление нового виджета Social_Widget.
 */
class Social_Widget extends WP_Widget {

	// Регистрация виджета используя основной класс
	function __construct() {
		// вызов конструктора выглядит так:
		// __construct( $id_base, $name, $widget_options = array(), $control_options = array() )
		parent::__construct(
			'social_widget', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: Downloader_Widget
			'Cоциальные сети ',
			array( 'description' => 'Социальные сети', 'classname' => 'widget-social', )
		);

		// скрипты/стили виджета, только если он активен
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action('wp_enqueue_scripts', array( $this, 'add_social_widget_scripts' ));
			add_action('wp_head', array( $this, 'add_social_widget_style' ) );
		}
	}

	/**
	 * Вывод виджета во Фронт-энде
	 *
	 * @param array $args     аргументы виджета.
	 * @param array $instance сохраненные данные из настроек
	 */
	function widget( $args, $instance ) {
		$title = $instance['title'];
		$facebook = $instance['facebook'];
        $instagram = $instance['instagram'];
        $vk = $instance['vk'];
		$twitter = $instance['twitter'];
		$youtube = $instance['youtube'];
		echo $args['before_widget'];
		echo '<p class="widget-title">' . $title . '</p>';
        if ( ! empty( $facebook ) ) {
			echo '<a class="widget-link widget-link-1" href="' . $facebook . '">
            <img class="widget-icon " src="' . get_template_directory_uri(  ) . '/assets/img/facebook.svg"></a>';
		}
		if ( ! empty( $instagram ) ) {
			echo '<a class="widget-link widget-link-2" href="' . $instagram . '">
            <img width="50px" class="widget-icon" src="' . get_template_directory_uri(  ) . '/assets/img/instagram.jpg"></a>';
		}
		if ( ! empty( $vk ) ) {
			echo '<a class="widget-link widget-link-3" href="' . $vk . '">
            <img class="widget-icon" src="' . get_template_directory_uri(  ) . '/assets/img/vk.svg"></a>';
		}
		if ( ! empty( $twitter ) ) {
			echo '<a class="widget-link widget-link-4" href="' . $twitter . '">
            <img class="widget-icon" src="' . get_template_directory_uri(  ) . '/assets/img/twitter.svg"></a>';
		}
		if ( ! empty( $youtube ) ) {
			echo '<a class="widget-link widget-link-5" href="' . $youtube . '">
            <img class="widget-icon" src="' . get_template_directory_uri(  ) . '/assets/img/youtube.svg"></a>';
		}
		echo $args['after_widget'];
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	function form( $instance ) {
		$title = @ $instance['title'] ?: '';
		$facebook = @ $instance['facebook'] ?: '';
        $instagram = @ $instance['instagram'] ?: '';
        $vk = @ $instance['vk'] ?: '';
		$twitter = @ $instance['description'] ?: '';
        $youtube = @ $instance['link'] ?: '';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'instagram' ); ?>"><?php _e( 'Instagram:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'instagram' ); ?>" name="<?php echo $this->get_field_name( 'instagram' ); ?>" type="text" value="<?php echo esc_attr( $instagram ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'vk' ); ?>"><?php _e( 'Vk:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'vk' ); ?>" name="<?php echo $this->get_field_name( 'vk' ); ?>" type="text" value="<?php echo esc_attr( $vk ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'Youtube:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo esc_attr( $youtube ); ?>">
		</p>
		
		<?php 
	}

	/**
	 * Сохранение настроек виджета. Здесь данные должны быть очищены и возвращены для сохранения их в базу данных.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance новые настройки
	 * @param array $old_instance предыдущие настройки
	 *
	 * @return array данные которые будут сохранены
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ) : '';
        $instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? strip_tags( $new_instance['instagram'] ) : '';
        $instance['vk'] = ( ! empty( $new_instance['vk'] ) ) ? strip_tags( $new_instance['vk'] ) : '';
		$instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? strip_tags( $new_instance['twitter'] ) : '';
        $instance['youtube'] = ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ) : '';
		return $instance;
	}

	// скрипт виджета
	function add_social_widget_scripts() {
		// фильтр чтобы можно было отключить скрипты
		if( ! apply_filters( 'show_social_widget_script', true, $this->id_base ) )
			return;

		$theme_url = get_stylesheet_directory_uri();

		wp_enqueue_script('social_widget_script', $theme_url .'/social_widget_script.js' );
	}

	// стили виджета
	function add_social_widget_style() {
		// фильтр чтобы можно было отключить стили
		if( ! apply_filters( 'show_social_widget_style', true, $this->id_base ) )
			return;
		?>
		<style type="text/css">
			.social_widget a{ display:inline; }
		</style>
		<?php
	}

} 
// конец класса Social_Widget

// регистрация Social_Widget в WordPress
function register_Social_Widget() {
	register_widget( 'Social_Widget' );
}
add_action( 'widgets_init', 'register_Social_Widget' );

/**
 * Добавление нового виджета Recents_posts_widget.
 */
class Recents_posts_widget extends WP_Widget {

	// Регистрация виджета используя основной класс
	function __construct() {
		// вызов конструктора выглядит так:
		// __construct( $id_base, $name, $widget_options = array(), $control_options = array() )
		parent::__construct(
			'recent_posts_widget', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: recent_posts_Widget
			'Недавно опубликовано',
			array( 'description' => 'Последние посты', 'classname' => 'widget-recent-posts', )
		);

		// скрипты/стили виджета, только если он активен
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action('wp_enqueue_scripts', array( $this, 'add_recent_posts_widget_scripts' ));
			add_action('wp_head', array( $this, 'add_recent_posts_widget_style' ) );
		}
	}

	/**
	 * Вывод виджета во Фронт-энде
	 *
	 * @param array $args     аргументы виджета.
	 * @param array $instance сохраненные данные из настроек
	 */
	function widget( $args, $instance ) {
		$title = $instance['title'];
        $count = $instance['count'];

		echo $args['before_widget'];

        if ( ! empty( $count ) ) {
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];
				echo '<div class="widget-recent-posts-wrapper">';
			}
			global $post;
			$postslist = get_posts( array( 'posts_per_page' => $count, 'order'=> 'ASC', 'orderby' => 'title' ) );
			foreach ( $postslist as $post ){
				setup_postdata($post);
				?>
				<a href="<?php echo get_the_permalink()?>" class="recent-posts-link">
					<img src="<?php
                        //должно находится внутри цикла
                        if( has_post_thumbnail() ) {
                            echo get_the_post_thumbnail_url(null, 'thumbnail');
                        }
                        else {
                            echo get_template_directory_uri().'/assets/img/img-default.jpg';
                        }
                        ?>" class="recent-posts-thumb" alt="">
					<div class="recent-posts-info">
						<h4 class="recent-posts-title">
							<?php the_title(); ?>
						</h4>
							<span class="recent-posts-time">
							<?php $time_diff = human_time_diff( get_post_time('U'), current_time('timestamp') );
							echo "Опубликовано $time_diff назад.";
							//> Опубликовано 5 лет назад.?>
							</span>
					

					</div>
					
				</a>
				<?php
			}
wp_reset_postdata();
echo '</div>';
		}
		echo $args['after_widget'];
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	function form( $instance ) {
		$title = @ $instance['title'] ?: 'Недавно опубликовано';
        $count = @ $instance['count'] ?: '7';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Количество постов:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>">
		</p>
		<?php 
	}

	/**
	 * Сохранение настроек виджета. Здесь данные должны быть очищены и возвращены для сохранения их в базу данных.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance новые настройки
	 * @param array $old_instance предыдущие настройки
	 *
	 * @return array данные которые будут сохранены
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
		return $instance;
	}

	// скрипт виджета
	function add_recent_posts_widget_scripts() {
		// фильтр чтобы можно было отключить скрипты
		if( ! apply_filters( 'show_recent_posts_widget_script', true, $this->id_base ) )
			return;

		$theme_url = get_stylesheet_directory_uri();

		wp_enqueue_script('recent_posts_widget_script', $theme_url .'/recent_posts_widget_script.js' );
	}

	// стили виджета
	function add_recent_posts_widget_style() {
		// фильтр чтобы можно было отключить стили
		if( ! apply_filters( 'show_recent_posts_widget_style', true, $this->id_base ) )
			return;
		?>
		<style type="text/css">
			.recent_posts_widget a{ display:inline; }
		</style>
		<?php
	}

} 
// конец класса Recents_posts_widget
// регистрация Social_Widget в WordPress
function register_Recents_posts_Widget() {
	register_widget( 'Recents_posts_Widget' );
}
add_action( 'widgets_init', 'register_Recents_posts_Widget' );

function plural_form($number,$after) { 
	$cases = array(2,0,1,1,1,2); 
	echo $number . ' ' . $after [($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)]];
}