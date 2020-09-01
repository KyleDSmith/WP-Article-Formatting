// After uploading the other PHP files to the theme's directory, add these lines to the theme's functions.php file

function add_class_to_content() {
     locate_template( array( '01-add_classes.php' ), true, true );
}
add_action( 'after_setup_theme', 'add_class_to_content' );

function add_chapter_sections() {
     locate_template( array( '02-add_sections.php' ), true, true );
}
add_action( 'after_setup_theme', 'add_chapter_sections' );

function chapter_formatting_file() {
     locate_template( array( '03-organize_chapters.php' ), true, true );
}
add_action( 'after_setup_theme', 'chapter_formatting_file' );
