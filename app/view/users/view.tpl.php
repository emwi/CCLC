<pre><?/*=var_dump($user->getProperties())*/?></pre>

<?php $props = $user->getProperties(); ?>

<h2><?=$props['acronym']?></h2>

<p>
<a href="<?=$this->url->create('users/edit/'.$props['id'])?>">Ändra</a> - 
<a href="<?=$this->url->create('users/soft-delete/'.$props['id'])?>">Lägg till papperskorgen</a> - 
<a href="<?=$this->url->create('users/delete/'.$props['id'])?>">Radera</a>
</p>

 <table class="one-user-list">
     <tr>
         <th>ID</th>
         <td><?=$props['id']?></td>
    </tr>
    <tr>
        <th>Namn</th>
        <td><?=$props['name']?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?=$props['email']?></td>
    </tr>
    <tr>
        <th>Skapad</th>
        <td><?=$props['created']?></td>
    </tr>
    <tr>
        <th>Uppdaterad</th>
        <td><?=$props['updated']?></td>
    </tr>
    <tr>
        <th>Borttagen</th>
        <td><?=$props['deleted']?></td>
    </tr>
    <tr>
        <th>Aktiv</th>
        <td><?=$props['active']?></td>
    </tr>
        <tr>
        <th>Inaktiverad</th>
        <td><?=$props['inactive']?></td>
    </tr>
</table>

<p><a href='<?=$this->url->create('users/list')?>'>Tillbaka</a></p> 
