<?php
// Wrap H2-H4 in <Section> Elements
function add_sections( $content ) {
	if (is_single() && in_the_loop() && is_main_query() && !empty($content) && !is_front_page() && !is_attachment() && !is_page()) {
		
		// Check if an <h2> begins the content
		$newContent = '';
		$contentArray = explode('<h2', $content);
		$i = 1;
		$newcontentPiece = '';
		foreach ( $contentArray as $contentPiece ) {
			if ( $i == 1 ) {
				$contentPiece = '<div class="intro-div div__articleIntro" id="articleIntro" role="doc-introduction">' . $contentPiece . '';
			} elseif ( $i == 2 ) {
				$contentPiece = '</div><section role="doc-chapter"><h2' . $contentPiece . '</section>';
			} else {
				$contentPiece = '<section role="doc-chapter"><h2' . $contentPiece . '</section>';
			}
			$newh3contentPiece = '';
			$h3contentArray = explode( '<h3', $contentPiece );
			$H3i = 1;
			if ( $h3contentArray !== '' ) {
				foreach ( $h3contentArray as $h3contentPiece ) {
					if ( $H3i == 1 ) {
						$h3contentPiece = '' . $h3contentPiece . '';
					} else {
						$h3contentPiece = '<section><h3' . $h3contentPiece . '</section>';
					}
					$newh4contentPiece = '';
					$h4contentArray = explode( '<h4', $h3contentPiece );
					$H4i = 1;
					if ( $h4contentArray !== '' ) {
						foreach ( $h4contentArray as $h4contentPiece ) {
							if ( $H4i == 1 ) {
								$h4contentPiece = '' . $h4contentPiece . '';
							} else {
								$h4contentPiece = '<section><h4' . $h4contentPiece . '</section>';
							}
							$newh4contentPiece = $newh4contentPiece . $h4contentPiece;
							$H4i++;
						}
						$h3contentPiece = $newh4contentPiece;
					}
					$newh3contentPiece = $newh3contentPiece . $h3contentPiece;
					$H3i++;
				}
				$newcontentPiece = $newh3contentPiece;
			} else {
				$newcontentPiece = $contentPiece;
			}
			$newContent = $newContent . $newcontentPiece;
			$i++;
		}
		$content = '<div id="entryContent" itemprop="articleBody text" class="e-content entry-content article--div__entryContent">' . $newContent . '</div>';
		$doc = new DOMDocument();
		global $wp;
		$libxml_previous_state = libxml_use_internal_errors(true);
		// Inject the Post Content
		$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOEMPTYTAG /*, LIBXML_HTML_NODEFDTD | LIBXML_NOXMLDECL | LIBXML_NOBLANKS | LIBXML_ERR_WARNING | LIBXML_NOEMPTYTAG | LIBXML_HTML_NOIMPLIED*/);
		libxml_use_internal_errors($libxml_previous_state);
		$content = trim($doc->saveHTML());
		return $content;
	} else {
		return $content;
	}
}
add_filter( 'the_content', 'add_sections', 9 );
?>
