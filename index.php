<?php
session_start();
get_header(); 
?>

<header>
	<h1>World of Ideas: The Modern Era</h1>
	<a style="cursor:pointer; position: absolute; top: 47px; right: 15px;" onclick="openNav()" role="button"><img src="/wp-content/themes/blankslate/icons/search-solid.svg" style="width: 50px"></a>
</header>



<main id="content" class="am_class <?php echo $user; ?>">



<?php
/*

There are 3 users for this site, there is no public access. content and styling changes depending on which role you are

1) morning class session students

2) afternoon class session students

3) the teacher (superadmin)

*/

if($current_user->user_login == "spring_22_am"){
	$post_types = "post,spring-22-morning";
	$post_types_Query = array('post', 'spring-22-morning');
	//$tag__not_in = array( 41 ); // fall-21-morning-only
} else if($current_user->user_login == "spring_22_pm"){
	$post_types = "post,spring-22-afternoon";
	$post_types_Query = array('post', 'spring-22-afternoon');
	//$tag__not_in = array( 41 ); // fall-21-morning-only
} else {
	// fall-21-morning,fall-21-afternoon
	$post_types = "post,spring-21-morning,spring-21-afternoon";
	$post_types_Query = array('post', 'spring-22-morning', 'spring-22-afternoon');
}

/*
timeline length variables
*/

if( isset($_GET['ts']) ){
	$timeline_start = intval($_GET['ts']);
} else {
	$timeline_start = 1750;
}

if( isset($_GET['te']) ){
	$timeline_end = intval($_GET['te']);
} else {
	$timeline_end = 2100;
}


/*
tag variables
*/
$user_tag = "";
$timeline_shortcode = "";
$map_shortcode = "";

if( isset($_GET['t']) ){
	$user_tag = $_GET['t'];
	$tag_name = get_term_by('slug', $user_tag, 'post_tag');
	echo '<p>Filtering on <strong>'.$tag_name->name.'</strong> <a href="/" class="sgc-link">Clear Filter</a></p>';
	// timeline
	$timeline_shortcode = "tags=".$user_tag;
	// map
	$map_shortcode = " tags=".$user_tag;
}

/*
option to highlight student generated content
*/

$_SESSION["student-generated-content"] = $_GET['sgc'] == "On" ? "Off" : "On";
$sgc_override = '';
if( strpos($_GET['t'], 'project-') !== false ) { 
	$sgc_override = "-disabled";
} else { ?>
<p><a href='?sgc=<?php echo $_SESSION["student-generated-content"]; ?>' style='<?php echo $_SESSION["student-generated-content"] == "On" ? "background-color: yellow;" : ""; ?>' class="sgc-link">Highlight Posts with Student Generated Content: <?php echo $_SESSION["student-generated-content"]; ?></a></p>
<?php }
?>


<style type="text/css">
	.tags a.<?php echo $user_tag; ?>, .weeks a.<?php echo $user_tag; ?> { background-color: yellow; }
	.vis-item.vis-background.future {	background-color: rgba(0, 0, 0, 0.05);	}
</style>












<!-- these filters let the teacher/students narrow the timeline to a specific topic for in-class discussions -->

<div id="mySidenav" class="sidenav">
	<section>
		<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

	<div>
			<div class="weeks">
				<h4>Weekly Material</h4>
				<a href='?t=week-2' class='week-2'>Week 2: Romanticism</a><br>
				<a href='?t=week-3' class='week-3'>Week 3: Transcendentalism &amp; American Folk Art</a><br>
				<a href='?t=week-4' class='week-4'>Week 4: Abolition, Civil War, and Reconstruction</a><br>
				<a href='?t=week-5' class='week-5'>Week 5: Revolution & Progress</a><br>
				<a href='?t=week-6' class='week-6'>Week 6: Industrial Utopias</a><br>
				<a href='?t=week-7' class='week-7'>Week 7: Rise of Pop Culture</a><br>
				<!-- <a href='?t=week-8' class='week-8 disabled'>Week 8: Modernism</a><br> -->
				<a href='?t=week-9' class='week-9'>Week 9: Women’s Rights</a><br>
				<a href='?t=week-10' class='week-10'>Week 10: Civil Rights</a><br>
				<a href='?t=week-11' class='week-11'>Week 11: Counter Culture</a><br>
				<a href='?t=week-12' class='week-12'>Week 12: Technology Boom</a><br>
				<a href='?t=week-13' class='week-13'>Week 13: Security & Terror</a><br>
				<a href='?t=week-14' class='week-14'>Week 14: Activism</a><br>
				<h4>Research Projects</h4>
				<a href='?t=project-1' class='project-21st-century'>Research Project 1</a><br>
				<a href='?t=project-2' class='project-21st-century'>Research Project 2</a><br>
				<a href='?t=project-3' class='project-21st-century'>Research Project 3</a><br>
				<a href='?t=project-4' class='project-21st-century'>Research Project 4</a>
			</div>
	</div>
		
	</section>
</div>











<!-- script to control the filter menu -->
<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

document.onkeydown = function(evt) {
    evt = evt || window.event;
    var isEscape = false;
    if ("key" in evt) {
        isEscape = (evt.key === "Escape" || evt.key === "Esc");
    } else {
        isEscape = (evt.keyCode === 27);
    }
    if (isEscape) {
        // alert("Escape");
        closeNav();
    }
};
</script>








<!-- initiate the timeline and set custom styles -->
<script type="text/javascript" src="https://unpkg.com/moment@latest"></script>
<script type="text/javascript" src="https://unpkg.com/vis-data@latest/peer/umd/vis-data.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/vis-timeline@latest/peer/umd/vis-timeline-graph2d.min.js"></script>

<div id="visualization" class="student-generated-content-<?php echo $_SESSION["student-generated-content"].$sgc_override; ?>"></div>

<script type="text/javascript">
	// https://visjs.github.io/vis-timeline/examples/timeline/
  // DOM element where the Timeline will be attached
  const container = document.getElementById("visualization");

  // Create a DataSet (allows two way data-binding)
  const items = new vis.DataSet([

				<?php
				// args
				// https://developer.wordpress.org/reference/classes/wp_query/#tag-parameters
				$args = array(
					's' => $_GET['s'],
					'post_type'		=> $post_types_Query,
					'tag' => $user_tag,
					'tag__not_in' => $tag__not_in,
					'posts_per_page' => -1
				);

				// query
				$the_query = new WP_Query( $args );


				if( $the_query->have_posts() ):
					while( $the_query->have_posts() ) : $the_query->the_post();

							$category_output = "health";
							$categories = get_the_category(); 
							if ( ! empty( $categories ) ) { 
							    $category_output = esc_html( $categories[0]->slug );   
							}

							// hack to display page titles in italics
							$title = get_the_title();
							$title = str_replace('&#8220;', "<i>", $title);
							$title = str_replace('&#8221;', "</i>", $title);

							$sgc = "";
							$sgc_icon = "";

							echo "{ id: ".get_the_ID().", content: '<a href=\"".get_permalink()."\">".$title."</a>".$sgc_icon."', 'className': '".get_post_type().' '.$category_output.$sgc."', start: '".get_field('date_start')."-01-01', group: '".$category_output."'";
							if( get_field('date_end') != "" ){
								echo ", end: '".get_field('date_end')."-01-01'";
							} else {
								echo ", type: 'point'";
							}
							echo " },".PHP_EOL;
				    
					endwhile;
				endif;
				wp_reset_query();	 // Restore global post data stomped by the_post().

?>

		{id: 'A', start: '1800-01-01', end: '1800-02-01', type: 'background', className: 'negative'},
		{id: 'B', start: '1900-01-01', end: '1900-02-01', type: 'background', className: 'negative'},
		{id: 'C', start: '2000-01-01', end: '2000-02-01', type: 'background', className: 'negative'},
    {id: 'past', start: '1700-01-01', end: '1800-01-01', type: 'background', className: 'future'},
    {id: 'future', start: '2022-01-01', end: '2100-01-01', type: 'background', className: 'future'},
  ]);


	// icons: https://fontawesome.com/v5.15/icons?d=gallery&p=2
  var groups = new vis.DataSet([
    {id: 'geopolitics', content: '<img src="/wp-content/themes/blankslate/icons/globe-americas-solid.svg"><br>Geopolitics'},
    {id: 'culture', content: '<img src="/wp-content/themes/blankslate/icons/palette-solid.svg"><br>Arts & Culture'},
    {id: 'science', content: '<img src="/wp-content/themes/blankslate/icons/atom-solid.svg"><br>Science & Technology'},
    {id: 'social', content: '<img src="/wp-content/themes/blankslate/icons/fist-raised-solid.svg"><br>Social Movements'},
    {id: 'health', content: '<img src="/wp-content/themes/blankslate/icons/prescription-bottle-alt-solid.svg"><br>Health & Medicine'}
  ]);


  // Configuration for the Timeline
  const options = {
    start: '<?php echo $timeline_start; ?>-01-01',
    end: '<?php echo $timeline_end; ?>-01-01',
    editable: false,
    zoomable: false,
    orientation: { axis: "both" },
    loadingScreenTemplate: function() {
      return '<h4>Loading...</h4>'
    },
    min: new Date(<?php echo $timeline_start; ?>, 0, 1),                // lower limit of visible range
    max: new Date(<?php echo $timeline_end; ?>, 0, 1),                // upper limit of visible range
  };

  // Create a Timeline
  const timeline = new vis.Timeline(container, items, groups, options);

</script>






<!-- tap into the map plugin -->
<section><?php echo do_shortcode( '[travelers-map init_maxzoom=3 minzoom=2 maxzoom=8'.$map_shortcode.']' ); ?></section>
</main>
<?php get_footer(); ?>
<?php echo $the_query->found_posts; ?>