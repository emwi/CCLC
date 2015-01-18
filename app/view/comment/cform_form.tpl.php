
<a href="javascript:showForm()" id="showFormLink">Lämna en kommentar</a>

<h3 id="editTitle" style="display: none;"><?=$title?></h3>

<?=$form?>

<script>
function showForm() {
    var form = document.getElementsByClassName('comment-form')[0];
    form.style.display = "block";
    showFormLink.style.display = "none";    
    editTitle.style.display = "block";
}
</script> 
