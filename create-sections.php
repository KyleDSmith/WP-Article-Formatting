// Wrap H2-H4 in <Section> Elements

function add_sections( $content ) {
    if (is_single() && !is_front_page()) {
        $content = '<div id="entry-content" itemprop="articleBody text" class="e-content entry-content">' . $content . '</div><!-- /entry-content -->';
        if ( (preg_match ( '/<h2([^>]+)?>(\s*?)<h2([^>]+)?>/', $content ) !== false) ) {
            $content = preg_replace( '/<h4(.+?)(?=((<h4)|(<h3)|(<h2)|(<\/div><\!-- \/entry-content -->)))/s', '<section><h4$1</section>', $content );
            $content = preg_replace( '/<h3(.+?)(?=((<h3)|(<h2)|(<\/div><\!-- \/entry-content -->)))/s', '<section><h3$1</section>', $content );
            $content = preg_replace( '/<h2(.+?)(?=((<h2)|(<\/div><\!-- \/entry-content -->)))/s', '<section><h2$1</section>', $content );
            $content = preg_replace( '/(<p)(.+)(?=(<h2|<section|<div|<\/div))/sU', '<div role="doc-introduction" itemprop="articleSection">$1 class="post-p first-paragraph" id="p0"$2</div>', $content, 1 );
            $content = preg_replace( '/<\/div><\!-- \/entry-content -->/s', '</div>', $content );
            return $content;
        } else {
            return $content;
        }
    } else {
            return $content;
    }
}
add_filter( 'the_content', 'add_sections', 8, 1);
