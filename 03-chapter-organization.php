<?php

function add_chapters_sections_toc($content) {
/*==============================================================

	# LONGFORM ARTICLE FORMATTING

	We'll do a few of things here:
	- 	Generate numbered chapters, sections, and subsections for each <h2>, <h3>, and <h4> tag (respectively).
	- 	Create anchor links to the headings of each chapter,  section, and subsection for users to click, copy, and share as desired.
	-	Generate a table of contents before the first <h2> tag.

	Here's a basic outline of the output we're hoping to generate (excluding most markup):

	<!-- Start Post Content -->
		<!-- Leave  Content Alone Before the First H2 -->
		<section>
			Introduction
		</section>

		<!-- Add a Table of Contents -->
		<nav>
			<ul>
				<li>Chapter 1 {Title}
					<ul>
						<li>§ 1.1 {Title}</li>
						<li>§ 1.2 {Title}
							<ul>
								<li>§ 1.2.1 {Title}</li>
								<li>§ 1.2.2 {Title}</li>
							</ul>
						</li>
					</ul>
				</li>
				<li>Chapter 2 {Title}</li>
			</ul>
		</nav>

		<!-- Add Chapter Numbering and Anchor Links to Each H2
		-->
		<section>
			<p>Chapter 1</p>
			<h2>Chapter Title <a>Anchor Link</a></h2>
			
			<!-- Add Section Numbering and Anchor Links to
			Each H3 -->
			<section>
				<a>§ 1.1<a>
				<h3>Section Title</h3>
			</section>

			<section>
				<a>§ 1.1<a>
				<h3>Section Title</h3>
				
				<!-- Add Subsection Numbering and Anchor
				Links to Each H3 -->
				<section>
					<a>§ 1.2.1<a>
					<h4>Subsection Title</h4>
				</section>

				<section>
					<a>§ 1.2.2</a>
					<h4>Subsection Title</h4>
				</section>

			</section>

		</section>

		<section>
			<p>Chapter 2</p>
			<h2>Chapter Title <a>Anchor Link</a></h2>
		</section>
	<!-- End of Content -->

	#	NOTES:
	-	Requires PHP 7.
	-	Requires chapters, sections, and subsections to be
			wrapped in <section> tags before exection. This can
			be done pretty easily with a separate function, but
			isn't included in this file.
	-	The H2, H3, and H4 elements must be the first children
			of their sections and not wrapped in any other
			elements before execution.
	-	I have no idea what I'm doing.

==============================================================*/

// Check if we're inside the main loop in a single post page.
if ( is_singular( 'post' ) && in_the_loop() ) {
	
	
	

	// Create an instance of DOMDocument.
    $doc = new DOMDocument();
	
	// Global instance of the Class_Reference/WP class.
	global $wp;
	
    // Supress errors due to malformed HTML.
    // See http://stackoverflow.com/a/17559716/3059883
    $libxml_previous_state = libxml_use_internal_errors( true );
	
	// Inject the Post Content
	
	$doc->loadHTML( $content, LIBXML_NOENT | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS |  LIBXML_NOEMPTYTAG | LIBXML_NOXMLDECL | LIBXML_BIGLINES | LIBXML_NSCLEAN | LIBXML_PARSEHUGE | LIBXML_PEDANTIC | LIBXML_XINCLUDE | LIBXML_ERR_NONE );
	
	// Restore previous state of libxml_use_internal_errors() now that we're done.
    libxml_use_internal_errors( $libxml_previous_state );

	// Define our current URL (For use later in our anchor links for users to copy.)
	
	
	$meta_value = get_post_meta( get_the_ID(), 'postShortLink', true );
	if( !empty( $meta_value ) ) {
		$currentURL = $meta_value;
		$currentLongURL = home_url(add_query_arg(array(
			$_GET
		) , $wp->request));
		$currentLongURL = $currentLongURL . '/';
	} else {
		$currentURL = home_url(add_query_arg(array(
			$_GET
		) , $wp->request));
		$currentURL = $currentURL . '/';
		$currentLongURL = $currentURL;
	};

	// Prepare some empty arrays (for use in our anchor links later)
	$anchors = array();
	$anchors3 = array();
	$anchors4 = array();

	$h2_headings = $doc->getElementsByTagName('h2');

	// Define the heading number we're on.
	// Note: The $h20_i is for a data attribute I needed and can be removed along with its subsequent references.
	$h2_i = 1;
	$h20_i = 0;

	// Count the Total Number of <h2> Elements
	$last = 0;
	foreach($h2_headings as $heading) {
		$last++;
	}

			// Number all chapters, generate a table of contents, and format the chapter headings
			foreach($h2_headings as $heading) {
				
				// Wrap the inner text of our <h2> in a <span>
				// tag, which allows us to distinguish the text
				// from the link we'll be adding later.
				// $childSpan = $doc->createElement('span', $heading->nodeValue);
				
				$chapterTitleCloneP = $doc->createElement('p', $heading->nodeValue);
				// $childSpan->setAttribute('id', 'chapter-' . $h2_i . '-title-text-wrapper');
				// $childSpan->setAttribute('class', 'span chapter-title-text-wrapper chapter-' . $h2_i . '-title-text-wrapper');
				// $newHeading = $doc->createElement('h2');
				// $heading->parentNode->replaceChild($newHeading, $heading);
				// $newHeading->appendChild($childSpan);
				// $heading = $newHeading;

				// Mark up the <section> tag that wraps each chapter
				$heading->parentNode->setAttribute('id', 'ch' . $h2_i );
				

				// Wrap the chapter title and chapter number in
				// a <div>, in case we want to use flexbox to
				// force them into a horizontal row.
				$parentDiv = $doc->createElement('div');
				$parentDiv->setAttribute('id', 'chapter-' . $h2_i . '-title-wrapper');
				$parentDiv->setAttribute('class', 'div chapter-title-wrapper chapter-' . $h2_i .
				'-title-wrapper');
				$heading->parentNode->replaceChild($parentDiv, $heading);
				$parentDiv->appendChild($heading);
				$heading->setAttribute('itemprop', 'name');

				// Make the <h2> element tabbable to make navigation a bit easier
				// $heading->setAttribute('tabindex', '0');
				// Note: this is conflicting with some CSS, so I'm removing it for now - February 11, 2019
				$heading->setAttribute('class', 'h2 chapter-heading chapter-' . $h2_i . '-heading');

				// Create our chapter number and insert it before the <h2> element.
				
				if ($last > 1) {
					$para = $doc->createElement('p', 'Chapter ' . $h2_i . ' ');
					$h2prefix = $heading->parentNode->insertBefore($para, $heading);
					$h2prefix->setAttribute('class', 'p h2-prefix chapter-' . $h2_i . '-prefix');
					$h2prefix->setAttribute('itemprop', 'alternateName');
				};			
				
				
				// Create a clean anchor link for both the table of contents and our anchor link.

				$slug = $tmpslug = sanitize_title($heading->nodeValue);
				$i = 2;
				while (false !== in_array($slug, $anchors)) {
					$slug = sprintf('%s-%d', $tmpslug, $i++);
				}

				$anchors[] = $slug;
				$heading->setAttribute('id', $slug);
				/* I like the clean text-based links for SEO purposes, but the users probably need a shorter link if they're going to copy/share it. So we'll create a shorter anchor that simply says "https://www.example.com/#ch1" (or whatever chapter number we're on). */
				$anchorSlug = 'ch' . $h2_i;
				$heading->parentNode->parentNode->setAttribute('aria-labelledby', $slug );

				// Create the initial table of contents wrappers if we're on the first <h2> and there are two or more <h2> elements

				if (($h2_i == 1) && ($last > 1)) {

					// Add class "first-chapter" to the parent <section> element, in case you want to format the first section in a special way.
					$heading->parentNode->parentNode->setAttribute('class', 'section chapter-section first-chapter chapter-' . $h2_i . '-section');
					$heading->parentNode->parentNode->setAttribute('itemscope', '');
					$heading->parentNode->parentNode->setAttribute('itemtype', 'https://schema.org/Chapter');
					$heading->parentNode->parentNode->setAttribute('itemprop', 'hasPart');

					// I wasn't sure whether <aside> or <nav> is better here, but we're go with <nav> per http://html5doctor.com/nav-element/
					$tocNAV = $doc->createElement('nav');
					$firstSection = $heading->parentNode->parentNode->parentNode->childNodes->item(1);
					$insertedTOCnav = $firstSection->parentNode->insertBefore($tocNAV, $firstSection);
					$insertedTOCnav->setAttribute('aria-label', 'Table of Contents');
					$insertedTOCnav->setAttribute('id', 'tableOfContents');
					$insertedTOCnav->setAttribute('property', 'dcterms:tableOfContents');
					$insertedTOCnav->setAttribute('class', 'nav article--nav__tableOfContents');
					$insertedTOCnav->setAttribute('role', 'doc-toc');
					 	
					// $insertedTOCnav->setAttribute('epub:type', 'toc');
					// Delete if you're not using epub markup http://www.idpf.org/epub/31/spec/epub-packages.html
					
					

					// Create the inner table of contents list
					$tocDIV = $doc->createElement('div');
					$insertedtocDIV = $insertedTOCnav->appendChild($tocDIV);
					$insertedtocDIV->setAttribute('aria-label', 'Chapter List');
					$insertedtocDIV->setAttribute('role', 'directory');
					$insertedtocDIV->setAttribute('class', 'div nav--div_tableOfContentsList');
					$insertedtocDIV->setAttribute('id', 'tableOfContentsList');

				} elseif ($h2_i == $last) {

					// Add class "last-chapter" to the parent <section> element, in case you want to format the first section in a special way.
					$heading->parentNode->parentNode->setAttribute('class', 'section chapter-section last-chapter chapter-' . $h2_i . '-section');
					$heading->parentNode->parentNode->setAttribute('role', 'doc-conclusion');
					$heading->parentNode->parentNode->setAttribute('itemscope', '');
					$heading->parentNode->parentNode->setAttribute('itemtype', 'https://schema.org/Chapter');
					$heading->parentNode->parentNode->setAttribute('itemprop', 'hasPart');
					
				} else {

					$heading->parentNode->parentNode->setAttribute('class', 'section chapter-section chapter-' . $h2_i . '-section');
					$heading->parentNode->parentNode->setAttribute('itemscope', '');
					$heading->parentNode->parentNode->setAttribute('itemtype', 'https://schema.org/Chapter');
					$heading->parentNode->parentNode->setAttribute('itemprop', 'hasPart');

				}

				// Create <li> Elements for Each Chapter
				$tocDiv1 = $doc->createElement('div', 'Chapter ');
				$tocDiv1->setAttribute('class', 'div toc--div__TOCchapterNumber TOCchapterNumber-' . $h2_i);
				if (($h2_i >= 1) && ($h2_i < 10) && ($last > 1)) {
					$tocDiv2 = $doc->createElement('div', '0' . $h2_i . ' ');
				} elseif ($h2_i > 1) {
					$tocDiv2 = $doc->createElement('div', $h2_i . ' ');
				};
				
				if (isset($tocDiv2)) {
					$tocDiv2->setAttribute('class', 'div toc--div__TOCchapterNumberNumber TOCchapterNumberNumber-' . $h2_i);
					$tocDiv3 = $doc->createElement('div', $heading->nodeValue);
					$tocDiv3->setAttribute('class', 'div toc--div__TOCchapterTitle TOCchapterTitle-' . $h2_i);
				
				
					// $tocLI = $doc->createElement('li');

					/*	
						OPTIONAL
						This snippet was being used in a JS sidebar to show users their progress in a chapter. Can be deleted.
						// $insertedtocLI->setAttribute('data-story', $h20_i);
					*/

					// Link to the appropriate chapter anchor.
					$tocDivWrapper = $doc->createElement('div');
					$insertedtocDivWrapper = $insertedtocDIV->appendChild($tocDivWrapper);
					$insertedtocDivWrapper->setAttribute('class', 'div toc--div__chapterLinkAndSectionWrapper');
					$tocA = $doc->createElement('a');
					$insertedtocA = $insertedtocDivWrapper->appendChild($tocA);
					$insertedtocA->setAttribute('href', $currentLongURL . '#' . $slug);
					$insertedtocA->setAttribute('class', 'a toc--a__chapterLink chapterLink-' . $h2_i );
					$insertedtocA->appendChild($tocDiv3);
					$tocDiv3->parentNode->insertBefore($tocDiv2, $tocDiv3);
					$tocDiv3->parentNode->insertBefore($tocDiv1, $tocDiv2);
					// $insertedtocA->appendChild($tocDiv1);
					// $insertedtocA->appendChild($tocDiv2);
				}
				

				/* 
					Holdover from some JS I disabled, but still might use in the future.
					// $progressWrapper = $doc->createElement('div');
					// $progressWrapperInserted = $insertedtocLI->appendChild($progressWrapper);
					// $progressWrapper->setAttribute('class', 'div progress-wrapper');
				*/

				// Create some anchor links and insert them at the end of each <h2> element.
				$a = $doc->createElement('a');
				$newnode = $heading->appendChild($a);
				$newnode->setAttribute('class', 'a h2-anchorlink anchorlink permalink');
				$newnode->setAttribute('href', $currentLongURL . '#' . $slug);
				$newnode->setAttribute('data-clipboard', $currentURL . '#' . $anchorSlug);
				$newnode->setAttribute('tabindex', '0');
				$newSpanNode = $doc->createElement('span', 'Click to Copy a Link to This Chapter');
				$newSpanNode2 = $doc->createElement('span', '&#x260D; ');
				$a->appendChild($newSpanNode2);
				$a->appendChild($newSpanNode);
				$newSpanNode->setAttribute('class', 'span sr-only');
				$newSpanNode2->setAttribute('class', 'span anchorLinkSpan');
				$newSpanNode2->setAttribute('role', 'presentation');

				/*
					Add data- attribute to our link, which goes to a shorter, but less-SEO-friendly URL. (Just in case we want to switch the URLs on page load so users can easily copy the shorter #ch1 anchor link.)
				
					The shorter links goes to the outer section wrapper of the chapter, so it should take users to roughly the same area on the page as the link we're switching it for.
				*/

				/*
					Insert a link emoji as a fallback.
					-	The span is there in case you want to treat the text differently than the link element surrounding it.
					-	The span can be set to display:none or deleted if unnecessary.
					-	You can also swap it out with a background SVG in your CSS.
					-	If you have a good icon font, you can use a service like icomoon.io to define this character as the U+1F517 entity in the font file and then explicitly define it in your @font-face declaration with unicode-range.
				*/
				// $aText = $doc->createElement('span', ' &#x1f517;');
				// $aText->setAttribute('class', 'span span__anchorLinkEmoji');
				// $aTextInsert = $newnode->appendChild($aText);
				
				/* 
				 * This section creates a duplicate section header, which can remain hidden until it becomes sticky,
				 * at which point it can drop down and follow the user until they reach the end of the chapter. This
				 * can be done in Javascript, but doing it here intends to save the user computer power, however slight.
				*/
				$cloneTemplateWrapper = $doc->createElement('template', '');
				$cloneTemplateWrapper->setAttribute('id', 'chapter' . $h2_i . 'Template');
				$cloneTemplateWrapper->setAttribute('class', 'template chapterTemplate');
				$chapterTitleWrapperCloneDiv = $doc->createElement('div');
				$heading->parentNode->parentNode->insertBefore($cloneTemplateWrapper, $parentDiv->nextSibling);
				$cloneTemplateWrapper->appendChild($chapterTitleWrapperCloneDiv);
				$chapterTitleWrapperCloneDiv->setAttribute('class', 'div chapter--div__chapterTitleCloneWrapper');
				$chapterPrefixClone = $doc->createElement('p', 'Ch. ' . $h2_i);
				$chapterPrefixClone->setAttribute('class', 'p chapter--p__stickyChapterPrefix chapter--p__stickyChapterPrefix-ch' . $h2_i);
				$chapterPrefixClone->setAttribute('aria-hidden', 'true');
				$chapterTitleWrapperCloneDiv->insertBefore($chapterPrefixClone);
				$chapterTitleCloneDiv = $doc->createElement('div');
				$chapterTitleWrapperCloneDiv->appendChild($chapterTitleCloneDiv);
				$chapterTitleCloneDiv->setAttribute('class', 'div chapter--div__chapterTitleCloneHeading');
				$chapterTitleCloneDiv->setAttribute('role', 'presentation');
				$chapterTitleCloneDiv->appendChild($chapterTitleCloneP);
				$chapterTitleCloneP->setAttribute('class', 'div chapter--p__chapterTitleCloneHeadingText');
				$chapterTitleCloneP->setAttribute('aria-hidden', 'true');
				$aClone = $doc->createElement('a', ' &#x260d;');
				$newCloneNode = $chapterTitleCloneDiv->appendChild($aClone);
				$newCloneNode->setAttribute('class', 'a h2-anchorlinkClone symlink2 anchorlink');
				$newCloneNode->setAttribute('href', $currentLongURL . '#' . $slug);
				$newCloneNode->setAttribute('title', 'Click to Copy a Shortlink to this Chapter');
				$newCloneNode->setAttribute('aria-hidden', 'true');
				
				// If you want to force the anchor URL to be copied on click, use:
				// https://clipboardjs.com/
				// Once added, the following JS, along with the next line of PHP will allow that.
				// new ClipboardJS('.h2-anchorlink');
				$newCloneNode->setAttribute('data-clipboard', $currentURL . '#' . $anchorSlug);
				$newCloneNode->setAttribute('tabindex', '0');

				/*==============================================
				//	We're pretty much done with our H2 headings.
				//	Now do mostly the same thing for the <h3> 
				// 	elements inside of our current chapter.
				//============================================*/
				$h3_headings = $heading->parentNode->parentNode->getElementsByTagName('h3');

				// Calculate Total Number of <h3> Elements (The $h30_i is for a data attribute I needed and can be removed along with its subsequent references.)
				$h3_i = 1;
				$h30_i = 0;
				$lastH3 = 0;
				foreach($h3_headings as $heading3) {
					$lastH3++;
				}

				// Number all sections, generate an inner list in our already-existing table of contents, and format the sections headings.
				foreach($h3_headings as $heading3) {

					// Wrap the inner text of our <h3> in a <span> tag, which allows us to distinguish the text from the link we'll be adding later.
					// $childSpan3 = $doc->createElement('span', $heading3->nodeValue);
					// $childSpan3->setAttribute('id', 'section-' . $h2_i . '-' . $h3_i . '-title-text-wrapper');
					// $childSpan3->setAttribute('class', 'span section-title-text-wrapper section-' . $h2_i . '-' . $h3_i . '-title-text-wrapper');
					// $newHeading3 = $doc->createElement('h3');
					// $heading3->parentNode->replaceChild($newHeading3, $heading3);
					// $newHeading3->appendChild($childSpan3);
					// $heading3 = $newHeading3;

					// Mark up the <section> tag that wraps each section
					$heading3->parentNode->setAttribute('id', 'section_' . $h2_i . '-' . $h3_i);
					

					// Wrap the section title and section number in a <div>, in case we want to use flexbox to force them into a horizontal row.
					$parentDiv3 = $doc->createElement('div');
					$parentDiv3->setAttribute('id', 'section-' . $h2_i . '-' . $h3_i . '-title-wrapper');
					$parentDiv3->setAttribute('class', 'div section-title-wrapper section-' . $h2_i . '-' . $h3_i . '-title-wrapper');
					$heading3->parentNode->replaceChild($parentDiv3, $heading3);
					$parentDiv3->appendChild($heading3);
					$heading3->setAttribute('class', 'h3 section-heading section-' . $h2_i . '-' . $h3_i . '-heading');

					// Make the <h3> element tabbable to make navigation a bit easier
					// $heading3->setAttribute('tabindex', '0');
					// Note: this is conflicting with some CSS, so I'm removing it for now - February 11, 2019
					
					// Create a clean anchor link for both the table of contents and the link we'll be placing next to the heading title.
					$slug3 = $tmpslug3 = sanitize_title($heading3->nodeValue);
					$i3 = 2;
					while (false !== in_array($slug3, $anchors3)) {
						$slug3 = sprintf('%s-%d', $tmpslug3, $i3++);
					}
					$anchors3[] = $slug3;
					$heading3->setAttribute('id', $slug3);
					
					$heading3->parentNode->parentNode->setAttribute('aria-labelledby', $slug3);

					/*
						I like the clean text-based links for SEO purposes, but the users probably need a shorter link if they're going to copy/share it. 
						So we'll create a shorter anchor that simply says:
							https://www.example.com/#ch1
						(or whatever chapter number we're on).
					*/
					$anchorSlug3 = 'section_' . $h2_i . '-' . $h3_i;

					// Create the initial table of contents wrappers if we're on the first-child <h3> of this parent chapter.
					if (($h3_i == 1) && ($lastH3 > 1) && ($last > 1)) {

						// Add class "first-section" to the parent <section> element, in case you want to format the first section in a special way.
						$heading3->parentNode->parentNode->setAttribute('class', 'section section-section first-section-of-chapter section-' . $h2_i . '-' . $h3_i . '-section');

						if (isset($tocDiv2)) {
							$tocDIV3 = $doc->createElement('div');
							$insertedtocDIV3 = $insertedtocA->parentNode->appendChild($tocDIV3);
							$insertedtocDIV3->setAttribute('class', 'div toc--div__tocSectionList tocSectionList-' . $h2_i . '-' . $h3_i );
							$insertedtocA->setAttribute('class', 'a toc--a__chapterLink chapterLink-' . $h2_i . ' chapterLink-has-children');
							$insertedtocDIV3->setAttribute('id', 'inner_toc_section_list-' . $h2_i);
						
							// OPTIONAL: Construct Pure CSS Collapsible Element: https://alligator.io/css/collapsible/
							$tocNAVlabel3 = $doc->createElement('div', '&#8744;');
							$tocNAVlabelInserted3 = $insertedtocA->parentNode->insertBefore($tocNAVlabel3, $insertedtocA);
							$tocNAVlabelInserted3->setAttribute('id', 'expandableTOCbutton_' . $h2_i );
							$tocNAVlabelInserted3->setAttribute('class', 'div_chapterExpandableTOCbutton');
							$tocNAVlabelInserted3->setAttribute('tabindex', '0');
							$tocNAVlabelInserted3->setAttribute('title', 'View Sections of Chapter ' . $h2_i );
							$tocNAVinput3 = $doc->createElement('div', '&#x24cd;');
							$tocNAVinputInserted3 = $insertedtocA->parentNode->insertBefore($tocNAVinput3, $insertedtocA);
							$tocNAVinputInserted3->setAttribute('id', 'collapsibleTOCbutton_' . $h2_i );
							$tocNAVinputInserted3->setAttribute('class', 'div_chapterCollapsibleTOCbutton');
							$tocNAVinputInserted3->setAttribute('title', 'Hide Sections of Chapter ' . $h2_i );
						}
					} elseif (($h3_i == $lastH3) && ($lastH3 > 1) && ($last > 1)) {

						// Add a "last-section" class in case you want to format the first or last section in some special way.
						$heading3->parentNode->parentNode->setAttribute('class', 'section section-section last-section-of-chapter section-' . $h2_i . '-' . $h3_i . '-section');
					} else {
						$heading3->parentNode->parentNode->setAttribute('class', 'section section-section section-' . $h2_i . '-' . $h3_i . '-section');
					}

					// Add <li> elements for each section
					// $tocLI3 = $doc->createElement('li');
					// $insertedtocLI3 = $insertedtocDIV3->appendChild($tocLI3);
					// $insertedtocLI3->setAttribute('class', 'li section-list-item toc-' . $h2_i . '-' . $h3_i . '-list-item');
					
					
					
					
					/*
						OPTIONAL
						This snippet was being used in a JS sidebar to show users their progress in a chapter. Can be deleted.
						// $insertedtocLI3->setAttribute('data-section-story', $h30_i); 
						*/

					
					if (($lastH3 > 1) && ($last > 1)) {
						
						// Create an element containing the text we'll be inserting into the table of contents.
						$tocNodeValue3 = $doc->createTextNode($heading3->nodeValue);

						if (isset($tocDiv2)) {
							// Insert a link into the table of contents.
							$tocA3 = $doc->createElement('a');
							$insertedtocA3 = $insertedtocDIV3->appendChild($tocA3);
							$insertedtocA3->setAttribute('href', $currentLongURL . '#' . $slug3);
							$insertedtocA3->setAttribute('class', 'a nav--a__TOCsectionLink TOCsectionLink-' . $h2_i . '-' . $h3_i);



							// Insert the current section number into the link.
							$tocSectionNumberSpanH3 = $doc->createElement('span');
							$tocSectionNumberSpanH3->setAttribute('class', 'span toc--span__sectionNumber sectionNumber-' . $h2_i . '-' . $h3_i);
							$tocSectionSpanH3 =  $doc->createTextNode($h2_i . '.' . $h3_i . '. ');
								$insertedtocA3->appendChild($tocSectionNumberSpanH3);
							$tocSectionNumberSpanH3->appendChild($tocSectionSpanH3);
							// Insert the text of our heading into the link.
							$LInodeValue3Inserted = $insertedtocA3->appendChild($tocNodeValue3);
						}
						/* 
							Holdover from some JS I disabled, but still might use in the future.
							// $progressSectionWrapper = $doc->createElement('div');
							// $progressSectionWrapperInserted = $insertedtocLI3->appendChild($progressSectionWrapper);
							// $progressSectionWrapper->setAttribute('class', 'div section-progress-wrapper');
						*/

						// Create an empty anchor link and insert it in front of the current <h3> element.
						// $para3 = $doc->createElement( 'p' );
						// $h3prefix = $parentDiv3->insertBefore($para3, $heading3);
						// $h3prefix->setAttribute('class', 'p h3-prefix section-' . $h2_i . '-' . $h3_i. '-prefix');

						$sectionTitle = $doc->createTextNode($h2_i . '.' . $h3_i . '. ');
						$a3 = $doc->createElement('a');
						$h3prefix = $parentDiv3->insertBefore($a3, $heading3);
						$a3->appendChild($sectionTitle);
						// $newnode3 = $para3->appendChild($a3);
						$h3prefix->setAttribute('class', 'a h3-anchorlink anchorlink');
						$heading3->setAttribute('id', $slug3);
						$h3prefix->setAttribute('href', $currentLongURL . '#' . $slug3);
						$h3prefix->setAttribute('title', 'Click to Copy a Shortlink to this Section');

						// If you want to force the anchor URL to be copied on click, use:
						// https://clipboardjs.com/
						// Once added, the following JS, along with the next line of PHP will allow that.
						// new ClipboardJS('.h3-anchorlink');
						$h3prefix->setAttribute('data-clipboard', $currentURL . '#' . $anchorSlug3);
						$h3prefix->setAttribute('tabindex', '0');
					}

					/*
						Next we'll create <span> tags containing the anchor link content.
						
						I've separated the section symbol and the current section number because I'm giving assigning the symbold a different font, formatting it a little larger, and then vertically aligning them.
						Those <span> tags can be deleted if you're fine with treating both entities the same in your CSS.
					*/
					// $spanSectionSymbol3 = $doc->createElement('span', '§');
					// $spanSectionNumber3 = $doc->createElement('span', $h2_i . '.' . $h3_i );
					// $a3->appendChild($spanSectionSymbol3);
					// $spanSectionSymbol3->setAttribute('class', 'span span__h3anchorlinkSymbol permalink');
					// $a3->appendChild($spanSectionNumber3);
					// $spanSectionNumber3->setAttribute('class', 'span span__h3anchorlinkNumber permalink');

					/*=======================================================
					//	Again: We're pretty much done with our H2 headings now.
					//	Next up is our H4 headings, where we'll once again do
					//	the same thing.
					======================================================*/

					$h4_headings = $heading3->parentNode->parentNode->getElementsByTagName('h4');

					// Calculate Total Number of <h4> Elements (The $h40_i is for a data attribute I needed and can be removed along with its subsequent references.)
					$h4_i = 1;
					$h40_i = 0;
					$lastH4 = 0;
					foreach($h4_headings as $heading4) {
						$lastH4++;
					}
					
					// Number all subsections, generate an inner list in our already-existing table of contents, and format the subsections headings.
					foreach($h4_headings as $heading4) {

						// Wrap the inner text of our <h4> in a <span> tag, which allows us to distinguish the text from the link we'll be adding later.
						// $childSpan4 = $doc->createElement('span', $heading4->nodeValue);
						// $childSpan4->setAttribute('id', 'subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-title-text-wrapper');
						// $childSpan4->setAttribute('class', 'span subsection-title-text-wrapper subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-title-text-wrapper');
						// $newHeading4 = $doc->createElement('h4');
						// $heading4->parentNode->replaceChild($newHeading4, $heading4);
						// $newHeading4->appendChild($childSpan4);
						// $heading4 = $newHeading4;

						// Mark up the <section> tag that wraps each section
						$heading4->parentNode->setAttribute('id', 'subsection_' . $h2_i . '-' . $h3_i . '-' . $h4_i);					
						$heading4->setAttribute('class', 'h4 subsection-heading subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-heading');

						// Make the <h4> element tabbable to make navigation a bit easier
						// $heading4->setAttribute('tabindex', '0');
						// Note: this is conflicting with some CSS, so I'm removing it for now - February 11, 2019

						// Create a clean anchor link for both the table of contents and the link we'll be placing next to the heading title.
						$slug4 = $tmpslug4 = sanitize_title($heading4->nodeValue);
						$i4 = 2;
						while (false !== in_array($slug4, $anchors4)) {
							$slug4 = sprintf('%s-%d', $tmpslug4, $i4++);
						}
						$anchors4[] = $slug4;
						$heading4->setAttribute('id', $slug4);

						$heading4->parentNode->setAttribute('aria-labelledby', $slug4);
						
						/*
							I like the clean text-based links for SEO purposes, but the users probably need a shorter link if they're going to copy/share it. 
							So we'll create a shorter anchor that simply says:
								https://www.example.com/#ch1
							(or whatever chapter number we're on).
						*/
						$anchorSlug4 = 'subsection_' . $h2_i . '-' . $h3_i . '-' . $h4_i;
						$heading4->setAttribute('id', $slug4);

						// Create the initial table of contents wrappers if we're on the first-child <h4> of this parent chapter.
						if (($h4_i == 1) && ($lastH4 > 1) && ($lastH3 > 1) && ($last > 1)) {

							// Add class "first-subsection" to the parent <section> element, in case you want to format the first section in a special way.
							$heading4->parentNode->setAttribute('class', 'section subsection first-subsection-of-chapter-section subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
							$tocDIV4 = $doc->createElement('div');
							$newInsertedtocA3 = $insertedtocA3->cloneNode(true);
							$tocSubsectionListWrapper = $doc->createElement('div');
							$insertedtocA3->parentNode->replaceChild($tocSubsectionListWrapper, $insertedtocA3);
							$tocSubsectionListWrapper->appendChild($newInsertedtocA3);
							$insertedtocDIV4 = $tocSubsectionListWrapper->appendChild($tocDIV4);
							$insertedtocDIV4->setAttribute('class', 'div toc--div__tocSubsectionList tocSubsectionList-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
							$insertedtocDIV4->setAttribute('id', 'inner_toc_subsection_list-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
							$insertedtocA3 = $newInsertedtocA3;
							$tocSubsectionListWrapper->setAttribute('class', 'div tocSubsectionListWrapper');
						}
						elseif (($h4_i == $lastH4) && ($lastH4 > 1) && ($lastH3 > 1) && ($last > 1)) {

							// Add a "last-subsection" class in case you want to format the first or last section in some special way.
							$heading4->parentNode->setAttribute('class', 'section subsection last-subsection-of-chapter-section subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
						}
						else {
							$heading4->parentNode->setAttribute('class', 'section subsection subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
						}

						// Add <li> elements for each subsection
						// $tocLI4 = $doc->createElement('li');
						// $insertedtocLI4 = $insertedtocUL4->appendChild($tocLI4);
						// $insertedtocLI4->setAttribute('class', 'li subsection-list-item toc-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-list-item');
						
						/*
							OPTIONAL
							This snippet was being used in a JS sidebar to show users their progress in a chapter. Can be deleted.
							// $insertedtocLI4->setAttribute('data-subsection-story', $h40_i);*/

						if (($lastH4 > 1) && ($lastH3 > 1) && ($last > 1)) {
						
							// Create and element containing the text we'll be inserting into the table of contents.
							$tocNodeValueH4 = $doc->createTextNode($heading4->nodeValue);

							// Insert a link into the table of contents.
							$tocA4 = $doc->createElement('a');
							$insertedtocA4 = $insertedtocDIV4->appendChild($tocA4);
							$insertedtocA4->setAttribute('href', $currentLongURL . '#' . $slug4);
							$insertedtocA4->setAttribute('class', 'a toc--a__tocSubsectionLink tocSubsectionLink-' . $h2_i . '-' . $h3_i . '-' . $h4_i);

							// Insert the current subsection number into the link.

							$insertedtocA4->appendChild($tocNodeValueH4);
							
						}
						
						// Create an empty anchor link and insert it in front of the current <h4> element.
						// $para4 = $doc->createElement('p');
						// $h4prefix = $parentDiv4->insertBefore($para4, $heading4);
						// $h4prefix->setAttribute('class', 'p h4-prefix subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
						
						$heading4->setAttribute('id', $slug4);

						// Add +1 to our count as we loop onto the next subsection.
						$h4_i+= 1;
						$h40_i+= 1;
					}

					// Add +1 to our counts as we loop onto the next section.
					$h3_i+= 1;
					$h30_i+= 1;
				}
					
				// Add +1 to our count as we loop onto the next subsection.
				$h2_i+= 1;
				$h20_i+= 1;
			}
	
			if ($last > 1) {
				$tocNAVheading = $doc->createElement('h2', 'Article Content');
				$tocNAV = $heading->parentNode->parentNode->parentNode->childNodes->item(1);
				$tocNAVheadingInserted = $tocNAV->insertBefore($tocNAVheading, $tocNAV->firstChild);
				$tocNAVheadingInserted->setAttribute('id', 'h2__tocHeading');
				$tocNAVheadingInserted->setAttribute('class', 'h2 h2__tocHeading');
			}
			
		// All done! How neat is that?
		// https://www.youtube.com/watch?v=Hm3JodBR-vs
		
		$new_content = $doc->saveHTML();
		return $new_content;
	} else {
	return $content;
}
	
};

add_filter('the_content', 'add_chapters_sections_toc', 9 );
?>
