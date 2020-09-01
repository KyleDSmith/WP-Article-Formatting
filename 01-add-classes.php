<?php
function add_classes_to_post_content($content = '') {
	/*=====================================================================================
	=              Add Classes to All HTML Elements Denoting Their Tag Name               =
	=              https://css-tricks.com/efficiently-rendering-css/					  =
	======================================================================================*/

	global $post;

	if ( in_the_loop() && is_main_query() && !empty($content) ) {
		
	// Create an instance of DOMDocument.
    	$doc = new DOMDocument();
		global $wp;
    	// Supress errors due to malformed HTML.
    	// See http://stackoverflow.com/a/17559716/3059883
    	$libxml_previous_state = libxml_use_internal_errors( true );
		
	$doc->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOEMPTYTAG );
	
	// Restore previous state of libxml_use_internal_errors() now that we're done.
    	libxml_use_internal_errors( $libxml_previous_state );
		
	foreach ( array( 'a' ) as $aSingular ) {
		$as = $doc->getElementsByTagName( 'a' );
		foreach ( $as as $asingle ) {
			$url = $asingle->getAttribute( 'href' );
    		if( stripos($url,'worklawyers.org') !== false || stripos($url,'worklawyers.com') !== false || substr($url, 0, 1) === '#' ) {
				$oldaClass = $asingle->getAttribute( 'class' );
				$newaClass = "a a__bodyLink a__bodyLinkInternal " . $oldaClass;
				$asingle->removeAttribute('class');
				$asingle->setAttribute('class', $newaClass );
			} elseif( stripos($url,'mailto:') !== false ) {
				$oldaClass = $asingle->getAttribute( 'class' );
				$newaClass = "a a__bodyLink a__bodyLinkEmail " . $oldaClass;
				$asingle->removeAttribute('class');
				$asingle->setAttribute('class', $newaClass );
			} else {
				$oldaClass = $asingle->getAttribute( 'class' );
				$newaClass = "a a__bodyLink a__bodyLinkExternal " . $oldaClass;
				$asingle->removeAttribute('class');
				$asingle->setAttribute('class', $newaClass );
				$asingle->setAttribute('rel', 'noopener noreferrer');
				$asingle->setAttribute('target', '_blank');
				$newSR_onlyText = $doc->createElement('span', ' (Opens in new window)');
				$asingle->appendChild($newSR_onlyText);
				$newSR_onlyText->setAttribute('class', 'sr-only' );
			}
		}
	};
	foreach ( array( 'abbr' ) as $abbrSingular ) {
		$abbrs = $doc->getElementsByTagName( 'abbr' );
		foreach ( $abbrs as $abbrsingle ) {
			$oldabbrClass = $abbrsingle->getAttribute( 'class' );
			$newabbrClass = "abbr " . $oldabbrClass;
			$abbrsingle->removeAttribute('class');
			$abbrsingle->setAttribute('class', $newabbrClass );
		}
	};
	foreach ( array( 'acronym' ) as $acronymSingular ) {
		$acronyms = $doc->getElementsByTagName( 'acronym' );
		foreach ( $acronyms as $acronymsingle ) {
			$oldacronymClass = $acronymsingle->getAttribute( 'class' );
			$newacronymClass = "acronym " . $oldacronymClass;
			$acronymsingle->removeAttribute('class');
			$acronymsingle->setAttribute('class', $newacronymClass );
		}
	};
	foreach ( array( 'address' ) as $addressSingular ) {
		$addresss = $doc->getElementsByTagName( 'address' );
		foreach ( $addresss as $addresssingle ) {
			$oldaddressClass = $addresssingle->getAttribute( 'class' );
			$newaddressClass = "address " . $oldaddressClass;
			$addresssingle->removeAttribute('class');
			$addresssingle->setAttribute('class', $newaddressClass );
		}
	};
	foreach ( array( 'applet' ) as $appletSingular ) {
		$applets = $doc->getElementsByTagName( 'applet' );
		foreach ( $applets as $appletsingle ) {
			$oldappletClass = $appletsingle->getAttribute( 'class' );
			$newappletClass = "applet " . $oldappletClass;
			$appletsingle->removeAttribute('class');
			$appletsingle->setAttribute('class', $newappletClass );
		}
	};
	foreach ( array( 'area' ) as $areaSingular ) {
		$areas = $doc->getElementsByTagName( 'area' );
		foreach ( $areas as $areasingle ) {
			$oldareaClass = $areasingle->getAttribute( 'class' );
			$newareaClass = "area " . $oldareaClass;
			$areasingle->removeAttribute('class');
			$areasingle->setAttribute('class', $newareaClass );
		}
	};
	foreach ( array( 'article' ) as $articleSingular ) {
		$articles = $doc->getElementsByTagName( 'article' );
		foreach ( $articles as $articlesingle ) {
			$oldarticleClass = $articlesingle->getAttribute( 'class' );
			$newarticleClass = "article " . $oldarticleClass;
			$articlesingle->removeAttribute('class');
			$articlesingle->setAttribute('class', $newarticleClass );
		}
	};
	foreach ( array( 'aside' ) as $asideSingular ) {
		$asides = $doc->getElementsByTagName( 'aside' );
		foreach ( $asides as $asidesingle ) {
			$oldasideClass = $asidesingle->getAttribute( 'class' );
			$newasideClass = "aside " . $oldasideClass;
			$asidesingle->removeAttribute('class');
			$asidesingle->setAttribute('class', $newasideClass );
		}
	};
	foreach ( array( 'audio' ) as $audioSingular ) {
		$audios = $doc->getElementsByTagName( 'audio' );
		foreach ( $audios as $audiosingle ) {
			$oldaudioClass = $audiosingle->getAttribute( 'class' );
			$newaudioClass = "audio " . $oldaudioClass;
			$audiosingle->removeAttribute('class');
			$audiosingle->setAttribute('class', $newaudioClass );
		}
	};
	foreach ( array( 'b' ) as $bSingular ) {
		$bs = $doc->getElementsByTagName( 'b' );
		foreach ( $bs as $bsingle ) {
			$oldbClass = $bsingle->getAttribute( 'class' );
			$newbClass = "b " . $oldbClass;
			$bsingle->removeAttribute('class');
			$bsingle->setAttribute('class', $newbClass );
		}
	};
	foreach ( array( 'bdi' ) as $bdiSingular ) {
		$bdis = $doc->getElementsByTagName( 'bdi' );
		foreach ( $bdis as $bdisingle ) {
			$oldbdiClass = $bdisingle->getAttribute( 'class' );
			$newbdiClass = "bdi " . $oldbdiClass;
			$bdisingle->removeAttribute('class');
			$bdisingle->setAttribute('class', $newbdiClass );
		}
	};
	foreach ( array( 'bdo' ) as $bdoSingular ) {
		$bdos = $doc->getElementsByTagName( 'bdo' );
		foreach ( $bdos as $bdosingle ) {
			$oldbdoClass = $bdosingle->getAttribute( 'class' );
			$newbdoClass = "bdo " . $oldbdoClass;
			$bdosingle->removeAttribute('class');
			$bdosingle->setAttribute('class', $newbdoClass );
		}
	};
	foreach ( array( 'big' ) as $bigSingular ) {
		$bigs = $doc->getElementsByTagName( 'big' );
		foreach ( $bigs as $bigsingle ) {
			$oldbigClass = $bigsingle->getAttribute( 'class' );
			$newbigClass = "big " . $oldbigClass;
			$bigsingle->removeAttribute('class');
			$bigsingle->setAttribute('class', $newbigClass );
		}
	};
	foreach ( array( 'blockquote' ) as $blockquoteSingular ) {
		$blockquotes = $doc->getElementsByTagName( 'blockquote' );
		foreach ( $blockquotes as $blockquotesingle ) {
			$oldblockquoteClass = $blockquotesingle->getAttribute( 'class' );
			$newblockquoteClass = "blockquote " . $oldblockquoteClass;
			$blockquotesingle->removeAttribute('class');
			$blockquotesingle->setAttribute('class', $newblockquoteClass );
		}
	};
	foreach ( array( 'body' ) as $bodySingular ) {
		$bodys = $doc->getElementsByTagName( 'body' );
		foreach ( $bodys as $bodysingle ) {
			$oldbodyClass = $bodysingle->getAttribute( 'class' );
			$newbodyClass = "body " . $oldbodyClass;
			$bodysingle->removeAttribute('class');
			$bodysingle->setAttribute('class', $newbodyClass );
		}
	};
	foreach ( array( 'bodydiv' ) as $bodydivSingular ) {
		$bodydivs = $doc->getElementsByTagName( 'bodydiv' );
		foreach ( $bodydivs as $bodydivsingle ) {
			$oldbodydivClass = $bodydivsingle->getAttribute( 'class' );
			$newbodydivClass = "bodydiv " . $oldbodydivClass;
			$bodydivsingle->removeAttribute('class');
			$bodydivsingle->setAttribute('class', $newbodydivClass );
		}
	};
	foreach ( array( 'br' ) as $brSingular ) {
		$brs = $doc->getElementsByTagName( 'br' );
		foreach ( $brs as $brsingle ) {
			$oldbrClass = $brsingle->getAttribute( 'class' );
			$newbrClass = "br " . $oldbrClass;
			$brsingle->removeAttribute('class');
			$brsingle->setAttribute('class', $newbrClass );
		}
	};
	foreach ( array( 'button' ) as $buttonSingular ) {
		$buttons = $doc->getElementsByTagName( 'button' );
		foreach ( $buttons as $buttonsingle ) {
			$oldbuttonClass = $buttonsingle->getAttribute( 'class' );
			$newbuttonClass = "button " . $oldbuttonClass;
			$buttonsingle->removeAttribute('class');
			$buttonsingle->setAttribute('class', $newbuttonClass );
		}
	};
	foreach ( array( 'canvas' ) as $canvasSingular ) {
		$canvass = $doc->getElementsByTagName( 'canvas' );
		foreach ( $canvass as $canvassingle ) {
			$oldcanvasClass = $canvassingle->getAttribute( 'class' );
			$newcanvasClass = "canvas " . $oldcanvasClass;
			$canvassingle->removeAttribute('class');
			$canvassingle->setAttribute('class', $newcanvasClass );
		}
	};
	foreach ( array( 'caption' ) as $captionSingular ) {
		$captions = $doc->getElementsByTagName( 'caption' );
		foreach ( $captions as $captionsingle ) {
			$oldcaptionClass = $captionsingle->getAttribute( 'class' );
			$newcaptionClass = "caption " . $oldcaptionClass;
			$captionsingle->removeAttribute('class');
			$captionsingle->setAttribute('class', $newcaptionClass );
		}
	};
	foreach ( array( 'center' ) as $centerSingular ) {
		$centers = $doc->getElementsByTagName( 'center' );
		foreach ( $centers as $centersingle ) {
			$oldcenterClass = $centersingle->getAttribute( 'class' );
			$newcenterClass = "center " . $oldcenterClass;
			$centersingle->removeAttribute('class');
			$centersingle->setAttribute('class', $newcenterClass );
		}
	};
	foreach ( array( 'cite' ) as $citeSingular ) {
		$cites = $doc->getElementsByTagName( 'cite' );
		foreach ( $cites as $citesingle ) {
			$oldciteClass = $citesingle->getAttribute( 'class' );
			$newciteClass = "cite " . $oldciteClass;
			$citesingle->removeAttribute('class');
			$citesingle->setAttribute('class', $newciteClass );
		}
	};
	foreach ( array( 'code' ) as $codeSingular ) {
		$codes = $doc->getElementsByTagName( 'code' );
		foreach ( $codes as $codesingle ) {
			$oldcodeClass = $codesingle->getAttribute( 'class' );
			$newcodeClass = "code " . $oldcodeClass;
			$codesingle->removeAttribute('class');
			$codesingle->setAttribute('class', $newcodeClass );
		}
	};
	foreach ( array( 'col' ) as $colSingular ) {
		$cols = $doc->getElementsByTagName( 'col' );
		foreach ( $cols as $colsingle ) {
			$oldcolClass = $colsingle->getAttribute( 'class' );
			$newcolClass = "col " . $oldcolClass;
			$colsingle->removeAttribute('class');
			$colsingle->setAttribute('class', $newcolClass );
		}
	};
	foreach ( array( 'colgroup' ) as $colgroupSingular ) {
		$colgroups = $doc->getElementsByTagName( 'colgroup' );
		foreach ( $colgroups as $colgroupsingle ) {
			$oldcolgroupClass = $colgroupsingle->getAttribute( 'class' );
			$newcolgroupClass = "colgroup " . $oldcolgroupClass;
			$colgroupsingle->removeAttribute('class');
			$colgroupsingle->setAttribute('class', $newcolgroupClass );
		}
	};
	foreach ( array( 'data' ) as $dataSingular ) {
		$datas = $doc->getElementsByTagName( 'data' );
		foreach ( $datas as $datasingle ) {
			$olddataClass = $datasingle->getAttribute( 'class' );
			$newdataClass = "data " . $olddataClass;
			$datasingle->removeAttribute('class');
			$datasingle->setAttribute('class', $newdataClass );
		}
	};
	foreach ( array( 'datalist' ) as $datalistSingular ) {
		$datalists = $doc->getElementsByTagName( 'datalist' );
		foreach ( $datalists as $datalistsingle ) {
			$olddatalistClass = $datalistsingle->getAttribute( 'class' );
			$newdatalistClass = "datalist " . $olddatalistClass;
			$datalistsingle->removeAttribute('class');
			$datalistsingle->setAttribute('class', $newdatalistClass );
		}
	};
	foreach ( array( 'dd' ) as $ddSingular ) {
		$dds = $doc->getElementsByTagName( 'dd' );
		foreach ( $dds as $ddsingle ) {
			$oldddClass = $ddsingle->getAttribute( 'class' );
			$newddClass = "dd " . $oldddClass;
			$ddsingle->removeAttribute('class');
			$ddsingle->setAttribute('class', $newddClass );
		}
	};
	foreach ( array( 'del' ) as $delSingular ) {
		$dels = $doc->getElementsByTagName( 'del' );
		foreach ( $dels as $delsingle ) {
			$olddelClass = $delsingle->getAttribute( 'class' );
			$newdelClass = "del " . $olddelClass;
			$delsingle->removeAttribute('class');
			$delsingle->setAttribute('class', $newdelClass );
		}
	};
	foreach ( array( 'desc' ) as $descSingular ) {
		$descs = $doc->getElementsByTagName( 'desc' );
		foreach ( $descs as $descsingle ) {
			$olddescClass = $descsingle->getAttribute( 'class' );
			$newdescClass = "desc " . $olddescClass;
			$descsingle->removeAttribute('class');
			$descsingle->setAttribute('class', $newdescClass );
		}
	};
	foreach ( array( 'details' ) as $detailsSingular ) {
		$detailss = $doc->getElementsByTagName( 'details' );
		foreach ( $detailss as $detailssingle ) {
			$olddetailsClass = $detailssingle->getAttribute( 'class' );
			$newdetailsClass = "details " . $olddetailsClass;
			$detailssingle->removeAttribute('class');
			$detailssingle->setAttribute('class', $newdetailsClass );
		}
	};
	foreach ( array( 'dfn' ) as $dfnSingular ) {
		$dfns = $doc->getElementsByTagName( 'dfn' );
		foreach ( $dfns as $dfnsingle ) {
			$olddfnClass = $dfnsingle->getAttribute( 'class' );
			$newdfnClass = "dfn " . $olddfnClass;
			$dfnsingle->removeAttribute('class');
			$dfnsingle->setAttribute('class', $newdfnClass );
		}
	};
	foreach ( array( 'dialog' ) as $dialogSingular ) {
		$dialogs = $doc->getElementsByTagName( 'dialog' );
		foreach ( $dialogs as $dialogsingle ) {
			$olddialogClass = $dialogsingle->getAttribute( 'class' );
			$newdialogClass = "dialog " . $olddialogClass;
			$dialogsingle->removeAttribute('class');
			$dialogsingle->setAttribute('class', $newdialogClass );
		}
	};
	foreach ( array( 'dir' ) as $dirSingular ) {
		$dirs = $doc->getElementsByTagName( 'dir' );
		foreach ( $dirs as $dirsingle ) {
			$olddirClass = $dirsingle->getAttribute( 'class' );
			$newdirClass = "dir " . $olddirClass;
			$dirsingle->removeAttribute('class');
			$dirsingle->setAttribute('class', $newdirClass );
		}
	};
	foreach ( array( 'div' ) as $divSingular ) {
		$divs = $doc->getElementsByTagName( 'div' );
		foreach ( $divs as $divsingle ) {
			$olddivClass = $divsingle->getAttribute( 'class' );
			$newdivClass = "div " . $olddivClass;
			$divsingle->removeAttribute('class');
			$divsingle->setAttribute('class', $newdivClass );
		}
	};
	foreach ( array( 'dl' ) as $dlSingular ) {
		$dls = $doc->getElementsByTagName( 'dl' );
		foreach ( $dls as $dlsingle ) {
			$olddlClass = $dlsingle->getAttribute( 'class' );
			$newdlClass = "dl " . $olddlClass;
			$dlsingle->removeAttribute('class');
			$dlsingle->setAttribute('class', $newdlClass );
		}
	};
	foreach ( array( 'dt' ) as $dtSingular ) {
		$dts = $doc->getElementsByTagName( 'dt' );
		foreach ( $dts as $dtsingle ) {
			$olddtClass = $dtsingle->getAttribute( 'class' );
			$newdtClass = "dt " . $olddtClass;
			$dtsingle->removeAttribute('class');
			$dtsingle->setAttribute('class', $newdtClass );
		}
	};
	foreach ( array( 'em' ) as $emSingular ) {
		$ems = $doc->getElementsByTagName( 'em' );
		foreach ( $ems as $emsingle ) {
			$oldemClass = $emsingle->getAttribute( 'class' );
			$newemClass = "em " . $oldemClass;
			$emsingle->removeAttribute('class');
			$emsingle->setAttribute('class', $newemClass );
		}
	};
	foreach ( array( 'embed' ) as $embedSingular ) {
		$embeds = $doc->getElementsByTagName( 'embed' );
		foreach ( $embeds as $embedsingle ) {
			$oldembedClass = $embedsingle->getAttribute( 'class' );
			$newembedClass = "embed " . $oldembedClass;
			$embedsingle->removeAttribute('class');
			$embedsingle->setAttribute('class', $newembedClass );
		}
	};
	foreach ( array( 'fieldset' ) as $fieldsetSingular ) {
		$fieldsets = $doc->getElementsByTagName( 'fieldset' );
		foreach ( $fieldsets as $fieldsetsingle ) {
			$oldfieldsetClass = $fieldsetsingle->getAttribute( 'class' );
			$newfieldsetClass = "fieldset " . $oldfieldsetClass;
			$fieldsetsingle->removeAttribute('class');
			$fieldsetsingle->setAttribute('class', $newfieldsetClass );
		}
	};
	foreach ( array( 'figcaption' ) as $figcaptionSingular ) {
		$figcaptions = $doc->getElementsByTagName( 'figcaption' );
		foreach ( $figcaptions as $figcaptionsingle ) {
			$oldfigcaptionClass = $figcaptionsingle->getAttribute( 'class' );
			$newfigcaptionClass = "figcaption " . $oldfigcaptionClass;
			$figcaptionsingle->removeAttribute('class');
			$figcaptionsingle->setAttribute('class', $newfigcaptionClass );
		}
	};
	foreach ( array( 'figure' ) as $figureSingular ) {
		$figures = $doc->getElementsByTagName( 'figure' );
		foreach ( $figures as $figuresingle ) {
			$oldfigureClass = $figuresingle->getAttribute( 'class' );
			$newfigureClass = "figure " . $oldfigureClass;
			$figuresingle->removeAttribute('class');
			$figuresingle->setAttribute('class', $newfigureClass );
		}
	};
	foreach ( array( 'font' ) as $fontSingular ) {
		$fonts = $doc->getElementsByTagName( 'font' );
		foreach ( $fonts as $fontsingle ) {
			$oldfontClass = $fontsingle->getAttribute( 'class' );
			$newfontClass = "font " . $oldfontClass;
			$fontsingle->removeAttribute('class');
			$fontsingle->setAttribute('class', $newfontClass );
		}
	};
	foreach ( array( 'footer' ) as $footerSingular ) {
		$footers = $doc->getElementsByTagName( 'footer' );
		foreach ( $footers as $footersingle ) {
			$oldfooterClass = $footersingle->getAttribute( 'class' );
			$newfooterClass = "footer " . $oldfooterClass;
			$footersingle->removeAttribute('class');
			$footersingle->setAttribute('class', $newfooterClass );
		}
	};
	foreach ( array( 'form' ) as $formSingular ) {
		$forms = $doc->getElementsByTagName( 'form' );
		foreach ( $forms as $formsingle ) {
			$oldformClass = $formsingle->getAttribute( 'class' );
			$newformClass = "form " . $oldformClass;
			$formsingle->removeAttribute('class');
			$formsingle->setAttribute('class', $newformClass );
		}
	};
	foreach ( array( 'frame' ) as $frameSingular ) {
		$frames = $doc->getElementsByTagName( 'frame' );
		foreach ( $frames as $framesingle ) {
			$oldframeClass = $framesingle->getAttribute( 'class' );
			$newframeClass = "frame " . $oldframeClass;
			$framesingle->removeAttribute('class');
			$framesingle->setAttribute('class', $newframeClass );
		}
	};
	foreach ( array( 'frameset' ) as $framesetSingular ) {
		$framesets = $doc->getElementsByTagName( 'frameset' );
		foreach ( $framesets as $framesetsingle ) {
			$oldframesetClass = $framesetsingle->getAttribute( 'class' );
			$newframesetClass = "frameset " . $oldframesetClass;
			$framesetsingle->removeAttribute('class');
			$framesetsingle->setAttribute('class', $newframesetClass );
		}
	};
	foreach ( array( 'h1' ) as $h1Singular ) {
		$h1s = $doc->getElementsByTagName( 'h1' );
		foreach ( $h1s as $h1single ) {
			$oldh1Class = $h1single->getAttribute( 'class' );
			$newh1Class = "h1 " . $oldh1Class;
			$h1single->removeAttribute('class');
			$h1single->setAttribute('class', $newh1Class );
		}
	};
	foreach ( array( 'h2' ) as $h2Singular ) {
		$h2s = $doc->getElementsByTagName( 'h2' );
		foreach ( $h2s as $h2single ) {
			$oldh2Class = $h2single->getAttribute( 'class' );
			$newh2Class = "h2 " . $oldh2Class;
			$h2single->removeAttribute('class');
			$h2single->setAttribute('class', $newh2Class );
		}
	};
	foreach ( array( 'h3' ) as $h3Singular ) {
		$h3s = $doc->getElementsByTagName( 'h3' );
		foreach ( $h3s as $h3single ) {
			$oldh3Class = $h3single->getAttribute( 'class' );
			$newh3Class = "h3 " . $oldh3Class;
			$h3single->removeAttribute('class');
			$h3single->setAttribute('class', $newh3Class );
		}
	};
	foreach ( array( 'h4' ) as $h4Singular ) {
		$h4s = $doc->getElementsByTagName( 'h4' );
		foreach ( $h4s as $h4single ) {
			$oldh4Class = $h4single->getAttribute( 'class' );
			$newh4Class = "h4 " . $oldh4Class;
			$h4single->removeAttribute('class');
			$h4single->setAttribute('class', $newh4Class );
		}
	};
	foreach ( array( 'h5' ) as $h5Singular ) {
		$h5s = $doc->getElementsByTagName( 'h5' );
		foreach ( $h5s as $h5single ) {
			$oldh5Class = $h5single->getAttribute( 'class' );
			$newh5Class = "h5 " . $oldh5Class;
			$h5single->removeAttribute('class');
			$h5single->setAttribute('class', $newh5Class );
		}
	};
	foreach ( array( 'h6' ) as $h6Singular ) {
		$h6s = $doc->getElementsByTagName( 'h6' );
		foreach ( $h6s as $h6single ) {
			$oldh6Class = $h6single->getAttribute( 'class' );
			$newh6Class = "h6 " . $oldh6Class;
			$h6single->removeAttribute('class');
			$h6single->setAttribute('class', $newh6Class );
		}
	};
	foreach ( array( 'head' ) as $headSingular ) {
		$heads = $doc->getElementsByTagName( 'head' );
		foreach ( $heads as $headsingle ) {
			$oldheadClass = $headsingle->getAttribute( 'class' );
			$newheadClass = "head " . $oldheadClass;
			$headsingle->removeAttribute('class');
			$headsingle->setAttribute('class', $newheadClass );
		}
	};
	foreach ( array( 'header' ) as $headerSingular ) {
		$headers = $doc->getElementsByTagName( 'header' );
		foreach ( $headers as $headersingle ) {
			$oldheaderClass = $headersingle->getAttribute( 'class' );
			$newheaderClass = "header " . $oldheaderClass;
			$headersingle->removeAttribute('class');
			$headersingle->setAttribute('class', $newheaderClass );
		}
	};
	foreach ( array( 'hr' ) as $hrSingular ) {
		$hrs = $doc->getElementsByTagName( 'hr' );
		foreach ( $hrs as $hrsingle ) {
			$oldhrClass = $hrsingle->getAttribute( 'class' );
			$newhrClass = "hr " . $oldhrClass;
			$hrsingle->removeAttribute('class');
			$hrsingle->setAttribute('class', $newhrClass );
		}
	};
	foreach ( array( 'html' ) as $htmlSingular ) {
		$htmls = $doc->getElementsByTagName( 'html' );
		foreach ( $htmls as $htmlsingle ) {
			$oldhtmlClass = $htmlsingle->getAttribute( 'class' );
			$newhtmlClass = "html " . $oldhtmlClass;
			$htmlsingle->removeAttribute('class');
			$htmlsingle->setAttribute('class', $newhtmlClass );
		}
	};
	foreach ( array( 'i' ) as $iSingular ) {
		$is = $doc->getElementsByTagName( 'i' );
		foreach ( $is as $isingle ) {
			$oldiClass = $isingle->getAttribute( 'class' );
			$newiClass = "i " . $oldiClass;
			$isingle->removeAttribute('class');
			$isingle->setAttribute('class', $newiClass );
		}
	};
	foreach ( array( 'iframe' ) as $iframeSingular ) {
		$iframes = $doc->getElementsByTagName( 'iframe' );
		foreach ( $iframes as $iframesingle ) {
			$oldiframeClass = $iframesingle->getAttribute( 'class' );
			$newiframeClass = "iframe " . $oldiframeClass;
			$iframesingle->removeAttribute('class');
			$iframesingle->setAttribute('class', $newiframeClass );
		}
	};
	foreach ( array( 'img' ) as $imgSingular ) {
		$imgs = $doc->getElementsByTagName( 'img' );
		foreach ( $imgs as $imgsingle ) {
			$oldimgClass = $imgsingle->getAttribute( 'class' );
			$newimgClass = "img " . $oldimgClass;
			$imgsingle->removeAttribute('class');
			$imgsingle->setAttribute('class', $newimgClass );
		}
	};
	foreach ( array( 'input' ) as $inputSingular ) {
		$inputs = $doc->getElementsByTagName( 'input' );
		foreach ( $inputs as $inputsingle ) {
			$oldinputClass = $inputsingle->getAttribute( 'class' );
			$newinputClass = "input " . $oldinputClass;
			$inputsingle->removeAttribute('class');
			$inputsingle->setAttribute('class', $newinputClass );
		}
	};
	foreach ( array( 'ins' ) as $insSingular ) {
		$inss = $doc->getElementsByTagName( 'ins' );
		foreach ( $inss as $inssingle ) {
			$oldinsClass = $inssingle->getAttribute( 'class' );
			$newinsClass = "ins " . $oldinsClass;
			$inssingle->removeAttribute('class');
			$inssingle->setAttribute('class', $newinsClass );
		}
	};
	foreach ( array( 'kbd' ) as $kbdSingular ) {
		$kbds = $doc->getElementsByTagName( 'kbd' );
		foreach ( $kbds as $kbdsingle ) {
			$oldkbdClass = $kbdsingle->getAttribute( 'class' );
			$newkbdClass = "kbd " . $oldkbdClass;
			$kbdsingle->removeAttribute('class');
			$kbdsingle->setAttribute('class', $newkbdClass );
		}
	};
	foreach ( array( 'label' ) as $labelSingular ) {
		$labels = $doc->getElementsByTagName( 'label' );
		foreach ( $labels as $labelsingle ) {
			$oldlabelClass = $labelsingle->getAttribute( 'class' );
			$newlabelClass = "label " . $oldlabelClass;
			$labelsingle->removeAttribute('class');
			$labelsingle->setAttribute('class', $newlabelClass );
		}
	};
	foreach ( array( 'legend' ) as $legendSingular ) {
		$legends = $doc->getElementsByTagName( 'legend' );
		foreach ( $legends as $legendsingle ) {
			$oldlegendClass = $legendsingle->getAttribute( 'class' );
			$newlegendClass = "legend " . $oldlegendClass;
			$legendsingle->removeAttribute('class');
			$legendsingle->setAttribute('class', $newlegendClass );
		}
	};
	foreach ( array( 'li' ) as $liSingular ) {
		$lis = $doc->getElementsByTagName( 'li' );
		foreach ( $lis as $lisingle ) {
			$oldliClass = $lisingle->getAttribute( 'class' );
			$newliClass = "li " . $oldliClass;
			$lisingle->removeAttribute('class');
			$lisingle->setAttribute('class', $newliClass );
		}
	};
	foreach ( array( 'link' ) as $linkSingular ) {
		$links = $doc->getElementsByTagName( 'link' );
		foreach ( $links as $linksingle ) {
			$oldlinkClass = $linksingle->getAttribute( 'class' );
			$newlinkClass = "link " . $oldlinkClass;
			$linksingle->removeAttribute('class');
			$linksingle->setAttribute('class', $newlinkClass );
		}
	};
	foreach ( array( 'main' ) as $mainSingular ) {
		$mains = $doc->getElementsByTagName( 'main' );
		foreach ( $mains as $mainsingle ) {
			$oldmainClass = $mainsingle->getAttribute( 'class' );
			$newmainClass = "main " . $oldmainClass;
			$mainsingle->removeAttribute('class');
			$mainsingle->setAttribute('class', $newmainClass );
		}
	};
	foreach ( array( 'map' ) as $mapSingular ) {
		$maps = $doc->getElementsByTagName( 'map' );
		foreach ( $maps as $mapsingle ) {
			$oldmapClass = $mapsingle->getAttribute( 'class' );
			$newmapClass = "map " . $oldmapClass;
			$mapsingle->removeAttribute('class');
			$mapsingle->setAttribute('class', $newmapClass );
		}
	};
	foreach ( array( 'mark' ) as $markSingular ) {
		$marks = $doc->getElementsByTagName( 'mark' );
		foreach ( $marks as $marksingle ) {
			$oldmarkClass = $marksingle->getAttribute( 'class' );
			$newmarkClass = "mark " . $oldmarkClass;
			$marksingle->removeAttribute('class');
			$marksingle->setAttribute('class', $newmarkClass );
		}
	};
	foreach ( array( 'menu' ) as $menuSingular ) {
		$menus = $doc->getElementsByTagName( 'menu' );
		foreach ( $menus as $menusingle ) {
			$oldmenuClass = $menusingle->getAttribute( 'class' );
			$newmenuClass = "menu " . $oldmenuClass;
			$menusingle->removeAttribute('class');
			$menusingle->setAttribute('class', $newmenuClass );
		}
	};
	foreach ( array( 'meta' ) as $metaSingular ) {
		$metas = $doc->getElementsByTagName( 'meta' );
		foreach ( $metas as $metasingle ) {
			$oldmetaClass = $metasingle->getAttribute( 'class' );
			$newmetaClass = "meta " . $oldmetaClass;
			$metasingle->removeAttribute('class');
			$metasingle->setAttribute('class', $newmetaClass );
		}
	};
	foreach ( array( 'meter' ) as $meterSingular ) {
		$meters = $doc->getElementsByTagName( 'meter' );
		foreach ( $meters as $metersingle ) {
			$oldmeterClass = $metersingle->getAttribute( 'class' );
			$newmeterClass = "meter " . $oldmeterClass;
			$metersingle->removeAttribute('class');
			$metersingle->setAttribute('class', $newmeterClass );
		}
	};
	foreach ( array( 'nav' ) as $navSingular ) {
		$navs = $doc->getElementsByTagName( 'nav' );
		foreach ( $navs as $navsingle ) {
			$oldnavClass = $navsingle->getAttribute( 'class' );
			$newnavClass = "nav " . $oldnavClass;
			$navsingle->removeAttribute('class');
			$navsingle->setAttribute('class', $newnavClass );
		}
	};
	foreach ( array( 'object' ) as $objectSingular ) {
		$objects = $doc->getElementsByTagName( 'object' );
		foreach ( $objects as $objectsingle ) {
			$oldobjectClass = $objectsingle->getAttribute( 'class' );
			$newobjectClass = "object " . $oldobjectClass;
			$objectsingle->removeAttribute('class');
			$objectsingle->setAttribute('class', $newobjectClass );
		}
	};
	foreach ( array( 'ol' ) as $olSingular ) {
		$ols = $doc->getElementsByTagName( 'ol' );
		foreach ( $ols as $olsingle ) {
			$oldolClass = $olsingle->getAttribute( 'class' );
			$newolClass = "ol " . $oldolClass;
			$olsingle->removeAttribute('class');
			$olsingle->setAttribute('class', $newolClass );
		}
	};
	foreach ( array( 'optgroup' ) as $optgroupSingular ) {
		$optgroups = $doc->getElementsByTagName( 'optgroup' );
		foreach ( $optgroups as $optgroupsingle ) {
			$oldoptgroupClass = $optgroupsingle->getAttribute( 'class' );
			$newoptgroupClass = "optgroup " . $oldoptgroupClass;
			$optgroupsingle->removeAttribute('class');
			$optgroupsingle->setAttribute('class', $newoptgroupClass );
		}
	};
	foreach ( array( 'option' ) as $optionSingular ) {
		$options = $doc->getElementsByTagName( 'option' );
		foreach ( $options as $optionsingle ) {
			$oldoptionClass = $optionsingle->getAttribute( 'class' );
			$newoptionClass = "option " . $oldoptionClass;
			$optionsingle->removeAttribute('class');
			$optionsingle->setAttribute('class', $newoptionClass );
		}
	};
	foreach ( array( 'output' ) as $outputSingular ) {
		$outputs = $doc->getElementsByTagName( 'output' );
		foreach ( $outputs as $outputsingle ) {
			$oldoutputClass = $outputsingle->getAttribute( 'class' );
			$newoutputClass = "output " . $oldoutputClass;
			$outputsingle->removeAttribute('class');
			$outputsingle->setAttribute('class', $newoutputClass );
		}
	};
	
	foreach ( array( 'p' ) as $pSingular ) {
		$ps = $doc->getElementsByTagName( 'p' );
		$pnum = 1;
		foreach ( $ps as $psingle ) {
			$oldpClass = $psingle->getAttribute( 'class' );
			$newpClass = "p post-p p" . $pnum . ' ' . $oldpClass;
			$psingle->removeAttribute('class');
			$psingle->setAttribute('class', $newpClass );
			$psingle->setAttribute('id', 'p' . $pnum );
			$pnum++;
		}
		
	};
	foreach ( array( 'picture' ) as $pictureSingular ) {
		$pictures = $doc->getElementsByTagName( 'picture' );
		foreach ( $pictures as $picturesingle ) {
			$oldpictureClass = $picturesingle->getAttribute( 'class' );
			$newpictureClass = "picture " . $oldpictureClass;
			$picturesingle->removeAttribute('class');
			$picturesingle->setAttribute('class', $newpictureClass );
		}
	};
	foreach ( array( 'pre' ) as $preSingular ) {
		$pres = $doc->getElementsByTagName( 'pre' );
		foreach ( $pres as $presingle ) {
			$oldpreClass = $presingle->getAttribute( 'class' );
			$newpreClass = "pre " . $oldpreClass;
			$presingle->removeAttribute('class');
			$presingle->setAttribute('class', $newpreClass );
		}
	};
	foreach ( array( 'progress' ) as $progressSingular ) {
		$progresss = $doc->getElementsByTagName( 'progress' );
		foreach ( $progresss as $progresssingle ) {
			$oldprogressClass = $progresssingle->getAttribute( 'class' );
			$newprogressClass = "progress " . $oldprogressClass;
			$progresssingle->removeAttribute('class');
			$progresssingle->setAttribute('class', $newprogressClass );
		}
	};
	foreach ( array( 'q' ) as $qSingular ) {
		$qs = $doc->getElementsByTagName( 'q' );
		foreach ( $qs as $qsingle ) {
			$oldqClass = $qsingle->getAttribute( 'class' );
			$newqClass = "q " . $oldqClass;
			$qsingle->removeAttribute('class');
			$qsingle->setAttribute('class', $newqClass );
		}
	};
	foreach ( array( 'rp' ) as $rpSingular ) {
		$rps = $doc->getElementsByTagName( 'rp' );
		foreach ( $rps as $rpsingle ) {
			$oldrpClass = $rpsingle->getAttribute( 'class' );
			$newrpClass = "rp " . $oldrpClass;
			$rpsingle->removeAttribute('class');
			$rpsingle->setAttribute('class', $newrpClass );
		}
	};
	foreach ( array( 'rt' ) as $rtSingular ) {
		$rts = $doc->getElementsByTagName( 'rt' );
		foreach ( $rts as $rtsingle ) {
			$oldrtClass = $rtsingle->getAttribute( 'class' );
			$newrtClass = "rt " . $oldrtClass;
			$rtsingle->removeAttribute('class');
			$rtsingle->setAttribute('class', $newrtClass );
		}
	};
	foreach ( array( 'ruby' ) as $rubySingular ) {
		$rubys = $doc->getElementsByTagName( 'ruby' );
		foreach ( $rubys as $rubysingle ) {
			$oldrubyClass = $rubysingle->getAttribute( 'class' );
			$newrubyClass = "ruby " . $oldrubyClass;
			$rubysingle->removeAttribute('class');
			$rubysingle->setAttribute('class', $newrubyClass );
		}
	};
	foreach ( array( 's' ) as $sSingular ) {
		$ss = $doc->getElementsByTagName( 's' );
		foreach ( $ss as $ssingle ) {
			$oldsClass = $ssingle->getAttribute( 'class' );
			$newsClass = "s " . $oldsClass;
			$ssingle->removeAttribute('class');
			$ssingle->setAttribute('class', $newsClass );
		}
	};
	foreach ( array( 'samp' ) as $sampSingular ) {
		$samps = $doc->getElementsByTagName( 'samp' );
		foreach ( $samps as $sampsingle ) {
			$oldsampClass = $sampsingle->getAttribute( 'class' );
			$newsampClass = "samp " . $oldsampClass;
			$sampsingle->removeAttribute('class');
			$sampsingle->setAttribute('class', $newsampClass );
		}
	};
	foreach ( array( 'section' ) as $sectionSingular ) {
		$sections = $doc->getElementsByTagName( 'section' );
		foreach ( $sections as $sectionsingle ) {
			$oldsectionClass = $sectionsingle->getAttribute( 'class' );
			$newsectionClass = "section " . $oldsectionClass;
			$sectionsingle->removeAttribute('class');
			$sectionsingle->setAttribute('class', $newsectionClass );
		}
	};
	foreach ( array( 'select' ) as $selectSingular ) {
		$selects = $doc->getElementsByTagName( 'select' );
		foreach ( $selects as $selectsingle ) {
			$oldselectClass = $selectsingle->getAttribute( 'class' );
			$newselectClass = "select " . $oldselectClass;
			$selectsingle->removeAttribute('class');
			$selectsingle->setAttribute('class', $newselectClass );
		}
	};
	foreach ( array( 'small' ) as $smallSingular ) {
		$smalls = $doc->getElementsByTagName( 'small' );
		foreach ( $smalls as $smallsingle ) {
			$oldsmallClass = $smallsingle->getAttribute( 'class' );
			$newsmallClass = "small " . $oldsmallClass;
			$smallsingle->removeAttribute('class');
			$smallsingle->setAttribute('class', $newsmallClass );
		}
	};
	foreach ( array( 'source' ) as $sourceSingular ) {
		$sources = $doc->getElementsByTagName( 'source' );
		foreach ( $sources as $sourcesingle ) {
			$oldsourceClass = $sourcesingle->getAttribute( 'class' );
			$newsourceClass = "source " . $oldsourceClass;
			$sourcesingle->removeAttribute('class');
			$sourcesingle->setAttribute('class', $newsourceClass );
		}
	};
	foreach ( array( 'span' ) as $spanSingular ) {
		$spans = $doc->getElementsByTagName( 'span' );
		foreach ( $spans as $spansingle ) {
			$oldspanClass = $spansingle->getAttribute( 'class' );
			$newspanClass = "span " . $oldspanClass;
			$spansingle->removeAttribute('class');
			$spansingle->setAttribute('class', $newspanClass );
		}
	};
	foreach ( array( 'strike' ) as $strikeSingular ) {
		$strikes = $doc->getElementsByTagName( 'strike' );
		foreach ( $strikes as $strikesingle ) {
			$oldstrikeClass = $strikesingle->getAttribute( 'class' );
			$newstrikeClass = "strike " . $oldstrikeClass;
			$strikesingle->removeAttribute('class');
			$strikesingle->setAttribute('class', $newstrikeClass );
		}
	};
	foreach ( array( 'strong' ) as $strongSingular ) {
		$strongs = $doc->getElementsByTagName( 'strong' );
		foreach ( $strongs as $strongsingle ) {
			$oldstrongClass = $strongsingle->getAttribute( 'class' );
			$newstrongClass = "strong " . $oldstrongClass;
			$strongsingle->removeAttribute('class');
			$strongsingle->setAttribute('class', $newstrongClass );
		}
	};
	foreach ( array( 'sub' ) as $subSingular ) {
		$subs = $doc->getElementsByTagName( 'sub' );
		foreach ( $subs as $subsingle ) {
			$oldsubClass = $subsingle->getAttribute( 'class' );
			$newsubClass = "sub " . $oldsubClass;
			$subsingle->removeAttribute('class');
			$subsingle->setAttribute('class', $newsubClass );
		}
	};
	foreach ( array( 'summary' ) as $summarySingular ) {
		$summarys = $doc->getElementsByTagName( 'summary' );
		foreach ( $summarys as $summarysingle ) {
			$oldsummaryClass = $summarysingle->getAttribute( 'class' );
			$newsummaryClass = "summary " . $oldsummaryClass;
			$summarysingle->removeAttribute('class');
			$summarysingle->setAttribute('class', $newsummaryClass );
		}
	};
	foreach ( array( 'sup' ) as $supSingular ) {
		$sups = $doc->getElementsByTagName( 'sup' );
		foreach ( $sups as $supsingle ) {
			$oldsupClass = $supsingle->getAttribute( 'class' );
			$newsupClass = "sup " . $oldsupClass;
			$supsingle->removeAttribute('class');
			$supsingle->setAttribute('class', $newsupClass );
		}
	};
	foreach ( array( 'svg' ) as $svgSingular ) {
		$svgs = $doc->getElementsByTagName( 'svg' );
		foreach ( $svgs as $svgsingle ) {
			$oldsvgClass = $svgsingle->getAttribute( 'class' );
			$newsvgClass = "svg " . $oldsvgClass;
			$svgsingle->removeAttribute('class');
			$svgsingle->setAttribute('class', $newsvgClass );
		}
	};
	foreach ( array( 'table' ) as $tableSingular ) {
		$tables = $doc->getElementsByTagName( 'table' );
		foreach ( $tables as $tablesingle ) {
			$oldtableClass = $tablesingle->getAttribute( 'class' );
			$newtableClass = "table " . $oldtableClass;
			$tablesingle->removeAttribute('class');
			$tablesingle->setAttribute('class', $newtableClass );
		}
	};
	foreach ( array( 'tbody' ) as $tbodySingular ) {
		$tbodys = $doc->getElementsByTagName( 'tbody' );
		foreach ( $tbodys as $tbodysingle ) {
			$oldtbodyClass = $tbodysingle->getAttribute( 'class' );
			$newtbodyClass = "tbody " . $oldtbodyClass;
			$tbodysingle->removeAttribute('class');
			$tbodysingle->setAttribute('class', $newtbodyClass );
		}
	};
	foreach ( array( 'td' ) as $tdSingular ) {
		$tds = $doc->getElementsByTagName( 'td' );
		foreach ( $tds as $tdsingle ) {
			$oldtdClass = $tdsingle->getAttribute( 'class' );
			$newtdClass = "td " . $oldtdClass;
			$tdsingle->removeAttribute('class');
			$tdsingle->setAttribute('class', $newtdClass );
		}
	};
	foreach ( array( 'tdimg' ) as $tdimgSingular ) {
		$tdimgs = $doc->getElementsByTagName( 'tdimg' );
		foreach ( $tdimgs as $tdimgsingle ) {
			$oldtdimgClass = $tdimgsingle->getAttribute( 'class' );
			$newtdimgClass = "tdimg " . $oldtdimgClass;
			$tdimgsingle->removeAttribute('class');
			$tdimgsingle->setAttribute('class', $newtdimgClass );
		}
	};
	foreach ( array( 'template' ) as $templateSingular ) {
		$templates = $doc->getElementsByTagName( 'template' );
		foreach ( $templates as $templatesingle ) {
			$oldtemplateClass = $templatesingle->getAttribute( 'class' );
			$newtemplateClass = "template " . $oldtemplateClass;
			$templatesingle->removeAttribute('class');
			$templatesingle->setAttribute('class', $newtemplateClass );
		}
	};
	foreach ( array( 'text' ) as $textSingular ) {
		$texts = $doc->getElementsByTagName( 'text' );
		foreach ( $texts as $textsingle ) {
			$oldtextClass = $textsingle->getAttribute( 'class' );
			$newtextClass = "text " . $oldtextClass;
			$textsingle->removeAttribute('class');
			$textsingle->setAttribute('class', $newtextClass );
		}
	};
	foreach ( array( 'textarea' ) as $textareaSingular ) {
		$textareas = $doc->getElementsByTagName( 'textarea' );
		foreach ( $textareas as $textareasingle ) {
			$oldtextareaClass = $textareasingle->getAttribute( 'class' );
			$newtextareaClass = "textarea " . $oldtextareaClass;
			$textareasingle->removeAttribute('class');
			$textareasingle->setAttribute('class', $newtextareaClass );
		}
	};
	foreach ( array( 'tfoot' ) as $tfootSingular ) {
		$tfoots = $doc->getElementsByTagName( 'tfoot' );
		foreach ( $tfoots as $tfootsingle ) {
			$oldtfootClass = $tfootsingle->getAttribute( 'class' );
			$newtfootClass = "tfoot " . $oldtfootClass;
			$tfootsingle->removeAttribute('class');
			$tfootsingle->setAttribute('class', $newtfootClass );
		}
	};
	foreach ( array( 'th' ) as $thSingular ) {
		$ths = $doc->getElementsByTagName( 'th' );
		foreach ( $ths as $thsingle ) {
			$oldthClass = $thsingle->getAttribute( 'class' );
			$newthClass = "th " . $oldthClass;
			$thsingle->removeAttribute('class');
			$thsingle->setAttribute('class', $newthClass );
		}
	};
	foreach ( array( 'thead' ) as $theadSingular ) {
		$theads = $doc->getElementsByTagName( 'thead' );
		foreach ( $theads as $theadsingle ) {
			$oldtheadClass = $theadsingle->getAttribute( 'class' );
			$newtheadClass = "thead " . $oldtheadClass;
			$theadsingle->removeAttribute('class');
			$theadsingle->setAttribute('class', $newtheadClass );
		}
	};
	foreach ( array( 'time' ) as $timeSingular ) {
		$times = $doc->getElementsByTagName( 'time' );
		foreach ( $times as $timesingle ) {
			$oldtimeClass = $timesingle->getAttribute( 'class' );
			$newtimeClass = "time " . $oldtimeClass;
			$timesingle->removeAttribute('class');
			$timesingle->setAttribute('class', $newtimeClass );
		}
	};
	foreach ( array( 'tr' ) as $trSingular ) {
		$trs = $doc->getElementsByTagName( 'tr' );
		foreach ( $trs as $trsingle ) {
			$oldtrClass = $trsingle->getAttribute( 'class' );
			$newtrClass = "tr " . $oldtrClass;
			$trsingle->removeAttribute('class');
			$trsingle->setAttribute('class', $newtrClass );
		}
	};
	foreach ( array( 'track' ) as $trackSingular ) {
		$tracks = $doc->getElementsByTagName( 'track' );
		foreach ( $tracks as $tracksingle ) {
			$oldtrackClass = $tracksingle->getAttribute( 'class' );
			$newtrackClass = "track " . $oldtrackClass;
			$tracksingle->removeAttribute('class');
			$tracksingle->setAttribute('class', $newtrackClass );
		}
	};
	foreach ( array( 'tt' ) as $ttSingular ) {
		$tts = $doc->getElementsByTagName( 'tt' );
		foreach ( $tts as $ttsingle ) {
			$oldttClass = $ttsingle->getAttribute( 'class' );
			$newttClass = "tt " . $oldttClass;
			$ttsingle->removeAttribute('class');
			$ttsingle->setAttribute('class', $newttClass );
		}
	};
	foreach ( array( 'u' ) as $uSingular ) {
		$us = $doc->getElementsByTagName( 'u' );
		foreach ( $us as $usingle ) {
			$olduClass = $usingle->getAttribute( 'class' );
			$newuClass = "u " . $olduClass;
			$usingle->removeAttribute('class');
			$usingle->setAttribute('class', $newuClass );
		}
	};
	foreach ( array( 'ul' ) as $ulSingular ) {
		$uls = $doc->getElementsByTagName( 'ul' );
		foreach ( $uls as $ulsingle ) {
			$oldulClass = $ulsingle->getAttribute( 'class' );
			$newulClass = "ul " . $oldulClass;
			$ulsingle->removeAttribute('class');
			$ulsingle->setAttribute('class', $newulClass );
		}
	};
	foreach ( array( 'var' ) as $varSingular ) {
		$vars = $doc->getElementsByTagName( 'var' );
		foreach ( $vars as $varsingle ) {
			$oldvarClass = $varsingle->getAttribute( 'class' );
			$newvarClass = "var " . $oldvarClass;
			$varsingle->removeAttribute('class');
			$varsingle->setAttribute('class', $newvarClass );
		}
	};
	foreach ( array( 'video' ) as $videoSingular ) {
		$videos = $doc->getElementsByTagName( 'video' );
		foreach ( $videos as $videosingle ) {
			$oldvideoClass = $videosingle->getAttribute( 'class' );
			$newvideoClass = "video " . $oldvideoClass;
			$videosingle->removeAttribute('class');
			$videosingle->setAttribute('class', $newvideoClass );
		}
	};
	foreach ( array( 'wbr' ) as $wbrSingular ) {
		$wbrs = $doc->getElementsByTagName( 'wbr' );
		foreach ( $wbrs as $wbrsingle ) {
			$oldwbrClass = $wbrsingle->getAttribute( 'class' );
			$newwbrClass = "wbr " . $oldwbrClass;
			$wbrsingle->removeAttribute('class');
			$wbrsingle->setAttribute('class', $newwbrClass );
		}
	}
		$new_content = $doc->saveHTML();
		return $new_content;
	}
	else {
		return $content;
	}
 
}
	
add_filter( 'the_content', 'add_classes_to_post_content', 9 );

?>
