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
if ( is_single() && in_the_loop() && is_main_query() && !empty($content) && !is_front_page() && !is_attachment() && !is_page() ) {

	// Create an instance of DOMDocument.
    $doc = new DOMDocument();
	
	// Global instance of the Class_Reference/WP class.
	global $wp;
	
    // Supress errors due to malformed HTML.
    // See http://stackoverflow.com/a/17559716/3059883
    $libxml_previous_state = libxml_use_internal_errors( true );
	
	// Inject the Post Content
	$doc->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG | LIBXML_HTML_NOIMPLIED | LIBXML_NOXMLDECL );
	
	// Restore previous state of libxml_use_internal_errors() now that we're done.
    libxml_use_internal_errors( $libxml_previous_state );

	// Define our current URL (For use later in our anchor links for users to copy.)
	$currentURL = home_url(add_query_arg(array(
		$_GET
	) , $wp->request));

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
				$childSpan = $doc->createElement('span', $heading->nodeValue);
				$childSpan->setAttribute('id', 'chapter-' . $h2_i . '-title-text-wrapper');
				$childSpan->setAttribute('class', 'span chapter-title-text-wrapper chapter-' . $h2_i . '-title-text-wrapper');
				$newHeading = $doc->createElement('h2');
				$heading->parentNode->replaceChild($newHeading, $heading);
				$newHeading->appendChild($childSpan);
				$heading = $newHeading;

				// Mark up the <section> tag that wraps each chapter
				$heading->parentNode->setAttribute('id', 'ch' . $h2_i);
				$heading->parentNode->setAttribute('role', 'doc-chapter');
				$heading->parentNode->setAttribute('aria-labelledby', 'chapter-' . $h2_i . '-title-text-wrapper');

				// Wrap the chapter title and chapter number in
				// a <div>, in case we want to use flexbox to
				// force them into a horizontal row.
				$parentDiv = $doc->createElement('div');
				$parentDiv->setAttribute('id', 'chapter-' . $h2_i . '-title-wrapper');
				$parentDiv->setAttribute('class', 'div chapter-title-wrapper chapter-' . $h2_i .
				'-title-wrapper');
				$new_heading_clone = $heading->cloneNode();
				$heading->parentNode->replaceChild($parentDiv, $heading);
				$parentDiv->appendChild($heading);
				$heading->setAttribute('aria-level', '2');

				// Make the <h2> element tabbable to make navigation a bit easier
				$heading->setAttribute('tabindex', '0');
				$heading->setAttribute('class', 'h2 chapter-heading chapter-' . $h2_i . '-heading');

				// Create our chapter number and insert it before the <h2> element.

				$para = $doc->createElement('p', 'Chapter ' . $h2_i . ' ');
				$h2prefix = $heading->parentNode->insertBefore($para, $heading);
				$h2prefix->setAttribute('class', 'p h2-prefix chapter-p chapter-' . $h2_i);

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

				// Create the initial table of contents wrappers if we're on the first <h2>

				if ($h2_i == 1) {

					// Add class "first-chapter" to the parent <section> element, in case you want to format the first section in a special way.
					$heading->parentNode->parentNode->setAttribute('class', 'section chapter-section first-chapter chapter-' . $h2_i . '-section');

					// I wasn't sure whether <aside> or <nav> is better here, but we're go with <nav> per http://html5doctor.com/nav-element/
					$tocNAV = $doc->createElement('nav');
					$firstSection = $heading->parentNode->parentNode->parentNode->childNodes->item(1);
					$insertedTOCnav = $firstSection->parentNode->insertBefore($tocNAV, $firstSection);
					$insertedTOCnav->setAttribute('aria-label', 'Table of Contents');
					$insertedTOCnav->setAttribute('role', 'doc-toc');
					$insertedTOCnav->setAttribute('id', 'table-of-contents');
					$insertedTOCnav->setAttribute('class', 'nav table-of-contents article--nav__tableOfContents');
					// $insertedTOCnav->setAttribute('epub:type', 'toc'); // Delete if you're not using epub markup http://www.idpf.org/epub/31/spec/epub-packages.html

					// Create the inner table of contents list
					$tocUL = $doc->createElement('ul');
					$insertedtocUL = $insertedTOCnav->appendChild($tocUL);
					$insertedtocUL->setAttribute('aria-label', 'Chapter List');
					$insertedtocUL->setAttribute('role', 'directory');
					$insertedtocUL->setAttribute('class', 'ul toc_list nav--ul_tableOfContentsList');
					$insertedtocUL->setAttribute('id', 'table-of-contents-ul');

				} elseif ($h2_i == $last) {

					// Add class "last-chapter" to the parent <section> element, in case you want to format the first section in a special way.
					$heading->parentNode->parentNode->setAttribute('class', 'section chapter-section last-chapter chapter-' . $h2_i . '-section');
				} else {

					$heading->parentNode->parentNode->setAttribute('class', 'section chapter-section chapter-' . $h2_i . '-section');

				}

				// Create <li> Elements for Each Chapter
				$tocSpan1 = $doc->createElement('span', 'Chapter ' . $h2_i . ' ');
				$LInodeValue = $doc->createElement('span', $heading->nodeValue);
				$tocLI = $doc->createElement('li');
				$insertedtocLI = $insertedtocUL->appendChild($tocLI);
				$insertedtocLI->setAttribute('class', 'li chapter-list-item toc-' . $h2_i . '-list-item');
				
				/*	
					OPTIONAL
					This snippet was being used in a JS sidebar to show users their progress in a chapter. Can be deleted.
					// $insertedtocLI->setAttribute('data-story', $h20_i);
				*/

				// Link to the appropriate chapter anchor.
				$tocA = $doc->createElement('a');
				$insertedtocA = $insertedtocLI->appendChild($tocA);
				$tocSpan1Inserted = $insertedtocA->parentNode->firstChild->appendChild($tocSpan1);
				$tocSpan1->setAttribute('class', 'span nav--span__TOCchapterNumber toc-chapter-number toc-chapter-number-ch-' . $h2_i);
				$LInodeValueInserted = $insertedtocA->parentNode->firstChild->appendChild($LInodeValue);
				$LInodeValue->setAttribute('class', 'span nav--span__TOCchapterTitle toc-chapter-title toc-chapter-title-ch-' . $h2_i);
				$insertedtocA->setAttribute('href', $currentURL . '/#' . $slug);
				$insertedtocA->setAttribute('class', 'a nav--a__TOCchapterLink toc-chapter-link toc-chapter-link-' . $h2_i);

				/*	
					OPTIONAL
					Add data- attribute to our link, which goes to a shorter URL.
					This can be used to swap the SEO-friendly URL out with the share-friendly URL after pageload with JS.
					//	$insertedtocA->setAttribute('data-newhref', $currentURL . '/#' . $anchorSlug);
				*/

				/* 
					Holdover from some JS I disabled, but still might use in the future.
					// $progressWrapper = $doc->createElement('div');
					// $progressWrapperInserted = $insertedtocLI->appendChild($progressWrapper);
					// $progressWrapper->setAttribute('class', 'div progress-wrapper');
				*/

				// Create some anchor links and insert them at the end of each <h2> element.
				$a = $doc->createElement('a');
				$newnode = $heading->appendChild($a);
				$newnode->setAttribute('class', 'a h2-anchorlink a__bodyLink permalink');
				$newnode->setAttribute('href', $currentURL . '/#' . $slug);
				$newnode->setAttribute('title', 'Click to Copy a Link to this Chapter');

				/*
					Add data- attribute to our link, which goes to a shorter, but less-SEO-friendly URL. (Just in case we want to switch the URLs on page load so users can easily copy the shorter #ch1 anchor link.)
				
					The shorter links goes to the outer section wrapper of the chapter, so it should take users to roughly the same area on the page as the link we're switching it for.
				*/

				$newnode->setAttribute('data-newhref', $currentURL . '/#' . $anchorSlug);

				/*
					Insert a link emoji as a fallback.
					-	The span is there in case you want to treat the text differently than the link element surrounding it.
					-	The span can be set to display:none or deleted if unnecessary.
					-	You can also swap it out with a background SVG in your CSS.
					-	If you have a good icon font, you can use a service like icomoon.io to define this character as the U+1F517 entity in the font file and then explicitly define it in your @font-face declaration with unicode-range.
				*/
				$aText = $doc->createElement('span', ' &#128279;');
				$aText->setAttribute('class', 'span span__anchorLinkEmoji');
				$aTextInsert = $newnode->appendChild($aText);

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
					$childSpan3 = $doc->createElement('span', $heading3->nodeValue);
					$childSpan3->setAttribute('id', 'section-' . $h2_i . '-' . $h3_i . '-title-text-wrapper');
					$childSpan3->setAttribute('class', 'span section-title-text-wrapper section-' . $h2_i . '-' . $h3_i . '-title-text-wrapper');
					$newHeading3 = $doc->createElement('h3');
					$heading3->parentNode->replaceChild($newHeading3, $heading3);
					$newHeading3->appendChild($childSpan3);
					$heading3 = $newHeading3;

					// Mark up the <section> tag that wraps each section
					$heading3->parentNode->setAttribute('id', 'section_' . $h2_i . '-' . $h3_i);
					$heading3->parentNode->setAttribute('aria-labelledby', 'section-' . $h2_i . '-' . $h3_i . '-title-text-wrapper');

					// Wrap the section title and section number in a <div>, in case we want to use flexbox to force them into a horizontal row.
					$parentDiv3 = $doc->createElement('div');
					$parentDiv3->setAttribute('id', 'section-' . $h2_i . '-' . $h3_i . '-title-wrapper');
					$parentDiv3->setAttribute('class', 'div section-title-wrapper section-' . $h2_i . '-' . $h3_i . '-title-wrapper');
					$new_heading_clone3 = $heading3->cloneNode();
					$heading3->parentNode->replaceChild($parentDiv3, $heading3);
					$parentDiv3->appendChild($heading3);
					$heading3->setAttribute('aria-level', '3');
					$heading3->setAttribute('class', 'h3 section-heading section-' . $h2_i . '-' . $h3_i . '-heading');

					// Make the <h3> element tabbable to make navigation a bit easier
					$heading3->setAttribute('tabindex', '0');
					
					// Create a clean anchor link for both the table of contents and the link we'll be placing next to the heading title.
					$slug3 = $tmpslug3 = sanitize_title($heading3->nodeValue);
					$i3 = 2;
					while (false !== in_array($slug3, $anchors3)) {
						$slug3 = sprintf('%s-%d', $tmpslug3, $i3++);
					}
					$anchors3[] = $slug3;
					$heading3->setAttribute('id', $slug3);

					/*
						I like the clean text-based links for SEO purposes, but the users probably need a shorter link if they're going to copy/share it. 
						So we'll create a shorter anchor that simply says:
							https://www.example.com/#ch1
						(or whatever chapter number we're on).
					*/
					$anchorSlug3 = 'section_' . $h2_i . '-' . $h3_i;

					// Create the initial table of contents wrappers if we're on the first-child <h3> of this parent chapter.
					if ($h3_i == 1) {

						// Add class "first-section" to the parent <section> element, in case you want to format the first section in a special way.
						$heading3->parentNode->parentNode->setAttribute('class', 'section section-section first-section-of-chapter section-' . $h2_i . '-' . $h3_i . '-section');

						$tocUL3 = $doc->createElement('ul');
						$insertedtocUL3 = $insertedtocLI->appendChild($tocUL3);
						$insertedtocUL3->setAttribute('aria-label', 'Chapter ' . $h2_i . ' Section List');
						$insertedtocUL3->setAttribute('class', 'ul toc_section_list');
						$insertedtocUL3->setAttribute('id', 'inner_toc_section_list-' . $h2_i . '-' . $h3_i);
					}
					elseif ($h3_i == $lastH3) {

						// Add a "last-section" class in case you want to format the first or last section in some special way.
						$heading3->parentNode->parentNode->setAttribute('class', 'section section-section last-section-of-chapter section-' . $h2_i . '-' . $h3_i . '-section');
					}
					else {
						$heading3->parentNode->parentNode->setAttribute('class', 'section section-section section-' . $h2_i . '-' . $h3_i . '-section');
					}

					// Add <li> elements for each section
					$tocLI3 = $doc->createElement('li');
					$insertedtocLI3 = $insertedtocUL3->appendChild($tocLI3);
					$insertedtocLI3->setAttribute('class', 'li section-list-item toc-' . $h2_i . '-' . $h3_i . '-list-item');
					
					/*
						OPTIONAL
						This snippet was being used in a JS sidebar to show users their progress in a chapter. Can be deleted.
						// $insertedtocLI3->setAttribute('data-section-story', $h30_i); 
						*/

					// Create an element containing the text we'll be inserting into the table of contents.
					$LInodeValue3 = $doc->createElement('span', $heading3->nodeValue);
					$LInodeValue3->setAttribute('class', 'span nav--span__TOCsectionTitle toc-section-title toc-section-title-section-' . $h2_i . '-' . $h3_i);

					// Insert a link into the table of contents.
					$tocA3 = $doc->createElement('a');
					$insertedtocA3 = $insertedtocLI3->appendChild($tocA3);
					$insertedtocA3->setAttribute('href', $currentURL . '/#' . $slug3);
					$insertedtocA3->setAttribute('data-newhref', $currentURL . '/#' . $anchorSlug3);
					$insertedtocA3->setAttribute('class', 'a nav--a__TOCsectionLink toc-section-link toc-section-link-' . $h2_i . '-' . $h3_i);

					// Insert the text of our heading into the link.
					$LInodeValue3Inserted = $insertedtocA3->parentNode->firstChild->appendChild($LInodeValue3);

					// Insert the current section number into the link.
					$tocSpan3 = $doc->createElement('span', '§&nbsp;' . $h2_i . '.' . $h3_i . ' ');
					$tocSpan3Inserted = $insertedtocA3->parentNode->firstChild->appendChild($tocSpan3);
					$tocSpan3->setAttribute('class', 'span nav--span__TOCsectionNumber toc-section-number toc-section-number-' . $h2_i . '-' . $h2_i);

					/* 
						Holdover from some JS I disabled, but still might use in the future.
						// $progressSectionWrapper = $doc->createElement('div');
						// $progressSectionWrapperInserted = $insertedtocLI3->appendChild($progressSectionWrapper);
						// $progressSectionWrapper->setAttribute('class', 'div section-progress-wrapper');
					*/

					// Create an empty anchor link and insert it in front of the current <h3> element.
					$para3 = $doc->createElement('p');
					$h3prefix = $parentDiv3->insertBefore($para3, $heading3);
					$h3prefix->setAttribute('class', 'p h3-prefix section-' . $h2_i . '-' . $h3_i);
					$a3 = $doc->createElement('a', '');
					$newnode3 = $para3->appendChild($a3);
					$newnode3->setAttribute('class', 'a h3-anchorlink a__bodyLink permalink');
					$heading3->setAttribute('id', $slug3);
					$newnode3->setAttribute('href', $currentURL . '/#' . $slug3);
					$newnode3->setAttribute('data-newhref', $currentURL . '/#' . $anchorSlug3);
					$newnode3->setAttribute('title', 'Click to Copy a Link to this Section');

					/*
						Next we'll create <span> tags containing the anchor link content.
						
						I've separated the section symbol and the current section number because I'm giving assigning the symbold a different font, formatting it a little larger, and then vertically aligning them.
						Those <span> tags can be deleted if you're fine with treating both entities the same in your CSS.
					*/
					$spanSectionSymbol3 = $doc->createElement('span', '§');
					$spanSectionNumber3 = $doc->createElement('span', $h2_i . '.' . $h3_i );
					$a3->appendChild($spanSectionSymbol3);
					$spanSectionSymbol3->setAttribute('class', 'span span__h3anchorlinkSymbol permalink');
					$a3->appendChild($spanSectionNumber3);
					$spanSectionNumber3->setAttribute('class', 'span span__h3anchorlinkNumber permalink');

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
						$childSpan4 = $doc->createElement('span', $heading4->nodeValue);
						$childSpan4->setAttribute('id', 'subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-title-text-wrapper');
						$childSpan4->setAttribute('class', 'span subsection-title-text-wrapper subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-title-text-wrapper');
						$newHeading4 = $doc->createElement('h4');
						$heading4->parentNode->replaceChild($newHeading4, $heading4);
						$newHeading4->appendChild($childSpan4);
						$heading4 = $newHeading4;

						// Mark up the <section> tag that wraps each section
						$heading4->parentNode->setAttribute('id', 'subsection_' . $h2_i . '-' . $h3_i . '-' . $h4_i);
						$heading4->parentNode->setAttribute('aria-labelledby', 'subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-title-text-wrapper');

						// Wrap the subsection title and subsection number in a <div>, in case we want to use flexbox to force them into a horizontal row.
						$parentDiv4 = $doc->createElement('div');
						$parentDiv4->setAttribute('id', 'subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-title-wrapper');
						$parentDiv4->setAttribute('class', 'div subsection-title-wrapper subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-title-wrapper');
						$new_heading_clone4 = $heading4->cloneNode();
						$heading4->parentNode->replaceChild($parentDiv4, $heading4);
						$parentDiv4->appendChild($heading4);
						$heading4->setAttribute('aria-level', '4');
						$heading4->setAttribute('class', 'h4 subsection-heading subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-heading');

						// Make the <h4> element tabbable to make navigation a bit easier
						$heading4->setAttribute('tabindex', '0');

						// Create a clean anchor link for both the table of contents and the link we'll be placing next to the heading title.
						$slug4 = $tmpslug4 = sanitize_title($heading4->nodeValue);
						$i4 = 2;
						while (false !== in_array($slug4, $anchors4)) {
							$slug4 = sprintf('%s-%d', $tmpslug4, $i4++);
						}
						$anchors4[] = $slug4;
						$heading4->setAttribute('id', $slug4);

						/*
							I like the clean text-based links for SEO purposes, but the users probably need a shorter link if they're going to copy/share it. 
							So we'll create a shorter anchor that simply says:
								https://www.example.com/#ch1
							(or whatever chapter number we're on).
						*/
						$anchorSlug4 = 'subsection_' . $h2_i . '-' . $h3_i . '-' . $h4_i;
						$heading4->setAttribute('id', $slug4);

						// Create the initial table of contents wrappers if we're on the first-child <h4> of this parent chapter.
						if ($h4_i == 1) {

							// Add class "first-subsection" to the parent <section> element, in case you want to format the first section in a special way.
							$heading4->parentNode->parentNode->setAttribute('class', 'section subsection-section first-subsection-of-chapter-section subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-section');

							$tocUL4 = $doc->createElement('ul');
							$insertedtocUL4 = $insertedtocLI3->appendChild($tocUL4);
							$insertedtocUL4->setAttribute('aria-label', 'Section ' . $h2_i . '.' . $h3_i . ' Subsection List');
							$insertedtocUL4->setAttribute('class', 'ul toc_subsection_list');
							$insertedtocUL4->setAttribute('id', 'inner_toc_subsection_list-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
						}
						elseif ($h4_i == $lastH4) {

							// Add a "last-subsection" class in case you want to format the first or last section in some special way.
							$heading4->parentNode->parentNode->setAttribute('class', 'section subsection-section last-subsection-of-chapter-section subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-section');
						}
						else {
							$heading4->parentNode->parentNode->setAttribute('class', 'section subsection-section subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-section');
						}

						// Add <li> elements for each subsection
						$tocLI4 = $doc->createElement('li');
						$insertedtocLI4 = $insertedtocUL4->appendChild($tocLI4);
						$insertedtocLI4->setAttribute('class', 'li subsection-list-item toc-' . $h2_i . '-' . $h3_i . '-' . $h4_i . '-list-item');
						
						/*
							OPTIONAL
							This snippet was being used in a JS sidebar to show users their progress in a chapter. Can be deleted.
							// $insertedtocLI4->setAttribute('data-subsection-story', $h40_i);*/

						// Create and element containing the text we'll be inserting into the table of contents.
						$LInodeValue4 = $doc->createElement('span', $heading4->nodeValue);
						$LInodeValue4->setAttribute('class', 'span nav--span__TOCsubsectionTitle toc-subsection-title toc-subsection-title-section-' . $h2_i . '-' . $h3_i . '-' . $h4_i);

						// Insert a link into the table of contents.
						$tocA4 = $doc->createElement('a');
						$insertedtocA4 = $insertedtocLI4->appendChild($tocA4);
						$insertedtocA4->setAttribute('href', $currentURL . '/#' . $slug4);
						$insertedtocA4->setAttribute('data-newhref', $currentURL . '/#' . $anchorSlug4);
						$insertedtocA4->setAttribute('class', 'a nav--a__TOCsubsectionLink toc-subsection-link toc-subsection-link-' . $h2_i . '-' . $h3_i . '-' . $h4_i);

						// Insert the text of our heading into the link.
						$LInodeValue4Inserted = $insertedtocA4->parentNode->firstChild->appendChild($LInodeValue4);

						// Insert the current subsection number into the link.
						$tocSpan4 = $doc->createElement('span', '§&nbsp;' . $h2_i . '.' . $h3_i . '.' . $h4_i . ' ');
						$tocSpan4Inserted = $insertedtocA4->parentNode->firstChild->appendChild($tocSpan4);
						$tocSpan4->setAttribute('class', 'span nav--span__TOCsubsectionNumber toc-subsection-number toc-subsection-number-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
						
						// Create an empty anchor link and insert it in front of the current <h4> element.
						$para4 = $doc->createElement('p');
						$h4prefix = $parentDiv4->insertBefore($para4, $heading4);
						$h4prefix->setAttribute('class', 'p h4-prefix subsection-' . $h2_i . '-' . $h3_i . '-' . $h4_i);
						$a4 = $doc->createElement('a', '§&nbsp;' . $h2_i . '.' . $h3_i . '.' . $h4_i . ' ');
						$newnode4 = $para4->appendChild($a4);
						$newnode4->setAttribute('class', 'a h4-anchorlink a__bodyLink permalink');
						$heading4->setAttribute('id', $slug4);
						$newnode4->setAttribute('href', $currentURL . '/#' . $slug4);
						$newnode4->setAttribute('data-newhref', $currentURL . '/#' . $anchorSlug4);
						$newnode4->setAttribute('title', 'Click to Copy a Link to this Subsection');

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

		/*
			Want to add a <h2>heading</h2> to your table of contents?
			Here you go:
				$tocH2 = $doc->createElement( 'h2', 'Article Contents' );
				$insertedtocH2 = $insertedTOCnav->firstChild->parentNode->insertBefore( $tocH2, $insertedtocUL );
				$tocH2->setAttribute( 'class', 'h2 table-of-contents-heading' );
		*/

		// All done! How neat is that?
		// https://www.youtube.com/watch?v=Hm3JodBR-vs
		$new_content = trim($doc->saveHTML());
		return $new_content;
	}
	else {
		return $content;
	}
}

add_filter('the_content', 'add_chapters_sections_toc', 9, 1);
?>
