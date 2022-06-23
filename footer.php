<?php wp_footer(); ?>

<script>
// Listen for the event.
document.addEventListener('cttm_map_loaded', function (e) {
  // cttm_map is an array of all the maps included in the page, so we loop through each.
  for (let i = 0; i < cttm_map.length; i++) {
    console.log(cttm_map[i]._container);
    cttm_map[i]._container.style.display = "block";
  }
}, false);

// 
document.addEventListener('DOMContentLoaded', function(event) {
  jQuery( '<h2 class="student-contributions">Student Contributions</h2>' ).insertBefore( ".student:visible:first" );
})
</script>

</body>
</html>