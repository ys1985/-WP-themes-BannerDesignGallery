<?php
add_theme_support('post-thumbnails');
// add_theme_support( 'menus' );

//========================================================================================
//管理画面左メニュー 表示設定
//========================================================================================
function remove_menus () {
    global $menu;
    unset($menu[2]);  // ダッシュボード
    // unset($menu[4]);  // メニューの線1
    unset($menu[5]);  // 投稿
    unset($menu[10]); // メディア
    // unset($menu[15]); // リンク
    // unset($menu[20]); // ページ
    // unset($menu[25]); // コメント
    // unset($menu[59]); // メニューの線2
    // unset($menu[60]); // テーマ
    // unset($menu[65]); // プラグイン
    // unset($menu[70]); // プロフィール
    // unset($menu[75]); // ツール
    // unset($menu[80]); // 設定
    // unset($menu[90]); // メニューの線3
}
add_action('admin_menu', 'remove_menus');

//========================================================================================
//カスタムメニューの位置を定義する
//========================================================================================
// register_nav_menus(array(
//     'category_menu' => '商材メニュー',
//     'color_menu' => 'カラーメニュー'
// ));
// register_nav_menus(array($location => $description));

//========================================================================================
//カテゴリメニュー 大カテゴリチェックボックス非表示
//========================================================================================
// require_once(ABSPATH . '/wp-admin/includes/template.php');
// class Danda_Category_Checklist extends Walker_Category_Checklist {
//
//      function start_el( &$output, $category, $depth, $args, $id = 0 ) {
//         extract($args);
//         if ( empty($taxonomy) )
//             $taxonomy = 'category';
//
//         if ( $taxonomy == 'category' )
//             $name = 'post_category';
//         else
//             $name = 'tax_input['.$taxonomy.']';
//
//         $class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';
//         //親カテゴリの時はチェックボックス表示しない
//         if($category->parent == 0){
//                $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<label class="selectit">' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
//         }else{
//             $output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<label class="selectit"><input value="' . $category->term_id . '" type="checkbox" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . disabled( empty( $args['disabled'] ), false, false ) . ' /> ' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
//         }
//     }
//
// }
// function lig_wp_category_terms_checklist_no_top( $args, $post_id = null ) {
//     $args['checked_ontop'] = false;
//     $args['walker'] = new Danda_Category_Checklist();
//     return $args;
// }
// add_action( 'wp_terms_checklist_args', 'lig_wp_category_terms_checklist_no_top' );



// require_once locate_template('lib/init.php');        // 初期設定の関数
// require_once locate_template('lib/cleanup.php');     // 不要なモノを削除する関数
// require_once locate_template('lib/titles.php');      // タイトル出力の関数
// require_once locate_template('lib/breadcrumbs.php'); // パンくずリストの関数
// require_once locate_template('lib/scripts.php');     // CSSやJavascript関連の関数
// require_once locate_template('lib/ads.php');         // 広告関連の関数
// require_once locate_template('lib/widgets.php');     // サイドバー、ウィジェットの関数
// require_once locate_template('lib/custom.php');      // その他カスタマイズの関数

//========================================================================================
// カスタム投稿タイプ 追加
//========================================================================================
add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type(
   'brands',
   array(
     'label' => 'バナー投稿',
     'public' => true,
     'has_archive' => true,
     'rewrite' => array( 'with_front' => false ),
     'menu_position' => 5,
     'supports' => array( 'title', 'thumbnail'),
     'taxonomies' => array( 'genre' )
   )
 );

/* ここから */
  register_taxonomy(
    'brand-category',
    'brands',
    array(
      'hierarchical' => true, /* 親子関係が必要なければ false */
      'update_count_callback' => '_update_post_term_count',
      'label' => '商材名',
      'singular_label' => '商材名',
      'public' => true,
      'show_ui' => true,
      'rewrite' => array( 'slug' => 'brand-category' )
    )
  );
  register_taxonomy(
    'color',
    'brands',
    array(
      'hierarchical' => true, /* 親子関係が必要なければ false */
      'update_count_callback' => '_update_post_term_count',
      'label' => 'カラー',
      'singular_label' => 'カラー',
      'public' => true,
      'show_ui' => true,
      'rewrite' => array( 'slug' => 'color' )
    )
  );
  register_taxonomy(
    'person',
    'brands',
    array(
      'hierarchical' => true, /* 親子関係が必要なければ false */
      'update_count_callback' => '_update_post_term_count',
      'label' => '人物',
      'singular_label' => '人物',
      'public' => true,
      'show_ui' => true,
      'rewrite' => array( 'slug' => 'person' )
    )
  );
  register_taxonomy(
    'size',
    'brands',
    array(
      'hierarchical' => true, /* 親子関係が必要なければ false */
      'update_count_callback' => '_update_post_term_count',
      'label' => 'サイズ',
      'singular_label' => 'サイズ',
      'public' => true,
      'show_ui' => true,
      'rewrite' => array( 'slug' => 'size' )
    )
  );
/* ここまでを追加 */
}

//========================================================================================
// 絞込用の検索機能追加
//========================================================================================
//ヘッダー検索窓用
// function search_form_header() {
//   global $wp_query, $query;
//   if(is_tax('brand-category')) {
//     $action = home_url( '/' ) . '/brands/brand-category/' . get_query_var('term');
//   }
//   else {
//     $action = home_url( '/' );
//   }
//   $html = '<form method="post" id="searchform" action="' . $action . '">';
//
//   $html .= '<input class="search_all" type="text" name="s"  id="s" value="' . $wp_query->get('s') . '" placeholder="検索したいキーワードを入力してください">';
//
//   $html .= '</form>';
//
//   echo $html;  //作成したフォームを返す
// }


//タクソノミーとタームからフォームを作る関数（archive-rent.phpとかから呼び出す関数）
function search_form_sidenav() {
  global $wp_query, $query;

  if(is_tax('brand-category')) {
    $action = home_url( '/' ) . '/brands/brand-category/' . get_query_var('term');
  }
  else {
    $action = home_url( '/' );
  }

  $html = '<form method="post" id="searchform" action="' . $action . '">';


  $taxonomies = get_taxonomies( array(  //全タクソノミーを配列で取得
    'public'   => true,
    '_builtin' => false
  ) );

  foreach( $taxonomies as $key => $taxonomie ) {  //タクソノミー配列を回す
    switch ($taxonomie) {
      case 'color':
        $html .= '<dl class="search_taxonomie color"><dt>' . get_taxonomy($taxonomie)->labels->name . '</dt>';
        break;
      default:
        $html .= '<dl class="search_taxonomie"><dt>' . get_taxonomy($taxonomie)->labels->name . '</dt>';
        break;
    }

    $terms = get_terms( $taxonomie, 'hide_empty=0' );   //各タクソノミーのタームを取得
    if ( ! empty( $terms ) && !is_wp_error( $terms ) ){
      foreach ( $terms as $key => $term ) {
        switch ($taxonomie) {
          case 'color':
            $html .= '<dd class="'. $term->slug . '"><input type="checkbox" id="' . $term->slug . '" name="' . $term->taxonomy . '[]" value="' . $term->slug . '"><label for="' . $term->slug . '" class="checkbox">' . $term->name  . '</label></dd>';
              break;
          default:
          $html .= '<dd><input type="checkbox" id="' . $term->slug . '" name="' . $term->taxonomy . '[]" value="' . $term->slug . '"><label for="' . $term->slug . '" class="checkbox">' . $term->name  . '</label></dd>';
            break;
        }
        // if($term->count > 0){ //各タームを回して
        //   $html .= '<dd><input type="checkbox" name="' . $term->taxonomy . '[]" value="' . $term->slug . '">' . $term->name  . '</dd>';  //インプットを作成
        // }
      }
      $html .= '</dl>';
    }
  }
  $html .= '<input type="submit" class="searchsubmit" value="検索する">';

  $html .= '</form>';

  echo $html;  //作成したフォームを返す
}

// カスタムクエリ追加
function myQueryVars( $public_query_vars ) {
  $taxonomies = get_taxonomies( array(  //前回作ったタクソノミーを取得
  'public'   => true,
  '_builtin' => false
  ) );
  foreach ( $taxonomies as $taxonomie ) {  //それを回す
    $public_query_vars[] = $taxonomie;  //カスタムクエリを既存のクエリに追加
  }
  return $public_query_vars;
}
add_filter( 'query_vars', 'myQueryVars' );  //SQL が生成される前に、WordPress のパブリッククエリ変数のリストに対して適用される。


//?brands=30000+over-135000&post_type=rent&key-money-deposit=30000+40000&s= の様なパラメーターを作る
function myRequest( $vars ) {

$taxonomies = get_taxonomies( array(  //タクソノミー配列取得
  'public'   => true,
  '_builtin' => false
) );


foreach( $taxonomies as $taxonomie ) {  //タクソノミー配列回す

  $terms = get_terms( $taxonomie, 'hide_empty=0' );  //タームオブジェクト取得
  if ( ! empty( $terms ) && !is_wp_error( $terms ) ){
    foreach ( $terms as $key => $term ) {  //タームオブジェクト回す
      if ( !empty( $vars[$term->taxonomy] ) && is_array( $vars[$term->taxonomy] ) ) {  //クエリに選択したタクソノミーが含まれていたら
        $vars[$term->taxonomy] = implode( '+', $vars[$term->taxonomy] );  //プラスで連結してクエリに入れる
      }
    }
  }
}
if ( isset( $_POST['s'] ) && !empty( $vars ) ) {  //検索フォームから来ていて、クエリからじゃなかったら
  $url = home_url('/brands/') . "?";
  $gets = array();

  foreach( $vars as $key => $val ) {
    if ($key == 's') {
      $val = str_replace('&', '%26', $val);  //文字化けとかしないようにして
    }
    if ( strlen( $val ) > 0 ) {
      if ( mb_detect_encoding( $val ) !== 'ASCII' ) {  //しないようにして
        $val = urlencode( $val );
      }
      $gets[] = "{$key}={$val}";
    }
  }
  if ( empty( $vars['s'] ) ) {
    $gets[] = "s=";
  }
  wp_redirect( $url . implode( '&', $gets ) );  //?rent=over-30000+over-135000&s=みたいにして/rent/にリダイレクト
  exit;
}

return $vars;
}

add_filter( 'request', 'myRequest');  //追加クエリ変数・プライベートクエリ変数が追加された後に適用される。
//パラメーターを元にtax_queryを作る
function myFilter( $query ) {
if (is_admin()) {
  return $query;
}

global $wp_query;

$query->set("post_type", "brands");

if ( !array_key_exists( 's', $query->query ) ) { //詳細ページの場合
  return $query;  //そのまま表示
} else {
  if ( $query->get('name') ) {  //違ったらnameをクエリから取り除く
    unset($wp_query->query['name']);
  }
}

if ( $query->get( 'post_type' ) === 'brands') {
  if ( count( $wp_query->query ) === 1 ) return $query;
  $args = $wp_query->query;
  $meta_query = array();
  $tax_query = array();

  $taxonomies = get_taxonomies( array(
    'public'   => true,
    '_builtin' => false
  ));
  //tax_queryを作っていく
  foreach( $taxonomies as $taxonomie ) {
    $terms = get_terms( $taxonomie, 'hide_empty=0' );
    if ( ! empty( $terms ) && !is_wp_error( $terms ) ){
      foreach ( $terms as $key => $term ) {

        if ( array_key_exists( $taxonomie, $wp_query->query ) ) {

          $slug = $query->get('brands');

          $slug = $query->get($taxonomie);
          $slug = explode( '+', $slug );
          $tax_query[] = array(
            'relation' => 'AND',
            array(
              'taxonomy' => $taxonomie,
              'field' => 'slug',
              'terms' => $slug,
              'operator' => 'IN',
            )
          );
          unset($args[$taxonomie]);
          break ;
        }

      }
    }
  }
  //クエリを作りなおしたら
  $args['meta_query'] = $meta_query;
  $args['tax_query'] = $tax_query;

  //古いの消して
  $query->init();
  $query->parse_query( $args );  //新しいクエリにする
  $query->is_search = true;      //検索ページだよってことにする

}

return $query;
}
add_filter('pre_get_posts','myFilter');  //クエリを実行する前に呼び出し
