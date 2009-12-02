<script type="text/javascript">
function change_num(item) {
var s = document.getElementById('fpm_num');
var t = document.getElementById('truncate');

switch(item.value) {	
case 'none':
    t.style.display = 'none';
    break;
case 'paragraph':
    s.innerHTML= "<strong># paragraphs before cutoff</strong> (default <em>1</em>)";
    t.style.display = 'block';
    break;
case 'letter': 
    s.innerHTML= "<strong># characters before cutoff</strong> (default <em>600</em>)";
    t.style.display = 'block';
    break;
case 'word':
    s.innerHTML= "<strong># words before cutoff</strong> (default <em>200</em>)";
    t.style.display = 'block';
    break;
}
}
</script>
