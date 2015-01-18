<a href="javascript:showForm()" id="showFormLink">Lämna en kommentar</a>

<div class='comment-form' style="display:none;">
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create('')?>">
     <input type=hidden name="page" value="<?=$this->url->getPage()?>"/>
        <h3>Kommentera</h3>
        <p><label>Kommentar:<br/><textarea name='content'><?=$content?></textarea></label></p>
        <p><label>Namn:<br/><input type='text' name='name' value='<?=$name?>'/></label></p>
        <p><label>Hemsida:<br/>http://<input type='text' name='web' value='<?=$web?>'/></label></p>
        <p><label>E-post:<br/><input type='text' name='mail' value='<?=$mail?>'/></label></p>
        <p class=buttons>
            <input type='submit' name='doCreate' value='Comment' onClick="this.form.action = '<?=$this->url->create('comments/add')?>'"/>
            <input type='reset' value='Reset'/>
            <input type='submit' name='doRemoveAll' value='Remove all' onClick="this.form.action = '<?=$this->url->create('comment/remove-all')?>'"/>
        </p>
        <output><?=$output?></output>
    </form>
</div>

<script>
function showForm() {
    var form = document.getElementsByClassName('comment-form')[0];
    form.style.display = "block";
    showFormLink.style.display = "none";    
}
</script>
